<?php

namespace App\Filament\Admin\Resources\Peminjamen\Pages;

use App\Models\Peminjaman;
use App\Enums\StatusPeminjaman;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\Peminjamen\PeminjamanResource;

class ListPeminjamen extends ListRecords
{
    protected static string $resource = PeminjamanResource::class;

    public function mount(?string $activeTab = null): void
    {
        parent::mount($activeTab);

        Peminjaman::whereNull('tanggal_dikembalikan')
            ->orWhere('status', StatusPeminjaman::TERLAMBAT)
            ->get()
            ->each
            ->refreshStatusDanDenda();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}