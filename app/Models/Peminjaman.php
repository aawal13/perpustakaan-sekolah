<?php

namespace App\Models;

use App\Enums\StatusPeminjaman;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'buku_id',
        'siswa_id',
        'tanggal_dipinjam',
        'tanggal_dikembalikan',
    ];

    protected $casts = [
        'status' => StatusPeminjaman::class,
        'tanggal_dipinjam' => 'date',
        'tanggal_dikembalikan' => 'date',
    ];

    /**
     * Track previous status for stock updates
     */
    protected $previousStatus = null;

    /**
     * Boot method to track status changes
     */
    protected static function booted()
    {
        // Save previous status before updating
        static::updating(function ($peminjaman) {
            if ($peminjaman->isDirty('status')) {
                $peminjaman->previousStatus = $peminjaman->getOriginal('status');
            }
        });

        // Handle stock updates after save (for status changes)
        static::saved(function ($peminjaman) {
            // Only update stock if status was changed
            if (!is_null($peminjaman->previousStatus)) {
                $peminjaman->updateStokBuku();
                // Reset after processing
                $peminjaman->previousStatus = null;
            }
        });

        // Handle stock when creating new peminjaman
        // NOTE: Stock is calculated dynamically by accessor, no need to decrement database
        static::created(function ($peminjaman) {
            // Calculate initial status and fine
            $peminjaman->calculateStatusDanDenda();
            $peminjaman->save();
        });

        // Handle stock when deleting peminjaman
        // NOTE: Stock is calculated dynamically, no need to increment database
        static::deleting(function ($peminjaman) {
            // No stock manipulation needed - stock is calculated dynamically
        });
    }

    /**
     * Update book stock based on status changes
     * NOTE: This method is kept for compatibility but stock is now calculated dynamically
     */
    public function updateStokBuku(): void
    {
        // Stock is now calculated dynamically by Buku::getStokAttribute()
        // This method is no longer needed but kept for backward compatibility
    }

    /**
     * Calculate status and fine without saving
     * Returns [status, denda] as raw values
     */
    public function calculateStatusDanDenda(): array
    {
        if (is_null($this->tanggal_dipinjam)) {
            return [StatusPeminjaman::DIPINJAM, 0];
        }

        // ===============================
        // AMBIL DARI SETTINGS
        // ===============================
        $maksHariPinjam = (int) Setting::get('maks_hari_pinjam', 0);
        $dendaPerHari = (int) Setting::get('denda_perhari', 0);
        $maksDenda = (int) Setting::get('max_denda', 0);

        $tanggalPinjam = $this->tanggal_dipinjam->startOfDay();

        // ===============================
        // BELUM DIKEMBALIKAN
        // ===============================
        if (is_null($this->tanggal_dikembalikan)) {

            // 🔴 TIDAK ADA BATAS PINJAM
            if ($maksHariPinjam <= 0) {
                return [StatusPeminjaman::DIPINJAM, 0];
            }

            $hariDipinjam = $tanggalPinjam
                ->diffInDays(now()->startOfDay());

            if ($hariDipinjam > $maksHariPinjam) {
                $hariTerlambat = $hariDipinjam - $maksHariPinjam;

                $totalDenda = $hariTerlambat * $dendaPerHari;

                $finalDenda = $maksDenda > 0
                    ? min($totalDenda, $maksDenda)
                    : $totalDenda;

                return [StatusPeminjaman::TERLAMBAT, $finalDenda];
            }

            return [StatusPeminjaman::DIPINJAM, 0];
        }

        // ===============================
        // SUDAH DIKEMBALIKAN
        // ===============================
        $hariDipinjam = $tanggalPinjam
            ->diffInDays($this->tanggal_dikembalikan->startOfDay());

        $hariTerlambat = max(0, $hariDipinjam - $maksHariPinjam);

        $totalDenda = $hariTerlambat * $dendaPerHari;

        $finalDenda = $maksDenda > 0
            ? min($totalDenda, $maksDenda)
            : $totalDenda;

        return [StatusPeminjaman::DIKEMBALIKAN, $finalDenda];
    }

    /**
     * Refresh status and fine (legacy method - use calculateStatusDanDenda + save for better control)
     */
    public function refreshStatusDanDenda(): void
    {
        if (is_null($this->tanggal_dipinjam)) {
            return;
        }

        // ===============================
        // AMBIL DARI SETTINGS
        // ===============================
        $maksHariPinjam = (int) Setting::get('maks_hari_pinjam', 0);
        $dendaPerHari = (int) Setting::get('denda_perhari', 0);
        $maksDenda = (int) Setting::get('max_denda', 0);

        $tanggalPinjam = $this->tanggal_dipinjam->startOfDay();

        // ===============================
        // BELUM DIKEMBALIKAN
        // ===============================
        if (is_null($this->tanggal_dikembalikan)) {

            // 🔴 TIDAK ADA BATAS PINJAM
            if ($maksHariPinjam <= 0) {
                $this->status = StatusPeminjaman::DIPINJAM;
                $this->denda = 0;

                return;
            }

            $hariDipinjam = $tanggalPinjam
                ->diffInDays(now()->startOfDay());

            if ($hariDipinjam > $maksHariPinjam) {
                $hariTerlambat = $hariDipinjam - $maksHariPinjam;

                $this->status = StatusPeminjaman::TERLAMBAT;

                $totalDenda = $hariTerlambat * $dendaPerHari;

                $this->denda = $maksDenda > 0
                    ? min($totalDenda, $maksDenda)
                    : $totalDenda;
            } else {
                $this->status = StatusPeminjaman::DIPINJAM;
                $this->denda = 0;
            }

            return;
        }

        // ===============================
        // SUDAH DIKEMBALIKAN
        // ===============================
        $hariDipinjam = $tanggalPinjam
            ->diffInDays($this->tanggal_dikembalikan->startOfDay());

        $hariTerlambat = max(0, $hariDipinjam - $maksHariPinjam);

        $totalDenda = $hariTerlambat * $dendaPerHari;

        $this->status = StatusPeminjaman::DIKEMBALIKAN;
        $this->denda = $maksDenda > 0
            ? min($totalDenda, $maksDenda)
            : $totalDenda;
    }

    /**
     * Handle status change for pengembalian
     * Stock is calculated dynamically by Buku accessor
     */
    public function processPengembalian(): void
    {
        // Calculate new status and fine
        $this->refreshStatusDanDenda();
    }

    // ===============================
    // RELATIONS
    // ===============================
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function batasPeminjaman(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->tanggal_dipinjam) {
                    return null;
                }

                $maksHariPinjam = (int) Setting::get('maks_hari_pinjam', 0);

                // 🔴 TIDAK ADA BATAS
                if ($maksHariPinjam <= 0) {
                    return null;
                }

                return Carbon::parse($this->tanggal_dipinjam)
                    ->addDays($maksHariPinjam);
            }
        );
    }
}

