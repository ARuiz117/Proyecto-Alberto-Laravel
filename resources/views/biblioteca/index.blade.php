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
                    <form method="POST" action="{{ route('biblioteca.devolver') }}" onsubmit="return confirm('¿Seguro que quieres devolver este juego?');">
                        @csrf
                        <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                        <button class="btn" type="submit">Devolver juego</button>
                    </form>
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
@endsection
