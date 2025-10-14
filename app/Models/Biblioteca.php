<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Biblioteca extends Model
{
    protected $table = 'bibliotecas';

    protected $fillable = [
        'usuario_id',
        'juego_id',
    ];

    // Relación: Una biblioteca pertenece a un usuario
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Relación: Una biblioteca pertenece a un juego
    public function juego(): BelongsTo
    {
        return $this->belongsTo(Juego::class, 'juego_id');
    }
}
