@extends('layouts.app')

@section('title', 'Iniciar Sesión - Steam HRG')

@section('content')
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Bienvenido a Steam HRG</h1>
        <p class="hero-subtitle">Compra, gestiona y reseña tus juegos favoritos. ¡Simula la experiencia Steam con accesibilidad mejorada!</p>
    </div>
</section>

<section id="login-form">
    <h2>Iniciar sesión</h2>
    
    @if(session('success'))
        <p class="success-message" style="color:green; text-align:center; margin-bottom: 1rem;">{{ session('success') }}</p>
    @endif
    
    @if($errors->any())
        <p class="error-message" style="color:red; text-align:center; margin-bottom: 1rem;">{{ $errors->first() }}</p>
    @endif
    
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="text" name="usuario" placeholder="Usuario" required class="form-input" value="{{ old('usuario') }}" /><br />
        <input type="password" name="clave" placeholder="Clave" required class="form-input" /><br />
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
    
    <div class="register-section">
        <p class="register-text">¿No tienes cuenta?</p>
        <a href="{{ route('register') }}" class="btn btn-primary">Crear cuenta nueva</a>
    </div>
</section>
@endsection
