<?php

namespace App\Filament\Admin\Resources\Peminjaman\Pages;

use App\Filament\Admin\Resources\Peminjaman\PeminjamanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPeminjaman extends EditRecord
{
    protected static string $resource = PeminjamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
