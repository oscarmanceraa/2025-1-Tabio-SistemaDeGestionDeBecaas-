@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Formulario de Postulación') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('postulaciones.store') }}">
                        @csrf

                        <!-- Persona -->
                        <div class="form-group row mb-3">
                            <label for="id_persona" class="col-md-4 col-form-label text-md-right">{{ __('Persona') }}</label>
                            <div class="col-md-6">
                                <select id="id_persona" class="form-control @error('id_persona') is-invalid @enderror" name="id_persona" required>
                                    <option value="">Seleccione una persona</option>
                                    @foreach($personas as $persona)
                                        <option value="{{ $persona->id_persona }}">{{ $persona->primer_nombre }} {{ $persona->segundo_nombre }} {{ $persona->primer_apellido }} {{ $persona->segundo_apellido }} - {{ $persona->numero_documento }}</option>
                                    @endforeach
                                </select>
                                @error('id_persona')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Tipo de Beneficio -->
                        <div class="form-group row mb-3">
                            <label for="id_tipo_beneficio" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de Beneficio') }}</label>
                            <div class="col-md-6">
                                <select id="id_tipo_beneficio" class="form-control @error('id_tipo_beneficio') is-invalid @enderror" name="id_tipo_beneficio" required>
                                    <option value="">Seleccione un tipo de beneficio</option>
                                    @foreach($tiposBeneficio as $tipo)
                                        <option value="{{ $tipo->id_tipo_beneficio }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('id_tipo_beneficio')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Cantidad de Postulaciones -->
                        <div class="form-group row mb-3">
                            <label for="cantidad_postulaciones" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad de Postulaciones') }}</label>
                            <div class="col-md-6">
                                <input id="cantidad_postulaciones" type="number" min="1" max="10" class="form-control @error('cantidad_postulaciones') is-invalid @enderror" name="cantidad_postulaciones" value="{{ old('cantidad_postulaciones', 1) }}" required>
                                @error('cantidad_postulaciones')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Semestre -->
                        <div class="form-group row mb-3">
                            <label for="semestre" class="col-md-4 col-form-label text-md-right">{{ __('Semestre') }}</label>
                            <div class="col-md-6">
                                <input id="semestre" type="number" min="1" max="12" class="form-control @error('semestre') is-invalid @enderror" name="semestre" value="{{ old('semestre') }}" required>
                                @error('semestre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Universidad -->
                        <div class="form-group row mb-3">
                            <label for="id_universidad" class="col-md-4 col-form-label text-md-right">{{ __('Universidad') }}</label>
                            <div class="col-md-6">
                                <select id="id_universidad" class="form-control @error('id_universidad') is-invalid @enderror" name="id_universidad" required>
                                    <option value="">Seleccione una universidad</option>
                                    @foreach($universidades as $universidad)
                                        <option value="{{ $universidad->id_universidad }}">{{ $universidad->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('id_universidad')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Programa -->
                        <div class="form-group row mb-3">
                            <label for="id_programa" class="col-md-4 col-form-label text-md-right">{{ __('Programa') }}</label>
                            <div class="col-md-6">
                                <select id="id_programa" class="form-control @error('id_programa') is-invalid @enderror" name="id_programa" required>
                                    <option value="">Seleccione un programa</option>
                                    <!-- Los programas se cargarán dinámicamente según la universidad seleccionada -->
                                </select>
                                @error('id_programa')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Sisben -->
                        <div class="form-group row mb-3">
                            <label for="id_sisben" class="col-md-4 col-form-label text-md-right">{{ __('Categoría Sisben') }}</label>
                            <div class="col-md-6">
                                <select id="id_sisben" class="form-control @error('id_sisben') is-invalid @enderror" name="id_sisben" required>
                                    <option value="">Seleccione categoría Sisben</option>
                                    @foreach($sisben as $categoria)
                                        <option value="{{ $categoria->id_sisben }}">{{ $categoria->letra }}{{ $categoria->numero }} - Puntaje: {{ $categoria->puntaje }}</option>
                                    @endforeach
                                </select>
                                @error('id_sisben')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Nota -->
                        <div class="form-group row mb-3">
                            <label for="id_nota" class="col-md-4 col-form-label text-md-right">{{ __('Nota Promedio') }}</label>
                            <div class="col-md-6">
                                <select id="id_nota" class="form-control @error('id_nota') is-invalid @enderror" name="id_nota" required>
                                    <option value="">Seleccione nota registrada</option>
                                    @foreach($notas as $nota)
                                        <option value="{{ $nota->id_nota }}">{{ $nota->promedio }}</option>
                                    @endforeach
                                </select>
                                @error('id_nota')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Preguntas -->
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">{{ __('Información Adicional') }}</label>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Preguntas Adicionales</h5>
                                        
                                        <!-- Horas Sociales -->
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="horas_sociales" id="horas_sociales" value="1">
                                            <label class="form-check-label" for="horas_sociales">
                                                ¿Ha realizado horas sociales?
                                            </label>
                                        </div>
                                        
                                        <div class="mb-3 horas-sociales-details" style="display: none;">
                                            <label for="cantidad_horas_sociales" class="form-label">Cantidad de horas sociales</label>
                                            <input type="number" class="form-control" id="cantidad_horas_sociales" name="cantidad_horas_sociales">
                                            
                                            <label for="obs_horas" class="form-label">Observaciones</label>
                                            <textarea class="form-control" id="obs_horas" name="obs_horas" rows="2"></textarea>
                                        </div>
                                        
                                        <!-- Discapacidad -->
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="discapacidad" id="discapacidad" value="1">
                                            <label class="form-check-label" for="discapacidad">
                                                ¿Tiene alguna discapacidad?
                                            </label>
                                        </div>
                                        
                                        <div class="mb-3 discapacidad-details" style="display: none;">
                                            <label for="tipo_discapacidad" class="form-label">Tipo de discapacidad</label>
                                            <input type="text" class="form-control" id="tipo_discapacidad" name="tipo_discapacidad">
                                            
                                            <label for="obs_discapacidad" class="form-label">Observaciones</label>
                                            <textarea class="form-control" id="obs_discapacidad" name="obs_discapacidad" rows="2"></textarea>
                                        </div>
                                        
                                        <!-- Colegio Público -->
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="colegio_publico" id="colegio_publico" value="1">
                                            <label class="form-check-label" for="colegio_publico">
                                                ¿Estudió en colegio público?
                                            </label>
                                        </div>
                                        
                                        <div class="mb-3 colegio-details" style="display: none;">
                                            <label for="nombre_colegio" class="form-label">Nombre del colegio</label>
                                            <input type="text" class="form-control" id="nombre_colegio" name="nombre_colegio">
                                        </div>
                                        
                                        <!-- Madre Cabeza de Familia -->
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="madre_cabeza_familia" id="madre_cabeza_familia" value="1">
                                            <label class="form-check-label" for="madre_cabeza_familia">
                                                ¿Su madre es cabeza de familia?
                                            </label>
                                        </div>
                                        
                                        <!-- Víctima de Conflicto -->
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="victima_conflicto" id="victima_conflicto" value="1">
                                            <label class="form-check-label" for="victima_conflicto">
                                                ¿Es víctima del conflicto armado?
                                            </label>
                                        </div>
                                        
                                        <!-- Declaración Juramentada -->
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="declaracion_juramentada" id="declaracion_juramentada" value="1" required>
                                            <label class="form-check-label" for="declaracion_juramentada">
                                                Declaro bajo juramento que la información proporcionada es verídica
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fecha de Postulación -->
                        <div class="form-group row mb-3">
                            <label for="fecha_postulacion" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Postulación') }}</label>
                            <div class="col-md-6">
                                <input id="fecha_postulacion" type="date" class="form-control @error('fecha_postulacion') is-invalid @enderror" name="fecha_postulacion" value="{{ old('fecha_postulacion', date('Y-m-d')) }}" required>
                                @error('fecha_postulacion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Enviar Postulación') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mostrar/ocultar campos adicionales según los checkboxes
        const horasSocialesCheck = document.getElementById('horas_sociales');
        const horasSocialesDetails = document.querySelector('.horas-sociales-details');
        
        horasSocialesCheck.addEventListener('change', function() {
            horasSocialesDetails.style.display = this.checked ? 'block' : 'none';
        });
        
        const discapacidadCheck = document.getElementById('discapacidad');
        const discapacidadDetails = document.querySelector('.discapacidad-details');
        
        discapacidadCheck.addEventListener('change', function() {
            discapacidadDetails.style.display = this.checked ? 'block' : 'none';
        });
        
        const colegioCheck = document.getElementById('colegio_publico');
        const colegioDetails = document.querySelector('.colegio-details');
        
        colegioCheck.addEventListener('change', function() {
            colegioDetails.style.display = this.checked ? 'block' : 'none';
        });
        
        // Cargar programas según la universidad seleccionada
        const universidadSelect = document.getElementById('id_universidad');
        const programaSelect = document.getElementById('id_programa');
        
        universidadSelect.addEventListener('change', function() {
            const universidadId = this.value;
            if (universidadId) {
                // Limpiar opciones actuales
                programaSelect.innerHTML = '<option value="">Seleccione un programa</option>';
                
                // Hacer petición AJAX para obtener programas
                fetch(`/api/universidades/${universidadId}/programas`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(programa => {
                            const option = document.createElement('option');
                            option.value = programa.id_programa;
                            option.textContent = programa.nombre;
                            programaSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    });
</script>
@endpush
@endsection