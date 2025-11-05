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
        // Usuarios (SIN encriptar - SOLO PARA DESARROLLO)
        DB::table('usuarios')->upsert([
            [
                'id' => 1,
                'nombre' => 'usuario1',
                'email' => 'usuario1@steamhrg.com',
                'clave' => 'usuario1',
                'rol' => 'user',
                'saldo' => 60.01,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'admin1',
                'email' => 'admin1@steamhrg.com',
                'clave' => 'admin1',
                'rol' => 'admin',
                'saldo' => 100.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nombre' => 'usuario3',
                'email' => 'usuario3@gmail.com',
                'clave' => 'usuario3',
                'rol' => 'user',
                'saldo' => 100.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['id'], ['nombre', 'email', 'clave', 'rol', 'saldo', 'updated_at']);

        // Juegos
        DB::table('juegos')->upsert([
            [
                'id' => 21,
                'titulo' => 'The Witcher 3: Wild Hunt',
                'descripcion' => 'Un épico RPG de mundo abierto donde juegas como Geralt de Rivia, un cazador de monstruos en busca de su hija adoptiva.',
                'precio' => 39.99,
                'imagen_url' => 'https://image.api.playstation.com/vulcan/ap/rnd/202211/0711/kh4MUIuMmHlktOHar3lVl6rY.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 22,
                'titulo' => 'Red Dead Redemption 2',
                'descripcion' => 'Una aventura de acción en el salvaje oeste con una historia profunda y un mundo abierto impresionante.',
                'precio' => 59.99,
                'imagen_url' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1174180/header.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 23,
                'titulo' => 'Cyberpunk 2077',
                'descripcion' => 'Un RPG futurista en Night City, donde tus decisiones afectan la historia y el mundo.',
                'precio' => 49.99,
                'imagen_url' => 'https://image.api.playstation.com/vulcan/ap/rnd/202111/3013/cKZ4tKNFj9C00giTzYtH8PF1.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 24,
                'titulo' => 'Grand Theft Auto V',
                'descripcion' => 'Explora Los Santos en este juego de acción y mundo abierto, con modo historia y multijugador.',
                'precio' => 29.99,
                'imagen_url' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/271590/header.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 25,
                'titulo' => 'Elden Ring',
                'descripcion' => 'Un RPG de acción en un mundo abierto creado por FromSoftware y George R. R. Martin.',
                'precio' => 59.99,
                'imagen_url' => 'https://assets.xboxservices.com/assets/7b/54/7b54f5e4-0857-4ce3-8a18-2b8c431e8a9e.jpg?n=Elden-Ring_GLP-Page-Hero-0_1083x1222_01.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 26,
                'titulo' => 'Hollow Knight',
                'descripcion' => 'Un metroidvania de acción y aventura en un mundo subterráneo lleno de insectos y misterios.',
                'precio' => 14.99,
                'imagen_url' => 'https://gaming-cdn.com/images/products/2198/616x353/hollow-knight-pc-mac-juego-steam-cover.jpg?v=1705490619',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 27,
                'titulo' => 'Celeste',
                'descripcion' => 'Un desafiante juego de plataformas sobre escalar una montaña y superar tus propios límites.',
                'precio' => 19.99,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bd/Celeste_video_game_logo.png/250px-Celeste_video_game_logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 28,
                'titulo' => 'Stardew Valley',
                'descripcion' => 'Simulador de granja donde puedes cultivar, criar animales, pescar y explorar cuevas.',
                'precio' => 13.99,
                'imagen_url' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/413150/header.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 29,
                'titulo' => 'Undertale',
                'descripcion' => 'Un RPG único donde puedes elegir no matar a ningún enemigo y tus decisiones importan.',
                'precio' => 9.99,
                'imagen_url' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/391540/header.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 30,
                'titulo' => 'Cuphead',
                'descripcion' => 'Un juego de plataformas y disparos con estética de dibujos animados de los años 30 y dificultad elevada.',
                'precio' => 19.99,
                'imagen_url' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/268910/header.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['id'], ['titulo', 'descripcion', 'precio', 'imagen_url', 'updated_at']);

        // Bibliotecas
        DB::table('bibliotecas')->upsert([
            [
                'id' => 21,
                'usuario_id' => 1,
                'juego_id' => 21,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['id'], ['usuario_id', 'juego_id', 'updated_at']);
    }
}
