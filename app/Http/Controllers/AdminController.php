<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Juego;
use App\Models\Biblioteca;

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
        
        // Desglosar saldos
        $saldoAdmin = Usuario::where('rol', 'admin')->sum('saldo');
        $saldoUsuarios = Usuario::where('rol', 'user')->sum('saldo');
        $totalVentas = $saldoAdmin + $saldoUsuarios; // Total de todos los saldos
        
        $usuariosRecientes = Usuario::latest()->take(5)->get();
        
        return view('admin.dashboard', compact('totalUsuarios', 'totalJuegos', 'totalVentas', 'saldoAdmin', 'saldoUsuarios', 'usuariosRecientes'));
    }

    // Listar usuarios
    public function usuarios()
    {
        $usuarios = Usuario::paginate(15);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    // Mostrar formulario para crear usuario
    public function crearUsuario()
    {
        return view('admin.usuarios.create');
    }

    // Guardar nuevo usuario
    public function guardarUsuario(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:3|unique:usuarios',
            'email' => 'required|email|unique:usuarios',
            'clave' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:user,admin',
            'saldo' => 'required|numeric|min:0',
        ], [
            'nombre.required' => 'El nombre de usuario es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'nombre.unique' => 'El nombre de usuario ya existe',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email no es válido',
            'email.unique' => 'El email ya está registrado',
            'clave.required' => 'La contraseña es obligatoria',
            'clave.min' => 'La contraseña debe tener al menos 6 caracteres',
            'clave.confirmed' => 'Las contraseñas no coinciden',
            'rol.required' => 'El rol es obligatorio',
            'saldo.required' => 'El saldo es obligatorio',
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'clave' => Hash::make($request->clave),
            'rol' => $request->rol,
            'saldo' => (float) $request->saldo,
        ]);

        return redirect()->route('admin.usuarios')->with('success', 'Usuario creado correctamente.');
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
            'clave' => 'nullable|string|min:6',
        ]);

        $data = $request->only(['nombre', 'email', 'rol', 'saldo']);
        
        // Si se proporciona una nueva contraseña, actualizarla
        if ($request->filled('clave')) {
            $data['clave'] = Hash::make($request->clave);
        }

        $usuario->update($data);

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
            'genero' => 'required|string|in:Acción,Terror,RPG,Estrategia,Aventura,Deportes,Puzzle,Simulación',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['titulo', 'descripcion', 'genero']);
        $data['precio'] = (float) $request->precio;

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
            'genero' => 'required|string|in:Acción,Terror,RPG,Estrategia,Aventura,Deportes,Puzzle,Simulación',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['titulo', 'descripcion', 'genero']);
        $data['precio'] = (float) $request->precio;

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
