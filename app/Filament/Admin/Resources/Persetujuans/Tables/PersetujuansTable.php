<?php

namespace App\Filament\Admin\Resources\Persetujuans\Tables;

use App\Enums\StatusPeminjaman;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PersetujuansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.name')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->label('Nama Siswa'),
                TextColumn::make('buku.judul')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->label('Judul Buku'),
               TextColumn::make('tanggal_dipinjam')
                    ->date('d M, Y')
                    ->sortable()
                    ->label('Tanggal Dipinjam'),
                TextColumn::make('tanggal_dikembalikan')
                    ->date('d M, Y')
                    ->sortable()
                    ->label('Tanggal Dikembalikan'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('setujui')
                        ->label('SETUJUI')
                        ->color('success')
                        ->icon(Heroicon::HandThumbUp),
                Action::make('tolak')
                        ->label('TOLAK')
                        ->color('danger')
                        ->icon(Heroicon::XCircle)    
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
