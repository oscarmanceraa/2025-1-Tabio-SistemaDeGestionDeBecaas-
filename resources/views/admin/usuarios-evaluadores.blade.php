@extends('layouts.app')

@section('content')
<div class="container d-flex" style="min-height: 90vh;">
    <aside class="sidebar" style="width: 250px; background: #fff; border-right: 1px solid #ddd; padding: 20px;">
        <h2>Administrador</h2>
        <ul class="menu" style="list-style: none; padding: 0;">
            <li style="padding: 10px;">Gestión de Evaluadores</li>
            <li style="padding: 10px;"><a href="{{ route('admin.dashboard') }}">Panel principal</a></li>
        </ul>
    </aside>
    <main class="content" style="flex: 1; padding: 40px 30px;">
        <h2 class="mb-4">Gestión de Evaluadores</h2>
        <div class="card mb-4">
            <div class="card-header bg-light">Crear nuevo evaluador</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.evaluadores.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="username" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Crear Evaluador</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-light">Evaluadores registrados</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($evaluadores as $evaluador)
                            <tr>
                                <td>{{ $evaluador->persona->primer_nombre }} {{ $evaluador->persona->primer_apellido }}</td>
                                <td>{{ $evaluador->email }}</td>
                                <td>{{ $evaluador->username }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.evaluadores.destroy', $evaluador->id_user) }}" style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este evaluador?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
