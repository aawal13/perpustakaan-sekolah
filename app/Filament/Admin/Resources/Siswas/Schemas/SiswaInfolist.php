<?php

namespace App\Filament\Admin\Resources\Siswas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SiswaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nis'),
                TextEntry::make('name'),
                TextEntry::make('jenis_kelamin'),
                TextEntry::make('tanggal_lahir')
                    ->date('j M Y'),
                TextEntry::make('kelas'),
            ]);
    }
}
