<?php

namespace App\Filament\Admin\Resources\Bukus\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BukuInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('detail')
                    ->inlineLabel()
                    ->schema([
                        TextEntry::make('judul'),
                        TextEntry::make('pengarang'),
                        TextEntry::make('penerbit')
                            ->placeholder('-'),
                        TextEntry::make('tahun_terbit')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('kategori.kategori_buku')
                            ->placeholder('-'),
                        TextEntry::make('stok')
                            ->numeric()
                            ->placeholder('Habis/Tidak ada'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
