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
            $table->enum('status', ['draft', 'submitted', 'under_review', 'completed', 'revision'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supervisi', function (Blueprint $table) {
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'completed'])->default('draft')->change();
        });
    }
};
