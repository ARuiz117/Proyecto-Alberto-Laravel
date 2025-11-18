@extends('layouts.app')

@section('title', 'Editar Juego - Steam HRG')

@section('content')

<div class="main admin-form">
    <h1>Editar Juego</h1>

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.juegos.update', $juego->id) }}" class="form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $juego->titulo) }}" required maxlength="100">
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" required>{{ old('descripcion', $juego->descripcion) }}</textarea>
        </div>

        <div class="form-group">
            <label for="precio">Precio (€)</label>
            <input type="number" id="precio" name="precio" step="0.01" value="{{ old('precio', $juego->precio) }}" required min="0">
        </div>

        <div class="form-group">
            <label for="genero">Género</label>
            <select id="genero" name="genero" required>
                <option value="">Selecciona un género</option>
                <option value="Acción" @if(old('genero', $juego->genero) === 'Acción') selected @endif>Acción</option>
                <option value="Terror" @if(old('genero', $juego->genero) === 'Terror') selected @endif>Terror</option>
                <option value="RPG" @if(old('genero', $juego->genero) === 'RPG') selected @endif>RPG</option>
                <option value="Estrategia" @if(old('genero', $juego->genero) === 'Estrategia') selected @endif>Estrategia</option>
                <option value="Aventura" @if(old('genero', $juego->genero) === 'Aventura') selected @endif>Aventura</option>
                <option value="Deportes" @if(old('genero', $juego->genero) === 'Deportes') selected @endif>Deportes</option>
                <option value="Puzzle" @if(old('genero', $juego->genero) === 'Puzzle') selected @endif>Puzzle</option>
                <option value="Simulación" @if(old('genero', $juego->genero) === 'Simulación') selected @endif>Simulación</option>
            </select>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen del juego</label>
            @if($juego->imagen_url)
                <div style="margin-bottom: 10px;">
                    <img src="/imagenes/{{ $juego->imagen_url }}" alt="{{ $juego->titulo }}" style="max-width: 150px; border-radius: 5px;">
                    <p style="font-size: 12px; color: #666;">Imagen actual</p>
                </div>
            @endif
            <input type="file" id="imagen" name="imagen" accept="image/*">
            <small>Formatos: JPG, PNG, GIF. Máximo 2MB. Dejar vacío para mantener la imagen actual.</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="{{ route('admin.juegos') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection
