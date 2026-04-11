<?php

namespace App\Models;

use App\Enums\StatusPeminjaman;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'buku_id',
        'siswa_id',
        'tanggal_dipinjam',
        'tanggal_dikembalikan',
        'status',
        'denda',
    ];

    protected $casts = [
        'status' => StatusPeminjaman::class,
        'tanggal_dipinjam' => 'date',
        'tanggal_dikembalikan' => 'date',
    ];

    protected static function booted()
    {
        // Saat pertama kali dibuat
        static::created(function (Peminjaman $peminjaman) {
            [$status, $denda] = $peminjaman->calculateStatusDanDenda();

            $peminjaman->updateQuietly([
                'status' => $status,
                'denda' => $denda,
            ]);
        });
    }

    public function processPengembalian(): void
{
    // set tanggal dikembalikan = sekarang
    $this->tanggal_dikembalikan = now();

    // hitung ulang status & denda
    [$status, $denda] = $this->calculateStatusDanDenda();

    // update data
    $this->update([
        'tanggal_dikembalikan' => $this->tanggal_dikembalikan,
        'status' => $status,
        'denda' => $denda,
    ]);
}

    // ===============================
    // 🔥 CORE LOGIC (INI YANG PENTING)
    // ===============================
    public function calculateStatusDanDenda(): array
    {
        if (! $this->tanggal_dipinjam) {
            return [StatusPeminjaman::DIPINJAM, 0];
        }

        $maksHariPinjam = (int) Setting::get('maks_hari_pinjam', 0);
        $dendaPerHari   = (int) Setting::get('denda_perhari', 0);
        $maksDenda      = (int) Setting::get('max_denda', 0);

        $tanggalPinjam = Carbon::parse($this->tanggal_dipinjam)->startOfDay();

        // ===============================
        // BELUM DIKEMBALIKAN
        // ===============================
        if (! $this->tanggal_dikembalikan) {

            if ($maksHariPinjam <= 0) {
                return [StatusPeminjaman::DIPINJAM, 0];
            }

            $hari = $tanggalPinjam->diffInDays(now()->startOfDay());

            if ($hari > $maksHariPinjam) {
                $telat = $hari - $maksHariPinjam;

                $denda = $telat * $dendaPerHari;

                if ($maksDenda > 0) {
                    $denda = min($denda, $maksDenda);
                }

                return [StatusPeminjaman::TERLAMBAT, $denda];
            }

            return [StatusPeminjaman::DIPINJAM, 0];
        }

        // ===============================
        // SUDAH DIKEMBALIKAN
        // ===============================
        $hari = $tanggalPinjam
            ->diffInDays(Carbon::parse($this->tanggal_dikembalikan)->startOfDay());

        $telat = max(0, $hari - $maksHariPinjam);

        $denda = $telat * $dendaPerHari;

        if ($maksDenda > 0) {
            $denda = min($denda, $maksDenda);
        }

        return [StatusPeminjaman::DIKEMBALIKAN, $denda];
    }

    // ===============================
    // ✅ REALTIME STATUS (TANPA CRON)
    // ===============================
    public function getStatusRealtimeAttribute()
    {
        return $this->calculateStatusDanDenda()[0];
    }

    public function getDendaRealtimeAttribute()
    {
        return $this->calculateStatusDanDenda()[1];
    }

    // ===============================
    // RELASI
    // ===============================
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // ===============================
    // BATAS PINJAM
    // ===============================
    public function batasPeminjaman(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                $this->tanggal_dipinjam
                    ? Carbon::parse($this->tanggal_dipinjam)
                        ->addDays((int) Setting::get('maks_hari_pinjam', 0))
                    : null
        );
    }
}