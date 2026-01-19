<?php

namespace App\Filament\Admin\Widgets;

use Carbon\Carbon;
use App\Models\Peminjaman;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

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
    $end   = Carbon::create($year, 12, 31)->endOfYear();

    $data = Trend::model(Peminjaman::class)
        ->dateColumn('tanggal_dipinjam')
        ->between(
            start: $start,
            end: $end,
        )
        ->perMonth()
        ->count();

        return [
        'datasets' => [
            [
                'label' => "Peminjaman Tahun {$year}",
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(
            fn (TrendValue $value) =>
                Carbon::parse($value->date)->translatedFormat('M')
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