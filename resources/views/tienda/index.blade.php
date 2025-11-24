@extends('layouts.app')

@section('title', 'Tienda - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/biblioteca.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/juego-cards.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/tienda-botones.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/filtrado.css') }}" />
@endsection

@section('content')

<div class="main">
    <!-- Encabezado con información del usuario -->
    <div style="background: #171a21; border: 1px solid #363c44; border-radius: 8px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="color: #66c0f4; font-size: 2rem; margin: 0 0 0.5rem 0; font-weight: 700;">
                    <i class='bx bx-store'></i> Tienda de Juegos
                </h1>
                <p style="color: #8F98A0; margin: 0; font-size: 0.95rem;">Explora nuestro catálogo de juegos exclusivos</p>
            </div>
            <div style="background: #171a21; border: 1px solid #1db954; border-radius: 6px; padding: 1.5rem 2rem; text-align: center;">
                <p style="color: #8F98A0; margin: 0 0 0.5rem 0; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Tu Saldo</p>
                <p style="color: #1db954; margin: 0; font-size: 1.8rem; font-weight: bold;">{{ number_format(Auth::user()->saldo, 2) }} €</p>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="mensaje">{{ session('success') }}</div>
    @endif

    <!-- Buscadores alineados -->
    <section class="busqueda-juegos" style="margin-bottom: 2rem; background: linear-gradient(135deg, #2a475e 0%, #1f3a4d 100%); border: 1px solid #417a9b; border-radius: 8px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); position: relative;">
        <h3 style="color: #66c0f4; margin-top: 0; margin-bottom: 1.5rem; text-transform: uppercase; font-size: 0.95rem; letter-spacing: 0.5px; font-weight: 600; text-align: center;">Filtrar y Buscar</h3>
        <div style="display: flex; justify-content: center; align-items: flex-end; gap: 2rem; flex-wrap: wrap;">
            <!-- Filtro por género -->
            <div class="filtro-genero" style="min-width: 250px;">
                <label style="display: block; color: #dfe3e6; font-size: 0.9rem; margin-bottom: 8px; font-weight: 600; text-align: center;">Género</label>
                <div class="custom-select-container">
                    <div class="custom-select-trigger" onclick="toggleDropdown()">
                        <span class="selected-value">Todos los géneros</span>
                        <div class="select-arrow">
                            <i class='bx bx-chevron-down'></i>
                        </div>
                    </div>
                </div>
                <form method="GET" action="{{ route('tienda.index') }}" id="generoForm" style="display: none;">
                    <input type="hidden" name="genero" id="generoInput" value="">
                </form>
            </div>
            
            <!-- Buscador con filtrado en tiempo real -->
            <div class="search-container" style="min-width: 300px;">
                <label style="display: block; color: #dfe3e6; font-size: 0.9rem; margin-bottom: 8px; font-weight: 600; text-align: center;">Buscar Juego</label>
                <div class="search-wrapper">
                    <input type="text" id="filtroJuegos" placeholder="Escribe para filtrar juegos..." autocomplete="off" style="width: 100%; padding: 12px 15px; border-radius: 6px; border: 1px solid #417a9b; background: linear-gradient(135deg, #0a1929 0%, #1a2332 100%); color: #dfe3e6; font-size: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); transition: all 0.3s ease;">
                </div>
            </div>
        </div>
        
        @if(!empty($generoSeleccionado))
            <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 1.5rem;">
                <span style="color: #dfe3e6; background: rgba(65, 122, 155, 0.2); padding: 8px 15px; border-radius: 20px; border: 1px solid #417a9b;">Filtro activo: <strong style="color: #66c0f4;">{{ $generoSeleccionado }}</strong></span>
                <a href="{{ route('tienda.index') }}" class="btn btn-small">Volver a la Tienda</a>
            </div>
        @endif
        
        <!-- Desplegable movido fuera del contenedor -->
        <div class="custom-select-options" id="generoDropdown">
            <div class="custom-option" data-value="" onclick="selectGenero(this, 'Todos los géneros')">
                <span class="option-icon"><i class='bx bx-category-alt'></i></span>
                <span class="option-text">Todos los géneros</span>
                <span class="option-check"><i class='bx bx-check'></i></span>
            </div>
            @foreach($generos as $g)
                <div class="custom-option" data-value="{{ $g }}" onclick="selectGenero(this, '{{ $g }}')">
                    <span class="option-icon">
                        @if($g == 'Acción') 
                            <i class='bx bx-game'></i>
                        @elseif($g == 'RPG') 
                            <i class='bx bx-shield-quarter'></i>
                        @elseif($g == 'Terror') 
                            <i class='bx bx-ghost'></i>
                        @elseif($g == 'Estrategia') 
                            <i class='bx bx-brain'></i>
                        @elseif($g == 'Aventura') 
                            <i class='bx bx-compass'></i>
                        @elseif($g == 'Deportes') 
                            <i class='bx bx-football'></i>
                        @elseif($g == 'Puzzle') 
                            <i class='bx bx-cube'></i>
                        @elseif($g == 'Simulación') 
                            <i class='bx bx-building-house'></i>
                        @else 
                            <i class='bx bx-game'></i>
                        @endif
                    </span>
                    <span class="option-text">{{ $g }}</span>
                    <span class="option-check"><i class='bx bx-check'></i></span>
                </div>
            @endforeach
        </div>
    </section>

    @if($juegos->isEmpty())
        <p>No hay juegos disponibles.</p>
    @else
        <section class="juegos-grid">
            @foreach($juegos as $juego)
                <article class="juego-card" data-titulo="{{ strtolower($juego->titulo) }}" data-genero="{{ strtolower($juego->genero) }}">
                    <h4>{{ $juego->titulo }}</h4>
                    <div class="img-container">
                        <img loading="lazy" decoding="async" src="{{ asset('imagenes/' . $juego->imagen_url) }}" alt="Portada de {{ $juego->titulo }}">
                    </div>
                    <p>{{ Str::limit($juego->descripcion, 80) }}</p>
                    <div class="precio">Precio: {{ number_format($juego->precio, 2) }} €</div>
                    <div class="juego-acciones">
                        <a href="{{ route('tienda.show', $juego->id) }}" class="btn btn-detalles">
                            <i class='bx bx-info-circle'></i> Detalles
                        </a>
                        <form method="POST" action="{{ route('carrito.agregar') }}" class="inline-form">
                            @csrf
                            <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                            <button class="btn btn-secondary" type="submit">
                                <i class='bx bx-cart-add'></i> Carrito
                            </button>
                        </form>
                        <form method="POST" action="{{ route('biblioteca.comprar') }}" class="inline-form" @if(Auth::user()->saldo < $juego->precio) class="form-disabled" title="Saldo insuficiente" @endif>
                            @csrf
                            <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                            <button class="btn btn-success" type="submit" @if(Auth::user()->saldo < $juego->precio) disabled @endif title="Comprar ahora">
                                <i class='bx bx-shopping-bag'></i> Comprar
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </section>

        <div class="pagination">
            {{ $juegos->links() }}
        </div>
    @endif
</div>

<!-- JavaScript de filtrado -->
<script src="{{ asset('js/filtrado.js') }}"></script>

<!-- JavaScript para desplegable personalizado -->
<script>
// Función para abrir/cerrar el desplegable
function toggleDropdown() {
    const trigger = document.querySelector('.custom-select-trigger');
    const options = document.getElementById('generoDropdown');
    
    trigger.classList.toggle('active');
    options.classList.toggle('show');
    
    // Calcular posición correcta del desplegable
    if (options.classList.contains('show')) {
        const triggerRect = trigger.getBoundingClientRect();
        const containerRect = document.querySelector('.busqueda-juegos').getBoundingClientRect();
        
        // Posicionar el desplegable relativo al contenedor (no se mueve con scroll)
        options.style.position = 'absolute';
        options.style.top = (triggerRect.bottom - containerRect.top + 8) + 'px';
        options.style.left = (triggerRect.left - containerRect.left) + 'px';
        options.style.width = triggerRect.width + 'px';
        options.style.zIndex = '9999';
        
        // Resetear animaciones al abrir
        const optionElements = options.querySelectorAll('.custom-option');
        optionElements.forEach((option, index) => {
            option.style.animation = 'none';
            option.offsetHeight; // Forzar reflow
            option.style.animation = `slideInOption 0.3s ease-out ${index * 0.05}s backwards`;
        });
    }
}

// Función para seleccionar un género
function selectGenero(element, value) {
    const trigger = document.querySelector('.custom-select-trigger');
    const selectedValue = trigger.querySelector('.selected-value');
    const options = document.querySelectorAll('.custom-option');
    const input = document.getElementById('generoInput');
    const form = document.getElementById('generoForm');
    
    // Actualizar texto seleccionado
    selectedValue.textContent = value;
    
    // Actualizar estado de las opciones
    options.forEach(option => {
        option.classList.remove('selected');
    });
    element.classList.add('selected');
    
    // Cerrar desplegable
    trigger.classList.remove('active');
    document.getElementById('generoDropdown').classList.remove('show');
    
    // Establecer valor y enviar formulario
    input.value = element.dataset.value;
    form.submit();
}

// Cerrar desplegable al hacer clic fuera
document.addEventListener('click', function(event) {
    const container = document.querySelector('.custom-select-container');
    const options = document.getElementById('generoDropdown');
    
    if (!container.contains(event.target) && !options.contains(event.target)) {
        document.querySelector('.custom-select-trigger').classList.remove('active');
        options.classList.remove('show');
    }
});

// Establecer valor inicial si hay un género seleccionado
document.addEventListener('DOMContentLoaded', function() {
    @if(!empty($generoSeleccionado))
        const selectedValue = '{{ $generoSeleccionado }}';
        const selectedText = document.querySelector('.selected-value');
        const options = document.querySelectorAll('.custom-option');
        
        selectedText.textContent = selectedValue;
        
        options.forEach(option => {
            if (option.dataset.value === selectedValue) {
                option.classList.add('selected');
            }
        });
    @endif
});
</script>

@endsection
