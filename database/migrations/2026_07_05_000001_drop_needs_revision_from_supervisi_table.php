<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * R3: kolom `needs_revision` tidak pernah ditulis aplikasi (selalu false);
     * penanda revisi yang dipakai adalah status='revision'.
     */
    public function up(): void
    {
        Schema::table('supervisi', function (Blueprint $table) {
            if (Schema::hasColumn('supervisi', 'needs_revision')) {
                $table->dropColumn('needs_revision');
            }
        });
    }

    public function down(): void
    {
        Schema::table('supervisi', function (Blueprint $table) {
            $table->boolean('needs_revision')->default(false)->after('reviewed_at');
        });
    }
};
