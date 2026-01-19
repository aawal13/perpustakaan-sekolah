<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Enums\StatusPeminjaman;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Buku', Buku::count()),
            Stat::make('Total Peminjaman', Peminjaman::count()),
            Stat::make('Total Dipinjam',  Peminjaman::where('status', StatusPeminjaman::DIPINJAM)->count()),
            Stat::make('Total Denda', 'Rp ' . number_format(Peminjaman::sum('denda'), 0, ',', '.'))

    ];
}
}