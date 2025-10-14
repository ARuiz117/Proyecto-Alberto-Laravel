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
                'imagen_url' => 'https://imgs.search.brave.com/U7HXX0NII5pcwVNRfh_erT2DvGkVYC3o2StcsaPY224/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9waWNz/LmZpbG1hZmZpbml0/eS5jb20vd2llZHpt/aW5fM19kemlraV9n/b24tMzU4NDk0NTQ3/LW1tZWQuanBn',
            ],
            [
                'titulo' => 'Red Dead Redemption 2',
                'descripcion' => 'Una aventura de acción en el salvaje oeste con una historia profunda y un mundo abierto impresionante.',
                'precio' => 59.99,
                'imagen_url' => 'https://imgs.search.brave.com/Vtg5ceu8xxVhdClvzhXBjwJvfM2YJMgFAmmIdFWx6SQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLmJs/b2dzLmVzL2NlZWRl/NC9ibG9iL29yaWdp/bmFsLmpwZWc',
            ],
            [
                'titulo' => 'Cyberpunk 2077',
                'descripcion' => 'Un RPG futurista en Night City, donde tus decisiones afectan la historia y el mundo.',
                'precio' => 49.99,
                'imagen_url' => 'https://imgs.search.brave.com/lMIOieYZW2t0YIMNyPDlxj5dib84-vURwzdDS01rGAE/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9zdGF0/aWMwLmdhbWVyYW50/aW1hZ2VzLmNvbS93/b3JkcHJlc3Mvd3At/Y29udGVudC91cGxv/YWRzLzIwMjQvMTIv/bWl4Y29sbGFnZS0w/OC1kZWMtMjAyNC0w/Mi0zOC1wbS0zMTE2/LmpwZw',
            ],
            [
                'titulo' => 'Grand Theft Auto V',
                'descripcion' => 'Explora Los Santos en este juego de acción y mundo abierto, con modo historia y multijugador.',
                'precio' => 29.99,
                'imagen_url' => 'https://imgs.search.brave.com/QhNppADLP_9weCtPB0jBuZXMxBKRNchoj7ISnb-y7fY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWFn/ZS5hcGkucGxheXN0/YXRpb24uY29tL2Nk/bi9VUDEwMDQvQ1VT/QTAwNDE5XzAwL2JU/TlNlN29rOGVGVkdl/UUJ5QTVxU3pCUW9L/QUFZMzJSLnBuZw',
            ],
            [
                'titulo' => 'Elden Ring',
                'descripcion' => 'Un RPG de acción en un mundo abierto creado por FromSoftware y George R. R. Martin.',
                'precio' => 59.99,
                'imagen_url' => 'https://assets.xboxservices.com/assets/7b/54/7b54f5e4-0857-4ce3-8a18-2b8c431e8a9e.jpg?n=Elden-Ring_GLP-Page-Hero-0_1083x1222_01.jpg',
            ],
            [
                'titulo' => 'Hollow Knight',
                'descripcion' => 'Un metroidvania de acción y aventura en un mundo subterráneo lleno de insectos y misterios.',
                'precio' => 14.99,
                'imagen_url' => 'https://gaming-cdn.com/images/products/2198/616x353/hollow-knight-pc-mac-juego-steam-cover.jpg?v=1705490619',
            ],
            [
                'titulo' => 'Celeste',
                'descripcion' => 'Un desafiante juego de plataformas sobre escalar una montaña y superar tus propios límites.',
                'precio' => 19.99,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bd/Celeste_video_game_logo.png/250px-Celeste_video_game_logo.png',
            ],
            [
                'titulo' => 'Stardew Valley',
                'descripcion' => 'Simulador de granja donde puedes cultivar, criar animales, pescar y explorar cuevas.',
                'precio' => 13.99,
                'imagen_url' => 'https://imgs.search.brave.com/DJqAJFbpHocH7w1Qk6dwIyyO0TDPCPQe_L1dWTNoMdU/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWFn/ZXMubmV4dXNtb2Rz/LmNvbS9pbWFnZXMv/Z2FtZXMvdjIvMTMw/My90aWxlLmpwZw',
            ],
            [
                'titulo' => 'Undertale',
                'descripcion' => 'Un RPG único donde puedes elegir no matar a ningún enemigo y tus decisiones importan.',
                'precio' => 9.99,
                'imagen_url' => 'https://imgs.search.brave.com/_1Cbq-sG1XFc0BPH2NXfqIRdZGOY_bO2CMgDCVMykUI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL00v/TVY1Qk1XSTNaVGt4/WmprdFlXVTNOQzAw/T0dRMUxXRmxOemd0/WXpJd01XSTRORGcy/WVRVMFhrRXlYa0Zx/Y0djQC5qcGc',
            ],
            [
                'titulo' => 'Cuphead',
                'descripcion' => 'Un juego de plataformas y disparos con estética de dibujos animados de los años 30 y dificultad elevada.',
                'precio' => 19.99,
                'imagen_url' => 'https://imgs.search.brave.com/Tavux6EMJShe1ZTkhrlhNtcVV-z62b3tv9PwxgOpTsQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLjNk/anVlZ29zLmNvbS9q/dWVnb3MvMTA1ODMv/Y3VwaGVhZC9mb3Rv/cy9maWNoYS9jdXBo/ZWFkLTM4NDE5MzIu/d2VicA',
            ],
        ];

        foreach ($juegos as $juego) {
            \App\Models\Juego::create($juego);
        }
    }
}
