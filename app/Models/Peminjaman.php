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

    protected static function booted()
    {
        static::saving(function ($peminjaman) {
            $peminjaman->refreshStatusDanDenda();
        });
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
