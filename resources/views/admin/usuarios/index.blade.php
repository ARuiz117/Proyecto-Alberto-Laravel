@extends('layouts.app')

@section('title', 'Gestión de Usuarios - Steam HRG')

@section('content')

<div class="main admin-usuarios">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Gestión de Usuarios</h1>
        <a href="{{ route('admin.usuarios.create') }}" class="btn" style="background: #1db954;">
            <i class='bx bx-plus'></i> Crear Usuario
        </a>
    </div>

    @if(session('success'))
        <div class="mensaje">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Saldo</th>
                <th>Creado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ ucfirst($usuario->rol) }}</td>
                    <td>{{ number_format($usuario->saldo, 2) }} €</td>
                    <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                    <td class="acciones">
                        <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="btn btn-small btn-info">Editar</a>
                        <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario->id) }}" style="display: inline;" onsubmit="return confirm('¿Eliminar este usuario?');">
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
        {{ $usuarios->links() }}
    </div>
</div>

@endsection
