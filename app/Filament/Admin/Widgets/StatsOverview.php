<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\StatusPeminjaman;
use App\Models\Buku;
use App\Models\Peminjaman;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getColumns(): int
    {
        return 5;
    }

    protected function getStats(): array
    {
        $user = Filament::auth()->user();
        $isSiswa = $user && $user->hasRole('Siswa');

        $peminjamanQuery = Peminjaman::query();

        // Filter untuk siswa
        if ($isSiswa) {
            $siswa = $user->siswa;
            if ($siswa) {
                $peminjamanQuery->where('siswa_id', $siswa->id);
            }
        }

        return [
    Stat::make('Jumlah Buku', Buku::count()),

    Stat::make(
        'Jumlah Peminjaman',
        (clone $peminjamanQuery)->count()
    ),

    Stat::make(
        'Status Dipinjam',
        (clone $peminjamanQuery)
            ->where('status', StatusPeminjaman::DIPINJAM)
            ->count()
    ),
    
    Stat::make(
        'Status Terlambat',
        (clone $peminjamanQuery)
            ->where('status', StatusPeminjaman::TERLAMBAT)
            ->count()
    ),

    Stat::make(
        'Total Denda',
        'Rp ' . number_format(
            (clone $peminjamanQuery)->sum('denda'),
            0,
            ',',
            '.'
        )
    ),
];

    }
}
