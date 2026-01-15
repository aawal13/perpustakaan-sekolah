<?php

namespace App\Filament\Admin\Resources\Peminjamen\Tables;

use Filament\Tables\Table;
use App\Enums\StatusPeminjaman;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Date;

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
                TextColumn::make('tanggal_dikembalikan')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->formatStateUsing(
                        fn (StatusPeminjaman|string $state) =>
                            $state instanceof StatusPeminjaman
                                ? $state->label()
                                : ucfirst($state)
                    )
                    ->color(fn ($state) => match ($state instanceof StatusPeminjaman ? $state->value : $state) {
                        StatusPeminjaman::DIPINJAM->value => 'warning',
                        StatusPeminjaman::DIKEMBALIKAN->value => 'success',
                        StatusPeminjaman::TERLAMBAT->value => 'danger',
                        default => 'gray',
    }),
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
                Action::make('Kembalikan buku')
                ->color('success')
                ->icon(Heroicon::BookOpen)
                ->schema([
                    DatePicker::make('tanggal_dikembalikan')
                    ->default(now())
                    ->displayFormat('d M, Y')
                    ->native(false),
                    TextInput::make('maks_hari_pinjam')
                    ->disabled()

                ])
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

