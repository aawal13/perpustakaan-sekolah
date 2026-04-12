<?php

namespace App\Filament\Admin\Resources\Persetujuans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PersetujuanForm
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
                    ->label('Tanggal Dipinjam')
                    ->date()
                    ->required(),
            ]);
    }
}
