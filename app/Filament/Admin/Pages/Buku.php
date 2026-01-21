<?php

namespace App\Filament\Admin\Pages;

use App\Models\Buku as ModelsBuku;
use UnitEnum;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class Buku extends Page implements HasActions, HasSchemas, HasTable
{
    
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;
    
    protected string $view = 'filament.admin.pages.buku';

    protected static string|BackedEnum|null $navigationIcon = Heroicon:: OutlinedBookOpen;

    protected static string|UnitEnum|null $navigationGroup = 'rekap';
    

    public function table(Table $table): Table
      {
          return $table
             ->query(
              ModelsBuku::query()->withCount('peminjaman'))
              ->defaultSort('peminjaman_count','desc')
              ->columns([
                TextColumn::make('judul'),
                TextColumn::make('kategori.kategori_buku'),
                TextColumn::make('peminjaman_count')
                  ->label('Jumlah Peminjaman')
                  ->sortable(),
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


