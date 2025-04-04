<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: First rename 'ort' to a temporary column
        Schema::table('kinos', function (Blueprint $table) {
            $table->renameColumn('ort', 'ort_temp');
        });

        // Step 2: Rename 'name' to 'ort'
        Schema::table('kinos', function (Blueprint $table) {
            $table->renameColumn('name', 'ort');
        });

        // Step 3: Rename temporary column to 'name'
        Schema::table('kinos', function (Blueprint $table) {
            $table->renameColumn('ort_temp', 'name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the process to get back original structure
        // Step 1: First rename 'name' to a temporary column
        Schema::table('kinos', function (Blueprint $table) {
            $table->renameColumn('name', 'name_temp');
        });

        // Step 2: Rename 'ort' to 'name'
        Schema::table('kinos', function (Blueprint $table) {
            $table->renameColumn('ort', 'name');
        });

        // Step 3: Rename temporary column to 'ort'
        Schema::table('kinos', function (Blueprint $table) {
            $table->renameColumn('name_temp', 'ort');
        });
    }
};
