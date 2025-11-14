<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('biblioteca.index');
        }
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'clave' => 'required|string',
        ]);

        $usuario = Usuario::where('nombre', $request->usuario)->first();

        // Verificar la contraseña usando Hash::check()
        if ($usuario && Hash::check($request->clave, $usuario->clave)) {
            Auth::login($usuario);
            $request->session()->regenerate();
            
            return redirect()->intended(route('biblioteca.index'));
        }

        return back()->withErrors([
            'usuario' => 'Usuario o contraseña incorrectos',
        ])->onlyInput('usuario');
    }

    // Mostrar formulario de registro
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('biblioteca.index');
        }
        return view('auth.register');
    }

    // Procesar registro
    public function register(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|min:3|unique:usuarios,nombre',
            'email' => 'required|email|unique:usuarios,email',
            'clave' => 'required|string|min:6|confirmed',
        ], [
            'usuario.required' => 'El nombre de usuario es obligatorio',
            'usuario.min' => 'El nombre de usuario debe tener al menos 3 caracteres',
            'usuario.unique' => 'El nombre de usuario ya existe',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email no es válido',
            'email.unique' => 'El email ya está registrado',
            'clave.required' => 'La contraseña es obligatoria',
            'clave.min' => 'La contraseña debe tener al menos 6 caracteres',
            'clave.confirmed' => 'Las contraseñas no coinciden',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->usuario,
            'email' => $request->email,
            'clave' => $request->clave, // Se encripta automáticamente en el modelo
            'rol' => 'user',
            'saldo' => 100.00,
        ]);

        return redirect()->route('login')->with('success', 'Cuenta creada exitosamente. Ya puedes iniciar sesión.');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
