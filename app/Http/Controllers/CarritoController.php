<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Carrito;
use App\Models\Juego;
use App\Models\Biblioteca;

class CarritoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Mostrar el carrito
    public function index()
    {
        $usuario = Auth::user();
        
        // Obtener items del carrito con información de los juegos
        $itemsCarrito = Carrito::where('usuario_id', $usuario->id)
            ->with('juego')
            ->get();
        
        // Calcular total
        $total = $itemsCarrito->sum(function ($item) {
            return $item->juego->precio * $item->cantidad;
        });
        
        return view('carrito.index', compact('itemsCarrito', 'total'));
    }

    // Añadir juego al carrito
    public function agregar(Request $request)
    {
        $request->validate([
            'juego_id' => 'required|exists:juegos,id',
        ]);

        $usuario = Auth::user();
        $juego = Juego::findOrFail($request->juego_id);

        // Verificar si ya lo tiene en la biblioteca
        if ($usuario->juegos()->where('juego_id', $juego->id)->exists()) {
            return back()->with('error', 'Ya tienes este juego en tu biblioteca.');
        }

        // Verificar si ya está en el carrito
        $itemCarrito = Carrito::where('usuario_id', $usuario->id)
            ->where('juego_id', $juego->id)
            ->first();

        if ($itemCarrito) {
            return back()->with('info', 'Este juego ya está en tu carrito.');
        }

        // Añadir al carrito
        Carrito::create([
            'usuario_id' => $usuario->id,
            'juego_id' => $juego->id,
            'cantidad' => 1,
        ]);

        return back()->with('success', '¡Juego añadido al carrito!');
    }

    // Eliminar un item del carrito
    public function eliminar(Request $request)
    {
        $request->validate([
            'juego_id' => 'required|exists:juegos,id',
        ]);

        $usuario = Auth::user();
        
        $itemCarrito = Carrito::where('usuario_id', $usuario->id)
            ->where('juego_id', $request->juego_id)
            ->firstOrFail();

        $itemCarrito->delete();

        return back()->with('success', 'Juego eliminado del carrito.');
    }

    // Vaciar todo el carrito
    public function vaciar()
    {
        $usuario = Auth::user();
        
        Carrito::where('usuario_id', $usuario->id)->delete();

        return back()->with('success', 'Carrito vaciado.');
    }

    // Comprar todos los juegos del carrito
    public function comprar()
    {
        $usuario = Auth::user();
        
        // Obtener items del carrito
        $itemsCarrito = Carrito::where('usuario_id', $usuario->id)
            ->with('juego')
            ->get();

        if ($itemsCarrito->isEmpty()) {
            return back()->with('error', 'Tu carrito está vacío.');
        }

        // Calcular total solo de juegos que no tiene ya
        $total = 0;
        $juegosAComprar = [];
        
        foreach ($itemsCarrito as $item) {
            // Verificar que no lo tenga ya en la biblioteca
            if (!$usuario->juegos()->where('juego_id', $item->juego_id)->exists()) {
                $total += $item->juego->precio * $item->cantidad;
                $juegosAComprar[] = $item;
            }
        }

        // Si no hay juegos nuevos para comprar
        if (empty($juegosAComprar)) {
            return back()->with('error', 'Todos los juegos del carrito ya están en tu biblioteca.');
        }

        // Verificar saldo
        if ($usuario->saldo < $total) {
            return back()->with('error', 'Saldo insuficiente. Necesitas ' . number_format($total, 2) . ' € pero solo tienes ' . number_format($usuario->saldo, 2) . ' €');
        }

        // Realizar la compra en una transacción
        DB::transaction(function () use ($usuario, $juegosAComprar, $total) {
            foreach ($juegosAComprar as $item) {
                // Añadir a la biblioteca
                Biblioteca::create([
                    'usuario_id' => $usuario->id,
                    'juego_id' => $item->juego_id,
                ]);
                
                // Eliminar del carrito
                $item->delete();
            }

            // Actualizar saldo
            $usuario->saldo -= $total;
            $usuario->save();
        });

        return redirect()->route('biblioteca.index')->with('success', '¡Compra realizada con éxito! Los juegos se han añadido a tu biblioteca.');
    }
}
