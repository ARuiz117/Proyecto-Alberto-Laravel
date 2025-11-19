@extends('layouts.app')

@section('title', 'Panel de Administración - Steam HRG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
@endsection

@section('content')

<div class="main admin-dashboard">
    <h1>Panel de Administración</h1>

    <section class="admin-stats">
        <div class="stat-card">
            <h3>Usuarios</h3>
            <p class="stat-number">{{ $totalUsuarios }}</p>
            <a href="{{ route('admin.usuarios') }}" class="btn btn-primary btn-small" style="text-decoration: none;">Gestionar</a>
        </div>
        <div class="stat-card">
            <h3>Juegos</h3>
            <p class="stat-number">{{ $totalJuegos }}</p>
            <a href="{{ route('admin.juegos') }}" class="btn btn-primary btn-small" style="text-decoration: none;">Gestionar</a>
        </div>
        <div class="stat-card">
            <h3>Saldo Total</h3>
            <p class="stat-number">{{ number_format($totalVentas, 2) }} €</p>
        </div>
    </section>

    <section class="admin-usuarios-recientes">
        <h2>Usuarios Recientes</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Saldo</th>
                    <th>Creado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuariosRecientes as $usuario)
                    <tr>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ ucfirst($usuario->rol) }}</td>
                        <td>{{ number_format($usuario->saldo, 2) }} €</td>
                        <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>

@endsection
