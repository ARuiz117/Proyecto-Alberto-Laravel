@extends('layouts.app')

@section('title', 'Mi Perfil - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}" />
@endsection

@section('content')

<div class="main profile-main">
    <!-- Encabezado con información del usuario -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-user-info">
                <div class="profile-avatar">
                    <i class='bx bx-user-circle'></i>
                </div>
                <div>
                    <h1 class="profile-title">Mi Perfil</h1>
                    <p class="profile-subtitle">{{ Auth::user()->nombre }} • {{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="profile-balance">
                <p class="balance-label">Tu Saldo</p>
                <p class="balance-amount">{{ number_format(Auth::user()->saldo, 2) }} €</p>
            </div>
        </div>
    </div>

    <!-- Navegación y Contenido -->
    <div class="profile-layout">
        <!-- Navegación del Perfil -->
        <div class="profile-sidebar">
            <h3 class="sidebar-title">Menú Perfil</h3>
            <nav class="profile-nav">
                <div class="nav-item">
                    <a href="{{ route('password.change.show') }}" class="nav-link {{ request()->routeIs('password.change.show') ? 'active' : '' }}">
                        <i class='bx bx-lock nav-icon'></i>
                        Cambiar Contraseña
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('profile.info') }}" class="nav-link {{ request()->routeIs('profile.info') ? 'active' : '' }}">
                        <i class='bx bx-user nav-icon'></i>
                        Información Personal
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('profile.history') }}" class="nav-link {{ request()->routeIs('profile.history') ? 'active' : '' }}">
                        <i class='bx bx-wallet nav-icon'></i>
                        Historial de Compras
                    </a>
                </div>
            </nav>
        </div>

        <!-- Contenido Dinámico del Perfil -->
        <div class="profile-content">
            @if(request()->routeIs('password.change.show'))
                <!-- Formulario de Cambio de Contraseña -->
                <div class="content-header">
                    <h2 class="content-title">
                        <i class='bx bx-shield title-icon'></i>
                        Cambiar Contraseña
                    </h2>
                    <p class="content-subtitle">Actualiza tu contraseña para mantener tu cuenta segura</p>
                </div>

                @if(session('success'))
                    <div class="alert">
                        <i class='bx bx-check-circle alert-icon'></i>
                        <span class="alert-message">{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.change') }}" class="profile-form">
                    @csrf
                    
                    <!-- Contraseña Actual -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class='bx bx-key label-icon'></i>
                            Contraseña Actual
                        </label>
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            class="form-input @error('current_password') is-invalid @enderror" 
                            placeholder="Ingresa tu contraseña actual"
                            required
                            autocomplete="current-password"
                        >
                        @error('current_password')
                            <div class="form-error">
                                <i class='bx bx-error-circle error-icon'></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Nueva Contraseña -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class='bx bx-lock-alt label-icon'></i>
                            Nueva Contraseña
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input @error('password') is-invalid @enderror" 
                            placeholder="Ingresa tu nueva contraseña (mínimo 6 caracteres)"
                            required
                            autocomplete="new-password"
                        >
                        @error('password')
                            <div class="form-error">
                                <i class='bx bx-error-circle error-icon'></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirmar Nueva Contraseña -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class='bx bx-lock-alt label-icon'></i>
                            Confirmar Nueva Contraseña
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="form-input @error('password_confirmation') is-invalid @enderror" 
                            placeholder="Repite tu nueva contraseña"
                            required
                            autocomplete="new-password"
                        >
                        @error('password_confirmation')
                            <div class="form-error">
                                <i class='bx bx-error-circle error-icon'></i>
                                Las contraseñas no coinciden
                            </div>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-refresh btn-icon'></i>
                            Actualizar Contraseña
                        </button>
                        <a href="{{ route('biblioteca.index') }}" class="btn btn-secondary">
                            <i class='bx bx-arrow-back btn-icon'></i>
                            Volver a la Biblioteca
                        </a>
                    </div>
                </form>

                <!-- Información de Seguridad -->
                <div class="security-info">
                    <h3 class="security-title">
                        <i class='bx bx-shield-check security-icon'></i>
                        Consejos de Seguridad:
                    </h3>
                    <ul class="security-list">
                        <li class="security-item">Usa al menos 6 caracteres</li>
                        <li class="security-item">Combina letras, números y símbolos</li>
                        <li class="security-item">No uses información personal</li>
                        <li class="security-item">Cambia tu contraseña regularmente</li>
                        <li class="security-item">No compartas tu contraseña con nadie</li>
                    </ul>
                </div>
            @elseif(request()->routeIs('profile.info'))
                <!-- Información Personal -->
                <div class="content-header">
                    <h2 class="content-title">
                        <i class='bx bx-user title-icon'></i>
                        Información Personal
                    </h2>
                    <p class="content-subtitle">Gestiona tu información de cuenta</p>
                </div>

                <div class="info-grid">
                    <div class="info-card">
                        <label class="info-label">Nombre de Usuario</label>
                        <div class="info-value">{{ Auth::user()->nombre }}</div>
                    </div>
                    <div class="info-card">
                        <label class="info-label">Email</label>
                        <div class="info-value">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="info-card">
                        <label class="info-label">Rol</label>
                        <div class="info-value">{{ Auth::user()->rol == 'admin' ? 'Administrador' : 'Usuario' }}</div>
                    </div>
                    <div class="info-card">
                        <label class="info-label">Saldo Actual</label>
                        <div class="info-value highlight">{{ number_format(Auth::user()->saldo, 2) }} €</div>
                    </div>
                    <div class="info-card">
                        <label class="info-label">Fecha de Registro</label>
                        <div class="info-value">{{ Auth::user()->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('password.change.show') }}" class="btn btn-primary">
                        <i class='bx bx-lock btn-icon'></i>
                        Cambiar Contraseña
                    </a>
                    <a href="{{ route('biblioteca.index') }}" class="btn btn-secondary">
                        <i class='bx bx-arrow-back btn-icon'></i>
                        Volver a la Biblioteca
                    </a>
                </div>
            @elseif(request()->routeIs('profile.history'))
                <!-- Historial de Compras -->
                <div class="content-header">
                    <h2 class="content-title">
                        <i class='bx bx-wallet title-icon'></i>
                        Historial de Compras
                    </h2>
                    <p class="content-subtitle">Tus juegos adquiridos</p>
                </div>

                <div class="history-list">
                    @php
                        $compras = Auth::user()->bibliotecas()->with('juego')->get();
                    @endphp
                    @if($compras->count() > 0)
                        @foreach($compras as $compra)
                            <div class="history-item">
                                <div class="history-game-info">
                                    <h4>{{ $compra->juego->titulo }}</h4>
                                    <p>Comprado el {{ $compra->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="history-price">{{ number_format($compra->juego->precio, 2) }} €</div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class='bx bx-shopping-bag empty-icon'></i>
                            <p class="empty-message">No tienes compras registradas</p>
                            <a href="{{ route('tienda.index') }}" class="btn btn-primary">
                                <i class='bx bx-store btn-icon'></i>
                                Ir a la Tienda
                            </a>
                        </div>
                    @endif
                </div>

                <div class="form-actions">
                    <a href="{{ route('tienda.index') }}" class="btn btn-primary">
                        <i class='bx bx-store btn-icon'></i>
                        Ir a la Tienda
                    </a>
                    <a href="{{ route('biblioteca.index') }}" class="btn btn-secondary">
                        <i class='bx bx-arrow-back btn-icon'></i>
                        Volver a la Biblioteca
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
