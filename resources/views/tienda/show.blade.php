@extends('layouts.app')

@section('title', $juego->titulo . ' - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/biblioteca.css') }}" />
@endsection

@section('content')

<div class="main">
    <a href="{{ route('tienda.index') }}" class="btn btn-secondary" style="text-decoration: none;">← Volver a la tienda</a>

    <section class="juego-detalle" style="margin: 20px 0;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
            <div style="max-width: 400px;">
                <!-- Imagen Principal -->
                <div class="juego-imagen" style="margin-bottom: 30px; width: 100%; max-width: 350px;">
                    <img src="/imagenes/{{ $juego->imagen_url }}" alt="Portada de {{ $juego->titulo }}" style="width: 100%; height: auto; border-radius: 10px; cursor: pointer; box-shadow: 0 8px 16px rgba(0,0,0,0.5); transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'" onclick="abrirTrailer('{{ $juego->titulo }}')">
                    <p style="color: #8b8e91; font-size: 12px; margin-top: 12px; text-align: center;">🎬 Haz clic para ver el trailer</p>
                </div>
                
                <!-- Carrusel de Screenshots con Hover Expandible -->
                <div id="carruselScreenshots" style="display: none; width: 100%;">
                    <h3 style="color: #66c0f4; margin-bottom: 15px; font-size: 18px;">📸 Capturas de pantalla</h3>
                    <div style="background: #0a1929; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.3); width: 100%; padding: 15px;">
                        <div style="display: flex; gap: 8px; justify-content: center; align-items: flex-end; height: 280px; width: 100%;" id="screenshotsContainer">
                            <!-- Screenshots se cargarán aquí -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="juego-info">
                <h1 style="color: #66c0f4; margin-bottom: 10px;">{{ $juego->titulo }}</h1>
                <p style="color: #dfe3e6; margin-bottom: 20px;">{{ $juego->descripcion }}</p>
                
                <div style="margin-bottom: 20px;">
                    <span style="font-size: 28px; color: #1db954; font-weight: bold;">{{ number_format($juego->precio, 2) }} €</span>
                </div>

                @if($tieneJuego)
                    <div style="background: #1db954; padding: 10px 15px; border-radius: 5px; color: white; text-align: center; margin-bottom: 15px;">
                        <i class='bx bx-check-circle'></i> Ya posees este juego
                    </div>
                    <a href="{{ route('biblioteca.index') }}" class="btn btn-primary" style="display: block; text-align: center; text-decoration: none;">
                        <i class='bx bx-arrow-back'></i> Volver a mi biblioteca
                    </a>
                @else
                    <div class="juego-acciones" style="display: flex; gap: 10px;">
                        <form method="POST" action="{{ route('carrito.agregar') }}" style="flex: 1;">
                            @csrf
                            <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                            <button class="btn btn-secondary" type="submit" style="width: 100%;">
                                <i class='bx bx-cart-add'></i> Carrito
                            </button>
                        </form>
                        <form method="POST" action="{{ route('biblioteca.comprar') }}" style="flex: 1;" @if(Auth::user()->saldo < $juego->precio) class="form-disabled" title="Saldo insuficiente" @endif>
                            @csrf
                            <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                            <button class="btn btn-success" type="submit" style="width: 100%;" @if(Auth::user()->saldo < $juego->precio) disabled @endif>
                                <i class='bx bx-shopping-bag'></i> Comprar
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="juego-resenas" style="margin-top: 40px;">
        <h2 style="color: #66c0f4; margin-bottom: 20px;">Reseñas ({{ $resenas->total() }})</h2>

        @if($tieneJuego)
            <div style="background: #2a475e; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center;">
                <p style="color: #dfe3e6; margin: 0;">¿Tienes este juego? Haz clic en el botón "Reseña" en tu biblioteca para compartir tu opinión.</p>
            </div>
        @endif

        @if($resenas->isEmpty())
            <p style="color: #dfe3e6;">No hay reseñas aún. ¡Sé el primero en reseñar!</p>
        @else
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($resenas as $resena)
                    <article style="background: #2a475e; padding: 15px; border-radius: 10px; @if(Auth::user()->id === $resena->usuario_id) border-left: 4px solid #1db954; @endif">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                            <div>
                                <strong style="color: #66c0f4;">
                                    {{ $resena->usuario->nombre }}
                                    @if(Auth::user()->id === $resena->usuario_id)
                                        <span style="background: #1db954; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px; margin-left: 8px;">Tu reseña</span>
                                    @endif
                                </strong>
                                <span style="color: #8b8e91; font-size: 12px; margin-left: 10px;">{{ $resena->created_at->diffForHumans() }}</span>
                            </div>
                            <div style="text-align: right;">
                                <!-- Calificación en estrellas -->
                                <div style="color: #ffd700; font-size: 16px; margin-bottom: 5px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span style="color: {{ $i <= $resena->calificacion ? '#ffd700' : '#8b8e91' }};">★</span>
                                    @endfor
                                    <span style="color: #8b8e91; font-size: 12px; margin-left: 5px;">{{ $resena->calificacion }}/5</span>
                                </div>
                                <!-- Recomendación -->
                                <div style="font-size: 14px;">
                                    @if($resena->recomendacion)
                                        <i class='bx bx-thumbs-up' style="color: #1db954;"></i>
                                        <span style="color: #1db954;">Recomendado</span>
                                    @else
                                        <i class='bx bx-thumbs-down' style="color: #c7302a;"></i>
                                        <span style="color: #c7302a;">No recomendado</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <p style="color: #dfe3e6; margin: 10px 0;">{{ $resena->contenido }}</p>
                        
                        @if(Auth::user()->id === $resena->usuario_id || Auth::user()->isAdmin())
                            <div style="margin-top: 10px;">
                                <form method="POST" action="{{ route('resena.destroy', $resena->id) }}" style="display: inline;" onsubmit="return confirm('¿Eliminar esta reseña?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Eliminar</button>
                                </form>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>

            <div class="pagination" style="margin-top: 20px;">
                {{ $resenas->links() }}
            </div>
        @endif
    </section>
</div>

<!-- Modal de Trailer/Screenshots -->
<div id="trailerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); z-index: 2000; justify-content: center; align-items: center;">
    <div style="position: relative; width: 90%; max-width: 900px; background: #0a1929; border-radius: 10px; overflow: hidden;">
        <button onclick="cerrarTrailer()" style="position: absolute; top: 10px; right: 10px; background: #c7302a; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; z-index: 2001; font-size: 16px;">✕ Cerrar</button>
        
        <!-- Botones de navegación para screenshots -->
        <button id="btnAnterior" onclick="mostrarScreenshotAnterior()" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); background: rgba(29, 185, 84, 0.9); color: white; border: none; padding: 12px 15px; cursor: pointer; border-radius: 5px; z-index: 2001; font-size: 18px; font-weight: bold; display: none; transition: background 0.3s ease;" onmouseover="this.style.background='rgba(29, 185, 84, 1)'" onmouseout="this.style.background='rgba(29, 185, 84, 0.9)'">◀</button>
        <button id="btnSiguiente" onclick="mostrarScreenshotSiguiente()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: rgba(29, 185, 84, 0.9); color: white; border: none; padding: 12px 15px; cursor: pointer; border-radius: 5px; z-index: 2001; font-size: 18px; font-weight: bold; display: none; transition: background 0.3s ease;" onmouseover="this.style.background='rgba(29, 185, 84, 1)'" onmouseout="this.style.background='rgba(29, 185, 84, 0.9)'">▶</button>
        
        <!-- Indicador de posición -->
        <div id="indicadorScreenshots" style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.7); color: white; padding: 8px 12px; border-radius: 5px; z-index: 2001; font-size: 12px; display: none;"></div>
        
        <div id="trailerContainer" style="width: 100%; padding-top: 56.25%; position: relative; background: #000; display: flex; align-items: center; justify-content: center;">
            <iframe id="trailerFrame" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; display: none;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <div id="mediaContent" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;"></div>
        </div>
    </div>
</div>

<script>
// Variables globales para navegación de screenshots
let screenshotsActuales = [];
let indexScreenshotActual = 0;

async function abrirTrailer(titulo) {
    const modal = document.getElementById('trailerModal');
    const frame = document.getElementById('trailerFrame');
    const mediaContent = document.getElementById('mediaContent');
    
    // Mostrar modal mientras carga
    modal.style.display = 'flex';
    frame.style.display = 'none';
    mediaContent.innerHTML = '<p style="color: #dfe3e6; text-align: center;">Cargando trailer...</p>';
    
    // Obtener trailer desde Steam API
    try {
        const response = await fetch('{{ route("trailer.obtener") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ titulo: titulo })
        });
        
        const data = await response.json();
        
        if (data.success && data.trailer_url) {
            frame.src = data.trailer_url;
            frame.style.display = 'block';
            mediaContent.innerHTML = '';
        } else {
            frame.style.display = 'none';
            mediaContent.innerHTML = '<p style="color: #dfe3e6; text-align: center;">No se encontró trailer disponible</p>';
        }
    } catch (error) {
        console.error('Error:', error);
        frame.style.display = 'none';
        mediaContent.innerHTML = '<p style="color: #dfe3e6; text-align: center;">Error al cargar el trailer</p>';
    }
}

function cerrarTrailer() {
    const modal = document.getElementById('trailerModal');
    const frame = document.getElementById('trailerFrame');
    const mediaContent = document.getElementById('mediaContent');
    
    modal.style.display = 'none';
    frame.src = '';
    frame.style.display = 'none';
    mediaContent.innerHTML = '';
}

// Cerrar modal al hacer clic fuera
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('trailerModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarTrailer();
            }
        });
    }
    
    // Cargar screenshots
    cargarScreenshots('{{ $juego->titulo }}');
});

// Cargar screenshots desde Steam API
async function cargarScreenshots(titulo) {
    try {
        const response = await fetch('{{ route("trailer.screenshots") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ titulo: titulo })
        });
        
        const data = await response.json();
        
        if (data.success && data.screenshots && data.screenshots.length > 0) {
            const container = document.getElementById('screenshotsContainer');
            const carrusel = document.getElementById('carruselScreenshots');
            
            // Guardar screenshots para navegación
            screenshotsActuales = data.screenshots;
            
            container.innerHTML = '';
            
            let activeIndex = 0;
            
            data.screenshots.forEach((screenshot, index) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'screenshot-wrapper';
                wrapper.style.cssText = `
                    position: relative;
                    flex-shrink: 0;
                    height: 100%;
                    border-radius: 12px;
                    overflow: hidden;
                    cursor: pointer;
                    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                    width: ${index === activeIndex ? '240px' : '60px'};
                    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                `;
                
                const img = document.createElement('img');
                img.src = screenshot.path_thumbnail;
                img.alt = `Screenshot ${index + 1}`;
                img.style.cssText = 'width: 100%; height: 100%; object-fit: cover; display: block;';
                
                // Overlay gradient
                const overlay = document.createElement('div');
                overlay.className = 'screenshot-overlay';
                overlay.style.cssText = `
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
                    opacity: ${index === activeIndex ? '1' : '0'};
                    transition: opacity 0.3s ease;
                    z-index: 2;
                `;
                
                // Número de screenshot
                const number = document.createElement('div');
                number.className = 'screenshot-number';
                number.style.cssText = `
                    position: absolute;
                    bottom: 10px;
                    right: 10px;
                    color: rgba(255,255,255,0.7);
                    font-size: 12px;
                    z-index: 3;
                    opacity: ${index === activeIndex ? '1' : '0'};
                    transition: opacity 0.3s ease;
                `;
                number.textContent = `${index + 1}/${data.screenshots.length}`;
                
                wrapper.appendChild(img);
                wrapper.appendChild(overlay);
                wrapper.appendChild(number);
                
                // Evento hover
                wrapper.addEventListener('mouseenter', () => {
                    // Actualizar todos los wrappers
                    const allWrappers = container.querySelectorAll('.screenshot-wrapper');
                    allWrappers.forEach((w, i) => {
                        const isActive = i === index;
                        w.style.width = isActive ? '240px' : '60px';
                        w.querySelector('.screenshot-overlay').style.opacity = isActive ? '1' : '0';
                        w.querySelector('.screenshot-number').style.opacity = isActive ? '1' : '0';
                    });
                    activeIndex = index;
                });
                
                wrapper.addEventListener('click', () => {
                    abrirScreenshot(screenshot.path_full, index);
                });
                
                container.appendChild(wrapper);
            });
            
            carrusel.style.display = 'block';
        }
    } catch (error) {
        console.error('Error al cargar screenshots:', error);
    }
}

// Abrir screenshot en modal
function abrirScreenshot(imagenUrl, index = 0) {
    const modal = document.getElementById('trailerModal');
    const frame = document.getElementById('trailerFrame');
    const mediaContent = document.getElementById('mediaContent');
    const btnAnterior = document.getElementById('btnAnterior');
    const btnSiguiente = document.getElementById('btnSiguiente');
    const indicador = document.getElementById('indicadorScreenshots');
    
    frame.style.display = 'none';
    frame.src = '';
    
    // Guardar índice actual
    indexScreenshotActual = index;
    
    // Mostrar imagen
    const img = document.createElement('img');
    img.src = imagenUrl;
    img.style.cssText = 'max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 8px;';
    img.alt = 'Screenshot completa';
    
    mediaContent.innerHTML = '';
    mediaContent.appendChild(img);
    
    // Mostrar botones de navegación si hay múltiples screenshots
    if (screenshotsActuales.length > 1) {
        btnAnterior.style.display = index > 0 ? 'block' : 'none';
        btnSiguiente.style.display = index < screenshotsActuales.length - 1 ? 'block' : 'none';
        indicador.style.display = 'block';
        indicador.textContent = `${index + 1} / ${screenshotsActuales.length}`;
    } else {
        btnAnterior.style.display = 'none';
        btnSiguiente.style.display = 'none';
        indicador.style.display = 'none';
    }
    
    modal.style.display = 'flex';
}

// Mostrar screenshot anterior
function mostrarScreenshotAnterior() {
    if (indexScreenshotActual > 0) {
        indexScreenshotActual--;
        abrirScreenshot(screenshotsActuales[indexScreenshotActual].path_full, indexScreenshotActual);
    }
}

// Mostrar screenshot siguiente
function mostrarScreenshotSiguiente() {
    if (indexScreenshotActual < screenshotsActuales.length - 1) {
        indexScreenshotActual++;
        abrirScreenshot(screenshotsActuales[indexScreenshotActual].path_full, indexScreenshotActual);
    }
}

</script>

@endsection
