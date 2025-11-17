<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class MisteamImportSeeder extends Seeder
{
    public function run(): void
    {
        // Los usuarios se crean manualmente o con un seeder separado
        // No se crean automáticamente para evitar problemas con contraseñas

        // Juegos
        DB::table('juegos')->insert([
            [
                'titulo' => 'The Witcher 3: Wild Hunt',
                'descripcion' => 'Un épico RPG de mundo abierto donde juegas como Geralt de Rivia, un cazador de monstruos en busca de su hija adoptiva.',
                'precio' => 39.99,
                'imagen_url' => 'THE_WITCHER_3_WILD_HUNT.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Red Dead Redemption 2',
                'descripcion' => 'Una aventura de acción en el salvaje oeste con una historia profunda y un mundo abierto impresionante.',
                'precio' => 59.99,
                'imagen_url' => 'RED_DEAD_REDEMPTION_2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Cyberpunk 2077',
                'descripcion' => 'Un RPG futurista en Night City, donde tus decisiones afectan la historia y el mundo.',
                'precio' => 49.99,
                'imagen_url' => 'CYBERPUNK_2077.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Grand Theft Auto V',
                'descripcion' => 'Explora Los Santos en este juego de acción y mundo abierto, con modo historia y multijugador.',
                'precio' => 29.99,
                'imagen_url' => 'GTA_V.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Elden Ring',
                'descripcion' => 'Un RPG de acción en un mundo abierto creado por FromSoftware y George R. R. Martin.',
                'precio' => 59.99,
                'imagen_url' => 'ELDEN RING.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Hollow Knight',
                'descripcion' => 'Un metroidvania de acción y aventura en un mundo subterráneo lleno de insectos y misterios.',
                'precio' => 14.99,
                'imagen_url' => 'HOLLOW_KNIGHT.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Celeste',
                'descripcion' => 'Un desafiante juego de plataformas sobre escalar una montaña y superar tus propios límites.',
                'precio' => 19.99,
                'imagen_url' => 'CELESTE.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Stardew Valley',
                'descripcion' => 'Simulador de granja donde puedes cultivar, criar animales, pescar y explorar cuevas.',
                'precio' => 13.99,
                'imagen_url' => 'STARDEW_VALLEY.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Undertale',
                'descripcion' => 'Un RPG único donde puedes elegir no matar a ningún enemigo y tus decisiones importan.',
                'precio' => 9.99,
                'imagen_url' => 'UNDERTALE.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Cuphead',
                'descripcion' => 'Un juego de plataformas y disparos con estética de dibujos animados de los años 30 y dificultad elevada.',
                'precio' => 19.99,
                'imagen_url' => 'CUPHEAD.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
