@extends('layouts.app')

@section('title', 'Crear Usuario - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
@endsection

@section('content')

<div class="main admin-form">
    <h1>Crear Nuevo Usuario</h1>

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.usuarios.store') }}" class="form">
        @csrf

        <div class="form-group">
            <label for="nombre">Nombre de usuario</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required minlength="3">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="clave">Contraseña</label>
            <input type="password" id="clave" name="clave" required minlength="6">
            <small style="color: #8b8e91;">Mínimo 6 caracteres</small>
        </div>

        <div class="form-group">
            <label for="clave_confirmation">Confirmar Contraseña</label>
            <input type="password" id="clave_confirmation" name="clave_confirmation" required minlength="6">
        </div>

        <div class="form-group">
            <label for="rol">Rol</label>
            <select id="rol" name="rol" required>
                <option value="user">Usuario</option>
                <option value="admin">Administrador</option>
            </select>
        </div>

        <div class="form-group">
            <label for="saldo">Saldo Inicial (€)</label>
            <input type="number" id="saldo" name="saldo" step="0.01" value="{{ old('saldo', 100.00) }}" required min="0">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                <i class='bx bx-check'></i> Crear Usuario
            </button>
            <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary" style="text-decoration: none;">
                <i class='bx bx-x'></i> Cancelar
            </a>
        </div>
    </form>
</div>

@endsection
