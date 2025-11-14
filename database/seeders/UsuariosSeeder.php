<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios de prueba (Contraseñas hasheadas con Bcrypt)
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
        ], ['id'], ['nombre', 'email', 'clave', 'rol', 'saldo', 'updated_at']);
    }
}
