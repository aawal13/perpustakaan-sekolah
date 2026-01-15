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
    { Schema::table('peminjaman', function (Blueprint $table) {
            $table->date('tanggal_dikembalikan')->nullable()->change();
            $table->date('tanggal_dipinjam')->nullable()->change();
            $table->dropForeign(['buku_id']);
            $table->foreign('buku_id')
                ->references('id')
                ->on('buku')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropForeign(['buku_id']);
            $table->foreign('buku_id')
                ->references('id')
                ->on('bukus')
                ->cascadeOnDelete();

            $table->date('tanggal_dikembalikan')->nullable(false)->change();
            $table->date('tanggal_dipinjam')->nullable(false)->change();
        });
    }
};
