<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PeminjamanPerbulan extends ChartWidget
{
    protected ?string $heading = 'Peminjaman PerBulan';

    protected string $color = 'info';

    // public ?string $filter = ' ';

    // protected ?string $pollingInterval = '5s';

    protected function getData(): array
    {

        $year = (int) ($this->filter ?? now()->year);

        $start = Carbon::create($year, 1, 1)->startOfYear();
        $end = Carbon::create($year, 12, 31)->endOfYear();

        $user = Filament::auth()->user();
        $isSiswa = $user && $user->hasRole('Siswa');

        $query = Peminjaman::query();

        // Filter untuk siswa
        if ($isSiswa) {
            $siswa = $user->siswa;
            if ($siswa) {
                $query->where('siswa_id', $siswa->id);
            }
        }

        $data = Trend::query($query)
            ->dateColumn('tanggal_dipinjam')
            ->between(
                start: $start,
                end: $end,
            )
            ->perMonth()
            ->count();

        $label = $isSiswa ? "Peminjaman Saya Tahun {$year}" : "Peminjaman Tahun {$year}";

        return [
            'datasets' => [
                [
                    'label' => $label,
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(
                fn (TrendValue $value) => Carbon::parse($value->date)->translatedFormat('M')
            ),
        ];
    }

    protected function getFilters(): ?array
    {
        $startYear = 2025;
        $currentYear = now()->year;

        return collect(range($currentYear, $startYear, -1))
            ->mapWithKeys(fn ($year) => [
                (string) $year => (string) $year,
            ])
            ->toArray();
    }

    protected function getDefaultFilter(): ?string
    {
        return (string) now()->year;
    }

    public function getDescription(): ?string
    {
        return 'Peminjaman kumulatif setiap bulan sesuai tahun yang kamu pilih.';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'scales' => [
                'y' => [
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
