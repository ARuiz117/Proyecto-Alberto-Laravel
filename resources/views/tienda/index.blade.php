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

    <section class="busqueda-juegos" style="margin-bottom: 30px;">
        <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
            <form method="GET" action="{{ route('tienda.index') }}" class="form-busqueda" style="flex: 1; min-width: 250px;">
                <select name="genero" onchange="this.form.submit()" style="padding: 10px; border-radius: 5px; border: 1px solid #1b3a52; background: #0a1929; color: #dfe3e6; cursor: pointer;">
                    <option value="">Todos los géneros</option>
                    @foreach($generos as $g)
                        <option value="{{ $g }}" @if(!empty($generoSeleccionado) && $generoSeleccionado === $g) selected @endif>{{ $g }}</option>
                    @endforeach
                </select>
            </form>
            
            <form method="GET" action="{{ route('tienda.buscar') }}" class="form-busqueda" style="flex: 1; min-width: 250px;">
                <input type="text" name="q" placeholder="Buscar juegos..." required>
                <button type="submit" class="btn">Buscar</button>
            </form>
        </div>
        
        @if(!empty($generoSeleccionado))
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                <span style="color: #dfe3e6;">Filtro activo: <strong>{{ $generoSeleccionado }}</strong></span>
                <a href="{{ route('tienda.index') }}" class="btn" style="padding: 5px 10px; font-size: 12px; background: #8b8e91;">
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
