@extends('layouts.app')

@section('title', 'Gestión de Usuarios - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
@endsection

@section('content')

<div class="main admin-usuarios">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <h1>Gestión de Usuarios</h1>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-success" style="text-decoration: none;">
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
                        <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="btn btn-small btn-info" style="text-decoration: none;">
                            <i class='bx bx-edit'></i> Editar
                        </a>
                        <button type="button" class="btn btn-small btn-danger" onclick="mostrarModalEliminarUsuario({{ $usuario->id }}, '{{ $usuario->nombre }}')">
                            <i class='bx bx-trash'></i> Eliminar
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $usuarios->links() }}
    </div>
</div>

<!-- Modal de confirmación para eliminar usuario -->
<div id="modalEliminarUsuario" class="modal-steam">
    <div class="modal-steam-content">
        <div class="modal-steam-title modal-icon-user">
            <i class='bx bx-user'></i>
            Eliminar Usuario
        </div>
        <div class="modal-steam-message">
            ¿Estás seguro de que quieres eliminar al usuario <strong id="nombreUsuario"></strong>? Esta acción no se puede deshacer y se eliminarán todos sus datos permanentemente.
        </div>
        <div class="modal-steam-buttons">
            <button type="button" class="modal-steam-btn modal-steam-btn-secondary" onclick="cerrarModalEliminarUsuario()">
                Cancelar
            </button>
            <button type="button" class="modal-steam-btn modal-steam-btn-danger" onclick="confirmarEliminarUsuario()">
                Eliminar Usuario
            </button>
        </div>
    </div>
</div>

<!-- Formulario oculto para eliminar usuario -->
<form id="formEliminarUsuario" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
let usuarioIdAEliminar = null;

function mostrarModalEliminarUsuario(id, nombre) {
    usuarioIdAEliminar = id;
    document.getElementById('nombreUsuario').textContent = nombre;
    document.getElementById('modalEliminarUsuario').style.display = 'flex';
    
    // Configurar formulario
    const form = document.getElementById('formEliminarUsuario');
    form.action = '{{ route("admin.usuarios.destroy", ":id") }}'.replace(':id', id);
}

function cerrarModalEliminarUsuario() {
    document.getElementById('modalEliminarUsuario').style.display = 'none';
    usuarioIdAEliminar = null;
}

function confirmarEliminarUsuario() {
    if (usuarioIdAEliminar) {
        // Añadir clase de animación
        document.getElementById('modalEliminarUsuario').classList.add('confirming');
        
        // Enviar formulario después de un pequeño delay
        setTimeout(() => {
            document.getElementById('formEliminarUsuario').submit();
        }, 300);
    }
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalEliminarUsuario').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalEliminarUsuario();
    }
});

// Cerrar modal al presionar Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('modalEliminarUsuario').style.display === 'flex') {
        cerrarModalEliminarUsuario();
    }
});
</script>

@endsection
