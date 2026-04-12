<?php

namespace App\Filament\Admin\Resources\Persetujuans\Tables;

use App\Enums\StatusPeminjaman;
use App\Models\Peminjaman;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
            ])
            ->filters([
                //
            ])
            ->recordActions([
            Action::make('setujui')
    ->label('Setujui')
    ->color('success')
    ->icon('heroicon-o-check')
    ->action(function ($record) {

        // 1. Buat peminjaman
        Peminjaman::create([
            'siswa_id' => $record->siswa_id,
            'buku_id' => $record->buku_id,
            'tanggal_dipinjam' => $record->tanggal_dipinjam,
            'status' => 'dipinjam', // atau enum kamu
            'denda' => 0,
        ]);

        // 2. Hapus persetujuan
        $record->delete();

        Notification::make()
            ->title('Peminjaman disetujui')
            ->success()
            ->send();
    }),

    Action::make('tolak')
    ->label('Tolak')
    ->color('danger')
    ->icon('heroicon-o-x-mark')
    ->action(function ($record) {

        $record->delete();

        Notification::make()
            ->title('Peminjaman ditolak')
            ->danger()
            ->send();
    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
