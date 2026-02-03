<?php

namespace App\Models;

use App\Enums\StatusPeminjaman;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    protected $table = 'buku';

    /**
     * Get the available stock (total stock minus borrowed books)
     * This is a computed attribute that calculates real-time stock
     */
    public function getStokAvailableAttribute(): int
    {
        $dipinjam = $this->peminjaman()
            ->whereIn('status', [StatusPeminjaman::DIPINJAM, StatusPeminjaman::TERLAMBAT])
            ->count();

        return max(0, $this->attributes['stok'] - $dipinjam);
    }

    /**
     * Check if stock is low (below threshold)
     */
    public function isStokRendah(int $threshold = 5): bool
    {
        return $this->stok <= $threshold;
    }

    /**
     * Check if stock is empty
     */
    public function isStokHabis(): bool
    {
        return $this->stok <= 0;
    }

    protected $fillable = [
        'judul',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'kategori_id',
        'stok',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }
}
