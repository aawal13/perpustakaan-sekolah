<?php

namespace App\Filament\Admin\Resources\Kategoris\Tables;

use App\Models\Kategori;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KategorisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('bukus_count', 'desc')
            ->query(
                Kategori::query()->withCount('bukus')
            )
            ->columns([
                TextColumn::make('kategori_buku')->searchable(),
                TextColumn::make('bukus_count')
                    ->label('Total Buku')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->visible(fn () => !Filament::auth()->user()->hasRole('Siswa')),
            ]);
    }
}
