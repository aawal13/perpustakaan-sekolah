<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Setting;
use App\Models\Peminjaman;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class BukuYgMendekatiPeminjaman extends TableWidget
{

    public function table(Table $table): Table
    {
        $maksHariPinjam = (int) Setting::get('maks_hari_pinjam');
        $hariMenjelangJatuhTempo = (int) Setting::get('hari_menjelang_jatuh_tempo');

        $user = Auth::user();
        $isSiswa = $user->hasRole('Siswa');

            $heading = "Buku yang Mendekati Batas Peminjaman";
        

        if ($maksHariPinjam <= 0) {
            $query = Peminjaman::query()->whereRaw('1=0');
        } else {
            $query = Peminjaman::query()
                ->whereNull('tanggal_dikembalikan') // Hanya yang belum dikembalikan
                ->whereDate(DB::raw("DATE_ADD(tanggal_dipinjam, INTERVAL {$maksHariPinjam} DAY)"), '>=', now())
                ->whereDate(DB::raw("DATE_ADD(tanggal_dipinjam, INTERVAL {$maksHariPinjam} DAY)"), '<=', now()->addDays($hariMenjelangJatuhTempo))
                ->orderBy(DB::raw("DATE_ADD(tanggal_dipinjam, INTERVAL {$maksHariPinjam} DAY)"), 'asc');
        }

        // Jika user adalah siswa, filter hanya peminjaman mereka sendiri
        if ($isSiswa && $user->siswa) {
            $query->where('siswa_id', $user->siswa->id);
        }

        return $table
            ->heading($heading)
            ->paginated()
            ->query(fn(): Builder => $query)
            ->columns([
                TextColumn::make('siswa.name')
                    ->searchable()
                    ->visible(fn () => !auth()->user()->hasRole('Siswa')),
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
