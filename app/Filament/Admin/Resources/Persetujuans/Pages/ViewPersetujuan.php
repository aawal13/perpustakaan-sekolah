<?php

namespace App\Filament\Admin\Resources\Persetujuans\Pages;

use App\Filament\Admin\Resources\Persetujuans\PersetujuanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPersetujuan extends ViewRecord
{
    protected static string $resource = PersetujuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
