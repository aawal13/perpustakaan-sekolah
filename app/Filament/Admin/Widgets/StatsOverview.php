<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\StatusPeminjaman;
use App\Filament\Admin\Resources\Bukus\BukuResource;
use App\Filament\Admin\Resources\Peminjaman\PeminjamanResource;
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
    Stat::make('Jumlah Buku', Buku::count())
        ->url(BukuResource::getUrl('index')
        ),

    Stat::make(
        'Jumlah Peminjaman',
        (clone $peminjamanQuery)->count())
        ->url(
            PeminjamanResource::getUrl('index',[
                'activeTab' => 'Semua',
            ])
        ),

    Stat::make(
        'Status Dipinjam',
        (clone $peminjamanQuery)
            ->where('status', StatusPeminjaman::DIPINJAM)
            ->count())
        ->url(
            PeminjamanResource::getUrl('index',[
                'activeTab' => 'Dipinjam',
            ])
        ),
    
    Stat::make(
        'Status Terlambat',
        (clone $peminjamanQuery)
            ->where('status', StatusPeminjaman::TERLAMBAT)
            ->count())
        ->url(
            PeminjamanResource::getUrl('index',[
                'activeTab' => 'Terlambat',
            ])
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
