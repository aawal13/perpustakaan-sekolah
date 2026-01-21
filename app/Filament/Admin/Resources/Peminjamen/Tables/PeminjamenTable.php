<?php

namespace App\Filament\Admin\Resources\Peminjamen\Tables;

use App\Models\Setting;
use App\Models\Peminjaman;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Enums\StatusPeminjaman;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;

class PeminjamenTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('buku.judul')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tanggal_dipinjam')
                    ->date('d M, Y')
                    ->sortable(),
                TextColumn::make('batas_peminjaman')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->getStateUsing(function ($record) {
                        return $record->batas_peminjaman
                            ? $record->batas_peminjaman->format('d M, Y')
                            : '-';
                    }),
                TextColumn::make('tanggal_dikembalikan')
                    ->date('d M, Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->label('Status'),
                TextColumn::make('denda')
                    ->money('ID',true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                ViewAction::make()
                    ->label('Detail'),
                EditAction::make(),
                DeleteAction::make()
                    ->label('Hapus')
                    ->successNotification(
                        Notification::make()
                        ->success()
                        ->title('Peminjaman di hapus')
                    ),
                Action::make('kembalikan_buku')
                    ->label('Kembalikan buku')
                    ->color('success')
                    ->icon(Heroicon::BookOpen)
                    ->visible(fn ($record) => $record->status === StatusPeminjaman::DIPINJAM || $record->status === StatusPeminjaman::TERLAMBAT)
                    ->schema([
                        DatePicker::make('tanggal_dikembalikan')
                            ->default(now())
                            ->native(false)
                            ->minDate(fn ($record) => $record->tanggal_dipinjam)
                            ->required(),

                        TextInput::make('maks_hari_pinjam')
                            ->disabled()
                            ->default(fn () => Setting::get('maks_hari_pinjam')),
            ])
            ->action(function (array $data, $record) {

                $record->update([
                    'tanggal_dikembalikan' => $data['tanggal_dikembalikan'],
                    'status' => StatusPeminjaman::DIKEMBALIKAN,
                ]);

                $record->refreshStatusDanDenda();
            })
            ->successNotificationTitle('Buku berhasil dikembalikan')

                ])
            ])
            ->toolbarActions([
                    DeleteBulkAction::make()
            ]);
    }
}