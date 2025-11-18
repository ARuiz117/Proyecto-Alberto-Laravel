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
        // Asignar géneros a juegos existentes
        $juegos = [
            'The Witcher 3: Wild Hunt' => 'RPG',           // RPG de acción y aventura
            'Elden Ring' => 'RPG',                          // RPG de acción tipo souls-like
            'Dark Souls III' => 'RPG',                      // RPG de acción tipo souls-like
            'Cyberpunk 2077' => 'RPG',                      // RPG de acción en mundo abierto
            'Resident Evil 7' => 'Terror',                  // Survival horror
            'Resident Evil 8' => 'Terror',                  // Survival horror
            'Starfield' => 'Aventura',                      // Aventura espacial y exploración
            'Baldur\'s Gate 3' => 'RPG',                    // RPG táctico
            'Stardew Valley' => 'Simulación',               // Simulación de granja
            'Hollow Knight' => 'Aventura',                  // Aventura metroidvania
        ];

        foreach ($juegos as $titulo => $genero) {
            DB::table('juegos')
                ->where('titulo', $titulo)
                ->update(['genero' => $genero]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a valor por defecto
        DB::table('juegos')->update(['genero' => 'Acción']);
    }
};
