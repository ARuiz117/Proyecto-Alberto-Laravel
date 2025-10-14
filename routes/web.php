<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BibliotecaController;

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
    Route::get('/biblioteca', [BibliotecaController::class, 'index'])->name('biblioteca.index');
    Route::post('/biblioteca/comprar', [BibliotecaController::class, 'comprar'])->name('biblioteca.comprar');
    Route::post('/biblioteca/devolver', [BibliotecaController::class, 'devolver'])->name('biblioteca.devolver');
});
