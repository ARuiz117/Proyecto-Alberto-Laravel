@extends('layouts.app')

@section('title', 'Mi Biblioteca - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/biblioteca.css') }}" />
@endsection

@section('content')

<div class="main">
    <h3>Bienvenido, {{ Auth::user()->nombre }}. Tu saldo: {{ number_format(Auth::user()->saldo, 2) }} €</h3>

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="mensaje">{{ session('success') }}</div>
    @endif

    <section class="biblioteca">
        <h3>Tu Biblioteca</h3>
        @if($misJuegos->isEmpty())
            <p>Aún no has comprado ningún juego.</p>
        @else
            @foreach($misJuegos as $juego)
                <article class="juego-card">
                    <h4>{{ $juego->titulo }}</h4>
                    <div class="img-container">
                        <img loading="lazy" decoding="async" src="/imagenes/{{ $juego->imagen_url }}" alt="Portada de {{ $juego->titulo }}">
                    </div>
                    <p>{{ $juego->descripcion }}</p>
                    
                    <!-- Botones de acciones -->
                    <div style="display: flex; gap: 10px; margin-top: 10px;">
                        <!-- Botón Ver Reseñas -->
                        <a href="{{ route('tienda.show', $juego->id) }}" class="btn" style="flex: 1; background: #66c0f4; text-decoration: none; text-align: center;">
                            <i class='bx bx-comment'></i> Ver reseñas
                        </a>
                        
                        <!-- Botón Reseña -->
                        <button class="btn" type="button" onclick="abrirFormularioResena({{ $juego->id }}, '{{ $juego->titulo }}')" style="flex: 1; background: #1db954;">
                            <i class='bx bx-star'></i> Reseña
                        </button>
                        
                        <!-- Botón Devolver -->
                        <form method="POST" action="{{ route('biblioteca.devolver') }}" style="flex: 1;" onsubmit="return confirm('¿Seguro que quieres devolver este juego?');">
                            @csrf
                            <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                            <button class="btn" type="submit" style="width: 100%; background: #c7302a;">
                                <i class='bx bx-trash'></i> Devolver
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        @endif
    </section>

    <section class="acciones-biblioteca" style="margin-top: 30px; display: flex; gap: 10px;">
        <a href="{{ route('tienda.index') }}" class="btn">
            <i class='bx bx-store'></i> Ir a la tienda
        </a>
        <a href="{{ route('carrito.index') }}" class="btn">
            <i class='bx bx-cart'></i> Ver carrito
        </a>
    </section>
</div>

<!-- Modal de Reseña -->
<div id="modalResena" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: #1b3a52; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="color: #66c0f4; margin: 0;">Reseña de <span id="tituloJuego"></span></h2>
            <button onclick="cerrarFormularioResena()" style="background: none; border: none; color: #dfe3e6; font-size: 24px; cursor: pointer;">✕</button>
        </div>

        <form method="POST" action="{{ route('resena.store') }}">
            @csrf
            <input type="hidden" name="juego_id" id="juegoId">
            
            <!-- Calificación -->
            <div style="margin-bottom: 20px;">
                <label style="color: #dfe3e6; display: block; margin-bottom: 10px; font-weight: bold;">Calificación (1-5 estrellas)</label>
                <div style="display: flex; gap: 15px; font-size: 32px;">
                    @for($i = 1; $i <= 5; $i++)
                        <label style="cursor: pointer;">
                            <input type="radio" name="calificacion" value="{{ $i }}" required style="display: none;" class="star-input">
                            <span class="star-label" style="color: #8b8e91;">★</span>
                        </label>
                    @endfor
                </div>
            </div>

            <!-- Recomendación -->
            <div style="margin-bottom: 20px;">
                <label style="color: #dfe3e6; display: block; margin-bottom: 10px; font-weight: bold;">¿Recomiendas este juego?</label>
                <div style="display: flex; gap: 20px;">
                    <label style="color: #dfe3e6; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="recomendacion" value="1" required style="cursor: pointer;"> 
                        <i class='bx bx-thumbs-up' style="color: #1db954; font-size: 20px;"></i> Sí, lo recomiendo
                    </label>
                    <label style="color: #dfe3e6; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="recomendacion" value="0" required style="cursor: pointer;">
                        <i class='bx bx-thumbs-down' style="color: #c7302a; font-size: 20px;"></i> No lo recomiendo
                    </label>
                </div>
            </div>

            <!-- Comentario -->
            <div style="margin-bottom: 20px;">
                <label style="color: #dfe3e6; display: block; margin-bottom: 10px; font-weight: bold;">Tu comentario</label>
                <textarea name="contenido" placeholder="Comparte tu opinión sobre este juego..." required minlength="10" maxlength="1000" style="width: 100%; padding: 12px; border-radius: 5px; border: 1px solid #2a475e; background: #0a1929; color: #dfe3e6; min-height: 120px; font-family: inherit; font-size: 14px;"></textarea>
                <small style="color: #8b8e91; display: block; margin-top: 5px;">Mínimo 10 caracteres, máximo 1000</small>
            </div>

            <!-- Botones -->
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn" style="flex: 1; background: #1db954;">Publicar reseña</button>
                <button type="button" onclick="cerrarFormularioResena()" class="btn" style="flex: 1; background: #8b8e91;">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirFormularioResena(juegoId, titulo) {
    document.getElementById('juegoId').value = juegoId;
    document.getElementById('tituloJuego').textContent = titulo;
    document.getElementById('modalResena').style.display = 'flex';
}

function cerrarFormularioResena() {
    document.getElementById('modalResena').style.display = 'none';
    document.getElementById('juegoId').value = '';
    document.getElementById('tituloJuego').textContent = '';
    // Limpiar formulario
    document.querySelector('form').reset();
}

// Efecto de estrellas interactivas
document.querySelectorAll('.star-input').forEach(input => {
    input.addEventListener('change', function() {
        const value = this.value;
        document.querySelectorAll('.star-label').forEach((label, index) => {
            if (index < value) {
                label.style.color = '#ffd700';
            } else {
                label.style.color = '#8b8e91';
            }
        });
    });
});

// Cerrar modal al hacer clic fuera
document.getElementById('modalResena').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarFormularioResena();
    }
});
</script>

@endsection
