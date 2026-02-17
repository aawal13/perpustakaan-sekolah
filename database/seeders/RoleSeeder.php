<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat role 'Siswa' jika belum ada
        Role::firstOrCreate(
            ['name' => 'Siswa', 'guard_name' => 'web']
        );

        // Membuat role 'super_admin' jika belum ada
        Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web']
        );
    }
}
