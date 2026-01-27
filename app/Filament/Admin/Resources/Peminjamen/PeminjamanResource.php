<?php

namespace App\Filament\Admin\Resources\Peminjamen;

use App\Filament\Admin\Resources\Peminjamen\Pages\CreatePeminjaman;
use App\Filament\Admin\Resources\Peminjamen\Pages\EditPeminjaman;
use App\Filament\Admin\Resources\Peminjamen\Pages\ListPeminjamen;
use App\Filament\Admin\Resources\Peminjamen\Pages\ViewPeminjaman;
use App\Filament\Admin\Resources\Peminjamen\Schemas\PeminjamanForm;
use App\Filament\Admin\Resources\Peminjamen\Schemas\PeminjamanInfolist;
use App\Filament\Admin\Resources\Peminjamen\Tables\PeminjamenTable;
use App\Models\Peminjaman;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;

class PeminjamanResource extends Resource
{
    protected static ?string $model = Peminjaman::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocument;

    protected static ?string $pluralModelLabel = 'Peminjaman';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return PeminjamanForm::configure($schema);
    }
    
    public static function infolist(Schema $schema): Schema
    {
        return PeminjamanInfolist::configure($schema);
    }
    
    public static function table(Table $table): Table
    {
        return PeminjamenTable::configure($table);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Filament::auth()->user();

        if (! $user) {
            return $query;
        }

        if ($user->hasRole('Siswa') && filled($user->no_identitas)) {
            return $query->whereHas('siswa', function ($q) use ($user) {
                $q->where('nis', $user->no_identitas);
            });
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPeminjamen::route('/'),
            'create' => CreatePeminjaman::route('/create'),
            'view' => ViewPeminjaman::route('/{record}'),
            'edit' => EditPeminjaman::route('/{record}/edit'),
        ];
    }
}
