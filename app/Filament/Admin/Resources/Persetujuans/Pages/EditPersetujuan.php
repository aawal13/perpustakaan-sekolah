<?php

namespace App\Filament\Admin\Resources\Persetujuans\Pages;

use App\Filament\Admin\Resources\Persetujuans\PersetujuanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPersetujuan extends EditRecord
{
    protected static string $resource = PersetujuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
