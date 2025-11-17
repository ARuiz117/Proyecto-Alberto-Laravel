@extends('layouts.app')

@section('title', 'Crear Juego - Steam HRG')

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
            <label for="imagen">Imagen del juego</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" required>
            <small>Formatos: JPG, PNG, GIF. Máximo 2MB</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Crear juego</button>
            <a href="{{ route('admin.juegos') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection
