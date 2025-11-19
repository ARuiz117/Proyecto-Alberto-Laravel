@extends('layouts.app')

@section('title', 'Tienda - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/biblioteca.css') }}" />
@endsection

@section('content')

<div class="main">
    <!-- Encabezado con información del usuario -->
    <div style="background: #171a21; border: 1px solid #363c44; border-radius: 8px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="color: #66c0f4; font-size: 2rem; margin: 0 0 0.5rem 0; font-weight: 700;">
                    <i class='bx bx-store'></i> Tienda de Juegos
                </h1>
                <p style="color: #8F98A0; margin: 0; font-size: 0.95rem;">Explora nuestro catálogo de juegos exclusivos</p>
            </div>
            <div style="background: #171a21; border: 1px solid #1db954; border-radius: 6px; padding: 1.5rem 2rem; text-align: center;">
                <p style="color: #8F98A0; margin: 0 0 0.5rem 0; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Tu Saldo</p>
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

    <!-- Buscadores alineados -->
    <section class="busqueda-juegos" style="margin-bottom: 2rem; background: linear-gradient(135deg, #2a475e 0%, #1f3a4d 100%); border: 1px solid #417a9b; border-radius: 8px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);">
        <h3 style="color: #66c0f4; margin-top: 0; margin-bottom: 1.5rem; text-transform: uppercase; font-size: 0.95rem; letter-spacing: 0.5px; font-weight: 600;">Filtrar y Buscar</h3>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; align-items: flex-end;">
            <!-- Filtro por género -->
            <form method="GET" action="{{ route('tienda.index') }}" class="form-busqueda">
                <label style="display: block; color: #dfe3e6; font-size: 0.9rem; margin-bottom: 8px; font-weight: 600;">Género</label>
                <select name="genero" onchange="this.form.submit()" style="width: 100%; padding: 10px 12px; border-radius: 4px; border: 1px solid #417a9b; background: rgba(10, 25, 41, 0.8); color: #dfe3e6; cursor: pointer; transition: all 0.3s ease; font-weight: 500;">
                    <option value="">Todos los géneros</option>
                    @foreach($generos as $g)
                        <option value="{{ $g }}" @if(!empty($generoSeleccionado) && $generoSeleccionado === $g) selected @endif>{{ $g }}</option>
                    @endforeach
                </select>
            </form>
            
            <!-- Buscador mejorado -->
            <form method="GET" action="{{ route('tienda.buscar') }}" class="form-busqueda">
                <label style="display: block; color: #dfe3e6; font-size: 0.9rem; margin-bottom: 8px; font-weight: 600;">Buscar Juego</label>
                <div style="display: flex; gap: 10px; align-items: flex-end;">
                    <div class="search-wrapper" style="flex: 1;">
                        <input type="text" name="q" placeholder="Escribe el nombre del juego..." required>
                        <span class="focus-border"></span>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin: 0; padding: 0.8rem 1.5rem;">
                        <i class='bx bx-search'></i> Buscar
                    </button>
                </div>
            </form>
        </div>
        
        @if(!empty($generoSeleccionado))
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                <span style="color: #dfe3e6;">Filtro activo: <strong>{{ $generoSeleccionado }}</strong></span>
                <a href="{{ route('tienda.index') }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">
                    Limpiar filtro
                </a>
            </div>
        @endif
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
                        <a href="{{ route('tienda.show', $juego->id) }}" class="btn btn-detalles">
                            <i class='bx bx-info-circle'></i> Detalles
                        </a>
                        <form method="POST" action="{{ route('carrito.agregar') }}" style="display: inline;">
                            @csrf
                            <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                            <button class="btn btn-secondary" type="submit">
                                <i class='bx bx-cart-add'></i> Carrito
                            </button>
                        </form>
                        <form method="POST" action="{{ route('biblioteca.comprar') }}" style="display: inline;" @if(Auth::user()->saldo < $juego->precio) class="form-disabled" title="Saldo insuficiente" @endif>
                            @csrf
                            <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                            <button class="btn btn-success" type="submit" @if(Auth::user()->saldo < $juego->precio) disabled @endif title="Comprar ahora">
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
