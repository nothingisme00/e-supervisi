<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ubah kolom role jadi enum baru
            $table->enum('role', ['admin', 'guru', 'kepala', 'kepala_sekolah'])
                  ->default('guru')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kembalikan ke enum sebelumnya (jika di-rollback)
            $table->enum('role', ['admin', 'guru', 'kepala'])
                  ->default('guru')
                  ->change();
        });
    }
};
