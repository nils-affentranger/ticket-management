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
        Schema::create('besuche', function (Blueprint $table) {
            $table->id();

            $table->dateTime('anfang');
            $table->dateTime('ende');
            $table->char('reihe');
            $table->tinyInteger('platz');
            $table->boolean('untertitel');
            $table->decimal('snackzuschlag_chf', 5, 2)->nullable();

            $table->foreignId('film_id')->constrained('filme')->onDelete('restrict');
            $table->foreignId('typ_id')->constrained('typen')->onDelete('restrict');
            $table->foreignId('sprache_id')->constrained('sprachen')->onDelete('restrict');
            $table->foreignId('saal_id')->constrained('saele')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('besuche');
    }
};
