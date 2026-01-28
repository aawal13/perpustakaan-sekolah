<?php

namespace App\Filament\Admin\Resources\Peminjamen\Pages;

use App\Enums\StatusPeminjaman;
use App\Filament\Admin\Resources\Peminjamen\PeminjamanResource;
use App\Models\Peminjaman;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
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

    /**
     * Get base query filtered by logged-in user (siswa)
     */
    protected function getBaseQuery(): Builder
    {
        $query = Peminjaman::query();

        $user = Filament::auth()->user();

        if (!$user) {
            return $query;
        }

        // Filter by student if user has 'Siswa' role and has no_identitas
        if ($user->hasRole('Siswa') && filled($user->no_identitas)) {
            return $query->whereHas('siswa', function ($q) use ($user) {
                $q->where('nis', $user->no_identitas);
            });
        }

        return $query;
    }

    /**
     * Get filtered count by status
     */
    protected function getCountByStatus(StatusPeminjaman $status): int
    {
        return $this->getBaseQuery()->where('status', $status)->count();
    }

    /**
     * Get total count filtered by user
     */
    protected function getTotalCount(): int
    {
        return $this->getBaseQuery()->count();
    }

    public function getTabs(): array
    {
        $user = Filament::auth()->user();
        $isSiswa = $user && $user->hasRole('Siswa') && filled($user->no_identitas);

        return [
            'Semua' => Tab::make()
                ->badge(fn () => $isSiswa ? $this->getTotalCount() : Peminjaman::count())
                ->modifyQueryUsing(fn (Builder $query) => $this->filterByUser($query)),

            'Dipinjam' => Tab::make()
                ->badge(fn () => $isSiswa ? $this->getCountByStatus(StatusPeminjaman::DIPINJAM) : Peminjaman::where('status', StatusPeminjaman::DIPINJAM)->count())
                ->modifyQueryUsing(fn (Builder $query) => $this->filterByUser($query)->where('status', StatusPeminjaman::DIPINJAM)),

            'Dikembalikan' => Tab::make()
                ->badge(fn () => $isSiswa ? $this->getCountByStatus(StatusPeminjaman::DIKEMBALIKAN) : Peminjaman::where('status', StatusPeminjaman::DIKEMBALIKAN)->count())
                ->modifyQueryUsing(fn (Builder $query) => $this->filterByUser($query)->where('status', StatusPeminjaman::DIKEMBALIKAN)),

            'Terlambat' => Tab::make()
                ->badge(fn () => $isSiswa ? $this->getCountByStatus(StatusPeminjaman::TERLAMBAT) : Peminjaman::where('status', StatusPeminjaman::TERLAMBAT)->count())
                ->modifyQueryUsing(fn (Builder $query) => $this->filterByUser($query)->where('status', StatusPeminjaman::TERLAMBAT)),
        ];
    }

    /**
     * Apply user filter to query
     */
    protected function filterByUser(Builder $query): Builder
    {
        $user = Filament::auth()->user();

        if (!$user) {
            return $query;
        }

        if ($user->hasRole('Siswa') && filled($user->no_identitas)) {
            return $query->whereHas('siswa', function ($q) use ($user) {
                $q->where('nis', $user->no_identitas);
            });
        }

        return $query;
    }
}
