<?php

namespace App\Filament\Siswa\Pages;

use App\Models\Siswa;
use App\Models\User;
use Filament\Auth\Pages\Register as BasePage;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class Register extends BasePage
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nis')
                    ->label('NIS')
                    ->required()
                    ->exists(table: 'siswa', column: 'nis')
                    ->unique(table: 'users', column: 'no_identitas')
                    ->validationMessages([
                        'exists' => 'Nis belum terdaftar.',
                        'unique' => 'NIS ini sudah digunakan.',
                    ]),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }   

    protected function handleRegistration(array $data): Model
    {
        // Validasi NIS harus ada di table siswa
        $siswa = Siswa::where('nis', $data['nis'])->first();
        
        if (!$siswa) {
            throw ValidationException::withMessages([
                'nis' => ['NIS tidak ditemukan di data siswa. Silakan hubungi administrator.'],
            ]);
        }
        
        // Validasi user belum terdaftar
        $userExists = User::where('no_identitas', $data['nis'])->exists();
        if ($userExists) {
            throw ValidationException::withMessages([
                'nis' => ['Siswa dengan NIS ini sudah memiliki akun. Silakan login.'],
            ]);
        }
        
        // Set name dari table siswa, dan no_identitas = nis
        $data['name'] = $siswa->name;
        $data['no_identitas'] = $data['nis'];
        $data['email'] = $data['name'] . '@gmail.com';
        
        $user = $this->getUserModel()::create($data);
        
        // Assign role 'Siswa' ke user yang baru register
        $siswaRole = Role::where('name', 'Siswa')->first();
        if ($siswaRole) {
            $user->assignRole($siswaRole);
        }
        
        return $user;
    }
}

