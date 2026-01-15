<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            // Hanya hapus kolom genre kalau ada
            if (Schema::hasColumn('bukus', 'genre')) {
                $table->dropColumn('genre');
            }

            // Tambah kategori_id kalau belum ada
            if (! Schema::hasColumn('bukus', 'kategori_id')) {
                $table->foreignId('kategori_id')
                    ->nullable() // supaya aman untuk data lama
                    ->after('id')
                    ->constrained('kategori')
                    ->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            if (Schema::hasColumn('bukus', 'kategori_id')) {
                $table->dropForeign(['kategori_id']);
                $table->dropColumn('kategori_id');
            }

            if (! Schema::hasColumn('bukus', 'genre')) {
                $table->string('genre')->nullable();
            }
        });
    }
};
