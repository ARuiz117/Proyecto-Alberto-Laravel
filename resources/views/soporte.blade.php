@extends('layouts.app')

@section('title', 'Soporte - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}" />
@endsection

@section('content')
<div class="main profile-main">
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-user-info">
                <div class="profile-avatar">
                    <i class='bx bx-user-circle'></i>
                </div>
                <div>
                    <h1 class="profile-title">Soporte</h1>
                    <p class="profile-subtitle">¿Tienes algún problema o duda? Envía un mensaje al equipo de soporte.</p>
                    <p class="profile-subtitle" style="font-size: 0.9em; margin-top: 0.5rem; opacity: 0.9;">Tu mensaje será enviado a: <strong>admin1@steamhrg.com</strong></p>
                </div>
            </div>
        </div>
    </div>
    <div class="profile-content" style="max-width: 500px; margin:auto;">
        @if(session('success'))
            <p class="success-message" style="color:green; text-align:center; margin-bottom: 1rem;">{{ session('success') }}</p>
        @endif
        @if($errors->any())
            <p class="error-message" style="color:red; text-align:center; margin-bottom: 1rem;">{{ $errors->first() }}</p>
        @endif
        <form method="POST" action="{{ route('soporte.send') }}">
            @csrf
            <input type="text" name="asunto" placeholder="Asunto" required class="form-input" value="{{ old('asunto') }}" />
            <textarea name="mensaje" placeholder="Describe tu problema o consulta" required class="form-input" rows="4">{{ old('mensaje') }}</textarea>
            <button type="submit" class="btn btn-primary">Enviar a Soporte</button>
        </form>
    </div>
</div>
@endsection
