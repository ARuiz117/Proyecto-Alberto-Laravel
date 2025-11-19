<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BibliotecaController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TrailerController;
use App\Models\Usuario;

// Redirigir raíz al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    // Tienda
    Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda.index');
    Route::get('/tienda/juego/{id}', [TiendaController::class, 'show'])->name('tienda.show');
    Route::get('/tienda/buscar', [TiendaController::class, 'buscar'])->name('tienda.buscar');
    
    // Biblioteca
    Route::get('/biblioteca', [BibliotecaController::class, 'index'])->name('biblioteca.index');
    Route::post('/biblioteca/comprar', [BibliotecaController::class, 'comprar'])->name('biblioteca.comprar');
    Route::post('/biblioteca/devolver', [BibliotecaController::class, 'devolver'])->name('biblioteca.devolver');
    
    // Carrito
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::post('/carrito/eliminar', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    Route::post('/carrito/comprar', [CarritoController::class, 'comprar'])->name('carrito.comprar');
    
    // Reseñas
    Route::post('/resena/crear', [ResenaController::class, 'store'])->name('resena.store');
    Route::put('/resena/{id}', [ResenaController::class, 'update'])->name('resena.update');
    Route::delete('/resena/{id}', [ResenaController::class, 'destroy'])->name('resena.destroy');
    
    // Trailers y Screenshots
    Route::post('/trailer/obtener', [TrailerController::class, 'obtenerTrailer'])->name('trailer.obtener');
    Route::post('/trailer/screenshots', [TrailerController::class, 'obtenerScreenshots'])->name('trailer.screenshots');
});

// Rutas de administrador
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestión de usuarios
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios');
    Route::get('/usuarios/crear', [AdminController::class, 'crearUsuario'])->name('usuarios.create');
    Route::post('/usuarios', [AdminController::class, 'guardarUsuario'])->name('usuarios.store');
    Route::get('/usuarios/{id}/editar', [AdminController::class, 'editarUsuario'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [AdminController::class, 'actualizarUsuario'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [AdminController::class, 'eliminarUsuario'])->name('usuarios.destroy');
    
    // Gestión de juegos
    Route::get('/juegos', [AdminController::class, 'juegos'])->name('juegos');
    Route::get('/juegos/crear', [AdminController::class, 'crearJuego'])->name('juegos.create');
    Route::post('/juegos', [AdminController::class, 'guardarJuego'])->name('juegos.store');
    Route::get('/juegos/{id}/editar', [AdminController::class, 'editarJuego'])->name('juegos.edit');
    Route::put('/juegos/{id}', [AdminController::class, 'actualizarJuego'])->name('juegos.update');
    Route::delete('/juegos/{id}', [AdminController::class, 'eliminarJuego'])->name('juegos.destroy');
});
