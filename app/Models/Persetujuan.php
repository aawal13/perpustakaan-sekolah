<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Persetujuan extends Model
{
    protected $fillable = [
        'siswa_id',
        'buku_id',
        'tanggal_dipinjam',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'siswa_id', 'siswa_id')
            ->where('buku_id', $this->buku_id);
    }

    public function siswa(): BelongsTo
{
    return $this->belongsTo(Siswa::class);
}

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }
}
