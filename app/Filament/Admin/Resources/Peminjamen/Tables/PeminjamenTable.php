<?php

namespace App\Filament\Admin\Resources\Peminjamen\Tables;

use Filament\Tables\Table;
use App\Enums\StatusPeminjaman;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;

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
                    ->date()
                    ->sortable(),
                TextColumn::make('batas_peminjaman')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tanggal_dikembalikan')
                    ->date()
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
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}