<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SettingsPage extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.admin.pages.settings-page';

    protected static ?string $navigationLabel = 'Umum';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    public ?array $data = [];

    protected static ?int $navigationSort = 10;

    public function mount(): void
    {
        $this->form->fill([
            'denda_perhari' => Setting::get('denda_perhari'),
            'max_denda' => Setting::get('max_denda'),
            'maks_hari_pinjam' => setting::get('maks_hari_pinjam'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Denda')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('denda_perhari')
                                    ->label('Denda per Hari')
                                    ->numeric(),
                                TextInput::make('max_denda')
                                    ->label('Maksimal Denda')
                                    ->numeric(),
                                TextInput::make('maks_hari_pinjam')
                                    ->label('Maksimal Hari Pinjam')
                                    ->numeric()
                                    ->minValue(1),

                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();

        Setting::set('denda_perhari', (int) $data['denda_perhari']);
        Setting::set('max_denda', (int) $data['max_denda']);
        Setting::set('maks_hari_pinjam', (int) $data['maks_hari_pinjam']);

        Notification::make()
            ->title('Berhasil')
            ->body('Pengaturan denda berhasil disimpan')
            ->success()
            ->send();

        $this->form->fill($data);

    }
}
