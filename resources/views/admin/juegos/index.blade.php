@extends('layouts.app')

@section('title', 'Gestión de Juegos - Steam HRG')

@section('content')

<div class="main admin-juegos">
    <h1>Gestión de Juegos</h1>

    @if(session('success'))
        <div class="mensaje">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.juegos.create') }}" class="btn btn-primary">+ Crear nuevo juego</a>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Precio</th>
                <th>Creado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($juegos as $juego)
                <tr>
                    <td>{{ $juego->id }}</td>
                    <td>{{ $juego->titulo }}</td>
                    <td>{{ number_format($juego->precio, 2) }} €</td>
                    <td>{{ $juego->created_at->format('d/m/Y') }}</td>
                    <td class="acciones">
                        <a href="{{ route('admin.juegos.edit', $juego->id) }}" class="btn btn-small btn-info">Editar</a>
                        <form method="POST" action="{{ route('admin.juegos.destroy', $juego->id) }}" style="display: inline;" onsubmit="return confirm('¿Eliminar este juego?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-small btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $juegos->links() }}
    </div>
</div>

@endsection
