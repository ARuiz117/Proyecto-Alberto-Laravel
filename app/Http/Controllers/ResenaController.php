<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Resena;
use App\Models\Juego;

class ResenaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Crear una nueva reseña
    public function store(Request $request)
    {
        $request->validate([
            'juego_id' => 'required|exists:juegos,id',
            'contenido' => 'required|string|min:10|max:1000',
            'calificacion' => 'required|integer|min:1|max:5',
            'recomendacion' => 'required|boolean',
        ], [
            'contenido.required' => 'La reseña es obligatoria',
            'contenido.min' => 'La reseña debe tener al menos 10 caracteres',
            'contenido.max' => 'La reseña no puede exceder 1000 caracteres',
            'calificacion.required' => 'La calificación es obligatoria',
            'calificacion.min' => 'La calificación debe ser de 1 a 5',
            'calificacion.max' => 'La calificación debe ser de 1 a 5',
            'recomendacion.required' => 'Debes indicar si recomiendas el juego',
        ]);

        $usuario = Auth::user();
        $juego = Juego::findOrFail($request->juego_id);

        // Verificar que el usuario tenga el juego
        if (!$usuario->juegos()->where('juego_id', $juego->id)->exists()) {
            return back()->with('error', 'Solo puedes reseñar juegos que posees.');
        }

        // Verificar si ya tiene una reseña para este juego
        $resenaExistente = Resena::where('usuario_id', $usuario->id)
                                  ->where('juego_id', $juego->id)
                                  ->first();

        if ($resenaExistente) {
            return back()->with('error', 'Ya has reseñado este juego.');
        }

        Resena::create([
            'usuario_id' => $usuario->id,
            'juego_id' => $juego->id,
            'contenido' => $request->contenido,
            'calificacion' => $request->calificacion,
            'recomendacion' => $request->recomendacion,
        ]);

        return back()->with('success', '¡Reseña creada exitosamente!');
    }

    // Actualizar una reseña
    public function update(Request $request, $id)
    {
        $resena = Resena::findOrFail($id);
        $usuario = Auth::user();

        // Verificar autorización
        if ($resena->usuario_id !== $usuario->id) {
            return back()->with('error', 'No tienes permiso para editar esta reseña.');
        }

        $request->validate([
            'contenido' => 'required|string|min:10|max:1000',
            'calificacion' => 'required|integer|min:1|max:5',
            'recomendacion' => 'required|boolean',
        ], [
            'contenido.required' => 'La reseña es obligatoria',
            'contenido.min' => 'La reseña debe tener al menos 10 caracteres',
            'contenido.max' => 'La reseña no puede exceder 1000 caracteres',
            'calificacion.required' => 'La calificación es obligatoria',
            'calificacion.min' => 'La calificación debe ser de 1 a 5',
            'calificacion.max' => 'La calificación debe ser de 1 a 5',
            'recomendacion.required' => 'Debes indicar si recomiendas el juego',
        ]);

        $resena->update([
            'contenido' => $request->contenido,
            'calificacion' => $request->calificacion,
            'recomendacion' => $request->recomendacion,
        ]);

        return back()->with('success', '¡Reseña actualizada exitosamente!');
    }

    // Eliminar una reseña
    public function destroy($id)
    {
        $resena = Resena::findOrFail($id);
        $usuario = Auth::user();

        // Verificar autorización
        if ($resena->usuario_id !== $usuario->id && !$usuario->isAdmin()) {
            return back()->with('error', 'No tienes permiso para eliminar esta reseña.');
        }

        $resena->delete();

        return back()->with('success', '¡Reseña eliminada exitosamente!');
    }
}
