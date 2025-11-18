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
        Schema::table('resenas', function (Blueprint $table) {
            $table->integer('calificacion')->default(5)->comment('Puntuación de 1 a 5');
            $table->boolean('recomendacion')->default(true)->comment('¿Recomienda el juego?');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resenas', function (Blueprint $table) {
            $table->dropColumn(['calificacion', 'recomendacion']);
        });
    }
};
