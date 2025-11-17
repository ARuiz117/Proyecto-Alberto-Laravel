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
        // Recrear tabla bibliotecas como tabla pivote sin ID
        Schema::dropIfExists('bibliotecas');
        
        Schema::create('bibliotecas', function (Blueprint $table) {
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('juego_id')->constrained('juegos')->onDelete('cascade');
            $table->timestamps();
            
            // Clave primaria compuesta
            $table->primary(['usuario_id', 'juego_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bibliotecas');
        
        Schema::create('bibliotecas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('juego_id')->nullable()->constrained('juegos')->onDelete('cascade');
            $table->timestamps();
        });
    }
};
