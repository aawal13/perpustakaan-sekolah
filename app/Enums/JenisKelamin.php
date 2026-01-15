<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum JenisKelamin: string implements HasLabel
{
    case LAKI_LAKI = 'L';
    case PEREMPUAN = 'P';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::LAKI_LAKI => 'Laki - Laki',
            self::PEREMPUAN => 'Perempuan'
        };
    }
}
