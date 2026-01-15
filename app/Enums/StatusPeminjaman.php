<?php

namespace App\Enums;

enum StatusPeminjaman: string
{
    case DIPINJAM = 'dipinjam';
    case DIKEMBALIKAN = 'dikembalikan';
    case TERLAMBAT = 'terlambat';

    public function color(): string
{
    return match ($this) {
        self::DIPINJAM => 'warning',
        self::DIKEMBALIKAN => 'success',
        self::TERLAMBAT => 'danger',
    };
}


    public function label(): string
    {
        return match ($this) {
            self::DIPINJAM => 'Dipinjam',
            self::DIKEMBALIKAN => 'Dikembalikan',
            self::TERLAMBAT => 'Terlambat',
        };
    }
}
