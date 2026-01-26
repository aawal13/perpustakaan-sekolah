<?php

namespace App\Filament\Admin\Resources\Peminjamen\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class PeminjamanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('siswa_id')
                    ->relationship('siswa', 'name')
                    ->searchable()
                    ->required()
                    ->preload(),

                Select::make('buku_id')
                    ->relationship('buku', 'judul')
                    ->searchable()
                    ->required()
                    ->preload(),

                DatePicker::make('tanggal_dipinjam')
                    ->default(now())
                    ->native(false)
                    ->required(),
            ]);
    }
}
