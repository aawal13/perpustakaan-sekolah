<?php

namespace App\Filament\Admin\Resources\Siswas\Schemas;

use App\Enums\JenisKelamin;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nis')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('jenis_kelamin')
                    ->required()
                    ->options(JenisKelamin::class)
                    ->native(false),
                DatePicker::make('tanggal_lahir')
                    ->required()
                    ->native(false)
                    ->maxDate(now()),
                TextInput::make('kelas')
                    ->required(),

            ]);
    }
}
