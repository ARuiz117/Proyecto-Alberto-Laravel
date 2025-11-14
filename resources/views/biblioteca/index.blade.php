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
                        <img loading="lazy" decoding="async" src="{{ $juego->imagen_url }}" alt="Portada de {{ $juego->titulo }}">
                    </div>
                    <p>{{ $juego->descripcion }}</p>
                    <form method="POST" action="{{ route('biblioteca.devolver') }}" onsubmit="return confirm('¿Seguro que quieres devolver este juego?');">
                        @csrf
                        <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                        <button class="btn" type="submit">Devolver juego</button>
                    </form>
                </article>
            @endforeach
        @endif
    </section>

    <section class="juegos-grid">
        <h3>Juegos Disponibles para Comprar</h3>
        @if($juegosDisponibles->isEmpty())
            <p>Ya tienes todos los juegos disponibles.</p>
        @else
            @foreach($juegosDisponibles as $juego)
                <article class="juego-card">
                    <h4>{{ $juego->titulo }}</h4>
                    <div class="img-container">
                        <img loading="lazy" decoding="async" src="{{ $juego->imagen_url }}" alt="Portada de {{ $juego->titulo }}">
                    </div>
                    <p>{{ $juego->descripcion }}</p>
                    <div class="precio">Precio: {{ number_format($juego->precio, 2) }} €</div>
                    <div class="juego-acciones">
                        <form method="POST" action="{{ route('carrito.agregar') }}" style="display: inline;">
                            @csrf
                            <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                            <button class="btn btn-carrito" type="submit">
                                <i class='bx bx-cart-add'></i> Añadir al carrito
                            </button>
                        </form>
                        <form method="POST" action="{{ route('biblioteca.comprar') }}" style="display: inline;" @if(Auth::user()->saldo < $juego->precio) class="form-disabled" title="Saldo insuficiente" @endif>
                            @csrf
                            <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                            <button class="btn btn-comprar" type="submit" @if(Auth::user()->saldo < $juego->precio) disabled @endif title="Comprar ahora">
                                <i class='bx bx-shopping-bag'></i> Comprar ahora
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        @endif
    </section>
</div>
@endsection
