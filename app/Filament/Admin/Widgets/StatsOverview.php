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
    Stat::make('Total Buku', Buku::count()),

    Stat::make(
        'Total Peminjaman',
        (clone $peminjamanQuery)->count()
    ),

    Stat::make(
        'Total Dipinjam',
        (clone $peminjamanQuery)
            ->where('status', StatusPeminjaman::DIPINJAM)
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
return [
    Stat::make('Total Buku', Buku::count()),

    Stat::make(
        'Total Peminjaman',
        (clone $peminjamanQuery)->count()
    ),

    Stat::make(
        'Total Dipinjam',
        (clone $peminjamanQuery)
            ->where('status', StatusPeminjaman::DIPINJAM)
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
