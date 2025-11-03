@extends('layouts.app')

@section('title', 'Carrito de Compras - Steam HRG')

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
                            <img loading="lazy" decoding="async" src="{{ $item->juego->imagen_url }}" alt="Portada de {{ $item->juego->titulo }}">
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
                              onsubmit="return false;" 
                              style="opacity: 0.5; cursor: not-allowed;" 
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

<style>
.carrito-vacio {
    text-align: center;
    padding: 3rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    margin: 2rem 0;
}

.carrito-contenido {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

.carrito-items {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.carrito-item {
    display: grid;
    grid-template-columns: 150px 1fr auto auto;
    gap: 1rem;
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem;
    border-radius: 8px;
    align-items: center;
}

.item-imagen img {
    width: 100%;
    height: auto;
    border-radius: 4px;
}

.item-info h4 {
    margin: 0 0 0.5rem 0;
    color: #fff;
}

.item-descripcion {
    font-size: 0.9rem;
    color: #ccc;
    margin: 0;
}

.item-precio {
    text-align: right;
}

.item-precio .precio {
    font-size: 1.2rem;
    font-weight: bold;
    color: #4CAF50;
}

.carrito-resumen {
    background: rgba(255, 255, 255, 0.1);
    padding: 1.5rem;
    border-radius: 8px;
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.carrito-resumen h4 {
    margin-top: 0;
    color: #fff;
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    padding-bottom: 0.5rem;
}

.resumen-linea {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    color: #ccc;
}

.resumen-linea.total {
    border-top: 2px solid rgba(255, 255, 255, 0.2);
    margin-top: 0.5rem;
    padding-top: 1rem;
    font-size: 1.2rem;
    color: #fff;
}

.resumen-saldo {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    color: #4CAF50;
    font-weight: bold;
}

.resumen-advertencia {
    background: rgba(244, 67, 54, 0.2);
    color: #f44336;
    padding: 0.75rem;
    border-radius: 4px;
    margin: 1rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.resumen-acciones {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 1.5rem;
}

.btn-block {
    width: 100%;
    text-align: center;
}

.btn-success {
    background: #4CAF50;
    color: white;
}

.btn-success:hover:not(:disabled) {
    background: #45a049;
}

.btn-success:disabled {
    cursor: not-allowed;
    opacity: 0.5;
}

.btn-danger {
    background: #f44336;
    color: white;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.btn-danger:hover {
    background: #da190b;
}

.info {
    background: rgba(33, 150, 243, 0.2);
    color: #2196F3;
    padding: 1rem;
    border-radius: 4px;
    margin: 1rem 0;
    border-left: 4px solid #2196F3;
}

@media (max-width: 768px) {
    .carrito-contenido {
        grid-template-columns: 1fr;
    }
    
    .carrito-item {
        grid-template-columns: 100px 1fr;
        gap: 0.75rem;
    }
    
    .item-precio, .item-acciones {
        grid-column: 2;
    }
    
    .carrito-resumen {
        position: static;
    }
}
</style>

@endsection
