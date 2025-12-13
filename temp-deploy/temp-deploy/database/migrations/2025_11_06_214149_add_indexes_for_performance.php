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
            // Add index on name for faster search queries (skip if exists)
            if (!$this->indexExists('users', 'users_name_index')) {
                $table->index('name');
            }
            // Add index on role for filtering
            if (!$this->indexExists('users', 'users_role_index')) {
                $table->index('role');
            }
            // Add index on tingkat for filtering
            if (!$this->indexExists('users', 'users_tingkat_index')) {
                $table->index('tingkat');
            }
        });

        Schema::table('supervisi', function (Blueprint $table) {
            // Add index on status for faster filtering
            if (!$this->indexExists('supervisi', 'supervisi_status_index')) {
                $table->index('status');
            }
            // Add index on tanggal_supervisi for sorting
            if (!$this->indexExists('supervisi', 'supervisi_tanggal_supervisi_index')) {
                $table->index('tanggal_supervisi');
            }
        });

        Schema::table('dokumen_evaluasi', function (Blueprint $table) {
            // Add index on jenis_dokumen for faster lookups
            if (!$this->indexExists('dokumen_evaluasi', 'dokumen_evaluasi_jenis_dokumen_index')) {
                $table->index('jenis_dokumen');
            }
        });
    }

    /**
     * Check if index exists on a table
     */
    private function indexExists(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();

        $result = $connection->select(
            "SELECT COUNT(*) as count FROM information_schema.statistics
             WHERE table_schema = ? AND table_name = ? AND index_name = ?",
            [$database, $table, $index]
        );

        return $result[0]->count > 0;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['role']);
            $table->dropIndex(['tingkat']);
        });

        Schema::table('supervisi', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['tanggal_supervisi']);
        });

        Schema::table('dokumen_evaluasi', function (Blueprint $table) {
            $table->dropIndex(['jenis_dokumen']);
        });
    }
};
