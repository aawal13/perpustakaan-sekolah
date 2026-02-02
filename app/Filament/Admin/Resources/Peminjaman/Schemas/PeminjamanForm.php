<?php

namespace App\Filament\Admin\Resources\Peminjaman\Schemas;

use App\Models\Buku;
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
                    ->preload()
                    ->options(function () {
                        return Buku::query()
                            ->with('peminjaman')
                            ->get()
                            ->mapWithKeys(function (Buku $buku) {
                                $stokTersedia = $buku->stok;
                                $label = $buku->judul;
                                
                                if ($stokTersedia <= 0) {
                                    $label .= ' (Stok Habis)';
                                } else {
                                    $label .= " (Tersedia: {$stokTersedia})";
                                }
                                
                                return [$buku->id => $label];
                            })
                            ->toArray();
                    })
                    ->disableOptionWhen(function (int $value): bool {
                        $buku = Buku::query()
                            ->with('peminjaman')
                            ->find($value);
                        
                        return $buku && $buku->stok <= 0;
                    }),

                DatePicker::make('tanggal_dipinjam')
                    ->default(now())
                    ->native(false)
                    ->required(),
            ]);
    }
}

