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
                        <button type="button" class="btn btn-small btn-danger" onclick="mostrarModalEliminarJuego({{ $juego->id }}, '{{ $juego->titulo }}')">
                            <i class='bx bx-trash'></i> Eliminar
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $juegos->links() }}
    </div>
</div>

<!-- Modal de confirmación para eliminar juego -->
<div id="modalEliminarJuego" class="modal-steam">
    <div class="modal-steam-content">
        <div class="modal-steam-title modal-icon-game">
            <i class='bx bx-game'></i>
            Eliminar Juego
        </div>
        <div class="modal-steam-message">
            ¿Estás seguro de que quieres eliminar el juego <strong id="nombreJuego"></strong>? Esta acción no se puede deshacer y se perderán todos los datos asociados incluyendo reseñas y bibliotecas de usuarios.
        </div>
        <div class="modal-steam-buttons">
            <button type="button" class="modal-steam-btn modal-steam-btn-secondary" onclick="cerrarModalEliminarJuego()">
                Cancelar
            </button>
            <button type="button" class="modal-steam-btn modal-steam-btn-danger" onclick="confirmarEliminarJuego()">
                Eliminar Juego
            </button>
        </div>
    </div>
</div>

<!-- Formulario oculto para eliminar juego -->
<form id="formEliminarJuego" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
let juegoIdAEliminar = null;

function mostrarModalEliminarJuego(id, titulo) {
    juegoIdAEliminar = id;
    document.getElementById('nombreJuego').textContent = titulo;
    document.getElementById('modalEliminarJuego').style.display = 'flex';
    
    // Configurar formulario
    const form = document.getElementById('formEliminarJuego');
    form.action = '{{ route("admin.juegos.destroy", ":id") }}'.replace(':id', id);
}

function cerrarModalEliminarJuego() {
    document.getElementById('modalEliminarJuego').style.display = 'none';
    juegoIdAEliminar = null;
}

function confirmarEliminarJuego() {
    if (juegoIdAEliminar) {
        // Añadir clase de animación
        document.getElementById('modalEliminarJuego').classList.add('confirming');
        
        // Enviar formulario después de un pequeño delay
        setTimeout(() => {
            document.getElementById('formEliminarJuego').submit();
        }, 300);
    }
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalEliminarJuego').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalEliminarJuego();
    }
});

// Cerrar modal al presionar Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('modalEliminarJuego').style.display === 'flex') {
        cerrarModalEliminarJuego();
    }
});
</script>

@endsection
