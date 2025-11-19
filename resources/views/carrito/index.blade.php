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
                            <img loading="lazy" decoding="async" src="/imagenes/{{ $item->juego->imagen_url }}" alt="Portada de {{ $item->juego->titulo }}">
                        </div>
                        <div class="item-info">
                            <h4>{{ $item->juego->titulo }}</h4>
                            <p class="item-descripcion">{{ Str::limit($item->juego->descripcion, 100) }}</p>
                        </div>
                        <div class="item-precio">
                            <span class="precio">{{ number_format($item->juego->precio, 2) }} €</span>
                        </div>
                        <div class="item-acciones">
                            <form method="POST" action="{{ route('carrito.eliminar') }}" style="display: inline;">
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
                    <form method="POST" action="{{ route('carrito.comprar') }}" 
                          @if(Auth::user()->saldo < $total) 
                              class="form-disabled"
                              onsubmit="return false;" 
                              title="Saldo insuficiente"
                          @else
                              onsubmit="return confirm('¿Confirmar la compra de {{ $itemsCarrito->count() }} juego(s) por {{ number_format($total, 2) }} €?');"
                          @endif>
                        @csrf
                        <button type="submit" class="btn btn-success btn-block" 
                                @if(Auth::user()->saldo < $total) disabled @endif>
                            <i class='bx bx-check-circle'></i> Comprar todo
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('carrito.vaciar') }}" 
                          onsubmit="return confirm('¿Seguro que quieres vaciar el carrito?');">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class='bx bx-trash'></i> Vaciar carrito
                        </button>
                    </form>
                    
                    <a href="{{ route('tienda.index') }}" class="btn btn-primary btn-block" style="text-decoration: none;">
                        <i class='bx bx-arrow-back'></i> Seguir comprando
                    </a>
                </div>
            </div>
        </section>
    @endif
</div>

@endsection
