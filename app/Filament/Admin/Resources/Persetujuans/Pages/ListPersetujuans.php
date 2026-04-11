<?php

namespace App\Filament\Admin\Resources\Persetujuans\Pages;

use App\Filament\Admin\Resources\Persetujuans\PersetujuanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersetujuans extends ListRecords
{
    protected static string $resource = PersetujuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
