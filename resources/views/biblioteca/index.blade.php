@extends('layouts.app')

@section('title', 'Mi Biblioteca - Steam HRG')

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
                    <form method="POST" action="{{ route('biblioteca.comprar') }}" @if(Auth::user()->saldo < $juego->precio) style="opacity:0.5; pointer-events:none;" title="Saldo insuficiente" @endif>
                        @csrf
                        <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                        <button class="btn" type="submit">Comprar</button>
                    </form>
                </article>
            @endforeach
        @endif
    </section>
</div>
@endsection
