@extends('layouts.app')

@section('title', 'Registro - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endsection

@section('content')
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Crear Cuenta en Steam HRG</h1>
        <p class="hero-subtitle">Únete a nuestra plataforma y disfruta de los mejores videojuegos</p>
    </div>
</section>

<section id="register-form">
    <h2>Crear nueva cuenta</h2>
    
    @if($errors->any())
        <div style="color:red; text-align:center; margin-bottom: 1rem;">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="text" name="usuario" placeholder="Nombre de usuario (mín. 3 caracteres)" required class="form-input" value="{{ old('usuario') }}" /><br />
        
        <input type="email" name="email" placeholder="Correo electrónico" required class="form-input" value="{{ old('email') }}" /><br />
        
        <input type="password" name="clave" placeholder="Contraseña (mín. 6 caracteres)" required class="form-input" /><br />
        
        <input type="password" name="clave_confirmation" placeholder="Confirmar contraseña" required class="form-input" /><br />
        
        <div class="button-container">
            <button type="submit" class="btn btn-primary">Crear Cuenta</button>
        </div>
    </form>
    
    <p style="text-align: center; margin-top: 1rem;">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}" style="color: #66c0f4;">Inicia sesión aquí</a>
    </p>
</section>
@endsection
