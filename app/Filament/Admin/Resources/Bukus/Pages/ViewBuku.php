<?php

namespace App\Filament\Admin\Resources\Bukus\Pages;

use App\Filament\Admin\Resources\Bukus\BukuResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBuku extends ViewRecord
{
    protected static string $resource = BukuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
