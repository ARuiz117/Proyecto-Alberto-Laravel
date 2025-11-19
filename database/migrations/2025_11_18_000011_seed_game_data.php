<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Asignar géneros a juegos existentes
        $generos = [
            'The Witcher 3: Wild Hunt' => 'RPG',
            'Elden Ring' => 'RPG',
            'Dark Souls III' => 'RPG',
            'Cyberpunk 2077' => 'RPG',
            'Resident Evil 7' => 'Terror',
            'Resident Evil 8' => 'Terror',
            'Starfield' => 'Aventura',
            'Baldur\'s Gate 3' => 'RPG',
            'Stardew Valley' => 'Simulación',
            'Hollow Knight' => 'Aventura',
        ];

        foreach ($generos as $titulo => $genero) {
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
        DB::table('juegos')->update(['genero' => 'Acción']);
    }
};
