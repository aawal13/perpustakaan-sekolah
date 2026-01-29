<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
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
    use InteractsWithSchemas, HasPageShield;

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
            'hari_menjelang_jatuh_tempo' => Setting::get('hari_menjelang_jatuh_tempo'),
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
                Section::make('Widget Peminjaman')
                    ->schema([
                        TextInput::make('hari_menjelang_jatuh_tempo')
                            ->label('Tampilkan Jika Jatuh Tempo Dalam (Hari)')
                            ->numeric()
                            ->default(3)
                            ->minValue(1),
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
        Setting::set('hari_menjelang_jatuh_tempo', (int) $data['hari_menjelang_jatuh_tempo']);

        Notification::make()
            ->title('Berhasil')
            ->body('Pengaturan berhasil disimpan')
            ->success()
            ->send();

        $this->form->fill($data);

    }
}
