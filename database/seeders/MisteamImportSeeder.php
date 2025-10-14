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
        // Usuarios (con hash de clave)
        DB::table('usuarios')->upsert([
            [
                'id' => 1,
                'nombre' => 'usuario1',
                'email' => 'usuario1@steamhrg.com',
                'clave' => Hash::make('usuario1'),
                'rol' => 'user',
                'saldo' => 60.01,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'admin1',
                'email' => 'admin1@steamhrg.com',
                'clave' => Hash::make('admin1'),
                'rol' => 'admin',
                'saldo' => 100.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nombre' => 'usuario3',
                'email' => 'usuario3@gmail.com',
                'clave' => Hash::make('usuario3'),
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
                'imagen_url' => 'https://imgs.search.brave.com/U7HXX0NII5pcwVNRfh_erT2DvGkVYC3o2StcsaPY224/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9waWNzL mZpbG1hZmZpbml0eS5jb20vd2llZHptaW5fM19kemlraV9nb24tMzU4NDk0NTQ3LW1tZWQuanBn',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 22,
                'titulo' => 'Red Dead Redemption 2',
                'descripcion' => 'Una aventura de acción en el salvaje oeste con una historia profunda y un mundo abierto impresionante.',
                'precio' => 59.99,
                'imagen_url' => 'https://imgs.search.brave.com/Vtg5ceu8xxVhdClvzhXBjwJvfM2YJMgFAmmIdFWx6SQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLmJsb2dzLmVzL2NlZWRlNC9ibG9iL29yaWdpbmFsLmpwZWc',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 23,
                'titulo' => 'Cyberpunk 2077',
                'descripcion' => 'Un RPG futurista en Night City, donde tus decisiones afectan la historia y el mundo.',
                'precio' => 49.99,
                'imagen_url' => 'https://imgs.search.brave.com/lMIOieYZW2t0YIMNyPDlxj5dib84-vURwzdDS01rGAE/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9zdGF0aWMwLmdhbWVyYW50aW1hZ2VzLmNvbS93b3JkcHJlc3Mvd3AtY29udGVudC91cGxvYWRzLzIwMjQvMTIvbWl4Y29sbGFnZS0wOC1kZWMtMjAyNC0wMi0zOC1wbS0zMTE2LmpwZw',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 24,
                'titulo' => 'Grand Theft Auto V',
                'descripcion' => 'Explora Los Santos en este juego de acción y mundo abierto, con modo historia y multijugador.',
                'precio' => 29.99,
                'imagen_url' => 'https://imgs.search.brave.com/QhNppADLP_9weCtPB0jBuZXMxBKRNchoj7ISnb-y7fY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWFnZS5hcGkucGxheXN0YXRpb24uY29tL2Nkbi9VUDIwMDQvQ1VTQTAwNDE5XzAwL2JUTlNlN29rOGVGVkdlUUJ5QTVxU3pCUW9LQUFZMzJSLnBuZw',
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
                'imagen_url' => 'https://imgs.search.brave.com/DJqAJFbpHocH7w1Qk6dwIyyO0TDPCPQe_L1dWTNoMdU/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWFnZXMubmV4dXNtb2RzLmNvbS9pbWFnZXMvZ2FtZXMvdjIvMTMwMy90aWxlLmpwZw',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 29,
                'titulo' => 'Undertale',
                'descripcion' => 'Un RPG único donde puedes elegir no matar a ningún enemigo y tus decisiones importan.',
                'precio' => 9.99,
                'imagen_url' => 'https://imgs.search.brave.com/_1Cbq-sG1XFc0BPH2NXfqIRdZGOY_bO2CMgDCVMykUI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tLm1lZGlhLWFtYXpvbi5jb20vaW1hZ2VzL00vTVY1Qk1XSTNaVGt4WmprdFlXVTNOQzAwT0dRMUxXRmxOemd0WXpJd01XSTRORGcyWVRVMFhrRXlYa0ZxY0djQC5qcGc',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 30,
                'titulo' => 'Cuphead',
                'descripcion' => 'Un juego de plataformas y disparos con estética de dibujos animados de los años 30 y dificultad elevada.',
                'precio' => 19.99,
                'imagen_url' => 'https://imgs.search.brave.com/Tavux6EMJShe1ZTkhrlhNtcVV-z62b3tv9PwxgOpTsQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLjNkYWp1ZWdvcy5jb20vanVlZ29zLzEwNTgzL2N1cGhlYWQvZm90b3MvZmljaGEvY3VwaGVhZC0zODQxOTMyLndlYnA',
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
