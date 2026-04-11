<?php

namespace App\Filament\Admin\Resources\Persetujuans;

use App\Filament\Admin\Resources\Persetujuans\Pages\CreatePersetujuan;
use App\Filament\Admin\Resources\Persetujuans\Pages\EditPersetujuan;
use App\Filament\Admin\Resources\Persetujuans\Pages\ListPersetujuans;
use App\Filament\Admin\Resources\Persetujuans\Pages\ViewPersetujuan;
use App\Filament\Admin\Resources\Persetujuans\Schemas\PersetujuanForm;
use App\Filament\Admin\Resources\Persetujuans\Schemas\PersetujuanInfolist;
use App\Filament\Admin\Resources\Persetujuans\Tables\PersetujuansTable;
use App\Models\Persetujuan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PersetujuanResource extends Resource
{
    protected static ?string $model = Persetujuan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static ?string $recordTitleAttribute = 'Persetujuan';

    protected static ?string $pluralModelLabel = 'Persetujuan';

    protected static ?string $slug = 'Persetujuan';

    public static function form(Schema $schema): Schema
    {
        return PersetujuanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PersetujuanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersetujuansTable::configure($table);
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
            'index' => ListPersetujuans::route('/'),
            'create' => CreatePersetujuan::route('/create'),
            'view' => ViewPersetujuan::route('/{record}'),
            'edit' => EditPersetujuan::route('/{record}/edit'),
        ];
    }
}
