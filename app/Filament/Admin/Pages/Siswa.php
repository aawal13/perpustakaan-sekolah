<?php

namespace App\Filament\Admin\Pages;

use App\Models\Siswa as ModelsSiswa;
use BackedEnum;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use UnitEnum;

class Siswa extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    protected string $view = 'filament.admin.pages.siswa';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|UnitEnum|null $navigationGroup = 'Rekap';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ModelsSiswa::query()
                    ->withCount('peminjaman')
                    ->withSum('peminjaman', 'denda'))
            ->defaultSort('peminjaman_count', 'desc')
            ->columns([
                TextColumn::make('nis')
                   ->searchable()
                   ->label('NIS'),
                TextColumn::make('name')
                   ->searchable()
                   ->label('Nama'),
                TextColumn::make('kelas')
                   ->label('Kelas'),
                TextColumn::make('peminjaman_count')
                   ->label('Jumlah Peminjaman')
                   ->sortable(),
                TextColumn::make('peminjaman_sum_denda')
                   ->label('Total Denda')
                   ->sortable()
                   ->money('IDR', true),
            ])
            ->filters([
                // ..
            ])
            ->recordActions([
                // ...
            ])
            ->toolbarActions([
                // ...
            ]);
    }
}
