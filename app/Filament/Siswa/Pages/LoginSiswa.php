<?php

namespace App\Filament\Siswa\Pages;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;

class LoginSiswa extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nis')
                    ->label('NIS')
                    ->required(),

                $this->getPasswordFormComponent(),
            ]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'nis' => $data['nis'],
            'password' => $data['password'],
        ];
    }

    public function authenticate(): ?LoginResponse
    {
        $this->rateLimit(5);

        $credentials = $this->getCredentialsFromFormData($this->form->getState());

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            $this->throwFailureException();
        }

        $user = Auth::user();

        // Verifikasi user memiliki role 'Siswa'
        if (!$user->hasRole('Siswa')) {
            Auth::logout();
            $this->throwFailureValidationException(
                $this->getForm(),
                ['nis' => 'Akun ini tidak memiliki akses sebagai Siswa.']
            );
        }

        $this->sessionRegenerateId();

        return app(LoginResponse::class);
    }
}

