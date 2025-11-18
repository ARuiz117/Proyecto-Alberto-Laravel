<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resena extends Model
{
    protected $table = 'resenas';

    protected $fillable = [
        'usuario_id',
        'juego_id',
        'contenido',
        'calificacion',
        'recomendacion',
    ];

    protected $casts = [
        'calificacion' => 'integer',
        'recomendacion' => 'boolean',
    ];

    // Relación: Una reseña pertenece a un usuario
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Relación: Una reseña pertenece a un juego
    public function juego(): BelongsTo
    {
        return $this->belongsTo(Juego::class, 'juego_id');
    }
}
