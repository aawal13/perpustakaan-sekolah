<?php

namespace App\Filament\Siswa\Pages;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginSiswa extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('no_identitas')
                    ->label('NIS')
                    ->required()
                    ->exists(table: 'users', column: 'no_identitas')
                    ->validationMessages([
                        'exists' => 'NIS belum terdaftar.',
                    ]),
                TextInput::make('password')
                    ->label(__('filament-panels::auth/pages/login.form.password.label'))
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->autocomplete('current-password')
                    ->required()
                ]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'no_identitas' => $data['no_identitas'],
            'password' => $data['password'],
        ];
    }

    public function authenticate(): ?LoginResponse
{

    $data = $this->form->getState();
    $credentials = $this->getCredentialsFromFormData($data);

    if (! Auth::attempt($credentials, false)) {
    throw ValidationException::withMessages([
        'password' => 'Password salah.',
    ]);
}

    $user = Auth::user();

    if (! $user->hasRole('Siswa')) {
        Auth::logout();
        $this->addError('no_identitas', 'Akun ini tidak memiliki akses sebagai Siswa.');
        return null;
    }

    return app(LoginResponse::class);
}
}

