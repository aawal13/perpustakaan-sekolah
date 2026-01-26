<?php

namespace App\Filament\Admin\Resources\Peminjamen\Pages;

use App\Enums\StatusPeminjaman;
use App\Filament\Admin\Resources\Peminjamen\PeminjamanResource;
use App\Models\Peminjaman;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

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
            CreateAction::make()
                ->label('Buat peminjaman'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make(),

            'Dipinjam' => Tab::make()
                ->badge(fn () => Peminjaman::where('status', StatusPeminjaman::DIPINJAM)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusPeminjaman::DIPINJAM)
                ),

            'Dikembalikan' => Tab::make()
                ->badge(fn () => Peminjaman::where('status', StatusPeminjaman::DIKEMBALIKAN)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusPeminjaman::DIKEMBALIKAN)
                ),

            'Terlambat' => Tab::make()
                ->badge(fn () => Peminjaman::where('status', StatusPeminjaman::TERLAMBAT)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusPeminjaman::TERLAMBAT)
                ),
        ];
    }
}
