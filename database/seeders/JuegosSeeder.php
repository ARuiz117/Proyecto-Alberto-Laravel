<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JuegosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $juegos = [
            [
                'titulo' => 'The Witcher 3: Wild Hunt',
                'descripcion' => 'Un épico RPG de mundo abierto donde juegas como Geralt de Rivia, un cazador de monstruos en busca de su hija adoptiva.',
                'precio' => 39.99,
                'imagen_url' => '/imagenes/THE_WITCHER_3_WILD_HUNT.jpg',
            ],
            [
                'titulo' => 'Red Dead Redemption 2',
                'descripcion' => 'Una aventura de acción en el salvaje oeste con una historia profunda y un mundo abierto impresionante.',
                'precio' => 59.99,
                'imagen_url' => '/imagenes/RED_DEAD_REDEMPTION_2.jpg',
            ],
            [
                'titulo' => 'Cyberpunk 2077',
                'descripcion' => 'Un RPG futurista en Night City, donde tus decisiones afectan la historia y el mundo.',
                'precio' => 49.99,
                'imagen_url' => '/imagenes/CYBERPUNK_2077.jpg',
            ],
            [
                'titulo' => 'Grand Theft Auto V',
                'descripcion' => 'Explora Los Santos en este juego de acción y mundo abierto, con modo historia y multijugador.',
                'precio' => 29.99,
                'imagen_url' => '/imagenes/GTA_V.jpg',
            ],
            [
                'titulo' => 'Elden Ring',
                'descripcion' => 'Un RPG de acción en un mundo abierto creado por FromSoftware y George R. R. Martin.',
                'precio' => 59.99,
                'imagen_url' => '/imagenes/ELDEN RING.jpg',
            ],
            [
                'titulo' => 'Hollow Knight',
                'descripcion' => 'Un metroidvania de acción y aventura en un mundo subterráneo lleno de insectos y misterios.',
                'precio' => 14.99,
                'imagen_url' => '/imagenes/HOLLOW_KNIGHT.jpg',
            ],
            [
                'titulo' => 'Celeste',
                'descripcion' => 'Un desafiante juego de plataformas sobre escalar una montaña y superar tus propios límites.',
                'precio' => 19.99,
                'imagen_url' => '/imagenes/CELESTE.png',
            ],
            [
                'titulo' => 'Stardew Valley',
                'descripcion' => 'Simulador de granja donde puedes cultivar, criar animales, pescar y explorar cuevas.',
                'precio' => 13.99,
                'imagen_url' => '/imagenes/STARDEW_VALLEY.jpg',
            ],
            [
                'titulo' => 'Undertale',
                'descripcion' => 'Un RPG único donde puedes elegir no matar a ningún enemigo y tus decisiones importan.',
                'precio' => 9.99,
                'imagen_url' => '/imagenes/UNDERTALE.jpg',
            ],
            [
                'titulo' => 'Cuphead',
                'descripcion' => 'Un juego de plataformas y disparos con estética de dibujos animados de los años 30 y dificultad elevada.',
                'precio' => 19.99,
                'imagen_url' => '/imagenes/CUPHEAD.jpg',
            ],
        ];

        foreach ($juegos as $juego) {
            \App\Models\Juego::create($juego);
        }
    }
}
