<?php

namespace App\Filament\Admin\Resources\Peminjaman\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PeminjamanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('buku.judul')

                    ->numeric(),
                TextEntry::make('siswa.name')
                    ->label('nama')
                    ->numeric(),
                TextEntry::make('tanggal_dipinjam')
                    ->date(),
                TextEntry::make('tanggal_dikembalikan')
                    ->date(),
                TextEntry::make('status'),
                TextEntry::make('denda')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
