@extends('layouts.app')

@section('title', 'Crear Juego - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
@endsection

@section('content')

<div class="main admin-form">
    <h1>Crear Nuevo Juego</h1>

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.juegos.store') }}" class="form" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" required maxlength="100">
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" required></textarea>
        </div>

        <div class="form-group">
            <label for="precio">Precio (€)</label>
            <input type="number" id="precio" name="precio" step="0.01" value="{{ old('precio') }}" required min="0">
        </div>

        <div class="form-group">
            <label for="genero">Género</label>
            <select id="genero" name="genero" required>
                <option value="">Selecciona un género</option>
                <option value="Acción" @if(old('genero') === 'Acción') selected @endif>Acción</option>
                <option value="Terror" @if(old('genero') === 'Terror') selected @endif>Terror</option>
                <option value="RPG" @if(old('genero') === 'RPG') selected @endif>RPG</option>
                <option value="Estrategia" @if(old('genero') === 'Estrategia') selected @endif>Estrategia</option>
                <option value="Aventura" @if(old('genero') === 'Aventura') selected @endif>Aventura</option>
                <option value="Deportes" @if(old('genero') === 'Deportes') selected @endif>Deportes</option>
                <option value="Puzzle" @if(old('genero') === 'Puzzle') selected @endif>Puzzle</option>
                <option value="Simulación" @if(old('genero') === 'Simulación') selected @endif>Simulación</option>
            </select>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen del juego</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" required>
            <small>Formatos: JPG, PNG, GIF, WebP. Máximo 2MB</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                <i class='bx bx-check'></i> Crear Juego
            </button>
            <a href="{{ route('admin.juegos') }}" class="btn btn-secondary" style="text-decoration: none;">
                <i class='bx bx-x'></i> Cancelar
            </a>
        </div>
    </form>
</div>

@endsection
