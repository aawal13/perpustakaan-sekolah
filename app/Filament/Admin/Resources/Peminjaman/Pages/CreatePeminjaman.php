<?php

namespace App\Filament\Admin\Resources\Peminjaman\Pages;

use App\Filament\Admin\Resources\Peminjaman\PeminjamanResource;
use App\Models\Peminjaman;
use App\Models\Persetujuan;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePeminjaman extends CreateRecord
{
    protected static string $resource = PeminjamanResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $user = auth()->user();

        // 👨‍🎓 SISWA → masuk persetujuan
        if ($user->hasRole('Siswa')) {

            $siswa = $user->siswa;

            if (! $siswa) {
                Notification::make()
                    ->title('Data siswa tidak ditemukan')
                    ->danger()
                    ->send();

                throw new \Exception('Siswa tidak ditemukan');
            }

            Persetujuan::create([
                'siswa_id' => $siswa->id,
                'buku_id' => $data['buku_id'],
                'tanggal_dipinjam' => $data['tanggal_dipinjam'],
            ]);

            Notification::make()
                ->title('Permintaan peminjaman dikirim ke admin')
                ->success()
                ->send();

            // ❗ STOP CREATE PEMINJAMAN
             $this->halt();
        }

        // 👨‍💼 ADMIN → langsung peminjaman
        return parent::handleRecordCreation($data);
    }
}