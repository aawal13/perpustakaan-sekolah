<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Setting;
use App\Models\Peminjaman;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class BukuYgMendekatiPeminjaman extends TableWidget
{

    public function table(Table $table): Table
    {
        $maksHariPinjam = (int) Setting::get('maks_hari_pinjam');
        $hariMenjelangJatuhTempo = (int) Setting::get('hari_menjelang_jatuh_tempo', 3);

        $heading = "Buku yang Mendekati Batas Peminjaman";
       $description = new HtmlString("Daftar batas peminjaman akan muncul di mulai hari ini selanjutnya di + <span class='text-danger-600 font-semibold'> {$hariMenjelangJatuhTempo}</span> hari ke depan dan batas peminjaman yang sudah mau jatuh tempo akan paling di atas.");

        return $table
            ->heading($heading)
            ->description($description)
            ->paginated()
            ->query(
                fn(): Builder => Peminjaman::query()
                    ->whereNull('tanggal_dikembalikan') // Hanya yang belum dikembalikan
                    ->whereDate(DB::raw("DATE_ADD(tanggal_dipinjam, INTERVAL {$maksHariPinjam} DAY)"), '>=', now())
                    ->whereDate(DB::raw("DATE_ADD(tanggal_dipinjam, INTERVAL {$maksHariPinjam} DAY)"), '<=', now()->addDays($hariMenjelangJatuhTempo))
                    ->orderBy(DB::raw("DATE_ADD(tanggal_dipinjam, INTERVAL {$maksHariPinjam} DAY)"), 'asc')
            )
            ->columns([
                TextColumn::make('siswa.name'),
                TextColumn::make('buku.judul')
                    ->label('Judul')
                    ->searchable(),
                TextColumn::make('tanggal_dipinjam')
                    ->date('d M, Y')
                    ->sortable(),
                TextColumn::make('batas_peminjaman')
                    ->getStateUsing(function ($record) {
                        return $record->batas_peminjaman
                            ? $record->batas_peminjaman->format('d M, Y')
                            : '-';
                        })
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
