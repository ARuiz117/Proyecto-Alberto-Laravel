@extends('layouts.app')

@section('title', 'Editar Usuario - Steam HRG')

@section('content')

<div class="main admin-form">
    <h1>Editar Usuario</h1>

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.usuarios.update', $usuario->id) }}" class="form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombre">Nombre de usuario</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
        </div>

        <div class="form-group">
            <label for="rol">Rol</label>
            <select id="rol" name="rol" required>
                <option value="user" @if($usuario->rol === 'user') selected @endif>Usuario</option>
                <option value="admin" @if($usuario->rol === 'admin') selected @endif>Administrador</option>
            </select>
        </div>

        <div class="form-group">
            <label for="saldo">Saldo</label>
            <input type="number" id="saldo" name="saldo" step="0.01" value="{{ old('saldo', $usuario->saldo) }}" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection
