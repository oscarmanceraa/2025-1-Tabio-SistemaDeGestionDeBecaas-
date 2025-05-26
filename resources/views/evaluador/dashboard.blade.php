@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="text-center mb-4">Panel de Evaluador</h1>
            @forelse($postulaciones as $postulacion)
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Postulación #{{ $postulacion->id_postulacion }} - {{ $postulacion->persona->primer_nombre }} {{ $postulacion->persona->primer_apellido }}</span>
                        <span class="badge bg-primary">{{ $postulacion->tipoBeneficio->nombre }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Universidad:</strong> {{ $postulacion->universidad->nombre }}
                            </div>
                            <div class="col-md-6">
                                <strong>Programa:</strong> {{ $postulacion->programa->nombre }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <strong>Semestre:</strong> {{ $postulacion->semestre }}
                            </div>
                            <div class="col-md-4">
                                <strong>Nota Promedio:</strong> {{ $postulacion->nota->promedio }}
                            </div>
                            <div class="col-md-4">
                                <strong>Fecha de Postulación:</strong> {{ $postulacion->fecha_postulacion }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <strong>Categoría Sisben:</strong> {{ $postulacion->sisben->letra ?? '' }}{{ $postulacion->sisben->numero ?? '' }}
                            </div>
                            <div class="col-md-4">
                                <strong>Colegio Público:</strong> {{ $postulacion->colegio_publico ? 'Sí' : 'No' }}
                            </div>
                            <div class="col-md-4">
                                <strong>Madre Cabeza de Familia:</strong> {{ $postulacion->madre_cabeza_familia ? 'Sí' : 'No' }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <strong>Discapacidad:</strong> {{ $postulacion->discapacidad ? 'Sí' : 'No' }}
                                @if($postulacion->discapacidad)
                                    <br><strong>Tipo:</strong> {{ $postulacion->tipo_discapacidad }}
                                @endif
                            </div>
                            <div class="col-md-4">
                                <strong>Horas Sociales:</strong> {{ $postulacion->horas_sociales ? 'Sí' : 'No' }}
                                @if($postulacion->horas_sociales)
                                    <br><strong>Cantidad:</strong> {{ $postulacion->cantidad_horas_sociales }}
                                @endif
                            </div>
                            <div class="col-md-4">
                                <strong>Víctima de Conflicto:</strong> {{ $postulacion->victima_conflicto ? 'Sí' : 'No' }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <strong>Declaración Juramentada:</strong> {{ $postulacion->declaracion_juramentada ? 'Sí' : 'No' }}
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 text-end">
                                <a href="#" class="btn btn-info btn-sm">Ver Detalles</a>
                                @if(!$postulacion->beneficiario)
                                    <form method="POST" action="{{ route('evaluador.seleccionarBeneficiario', $postulacion->id_postulacion) }}" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Seleccionar como Beneficiario</button>
                                    </form>
                                @else
                                    <span class="badge bg-success">Beneficiario</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">No hay postulaciones registradas.</div>
            @endforelse

        <hr>
        <h3 class="mt-5 mb-3">Beneficiarios Seleccionados</h3>
        <div class="card">
            <div class="card-body">
                <ul class="list-group">
                @php
                    $beneficiarios = \App\Models\Beneficiario::with('postulacion.persona')->get();
                @endphp
                @forelse($beneficiarios as $beneficiario)
                    <li class="list-group-item">
                        <strong>{{ $beneficiario->postulacion->persona->primer_nombre }} {{ $beneficiario->postulacion->persona->primer_apellido }}</strong>
                        - Postulación #{{ $beneficiario->id_postulacion }}
                        <span class="badge bg-primary ms-2">{{ $beneficiario->postulacion->tipoBeneficio->nombre ?? '' }}</span>
                        <span class="badge bg-info ms-2">Desde: {{ $beneficiario->fecha_inicio }}</span>
                        @if($beneficiario->fecha_fin)
                            <span class="badge bg-secondary ms-2">Hasta: {{ $beneficiario->fecha_fin }}</span>
                        @endif
                    </li>
                @empty
                    <li class="list-group-item">No hay beneficiarios seleccionados.</li>
                @endforelse
                </ul>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection