@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="min-height: 90vh; background: #f8fafc;">
    <div class="row">
        <div class="col-md-3 col-12 mb-4 mb-md-0">
            <aside class="bg-white rounded-4 shadow-sm p-4 h-100">
                <h2 class="fw-bold text-primary mb-4" style="font-size: 2rem;">Administrador</h2>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a class="nav-link active fw-semibold" href="#">Gestión de Evaluadores</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="{{ route('admin.dashboard') }}">Panel principal</a></li>
                    <li class="nav-item mt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</button>
                        </form>
                    </li>
                </ul>
            </aside>
        </div>
        <div class="col-md-9 col-12">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0 fw-bold fs-5">Crear nuevo evaluador</div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('admin.evaluadores.store') }}">
                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="alert alert-info small mb-3">
                                            <i class="bi bi-shield-lock me-2"></i>
                                            La contraseña debe tener al menos 8 caracteres.
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="primer_nombre" class="form-label">Primer Nombre</label>
                                        <input type="text" class="form-control @error('primer_nombre') is-invalid @enderror" id="primer_nombre" name="primer_nombre" value="{{ old('primer_nombre') }}" required>
                                        @error('primer_nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                                        <input type="text" class="form-control @error('segundo_nombre') is-invalid @enderror" id="segundo_nombre" name="segundo_nombre" value="{{ old('segundo_nombre') }}">
                                        @error('segundo_nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="primer_apellido" class="form-label">Primer Apellido</label>
                                        <input type="text" class="form-control @error('primer_apellido') is-invalid @enderror" id="primer_apellido" name="primer_apellido" value="{{ old('primer_apellido') }}" required>
                                        @error('primer_apellido')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                                        <input type="text" class="form-control @error('segundo_apellido') is-invalid @enderror" id="segundo_apellido" name="segundo_apellido" value="{{ old('segundo_apellido') }}">
                                        @error('segundo_apellido')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="id_tipo_documento" class="form-label">Tipo de Documento</label>
                                        <select class="form-select @error('id_tipo_documento') is-invalid @enderror" id="id_tipo_documento" name="id_tipo_documento" required>
                                            <option value="" disabled {{ old('id_tipo_documento') ? '' : 'selected' }}>Seleccione tipo de documento</option>
                                            @foreach($tipos_documento as $tipo)
                                                <option value="{{ $tipo->id_tipo_documento }}" {{ old('id_tipo_documento') == $tipo->id_tipo_documento ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
                                            @endforeach
                                        @error('id_tipo_documento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="numero_documento" class="form-label">Número de Documento</label>
                                        <input type="text" class="form-control @error('numero_documento') is-invalid @enderror" id="numero_documento" name="numero_documento" value="{{ old('numero_documento') }}" required>
                                        @error('numero_documento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="fecha_exp_documento" class="form-label">Fecha de Expedición</label>
                                        <input type="date" class="form-control @error('fecha_exp_documento') is-invalid @enderror" id="fecha_exp_documento" name="fecha_exp_documento" value="{{ old('fecha_exp_documento') }}" required>
                                        @error('fecha_exp_documento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
                                        @error('direccion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="email" class="form-label">Correo electrónico</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="username" class="form-label">Usuario</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-4 text-center">
                                    <button type="submit" class="btn btn-lg btn-outline-primary px-5 shadow-sm fw-bold">
                                        <i class="bi bi-person-plus"></i> Crear Evaluador
                                    </button>
                                </div>
                                <div class="mt-2 text-center text-muted small">
                                    <i class="bi bi-info-circle me-1"></i>Todos los campos marcados como requeridos son obligatorios.
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white border-0 fw-bold fs-5 d-flex justify-content-between align-items-center">
                                    <span>Evaluadores registrados</span>
                                    <span class="badge bg-primary bg-opacity-10 text-primary fw-normal">Total: {{ $evaluadores->total() }}</span>
                                </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Usuario</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($evaluadores as $evaluador)
                                            <tr>
                                                <td>{{ $evaluador->persona->primer_nombre }} {{ $evaluador->persona->primer_apellido }}</td>
                                                <td>{{ $evaluador->email }}</td>
                                                <td>{{ $evaluador->username }}</td>
                                                <td>
                                                    <form method="POST" action="{{ route('admin.evaluadores.destroy', $evaluador->id_user) }}" style="display:inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este evaluador?')">
                                                            <i class="bi bi-trash"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No hay evaluadores registrados.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $evaluadores->links('pagination::bootstrap-5') }}
                                </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<!-- Bootstrap JS for alert dismiss -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
