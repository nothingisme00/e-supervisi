<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menandai feedback yang berasal dari siklus review sebelum guru merevisi.
     * Feedback bertanda tetap tersimpan sebagai riwayat, tetapi tidak lagi
     * dihitung sebagai feedback siklus berjalan (stepper kepala reset).
     */
    public function up(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->boolean('sudah_direvisi')->default(false)->after('is_revision_request');
        });
    }

    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropColumn('sudah_direvisi');
        });
    }
};
