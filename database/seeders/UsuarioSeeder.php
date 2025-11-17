<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Usuario::create([
            'nombre' => 'usuario1',
            'email' => 'usuario1@steamhrg.com',
            'clave' => Hash::make('usuario1'),
            'rol' => 'user',
            'saldo' => 100.00,
        ]);

        Usuario::create([
            'nombre' => 'admin1',
            'email' => 'admin1@steamhrg.com',
            'clave' => Hash::make('admin1'),
            'rol' => 'admin',
            'saldo' => 100.00,
        ]);
    }
}
