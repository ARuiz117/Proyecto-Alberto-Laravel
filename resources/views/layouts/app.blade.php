<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Steam HRG')</title>
    
    <!-- CSS Modular -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/components.css') }}" />
    @yield('styles')
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="{{ asset('js/accessibility.js') }}" defer></script>
    <script src="{{ asset('js/session-manager.js') }}" defer></script>
</head>
<body @auth class="logged-in" @endauth>
    <!-- Video de fondo -->
    <video autoplay muted loop id="bgVideo">
        <source src="{{ asset('video/ingame.mp4') }}" type="video/mp4">
        Tu navegador no soporta video en HTML5.
    </video>

    <div class="app">
        <header class="header">
            <div class="header-content">
                <div class="logo">
                    <span class="logo-title">Steam HRG</span>
                    <span class="logo-subtitle">Tu plataforma de videojuegos</span>
                </div>
                <div class="header-controls">
                    <div class="auth-buttons">
                        @auth
                            <a href="{{ route('biblioteca.index') }}" class="btn btn-primary">Biblioteca</a>
                            <a href="{{ route('carrito.index') }}" class="btn btn-primary">
                                <i class='bx bx-cart'></i> Carrito
                                @php
                                    $cantidadCarrito = Auth::user()->carritos()->count();
                                @endphp
                                @if($cantidadCarrito > 0)
                                    <span class="badge">{{ $cantidadCarrito }}</span>
                                @endif
                            </a>
                            @if(Auth::user()->isAdmin())
                                <a href="#" class="btn btn-primary">Admin</a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-secondary">Cerrar sesión</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="btn btn-secondary">Registrarse</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main class="main">
            @yield('content')
        </main>

        <footer class="footer">
            <div class="footer-content">
                <span class="footer-copyright">&copy; 2025 Todos los derechos reservados</span>
                <a href="mailto:alberto.rugz@gmail.com" class="footer-email" aria-label="Enviar correo a alberto.rugz@gmail.com">
                    <i class='bx bx-envelope'></i>
                    alberto.rugz@gmail.com
                </a>
                <button class="btn btn-colorblind"
                        onclick="toggleColorBlindMode()"
                        aria-pressed="false"
                        aria-label="Activar modo daltonismo"
                        role="switch">
                    Modo daltonismo
                </button>
            </div>
        </footer>
    </div>
</body>
</html>
