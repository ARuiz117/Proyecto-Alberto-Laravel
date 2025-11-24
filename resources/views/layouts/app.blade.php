<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Steam HRG')</title>
    
    <!-- CSS Modular -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/components.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/cursor.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}" />
    @yield('styles')
    
    <!-- Admin CSS - Cargar último para máxima prioridad -->
    @if(request()->is('admin/*'))
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    @endif
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="{{ asset('js/session-manager.js') }}" defer></script>
    <script src="{{ asset('js/cursor.js') }}" defer></script>
</head>
<body @auth class="logged-in" @endauth>
    <!-- Formulario oculto para logout automático -->
    <form id="auto-logout-form" action="{{ url('/logout') }}" method="POST" class="hidden-form">
        @csrf
    </form>
    <!-- Cortina Stairs - Efecto Mar Rojo con rectángulos que se parten -->
    @auth
    @if(session()->has('just_logged_in'))
        <div id="stairs-curtain">
            <div class="red-sea-curtain">
                <!-- 10 columnas de agua que se parten en el medio -->
                <div class="water-column-container">
                    <!-- Columna 1 -->
                    <div class="water-column col-1">
                        <div class="water-half water-top"></div>
                        <div class="water-half water-bottom"></div>
                    </div>
                    <!-- Columna 2 -->
                    <div class="water-column col-2">
                        <div class="water-half water-top"></div>
                        <div class="water-half water-bottom"></div>
                    </div>
                    <!-- Columna 3 -->
                    <div class="water-column col-3">
                        <div class="water-half water-top"></div>
                        <div class="water-half water-bottom"></div>
                    </div>
                    <!-- Columna 4 -->
                    <div class="water-column col-4">
                        <div class="water-half water-top"></div>
                        <div class="water-half water-bottom"></div>
                    </div>
                    <!-- Columna 5 -->
                    <div class="water-column col-5">
                        <div class="water-half water-top"></div>
                        <div class="water-half water-bottom"></div>
                    </div>
                    <!-- Columna 6 -->
                    <div class="water-column col-6">
                        <div class="water-half water-top"></div>
                        <div class="water-half water-bottom"></div>
                    </div>
                    <!-- Columna 7 -->
                    <div class="water-column col-7">
                        <div class="water-half water-top"></div>
                        <div class="water-half water-bottom"></div>
                    </div>
                    <!-- Columna 8 -->
                    <div class="water-column col-8">
                        <div class="water-half water-top"></div>
                        <div class="water-half water-bottom"></div>
                    </div>
                    <!-- Columna 9 -->
                    <div class="water-column col-9">
                        <div class="water-half water-top"></div>
                        <div class="water-half water-bottom"></div>
                    </div>
                    <!-- Columna 10 -->
                    <div class="water-column col-10">
                        <div class="water-half water-top"></div>
                        <div class="water-half water-bottom"></div>
                    </div>
                </div>
            </div>
        </div>
        @php
            session()->forget('just_logged_in');
        @endphp
    @endif
    @endauth
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
                            <a href="{{ route('tienda.index') }}" class="btn btn-primary">Tienda</a>
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
                            <a href="{{ route('wallet.show') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #1db954 0%, #238636 100%); border: none;">
                                <i class='bx bx-wallet'></i> {{ number_format(Auth::user()->saldo, 2) }} €
                            </a>
                            <a href="{{ route('password.change.show') }}" class="btn btn-secondary" style="background: rgba(59, 130, 246, 0.8); border: 1px solid rgba(59, 130, 246, 0.4);">
                                <i class='bx bx-user'></i> Mi Perfil
                            </a>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Admin</a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="inline-form">
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
            </div>
        </footer>
    </div>

    <!-- Botón Flotante Scroll hacia Arriba -->
    <button id="scrollToTopBtn" class="scroll-to-top" onclick="scrollToTop()" title="Subir al inicio">
        <i class='bx bx-chevron-up'></i>
    </button>

    <style>
    .scroll-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(20px);
    }

    .scroll-to-top.visible {
        display: flex;
        opacity: 1;
        transform: translateY(0);
    }

    .scroll-to-top:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }

    /* Cortina Stairs - Efecto Mar Rojo con rectángulos que se parten */
    #stairs-curtain {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: transparent;
        z-index: 10000;
        overflow: hidden;
        pointer-events: none;
    }
    
    /* Contenedor del Mar Rojo */
    .red-sea-curtain {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        perspective: 1000px;
    }
    
    /* Contenedor de columnas */
    .water-column-container {
        display: flex;
        width: 100%;
        height: 100vh;
        justify-content: space-between;
        position: relative;
    }
    
    /* Columnas de agua que se parten */
    .water-column {
        width: 10%;
        height: 100vh;
        position: relative;
        overflow: hidden;
    }
    
    /* Mitades de agua */
    .water-half {
        position: absolute;
        width: 100%;
        height: 50vh;
        background: linear-gradient(180deg, 
            rgba(102, 192, 244, 0.9) 0%, 
            rgba(27, 40, 56, 0.95) 20%,
            rgba(15, 23, 42, 1) 50%,
            rgba(27, 40, 56, 0.95) 80%,
            rgba(102, 192, 244, 0.9) 100%);
        box-shadow: 
            0 0 40px rgba(102, 192, 244, 0.6),
            inset 0 0 30px rgba(102, 192, 244, 0.3),
            0 0 80px rgba(102, 192, 244, 0.2);
        border-left: 2px solid rgba(102, 192, 244, 0.5);
        border-right: 2px solid rgba(102, 192, 244, 0.3);
        overflow: hidden;
    }
    
    /* Mitad superior */
    .water-top {
        top: 0;
        transform-origin: bottom center;
    }
    
    /* Mitad inferior */
    .water-bottom {
        bottom: 0;
        transform-origin: top center;
    }
    
    /* Efecto de ola en cada mitad */
    .water-half::before {
        content: '';
        position: absolute;
        top: -100%;
        left: 0;
        width: 100%;
        height: 200%;
        background: linear-gradient(180deg, 
            transparent 0%, 
            rgba(102, 192, 244, 0.4) 25%,
            transparent 50%,
            rgba(102, 192, 244, 0.4) 75%,
            transparent 100%);
        animation: waterWave 3s linear infinite;
    }
    
    /* Efecto de brillo adicional */
    .water-half::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            transparent 0%, 
            rgba(255, 255, 255, 0.1) 50%,
            transparent 100%);
        animation: waterShimmer 2s ease-in-out infinite;
    }
    
    /* Animaciones de las mitades superiores (van hacia arriba) */
    .col-1 .water-top { animation: waterTopMove 3s linear 0.1s forwards; }
    .col-2 .water-top { animation: waterTopMove 3s linear 0.2s forwards; }
    .col-3 .water-top { animation: waterTopMove 3s linear 0.3s forwards; }
    .col-4 .water-top { animation: waterTopMove 3s linear 0.4s forwards; }
    .col-5 .water-top { animation: waterTopMove 3s linear 0.5s forwards; }
    .col-6 .water-top { animation: waterTopMove 3s linear 0.6s forwards; }
    .col-7 .water-top { animation: waterTopMove 3s linear 0.7s forwards; }
    .col-8 .water-top { animation: waterTopMove 3s linear 0.8s forwards; }
    .col-9 .water-top { animation: waterTopMove 3s linear 0.9s forwards; }
    .col-10 .water-top { animation: waterTopMove 3s linear 1.0s forwards; }
    
    /* Animaciones de las mitades inferiores (van hacia abajo) */
    .col-1 .water-bottom { animation: waterBottomMove 3s linear 0.1s forwards; }
    .col-2 .water-bottom { animation: waterBottomMove 3s linear 0.2s forwards; }
    .col-3 .water-bottom { animation: waterBottomMove 3s linear 0.3s forwards; }
    .col-4 .water-bottom { animation: waterBottomMove 3s linear 0.4s forwards; }
    .col-5 .water-bottom { animation: waterBottomMove 3s linear 0.5s forwards; }
    .col-6 .water-bottom { animation: waterBottomMove 3s linear 0.6s forwards; }
    .col-7 .water-bottom { animation: waterBottomMove 3s linear 0.7s forwards; }
    .col-8 .water-bottom { animation: waterBottomMove 3s linear 0.8s forwards; }
    .col-9 .water-bottom { animation: waterBottomMove 3s linear 0.9s forwards; }
    .col-10 .water-bottom { animation: waterBottomMove 3s linear 1.0s forwards; }
    
    /* Animaciones de las mitades - Movimiento continuo y siempre visibles */
    @keyframes waterTopMove {
        0% {
            transform: translateY(0) rotateX(0deg);
            opacity: 1;
            filter: brightness(1);
        }
        100% {
            transform: translateY(-150vh) rotateX(-45deg);
            opacity: 1;
            filter: brightness(1.5);
        }
    }
    
    @keyframes waterBottomMove {
        0% {
            transform: translateY(0) rotateX(0deg);
            opacity: 1;
            filter: brightness(1);
        }
        100% {
            transform: translateY(150vh) rotateX(45deg);
            opacity: 1;
            filter: brightness(1.5);
        }
    }
    
    @keyframes waterWave {
        0% {
            transform: translateY(-100%);
        }
        100% {
            transform: translateY(100%);
        }
    }
    
    @keyframes waterShimmer {
        0%, 100% {
            opacity: 0.3;
            transform: translateX(-100%);
        }
        50% {
            opacity: 0.8;
            transform: translateX(100%);
        }
    }
    
    /* Animación de salida completa */
    .curtain-complete {
        animation: seaFadeOut 0.5s ease-out 2.5s forwards;
    }
    
    @keyframes seaFadeOut {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            pointer-events: none;
        }
    }
    </style>

    <script>
    // Cortina Stairs - Efecto Mar Rojo con movimiento continuo
    document.addEventListener('DOMContentLoaded', function() {
        const stairsCurtain = document.getElementById('stairs-curtain');
        if (stairsCurtain) {
            // Los rectángulos se mueven de forma continua y siempre visibles
            // La cortina completa desaparece al final de todas las animaciones
            setTimeout(() => {
                stairsCurtain.remove();
            }, 4000);
        }
    });
    window.addEventListener('scroll', function() {
        const scrollBtn = document.getElementById('scrollToTopBtn');
        if (window.pageYOffset > 300) { // Mostrar después de 300px de scroll
            scrollBtn.classList.add('visible');
        } else {
            scrollBtn.classList.remove('visible');
        }
    });

    // Función para scroll suave hacia arriba
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // También mostrar con teclado (accesibilidad)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Home' && !e.ctrlKey && !e.altKey && !e.shiftKey) {
            e.preventDefault();
            scrollToTop();
        }
    });
    </script>
</body>
</html>
