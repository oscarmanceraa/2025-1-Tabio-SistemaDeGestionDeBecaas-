@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="text-center mb-4">Panel de Administrador</h1>
            <div class="card shadow-lg border-0 mb-5">
                <div class="card-body text-center py-5">
                    <h3 class="mb-4 fw-bold text-primary">Resumen General</h3>
                    <div class="row justify-content-center mb-4">
                        <div class="col-12 col-md-5 mb-3 mb-md-0">
                            <div class="rounded-4 bg-white border-0 shadow-sm py-4 px-2 h-100 d-flex flex-column align-items-center justify-content-center" style="min-height: 160px;">
                                <div class="mb-2" style="font-size: 2.5rem; color: #0d6efd;">
                                    <i class="bi bi-clipboard-data"></i>
                                </div>
                                <div class="fw-bold text-secondary mb-1" style="font-size: 1.2rem;">Total de Postulaciones</div>
                                <div class="display-4 fw-bold text-dark">{{ \App\Models\Postulacion::count() }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="rounded-4 bg-white border-0 shadow-sm py-4 px-2 h-100 d-flex flex-column align-items-center justify-content-center" style="min-height: 160px;">
                                <div class="mb-2" style="font-size: 2.5rem; color: #198754;">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div class="fw-bold text-secondary mb-1" style="font-size: 1.2rem;">Total de Beneficiarios</div>
                                <div class="display-4 fw-bold text-dark">{{ \App\Models\Beneficiario::count() }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.evaluadores.index') }}" class="btn btn-lg btn-outline-primary px-5 shadow-sm fw-bold">
                        <i class="bi bi-person-plus"></i> Gestión de Evaluadores
                    </a>
                </div>
            </div>
            <!-- Bootstrap Icons CDN -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        </div>
    </div>
</div>
@endsection