@extends('layouts.app')

@section('title', 'Carrito de Compras - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/carrito.css') }}" />
@endsection

@section('content')

<div class="main">
    <h3>Carrito de Compras</h3>
    <p>Saldo disponible: <strong>{{ number_format(Auth::user()->saldo, 2) }} €</strong></p>

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
            <a href="{{ route('biblioteca.index') }}" class="btn">Explorar juegos</a>
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
                        <button type="submit" class="btn btn-secondary btn-block">
                            <i class='bx bx-trash'></i> Vaciar carrito
                        </button>
                    </form>
                    
                    <a href="{{ route('biblioteca.index') }}" class="btn btn-primary btn-block">
                        <i class='bx bx-arrow-back'></i> Seguir comprando
                    </a>
                </div>
            </div>
        </section>
    @endif
</div>

@endsection
