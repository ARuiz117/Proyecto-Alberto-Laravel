<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Juego extends Model
{
    protected $table = 'juegos';

    protected $fillable = [
        'titulo',
        'descripcion',
        'precio',
        'imagen_url',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    // Relación: Un juego tiene muchas bibliotecas
    public function bibliotecas(): HasMany
    {
        return $this->hasMany(Biblioteca::class, 'juego_id');
    }

    // Relación: Un juego pertenece a muchos usuarios a través de bibliotecas
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'bibliotecas', 'juego_id', 'usuario_id')
                    ->withTimestamps();
    }

    // Relación: Un juego tiene muchas reseñas
    public function resenas(): HasMany
    {
        return $this->hasMany(Resena::class, 'juego_id');
    }
}
