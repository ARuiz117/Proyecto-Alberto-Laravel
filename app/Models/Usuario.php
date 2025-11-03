<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'clave',
        'rol',
        'saldo',
    ];

    protected $hidden = [
        'clave',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
    ];

    // Relación: Un usuario tiene muchas bibliotecas
    public function bibliotecas(): HasMany
    {
        return $this->hasMany(Biblioteca::class, 'usuario_id');
    }

    // Relación: Un usuario tiene muchos juegos a través de bibliotecas
    public function juegos(): BelongsToMany
    {
        return $this->belongsToMany(Juego::class, 'bibliotecas', 'usuario_id', 'juego_id')
                    ->withTimestamps();
    }

    // Relación: Un usuario tiene muchas reseñas
    public function resenas(): HasMany
    {
        return $this->hasMany(Resena::class, 'usuario_id');
    }

    // Relación: Un usuario tiene muchos items en el carrito
    public function carritos(): HasMany
    {
        return $this->hasMany(Carrito::class, 'usuario_id');
    }

    // Método para verificar si es admin
    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    // Override para usar 'clave' en lugar de 'password'
    public function getAuthPassword()
    {
        return $this->clave;
    }
}
