<?php

namespace App\Filament\Admin\Resources\Bukus\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BukuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->required(),
                TextInput::make('pengarang')
                    ->required(),
                TextInput::make('penerbit'),
                TextInput::make('tahun_terbit')
                    ->numeric(),
                Select::make('kategori_id')
                    ->relationship('kategori', 'kategori_buku')
                    ->preload()
                    ->searchable(),
                TextInput::make('stok')
                    ->numeric(),
            ]);
    }
}
