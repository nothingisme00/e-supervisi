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
        Schema::table('users', function (Blueprint $table) {
            // Tambah email field (CRITICAL - untuk password reset & login)
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->unique()->after('name');
            }
            
            // Tambah fields untuk guru
            if (!Schema::hasColumn('users', 'nip')) {
                $table->string('nip')->unique()->nullable()->after('nik');
            }
            
            if (!Schema::hasColumn('users', 'mata_pelajaran')) {
                $table->string('mata_pelajaran')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'tingkatan')) {
                $table->string('tingkatan')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'jabatan')) {
                $table->string('jabatan')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'unit_kerja')) {
                $table->string('unit_kerja')->nullable();
            }
            
            // Tambah indexes untuk performance
            $table->index('role');
            $table->index('nik');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['role']);
            $table->dropIndex(['nik']);
            $table->dropIndex(['email']);
            
            // Drop columns
            $columns = ['email', 'nip', 'mata_pelajaran', 'tingkatan', 'jabatan', 'unit_kerja'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};