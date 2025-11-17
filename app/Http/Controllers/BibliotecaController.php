<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Juego;
use App\Models\Biblioteca;

class BibliotecaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Mostrar biblioteca del usuario
    public function index()
    {
        $usuario = Auth::user();
        
        // Obtener juegos en la biblioteca del usuario
        $misJuegos = $usuario->juegos()->paginate(12);
        
        return view('biblioteca.index', compact('misJuegos'));
    }

    // Comprar un juego
    public function comprar(Request $request)
    {
        $request->validate([
            'juego_id' => 'required|exists:juegos,id',
        ]);

        $usuario = Auth::user();
        $juego = Juego::findOrFail($request->juego_id);

        // Verificar si ya lo tiene
        if ($usuario->juegos()->where('juego_id', $juego->id)->exists()) {
            return back()->with('error', 'Ya has comprado este juego.');
        }

        // Verificar saldo
        if ($usuario->saldo < $juego->precio) {
            return back()->with('error', 'Saldo insuficiente.');
        }

        DB::transaction(function () use ($usuario, $juego) {
            // Crear registro en biblioteca
            Biblioteca::create([
                'usuario_id' => $usuario->id,
                'juego_id' => $juego->id,
            ]);

            // Actualizar saldo
            $usuario->saldo -= $juego->precio;
            $usuario->save();
        });

        return back()->with('success', '¡Juego comprado con éxito!');
    }

    // Devolver un juego
    public function devolver(Request $request)
    {
        $request->validate([
            'juego_id' => 'required|exists:juegos,id',
        ]);

        $usuario = Auth::user();
        $juego = Juego::findOrFail($request->juego_id);

        // Verificar que el usuario tenga el juego
        $biblioteca = Biblioteca::where('usuario_id', $usuario->id)
                                ->where('juego_id', $juego->id)
                                ->first();

        if (!$biblioteca) {
            return back()->with('error', 'No tienes este juego en tu biblioteca.');
        }

        DB::transaction(function () use ($usuario, $juego, $biblioteca) {
            // Eliminar de biblioteca
            $biblioteca->delete();

            // Devolver saldo
            $usuario->saldo += $juego->precio;
            $usuario->save();
        });

        return back()->with('success', 'Juego devuelto correctamente y saldo reembolsado.');
    }
}
