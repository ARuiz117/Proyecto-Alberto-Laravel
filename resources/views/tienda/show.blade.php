@extends('layouts.app')

@section('title', $juego->titulo . ' - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/biblioteca.css') }}" />
@endsection

@section('content')

<div class="main">
    <a href="{{ route('tienda.index') }}" class="btn">← Volver a la tienda</a>

    <section class="juego-detalle" style="margin: 20px 0;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start;">
            <div class="juego-imagen">
                <img src="/imagenes/{{ $juego->imagen_url }}" alt="Portada de {{ $juego->titulo }}" style="width: 100%; border-radius: 10px;">
            </div>
            <div class="juego-info">
                <h1 style="color: #66c0f4; margin-bottom: 10px;">{{ $juego->titulo }}</h1>
                <p style="color: #dfe3e6; margin-bottom: 20px;">{{ $juego->descripcion }}</p>
                
                <div style="margin-bottom: 20px;">
                    <span style="font-size: 28px; color: #1db954; font-weight: bold;">{{ number_format($juego->precio, 2) }} €</span>
                </div>

                @if($tieneJuego)
                    <div style="background: #1db954; padding: 10px 15px; border-radius: 5px; color: white; text-align: center;">
                        <i class='bx bx-check-circle'></i> Ya posees este juego
                    </div>
                @else
                    <div class="juego-acciones" style="display: flex; gap: 10px;">
                        <form method="POST" action="{{ route('carrito.agregar') }}" style="flex: 1;">
                            @csrf
                            <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                            <button class="btn btn-carrito" type="submit" style="width: 100%;">
                                <i class='bx bx-cart-add'></i> Carrito
                            </button>
                        </form>
                        <form method="POST" action="{{ route('biblioteca.comprar') }}" style="flex: 1;" @if(Auth::user()->saldo < $juego->precio) class="form-disabled" title="Saldo insuficiente" @endif>
                            @csrf
                            <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                            <button class="btn btn-comprar" type="submit" style="width: 100%;" @if(Auth::user()->saldo < $juego->precio) disabled @endif>
                                <i class='bx bx-shopping-bag'></i> Comprar
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="juego-resenas" style="margin-top: 40px;">
        <h2 style="color: #66c0f4; margin-bottom: 20px;">Reseñas ({{ $resenas->total() }})</h2>

        @if($tieneJuego)
            <div style="background: #2a475e; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <h3 style="color: #66c0f4; margin-bottom: 15px;">Escribe tu reseña</h3>
                <form method="POST" action="{{ route('resena.store') }}">
                    @csrf
                    <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                    <textarea name="contenido" placeholder="Comparte tu opinión sobre este juego..." required minlength="10" maxlength="1000" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #1b3a52; background: #1b3a52; color: #dfe3e6; min-height: 100px;"></textarea>
                    <button type="submit" class="btn" style="margin-top: 10px;">Publicar reseña</button>
                </form>
            </div>
        @endif

        @if($resenas->isEmpty())
            <p style="color: #dfe3e6;">No hay reseñas aún. ¡Sé el primero en reseñar!</p>
        @else
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($resenas as $resena)
                    <article style="background: #2a475e; padding: 15px; border-radius: 10px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <strong style="color: #66c0f4;">{{ $resena->usuario->nombre }}</strong>
                            <span style="color: #8b8e91; font-size: 12px;">{{ $resena->created_at->diffForHumans() }}</span>
                        </div>
                        <p style="color: #dfe3e6; margin: 10px 0;">{{ $resena->contenido }}</p>
                        
                        @if(Auth::user()->id === $resena->usuario_id || Auth::user()->isAdmin())
                            <div style="margin-top: 10px;">
                                <form method="POST" action="{{ route('resena.destroy', $resena->id) }}" style="display: inline;" onsubmit="return confirm('¿Eliminar esta reseña?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="background: #c7302a; padding: 5px 10px; font-size: 12px;">Eliminar</button>
                                </form>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>

            <div class="pagination" style="margin-top: 20px;">
                {{ $resenas->links() }}
            </div>
        @endif
    </section>
</div>

@endsection
