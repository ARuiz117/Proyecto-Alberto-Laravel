@extends('layouts.app')

@section('title', 'Gestión de Juegos - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
@endsection

@section('content')

<div class="main admin-juegos">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <h1>Gestión de Juegos</h1>
        <a href="{{ route('admin.juegos.create') }}" class="btn btn-success" style="text-decoration: none;">
            <i class='bx bx-plus'></i> Crear Juego
        </a>
    </div>

    @if(session('success'))
        <div class="mensaje">{{ session('success') }}</div>
    @endif

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
                        <a href="{{ route('admin.juegos.edit', $juego->id) }}" class="btn btn-small btn-info" style="text-decoration: none;">
                            <i class='bx bx-edit'></i> Editar
                        </a>
                        <form method="POST" action="{{ route('admin.juegos.destroy', $juego->id) }}" style="display: inline;" onsubmit="return confirm('¿Eliminar este juego?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-small btn-danger">
                                <i class='bx bx-trash'></i> Eliminar
                            </button>
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
