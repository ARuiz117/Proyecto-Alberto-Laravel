<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Juego;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // Dashboard del administrador
    public function dashboard()
    {
        $totalUsuarios = Usuario::count();
        $totalJuegos = Juego::count();
        $totalVentas = Usuario::sum('saldo'); // Aproximado
        $usuariosRecientes = Usuario::latest()->take(5)->get();
        
        return view('admin.dashboard', compact('totalUsuarios', 'totalJuegos', 'totalVentas', 'usuariosRecientes'));
    }

    // Listar usuarios
    public function usuarios()
    {
        $usuarios = Usuario::paginate(15);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    // Editar usuario
    public function editarUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    // Actualizar usuario
    public function actualizarUsuario(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|unique:usuarios,nombre,' . $id,
            'email' => 'required|email|unique:usuarios,email,' . $id,
            'rol' => 'required|in:user,admin',
            'saldo' => 'required|numeric|min:0',
        ]);

        $usuario->update($request->only(['nombre', 'email', 'rol', 'saldo']));

        return redirect()->route('admin.usuarios')->with('success', 'Usuario actualizado correctamente.');
    }

    // Eliminar usuario
    public function eliminarUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);
        
        // Evitar eliminar al usuario actual
        if ($usuario->id === Auth::user()->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();
        return redirect()->route('admin.usuarios')->with('success', 'Usuario eliminado correctamente.');
    }

    // Listar juegos
    public function juegos()
    {
        $juegos = Juego::paginate(15);
        return view('admin.juegos.index', compact('juegos'));
    }

    // Crear juego
    public function crearJuego()
    {
        return view('admin.juegos.create');
    }

    // Guardar juego
    public function guardarJuego(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:100|unique:juegos',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['titulo', 'descripcion', 'precio']);

        // Guardar imagen
        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('imagenes'), $nombreArchivo);
            $data['imagen_url'] = $nombreArchivo;
        }

        Juego::create($data);

        return redirect()->route('admin.juegos')->with('success', 'Juego creado correctamente.');
    }

    // Editar juego
    public function editarJuego($id)
    {
        $juego = Juego::findOrFail($id);
        return view('admin.juegos.edit', compact('juego'));
    }

    // Actualizar juego
    public function actualizarJuego(Request $request, $id)
    {
        $juego = Juego::findOrFail($id);

        $request->validate([
            'titulo' => 'required|string|max:100|unique:juegos,titulo,' . $id,
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['titulo', 'descripcion', 'precio']);

        // Guardar nueva imagen si se proporciona
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe y tiene timestamp (fue subida por admin)
            if ($juego->imagen_url && strpos($juego->imagen_url, '_') !== false) {
                $rutaImagen = public_path('imagenes/' . $juego->imagen_url);
                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
            }

            $archivo = $request->file('imagen');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('imagenes'), $nombreArchivo);
            $data['imagen_url'] = $nombreArchivo;
        }

        $juego->update($data);

        return redirect()->route('admin.juegos')->with('success', 'Juego actualizado correctamente.');
    }

    // Eliminar juego
    public function eliminarJuego($id)
    {
        $juego = Juego::findOrFail($id);
        $juego->delete();
        return redirect()->route('admin.juegos')->with('success', 'Juego eliminado correctamente.');
    }
}
