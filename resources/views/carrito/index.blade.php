@extends('layouts.app')

@section('title', 'Carrito de Compras - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/carrito.css') }}" />
@endsection

@section('content')

<div class="main">
    <!-- Encabezado con información del usuario -->
    <div style="background: #171a21; border: 1px solid #363c44; border-radius: 8px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="color: #66c0f4; font-size: 2rem; margin: 0 0 0.5rem 0; font-weight: 700;">
                    <i class='bx bx-cart'></i> Carrito de Compras
                </h1>
                <p style="color: #8F98A0; margin: 0; font-size: 0.95rem;">Revisa y confirma tus compras</p>
            </div>
            <div style="background: #171a21; border: 1px solid #1db954; border-radius: 6px; padding: 1.5rem 2rem; text-align: center;">
                <p style="color: #8F98A0; margin: 0 0 0.5rem 0; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Saldo Disponible</p>
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

    @if(session('info'))
        <div class="info">{{ session('info') }}</div>
    @endif

    @if($itemsCarrito->isEmpty())
        <section class="carrito-vacio">
            <p>Tu carrito está vacío.</p>
            <a href="{{ route('tienda.index') }}" class="btn btn-primary" style="text-decoration: none;">Explorar juegos</a>
        </section>
    @else
        <section class="carrito-contenido">
            <div class="carrito-items">
                @foreach($itemsCarrito as $item)
                    <article class="carrito-item">
                        <div class="item-imagen">
                            <img loading="lazy" decoding="async" src="{{ asset('imagenes/' . $item->juego->imagen_url) }}" alt="Portada de {{ $item->juego->titulo }}">
                        </div>
                        <div class="item-info">
                            <h4>{{ $item->juego->titulo }}</h4>
                            <p class="item-descripcion">{{ Str::limit($item->juego->descripcion, 100) }}</p>
                        </div>
                        <div class="item-precio">
                            <span class="precio">{{ number_format($item->juego->precio, 2) }} €</span>
                        </div>
                        <div class="item-acciones">
                            <form method="POST" action="{{ route('carrito.eliminar') }}" class="inline-form">
                                @csrf
                                <input type="hidden" name="juego_id" value="{{ $item->juego_id }}">
                                <button type="submit" class="btn btn-danger" title="Eliminar del carrito">
                                    <i class='bx bx-trash'></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="carrito-resumen">
                <h4>Resumen del pedido</h4>
                <div class="resumen-linea">
                    <span>Juegos en el carrito:</span>
                    <span>{{ $itemsCarrito->count() }}</span>
                </div>
                <div class="resumen-linea total">
                    <span><strong>Total:</strong></span>
                    <span><strong>{{ number_format($total, 2) }} €</strong></span>
                </div>
                <div class="resumen-saldo">
                    <span>Tu saldo:</span>
                    <span>{{ number_format(Auth::user()->saldo, 2) }} €</span>
                </div>
                @if(Auth::user()->saldo < $total)
                    <div class="resumen-advertencia">
                        <i class='bx bx-error-circle'></i>
                        <span>Saldo insuficiente</span>
                    </div>
                @endif
                
                <div class="resumen-acciones">
                    <!-- Opción 1: Comprar con Stripe (Recomendado) -->
                    <form method="POST" action="{{ route('stripe.checkout') }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; font-weight: 600;">
                            <i class='bx bx-credit-card'></i> Pagar con Stripe
                        </button>
                    </form>
                    
                    <!-- Opción 2: Comprar con saldo (Método antiguo) -->
                    <button type="button" class="btn btn-success btn-block" 
                            @if(Auth::user()->saldo < $total) disabled @endif
                            @if(Auth::user()->saldo >= $total) 
                                onclick="abrirConfirmacionCompraSaldo({{ $itemsCarrito->count() }}, {{ number_format($total, 2) }})"
                            @endif
                            @if(Auth::user()->saldo < $total) 
                                title="Saldo insuficiente"
                            @endif>
                        <i class='bx bx-wallet'></i> Comprar con saldo ({{ number_format(Auth::user()->saldo, 2) }} €)
                    </button>
                    
                    <!-- Botón Vaciar Carrito -->
                    <button type="button" class="btn btn-danger btn-block" onclick="abrirConfirmacionVaciarCarrito()">
                        <i class='bx bx-trash'></i> Vaciar carrito
                    </button>
                    
                    <a href="{{ route('tienda.index') }}" class="btn btn-primary btn-block" style="text-decoration: none;">
                        <i class='bx bx-arrow-back'></i> Seguir comprando
                    </a>
                </div>
            </div>
        </section>
    @endif
</div>

<!-- Modal de Confirmación - Vaciar Carrito -->
<div id="modalConfirmacionVaciarCarrito" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); z-index: 2000; justify-content: center; align-items: center; animation: fadeIn 0.3s ease;">
    <div style="background: linear-gradient(135deg, #1b3a52 0%, #0d1929 100%); border: 2px solid #2a5f7f; border-radius: 12px; padding: 40px; max-width: 450px; width: 90%; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5); animation: slideUp 0.4s ease;">
        
        <!-- Icono de advertencia -->
        <div style="text-align: center; margin-bottom: 25px;">
            <i class='bx bx-error-circle' style="font-size: 48px; color: #e74c3c;"></i>
        </div>

        <!-- Título -->
        <h2 style="color: #dfe3e6; text-align: center; margin: 0 0 15px 0; font-size: 1.5rem; font-weight: 700;">
            ¿Vaciar Carrito?
        </h2>

        <!-- Mensaje -->
        <p style="color: #8b8e91; text-align: center; margin: 0 0 30px 0; font-size: 1rem; line-height: 1.5;">
            ¿Seguro que quieres vaciar el carrito? Se eliminarán todos los juegos que has agregado.
        </p>

        <!-- Botones -->
        <div style="display: flex; gap: 12px; justify-content: center;">
            <button type="button" onclick="cerrarConfirmacionVaciarCarrito()" style="background: #2a475e; color: #dfe3e6; border: 1px solid #3d5a73; padding: 12px 30px; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; min-width: 140px;">
                Cancelar
            </button>
            <button type="button" onclick="confirmarVaciarCarrito()" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; padding: 12px 30px; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; min-width: 140px;">
                <i class='bx bx-trash'></i> Vaciar
            </button>
        </div>
    </div>
</div>

<style>
#modalConfirmacionVaciarCarrito button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

#modalConfirmacionVaciarCarrito button[onclick*="confirmarVaciarCarrito"]:hover {
    background: linear-gradient(135deg, #f55c4a 0%, #e74c3c 100%);
}
</style>

<script>
function abrirConfirmacionVaciarCarrito() {
    document.getElementById('modalConfirmacionVaciarCarrito').style.display = 'flex';
}

function cerrarConfirmacionVaciarCarrito() {
    document.getElementById('modalConfirmacionVaciarCarrito').style.display = 'none';
}

function confirmarVaciarCarrito() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route('carrito.vaciar') }}';
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = csrfToken;
    
    form.appendChild(tokenInput);
    document.body.appendChild(form);
    form.submit();
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalConfirmacionVaciarCarrito').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarConfirmacionVaciarCarrito();
    }
});
</script>

<!-- Modal de Confirmación - Comprar con Saldo -->
<div id="modalConfirmacionCompraSaldo" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); z-index: 2000; justify-content: center; align-items: center; animation: fadeIn 0.3s ease;">
    <div style="background: linear-gradient(135deg, #1b3a52 0%, #0d1929 100%); border: 2px solid #2a5f7f; border-radius: 12px; padding: 40px; max-width: 450px; width: 90%; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5); animation: slideUp 0.4s ease;">
        
        <!-- Icono de compra -->
        <div style="text-align: center; margin-bottom: 25px;">
            <i class='bx bx-shopping-bag' style="font-size: 48px; color: #1db954;"></i>
        </div>

        <!-- Título -->
        <h2 style="color: #dfe3e6; text-align: center; margin: 0 0 15px 0; font-size: 1.5rem; font-weight: 700;">
            ¿Confirmar Compra?
        </h2>

        <!-- Mensaje -->
        <p style="color: #8b8e91; text-align: center; margin: 0 0 30px 0; font-size: 1rem; line-height: 1.5;">
            Vas a comprar <strong id="cantidadJuegos">0</strong> juego(s) por <strong id="totalCompra">0.00 €</strong>. 
            El importe se descontará de tu saldo actual.
        </p>

        <!-- Botones -->
        <div style="display: flex; gap: 12px; justify-content: center;">
            <button type="button" onclick="cerrarConfirmacionCompraSaldo()" style="background: #2a475e; color: #dfe3e6; border: 1px solid #3d5a73; padding: 12px 30px; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; min-width: 140px;">
                Cancelar
            </button>
            <button type="button" onclick="confirmarCompraSaldo()" style="background: linear-gradient(135deg, #1db954 0%, #1aa34a 100%); color: white; border: none; padding: 12px 30px; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; min-width: 140px;">
                <i class='bx bx-check-circle'></i> Comprar
            </button>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { 
        opacity: 0;
        transform: translateY(30px);
    }
    to { 
        opacity: 1;
        transform: translateY(0);
    }
}

#modalConfirmacionCompraSaldo button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

#modalConfirmacionCompraSaldo button[onclick*="confirmarCompraSaldo"]:hover {
    background: linear-gradient(135deg, #1ed760 0%, #1db954 100%);
}
</style>

<script>
let datosCompraSaldo = null;

function abrirConfirmacionCompraSaldo(cantidadJuegos, total) {
    datosCompraSaldo = { cantidadJuegos, total };
    document.getElementById('cantidadJuegos').textContent = cantidadJuegos;
    document.getElementById('totalCompra').textContent = total.toFixed(2) + ' €';
    document.getElementById('modalConfirmacionCompraSaldo').style.display = 'flex';
}

function cerrarConfirmacionCompraSaldo() {
    document.getElementById('modalConfirmacionCompraSaldo').style.display = 'none';
    datosCompraSaldo = null;
}

function confirmarCompraSaldo() {
    if (datosCompraSaldo) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('carrito.comprar') }}';
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;
        
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalConfirmacionCompraSaldo').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarConfirmacionCompraSaldo();
    }
});
</script>

@endsection
