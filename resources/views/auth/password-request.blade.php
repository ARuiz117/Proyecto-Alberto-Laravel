@extends('layouts.app')

@section('title', 'Recuperar Contraseña - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endsection

@section('content')
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">¿Olvidaste tu contraseña?</h1>
        <p class="hero-subtitle">Rellena el siguiente formulario y el administrador se pondrá en contacto contigo para restablecer tu acceso.</p>
        <p class="hero-subtitle" style="font-size: 0.9em; margin-top: 0.5rem; opacity: 0.9;">Tu solicitud será enviada a: <strong>admin1@steamhrg.com</strong></p>
    </div>
</section>

<section id="password-request-form">
    @if(session('success'))
        <p class="success-message" style="color:green; text-align:center; margin-bottom: 1rem;">{{ session('success') }}</p>
    @endif
    @if($errors->any())
        <p class="error-message" style="color:red; text-align:center; margin-bottom: 1rem;">{{ $errors->first() }}</p>
    @endif
    <form method="POST" action="{{ route('password.request.send') }}">
        @csrf
        <input type="text" name="usuario" placeholder="Usuario o Email" required class="form-input" value="{{ old('usuario') }}" />
        <textarea name="mensaje" placeholder="Mensaje para el administrador (opcional)" class="form-input" rows="3"></textarea>
        <button type="submit" class="btn btn-primary">Solicitar ayuda</button>
    </form>
    <div class="register-section">
        <a href="{{ route('login') }}" class="btn btn-secondary">Volver al login</a>
    </div>
</section>
@endsection
