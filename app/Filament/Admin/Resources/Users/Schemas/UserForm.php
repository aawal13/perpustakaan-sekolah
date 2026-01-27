<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DatePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn ($operation) => $operation == 'create'),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable()
                    ->live(),
                Select::make('no_identitas')
                    ->label('Pilih siswa')
                    ->relationship('siswa', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn($get) => Role::find($get('roles'))?->name == 'Siswa'),
            ]);
    }
}
