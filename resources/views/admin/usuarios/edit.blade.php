@extends('layouts.app')

@section('title', 'Editar Usuario - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
@endsection

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

        <div class="form-group">
            <label for="clave">Nueva Contraseña (opcional)</label>
            <input type="password" id="clave" name="clave" placeholder="Dejar en blanco para no cambiar" minlength="6">
            <small style="color: #8b8e91;">Mínimo 6 caracteres. Dejar en blanco si no deseas cambiar la contraseña.</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                <i class='bx bx-save'></i> Guardar Cambios
            </button>
            <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary" style="text-decoration: none;">
                <i class='bx bx-x'></i> Cancelar
            </a>
        </div>
    </form>
</div>

@endsection
