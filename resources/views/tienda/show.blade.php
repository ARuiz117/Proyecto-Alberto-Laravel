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
                    <img src="{{ asset('imagenes/' . $juego->imagen_url) }}" alt="Portada de {{ $juego->titulo }}" style="width: 100%; height: auto; border-radius: 10px; cursor: pointer; box-shadow: 0 8px 16px rgba(0,0,0,0.5); transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'" onclick="abrirTrailer('{{ $juego->titulo }}')">
                    <div style="background: linear-gradient(135deg, #2a475e 0%, #1f3a4d 100%); border: 1px solid #417a9b; border-radius: 8px; padding: 12px; margin-top: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                        <p style="color: #dfe3e6; font-size: 12px; margin: 0; text-align: center;">🎬 Haz clic para ver el trailer</p>
                    </div>
                </div>
            </div>
            <div class="juego-info">
                <!-- Caja para el título -->
                <div style="background: linear-gradient(135deg, #2a475e 0%, #1f3a4d 100%); border: 1px solid #417a9b; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                    <h1 style="color: #66c0f4; margin: 0; font-size: 28px;">{{ $juego->titulo }}</h1>
                </div>
                
                <!-- Caja para la descripción -->
                <div style="background: linear-gradient(135deg, #2a475e 0%, #1f3a4d 100%); border: 1px solid #417a9b; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                    <p style="color: #dfe3e6; margin: 0; line-height: 1.6; font-size: 16px;">{{ $juego->descripcion }}</p>
                </div>
                
                <!-- Caja para el precio -->
                <div style="background: linear-gradient(135deg, #2a475e 0%, #1f3a4d 100%); border: 1px solid #417a9b; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.3); text-align: center;">
                    <span style="font-size: 32px; color: #1db954; font-weight: bold;">{{ number_format($juego->precio, 2) }} €</span>
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

    <!-- Sección centrada para el carrusel -->
    <section class="carrusel-centrado" style="margin: 40px 0;">
        <div style="display: flex; justify-content: center; align-items: center; width: 100%;">
            <div id="carruselScreenshots" style="display: none; width: 100%; max-width: 1200px;">
                <div style="background: linear-gradient(135deg, #2a475e 0%, #1f3a4d 100%); border: 1px solid #417a9b; border-radius: 10px; padding: 15px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                    <h3 style="color: #66c0f4; margin: 0; font-size: 20px; text-align: center;">📸 Capturas de pantalla</h3>
                </div>
                <div style="background: #0a1929; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.3); width: 100%; padding: 25px;">
                    <div style="display: flex; gap: 20px; justify-content: center; align-items: center; height: 400px; width: 100%; position: relative;" id="screenshotsContainer">
                        <!-- Screenshots se cargarán aquí -->
                    </div>
                    <!-- Indicadores de progreso -->
                    <div id="carruselIndicators" style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;"></div>
                </div>
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
                                <div style="color: #ffd700; font-size: 18px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span style="color: {{ $i <= $resena->calificacion ? '#ffd700' : '#8b8e91' }};">★</span>
                                    @endfor
                                    <span style="color: #8b8e91; font-size: 12px; margin-left: 8px; display: block; margin-top: 4px;">{{ $resena->calificacion }}/5</span>
                                </div>
                            </div>
                        </div>
                        <p style="color: #dfe3e6; margin: 10px 0;">{{ $resena->contenido }}</p>
                        
                        @if(Auth::user()->id === $resena->usuario_id || Auth::user()->isAdmin())
                            <div style="margin-top: 10px;">
                                <button type="button" onclick="abrirConfirmacionEliminar({{ $resena->id }})" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">
                                    <i class='bx bx-trash'></i> Eliminar
                                </button>
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
            // Si es HLS, usar video element con HLS.js
            if (data.trailer_url.includes('.m3u8')) {
                mediaContent.innerHTML = `
                    <video id="trailerVideo" controls style="width: 100%; height: 400px;" autoplay muted>
                        <source src="${data.trailer_url}" type="application/x-mpegURL">
                        Tu navegador no soporta HLS streaming.
                    </video>
                `;
                
                // Cargar HLS.js si es necesario
                if (!window.Hls) {
                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/hls.js@latest';
                    script.onload = () => {
                        loadHlsVideo(data.trailer_url);
                    };
                    document.head.appendChild(script);
                } else {
                    loadHlsVideo(data.trailer_url);
                }
                
                frame.style.display = 'none';
            } else {
                // Para otros formatos, usar iframe
                frame.src = data.trailer_url;
                frame.style.display = 'block';
                mediaContent.innerHTML = '';
            }
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

function loadHlsVideo(hlsUrl) {
    const video = document.getElementById('trailerVideo');
    if (video && window.Hls) {
        if (Hls.isSupported()) {
            const hls = new Hls();
            hls.loadSource(hlsUrl);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, function() {
                video.play();
            });
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            // Safari soporte nativo
            video.src = hlsUrl;
            video.play();
        }
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
            const indicatorsContainer = document.getElementById('carruselIndicators');
            
            // Guardar screenshots para navegación
            screenshotsActuales = data.screenshots;
            
            container.innerHTML = '';
            indicatorsContainer.innerHTML = '';
            
            let activeIndex = 0;
            let autoPlayInterval = null;
            
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
                    width: ${index === activeIndex ? '350px' : '100px'};
                    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                    transform: ${index === activeIndex ? 'scale(1)' : 'scale(0.9)'};
                    z-index: ${index === activeIndex ? '10' : '1'};
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
                    updateActiveScreenshot(index);
                    // Pausar autoplay al hover
                    if (window.autoPlayInterval) {
                        clearInterval(window.autoPlayInterval);
                    }
                });
                
                wrapper.addEventListener('mouseleave', () => {
                    // Reanudar autoplay
                    startAutoPlay();
                });
                
                wrapper.addEventListener('click', () => {
                    abrirScreenshot(screenshot.path_full, index);
                });
                
                container.appendChild(wrapper);
                
                // Crear indicador
                const indicator = document.createElement('div');
                indicator.style.cssText = `
                    width: 10px;
                    height: 10px;
                    border-radius: 50%;
                    background: ${index === activeIndex ? '#66c0f4' : '#417a9b'};
                    cursor: pointer;
                    transition: all 0.3s ease;
                `;
                indicator.addEventListener('click', () => {
                    updateActiveScreenshot(index);
                });
                indicatorsContainer.appendChild(indicator);
            });
            
            // Función para actualizar screenshot activo
            function updateActiveScreenshot(newIndex) {
                const allWrappers = container.querySelectorAll('.screenshot-wrapper');
                const allIndicators = indicatorsContainer.querySelectorAll('div');
                
                allWrappers.forEach((w, i) => {
                    const isActive = i === newIndex;
                    w.style.width = isActive ? '350px' : '100px';
                    w.style.transform = isActive ? 'scale(1)' : 'scale(0.9)';
                    w.style.zIndex = isActive ? '10' : '1';
                    w.querySelector('.screenshot-overlay').style.opacity = isActive ? '1' : '0';
                    w.querySelector('.screenshot-number').style.opacity = isActive ? '1' : '0';
                });
                
                allIndicators.forEach((indicator, i) => {
                    indicator.style.background = i === newIndex ? '#66c0f4' : '#417a9b';
                    indicator.style.transform = i === newIndex ? 'scale(1.2)' : 'scale(1)';
                });
                
                activeIndex = newIndex;
            }
            
            // Función para autoplay
            function startAutoPlay() {
                if (window.autoPlayInterval) {
                    clearInterval(window.autoPlayInterval);
                }
                window.autoPlayInterval = setInterval(() => {
                    const nextIndex = (activeIndex + 1) % data.screenshots.length;
                    updateActiveScreenshot(nextIndex);
                }, 2500); // Cambiar cada 2.5 segundos
            }
            
            // Iniciar autoplay
            startAutoPlay();
            
            // Pausar autoplay cuando el usuario no está en la pestaña
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    if (window.autoPlayInterval) {
                        clearInterval(window.autoPlayInterval);
                    }
                } else {
                    startAutoPlay();
                }
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

<!-- Modal de Confirmación de Eliminación -->
<div id="modalConfirmacionEliminar" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); z-index: 2000; justify-content: center; align-items: center; animation: fadeIn 0.3s ease;">
    <div style="background: linear-gradient(135deg, #1b3a52 0%, #0d1929 100%); border: 2px solid #2a5f7f; border-radius: 12px; padding: 40px; max-width: 450px; width: 90%; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5); animation: slideUp 0.4s ease;">
        
        <!-- Icono de advertencia -->
        <div style="text-align: center; margin-bottom: 25px;">
            <i class='bx bx-trash' style="font-size: 48px; color: #c7302a;"></i>
        </div>

        <!-- Título -->
        <h2 style="color: #dfe3e6; text-align: center; margin: 0 0 15px 0; font-size: 1.5rem; font-weight: 700;">
            ¿Eliminar reseña?
        </h2>

        <!-- Mensaje -->
        <p style="color: #8b8e91; text-align: center; margin: 0 0 30px 0; font-size: 1rem; line-height: 1.5;">
            Esta acción no se puede deshacer. Tu reseña será eliminada permanentemente.
        </p>

        <!-- Botones -->
        <div style="display: flex; gap: 12px; justify-content: center;">
            <button type="button" onclick="cerrarConfirmacionEliminar()" style="background: #2a475e; color: #dfe3e6; border: 1px solid #3d5a73; padding: 12px 30px; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; min-width: 140px;">
                Cancelar
            </button>
            <button type="button" id="btnEliminarConfirmado" onclick="confirmarEliminacion()" style="background: linear-gradient(135deg, #c7302a 0%, #a02622 100%); color: white; border: none; padding: 12px 30px; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; min-width: 140px;">
                <i class='bx bx-trash'></i> Eliminar
            </button>
        </div>
    </div>
</div>

<!-- Estilos de animación -->
<style>
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

#modalConfirmacionEliminar button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

#btnEliminarConfirmado:hover {
    background: linear-gradient(135deg, #e63c32 0%, #b82d28 100%);
}
</style>

<!-- JavaScript -->
<script>
let resenaIdAEliminar = null;

function abrirConfirmacionEliminar(resenaId) {
    resenaIdAEliminar = resenaId;
    document.getElementById('modalConfirmacionEliminar').style.display = 'flex';
}

function cerrarConfirmacionEliminar() {
    document.getElementById('modalConfirmacionEliminar').style.display = 'none';
    resenaIdAEliminar = null;
}

function confirmarEliminacion() {
    if (resenaIdAEliminar) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('resena.destroy', ':id') }}`.replace(':id', resenaIdAEliminar);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(tokenInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Cerrar modal al presionar Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        cerrarConfirmacionEliminar();
    }
});

// Cerrar modal al hacer clic fuera
document.getElementById('modalConfirmacionEliminar').addEventListener('click', function(event) {
    if (event.target === this) {
        cerrarConfirmacionEliminar();
    }
});
</script>

@endsection
