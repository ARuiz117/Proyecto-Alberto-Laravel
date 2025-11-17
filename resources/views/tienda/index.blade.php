@extends('layouts.app')

@section('title', 'Tienda - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/biblioteca.css') }}" />
@endsection

@section('content')

<div class="main">
    <h3>Tienda de Juegos</h3>
    <p>Saldo disponible: <strong>{{ number_format(Auth::user()->saldo, 2) }} €</strong></p>

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="mensaje">{{ session('success') }}</div>
    @endif

    <section class="busqueda-juegos">
        <form method="GET" action="{{ route('tienda.buscar') }}" class="form-busqueda">
            <input type="text" name="q" placeholder="Buscar juegos..." required>
            <button type="submit" class="btn">Buscar</button>
        </form>
    </section>

    @if($juegos->isEmpty())
        <p>No hay juegos disponibles.</p>
    @else
        <section class="juegos-grid">
            @foreach($juegos as $juego)
                <article class="juego-card">
                    <h4>{{ $juego->titulo }}</h4>
                    <div class="img-container">
                        <img loading="lazy" decoding="async" src="/imagenes/{{ $juego->imagen_url }}" alt="Portada de {{ $juego->titulo }}">
                    </div>
                    <p>{{ Str::limit($juego->descripcion, 80) }}</p>
                    <div class="precio">Precio: {{ number_format($juego->precio, 2) }} €</div>
                    <div class="juego-acciones">
                        <a href="{{ route('tienda.show', $juego->id) }}" class="btn">
                            <i class='bx bx-info-circle'></i> Detalles
                        </a>
                        <form method="POST" action="{{ route('carrito.agregar') }}" style="display: inline;">
                            @csrf
                            <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                            <button class="btn btn-carrito" type="submit">
                                <i class='bx bx-cart-add'></i> Carrito
                            </button>
                        </form>
                        <form method="POST" action="{{ route('biblioteca.comprar') }}" style="display: inline;" @if(Auth::user()->saldo < $juego->precio) class="form-disabled" title="Saldo insuficiente" @endif>
                            @csrf
                            <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                            <button class="btn btn-comprar" type="submit" @if(Auth::user()->saldo < $juego->precio) disabled @endif title="Comprar ahora">
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

@endsection
