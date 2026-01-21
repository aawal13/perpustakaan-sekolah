<?php

namespace App\Filament\Admin\Resources\Bukus\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BukusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->searchable(),
                TextColumn::make('pengarang')
                    ->searchable(),
                TextColumn::make('penerbit')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('tahun_terbit')
                    ->placeholder('-')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kategori.kategori_buku')
                    ->placeholder('-')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('kategori')
                    ->relationship('kategori', 'kategori_buku'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                    DeleteBulkAction::make()
            ]);
    }
}
