<?php

namespace App\Filament\Admin\Resources\Peminjaman\Tables;

use App\Enums\StatusPeminjaman;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PeminjamanTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('buku.judul')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
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
                    ->money('ID', true)
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
                        ->icon(Heroicon::ArrowLeftEndOnRectangle)
                        ->visible(fn ($record) => $record->status === StatusPeminjaman::DIPINJAM || $record->status === StatusPeminjaman::TERLAMBAT)
                        ->schema([
                            TextInput::make('batas_peminjaman')
                                ->label('Batas Peminjaman')
                                ->disabled()
                                ->dehydrated(false)
                                ->default(function ($record) {
                                    if (! $record->batas_peminjaman) {
                                        return '-';
                                    }

                                    return $record->batas_peminjaman->format('d M Y');
                                }),

                            DatePicker::make('tanggal_dikembalikan')
                                ->default(now())
                                ->native(false)
                                ->minDate(fn ($record) => $record->tanggal_dipinjam)
                                ->required(),

                        ])
                        ->action(function (array $data, $record) {

                            $record->update([
                                'tanggal_dikembalikan' => $data['tanggal_dikembalikan'],
                                'status' => StatusPeminjaman::DIKEMBALIKAN,
                            ]);

                            $record->refreshStatusDanDenda();
                        })
                        ->successNotificationTitle('Buku berhasil dikembalikan')
                        ->visible(fn () => !auth()->user()->hasRole('Siswa')),

                ]),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->visible(fn () => !auth()->user()->hasRole('Siswa'))
            ])
            ->emptyStateHeading('No Peminjaman');
    }
}
