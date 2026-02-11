<?php

namespace App\Filament\Admin\Pages;

use Filament\Auth\Pages\Register as BasePage;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class Register extends BasePage
{
    // protected string $view = 'filament.admin.pages.register';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nis')
                    ->label('NIS')
                    ->required(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }   

    protected function handleRegistration(array $data): Model
    {
        return $this->getUserModel()::create($data);
    }
}
