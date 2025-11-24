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

        // Buscar por nombre de usuario O por email
        $usuario = Usuario::where('nombre', $request->usuario)
                          ->orWhere('email', $request->usuario)
                          ->first();

        // Verificar la contraseña usando Hash::check()
        if ($usuario && Hash::check($request->clave, $usuario->clave)) {
            Auth::login($usuario);
            $request->session()->regenerate();
            
            // Marcar que acaba de hacer login para mostrar mensaje de bienvenida
            session()->flash('just_logged_in', true);
            
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
            'clave' => Hash::make($request->clave),
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
        
        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
    }

    // Mostrar formulario de cambio de contraseña
    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    // Procesar cambio de contraseña
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria',
            'password.required' => 'La nueva contraseña es obligatoria',
            'password.min' => 'La nueva contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        $user = Auth::user();

        // Verificar contraseña actual
        if (!Hash::check($request->current_password, $user->clave)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual es incorrecta',
            ]);
        }

        // Actualizar contraseña
        $user->update([
            'clave' => Hash::make($request->password),
        ]);

        return redirect()->route('biblioteca.index')->with('success', 'Contraseña actualizada correctamente.');
    }

    // Mostrar información personal del perfil
    public function showProfileInfo()
    {
        return view('auth.change-password');
    }

    // Mostrar historial de compras del perfil
    public function showProfileHistory()
    {
        return view('auth.change-password');
    }
}
