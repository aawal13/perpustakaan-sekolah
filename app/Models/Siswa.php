<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'name',
        'jenis_kelamin',
        'tanggal_lahir',
        'kelas',
    ];

    protected $casts = [
        'jenis_kelamin' => JenisKelamin::class,
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class,);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'no_identitas', 'nis');
    }
}
