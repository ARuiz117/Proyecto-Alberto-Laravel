<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Juego;

class TiendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Mostrar catálogo de juegos disponibles
    public function index()
    {
        $usuario = Auth::user();
        
        // Obtener IDs de juegos que el usuario ya tiene
        $juegosComprados = $usuario->juegos()->pluck('id')->toArray();
        
        // Obtener juegos disponibles (no comprados)
        $juegos = Juego::whereNotIn('id', $juegosComprados)->paginate(12);
        
        return view('tienda.index', compact('juegos'));
    }

    // Ver detalles de un juego específico
    public function show($id)
    {
        $juego = Juego::findOrFail($id);
        $usuario = Auth::user();
        
        // Verificar si el usuario ya tiene este juego
        $tieneJuego = $usuario->juegos()->where('juego_id', $id)->exists();
        
        // Obtener reseñas del juego
        $resenas = $juego->resenas()->with('usuario')->latest()->paginate(10);
        
        return view('tienda.show', compact('juego', 'tieneJuego', 'resenas'));
    }

    // Búsqueda de juegos
    public function buscar(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $usuario = Auth::user();
        $juegosComprados = $usuario->juegos()->pluck('id')->toArray();
        $query = $request->input('q');
        
        $juegos = Juego::where('titulo', 'like', "%{$query}%")
                       ->orWhere('descripcion', 'like', "%{$query}%")
                       ->whereNotIn('id', $juegosComprados)
                       ->paginate(12);
        
        return view('tienda.index', compact('juegos', 'query'));
    }
}
