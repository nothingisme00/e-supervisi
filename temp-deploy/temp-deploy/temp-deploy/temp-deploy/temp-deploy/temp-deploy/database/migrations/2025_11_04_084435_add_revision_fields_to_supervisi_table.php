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
        Schema::table('supervisi', function (Blueprint $table) {
            $table->boolean('needs_revision')->default(false)->after('reviewed_at');
            $table->text('revision_notes')->nullable()->after('needs_revision');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supervisi', function (Blueprint $table) {
            $table->dropColumn(['needs_revision', 'revision_notes']);
        });
    }
};
