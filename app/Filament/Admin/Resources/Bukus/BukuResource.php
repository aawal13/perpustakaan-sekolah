<?php

namespace App\Filament\Admin\Resources\Bukus;

use App\Filament\Admin\Resources\Bukus\Pages\CreateBuku;
use App\Filament\Admin\Resources\Bukus\Pages\EditBuku;
use App\Filament\Admin\Resources\Bukus\Pages\ListBukus;
use App\Filament\Admin\Resources\Bukus\Pages\ViewBuku;
use App\Filament\Admin\Resources\Bukus\Schemas\BukuForm;
use App\Filament\Admin\Resources\Bukus\Schemas\BukuInfolist;
use App\Filament\Admin\Resources\Bukus\Tables\BukusTable;
use App\Models\Buku;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BukuResource extends Resource
{
    protected static ?string $model = Buku::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $pluralModelLabel = 'Buku';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Data';

    public static function form(Schema $schema): Schema
    {
        return BukuForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BukuInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BukusTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBukus::route('/'),
            'create' => CreateBuku::route('/create'),
            'view' => ViewBuku::route('/{record}'),
            'edit' => EditBuku::route('/{record}/edit'),
        ];
    }
}
