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
        Schema::table('saele', function (Blueprint $table) {
            $table->unique(['name', 'kino_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saele', function (Blueprint $table) {
            $table->dropUnique(['name', 'kino_id']);
        });
    }
};
