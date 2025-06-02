@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Formulario Postulante</title>
  @vite(['resources/css/user.css'])
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <h2>Postulante</h2>
      <ul class="menu">
        <li onclick="toggleSection('terminos')">Términos y condiciones</li>
        <li onclick="toggleSection('caracterizacion')">Caracterización de Usuario</li>
        <li onclick="toggleSection('documentos')">Comprobación de Documentos</li>
        <li onclick="toggleSection('respuesta')">Respuesta</li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Cerrar Sesión</button>
          </form>
        </li>
      </ul>
    </aside>

    <main class="content">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      @php
        $convocatoriaActiva = \App\Models\Convocatoria::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->first();
        $estaActiva = $convocatoriaActiva ? true : false;
      @endphp

      @if(!$estaActiva)
        <div class="alert alert-warning">
          <i class="bi bi-exclamation-triangle-fill"></i>
          La convocatoria está cerrada actualmente. No se pueden realizar nuevas postulaciones.
        </div>
      @else
        <div class="alert alert-info">
          <i class="bi bi-info-circle"></i>
          Convocatoria actual: {{ $convocatoriaActiva->nombre }}<br>
          Fecha de inicio: {{ \Carbon\Carbon::parse($convocatoriaActiva->fecha_inicio)->format('d/m/Y') }}<br>
          Fecha de fin: {{ \Carbon\Carbon::parse($convocatoriaActiva->fecha_fin)->format('d/m/Y') }}
        </div>
      @endif

      <div class="card" id="card-terminos">
        <!-- Eliminamos el checkbox -->
        <section id="terminos" class="section collapsed">
          <div class="section-header" onclick="toggleSection('terminos')">
            <h3>Términos y Condiciones</h3>
          </div>
          <div class="section-body">
            <h5>Subheading</h5>
            <p>Texto de ejemplo para los términos y condiciones.</p>
            <div class="form-checbox">
              <span>Yo</span><br/><br/>
              <input type="text" placeholder="Nombre Completo" /><br/><br/>
              <label class="checkbox-label">
                <input type="checkbox" name="acepto_terminos" />
                <span>Acepto términos y condiciones</span>
              </label>
            </div>
          </div>
        </section>
      </div>

      <div class="card" id="card-caracterizacion">
        <!-- Eliminamos el checkbox -->
        <section id="caracterizacion" class="section collapsed">
          <div class="section-header" onclick="toggleSection('caracterizacion')">
            <h3>Caracterización de Usuario</h3>
          </div>
          <div class="section-body">
            <h5>Información Personal y Postulación</h5>
            <p>Complete su información personal y datos de postulación para beneficios educativos.</p>
            
            @if(session('success'))
              <div class="alert alert-success">
                {{ session('success') }}
              </div>
            @endif

            @if($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
           
              <form method="POST" action="{{ route('postulaciones.store') }}" enctype="multipart/form-data" class="caracterizacion-form">
                @csrf
                @if(!$estaActiva)
                  <div class="alert alert-warning mb-4">
                    <i class="bi bi-lock-fill"></i>
                    El formulario está bloqueado porque la convocatoria está cerrada.
                  </div>
                @endif

                @if(isset($ultimaPostulacion))
                  <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle"></i>
                    Se ha cargado la información de tu última postulación. Los documentos anteriores se mantendrán a menos que subas nuevos.
                    <input type="hidden" name="ultima_postulacion" value="{{ $ultimaPostulacion->id_postulacion }}">
                  </div>
                @endif

                <fieldset {{ !$estaActiva ? 'disabled' : '' }}>
                  <h6 class="form-section-title">Datos Personales</h6>
                  <div class="form-grid">
                    <div class="form-group">
                      <label>Primer Nombre:</label>
                      <input type="text" name="primer_nombre" value="{{ old('primer_nombre', $persona->primer_nombre) }}" readonly />
                    </div>
                    <div class="form-group">
                      <label>Segundo Nombre:</label>
                      <input type="text" name="segundo_nombre" value="{{ old('segundo_nombre', $persona->segundo_nombre) }}" readonly />
                    </div>
                    <div class="form-group">
                      <label>Primer Apellido:</label>
                      <input type="text" name="primer_apellido" value="{{ old('primer_apellido', $persona->primer_apellido) }}" readonly />
                    </div>
                    <div class="form-group">
                      <label>Segundo Apellido:</label>
                      <input type="text" name="segundo_apellido" value="{{ old('segundo_apellido', $persona->segundo_apellido) }}" readonly />
                    </div>
                    <div class="form-group">
                      <label>Tipo de documento:</label>
                      <select name="tipo_documento" disabled>
                        @php
                          $tipos_documento = \App\Models\TipoDocumento::all();
                        @endphp
                        @foreach($tipos_documento as $tipo)
                          <option value="{{ $tipo->id_tipo_documento }}" {{ $persona->id_tipo_documento == $tipo->id_tipo_documento ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                    
                    <div class="form-group">
                      <label>Número de Documento:</label>
                      <input type="text" name="numero_documento" value="{{ old('numero_documento', $persona->numero_documento) }}" readonly />
                    </div>
                    <div class="form-group">
                      <label>Dirección:</label>
                      <input type="text" name="direccion" value="{{ old('direccion', $persona->direccion) }}" required />
                    </div>
                    <div class="form-group">
                      <label>Fecha de Expedición:</label>
                      <input type="date" name="fecha_exp_documento" value="{{ old('fecha_exp_documento', $persona->fecha_exp_documento ? \Carbon\Carbon::parse($persona->fecha_exp_documento)->format('Y-m-d') : '') }}" required />
                    </div>
                  </div>
                  
                  <h6 class="form-section-title">Datos de Postulación</h6>
                  <div class="form-grid">
                    <div class="form-group">
                      <label>Universidad:</label>
                      <select name="id_universidad" required>
                        <option value="">Seleccione una universidad</option>
                        @foreach($universidades as $universidad)
                          <option value="{{ $universidad->id_universidad }}" 
                            {{ old('id_universidad', $ultimaPostulacion->id_universidad ?? '') == $universidad->id_universidad ? 'selected' : '' }}>
                            {{ $universidad->nombre }}
                          </option>
                        @endforeach
                      </select>
                      @error('id_universidad')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    
                    <div class="form-group">
                      <label>Programa:</label>
                      <select name="id_programa" id="id_programa" class="@error('id_programa') is-invalid @enderror" required>
                        <option value="">Seleccione un programa</option>
                      </select>
                      @error('id_programa')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    
                    <div class="form-group">
                      <label>Categoría Sisben:</label>
                      <select name="id_sisben" class="@error('id_sisben') is-invalid @enderror" required>
                        <option value="">Seleccione categoría Sisben</option>
                        @php
                          $sisben = \App\Models\Sisben::orderBy('letra')->orderBy('numero')->get();
                        @endphp
                        @foreach($sisben as $categoria)
                          <option value="{{ $categoria->id_sisben }}" {{ old('id_sisben') == $categoria->id_sisben ? 'selected' : '' }}>
                            {{ $categoria->letra }}{{ $categoria->numero }}
                          </option>
                        @endforeach
                      </select>
                      @error('id_sisben')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    
                    <div class="form-group">
                      <label>Fecha de Postulación:</label>
                      <input type="date" name="fecha_postulacion" value="{{ old('fecha_postulacion', date('Y-m-d')) }}" class="@error('fecha_postulacion') is-invalid @enderror" required>
                      @error('fecha_postulacion')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    
                    <div class="form-group">
                      <label>Promedio ponderado: (en este formato "4.5" "5.0")</label>
                      <input type="number" step="0.01" min="0" max="5" name="promedio" value="{{ old('promedio', isset($ultimaPostulacion) ? $ultimaPostulacion->promedio : '') }}" class="@error('promedio') is-invalid @enderror" required>
                      @error('promedio')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>

                  <h6 class="form-section-title">Información Adicional</h6>
                  <div class="form-grid">
                    <div class="form-group">
                      <label>¿Tiene horas sociales?</label>
                      <select name="horas_sociales" id="horas_sociales" class="@error('horas_sociales') is-invalid @enderror" required>
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                      </select>
                    </div>

                    <div class="form-group" id="cantidad_horas_div" style="display: none;">
                      <label>Cantidad de horas sociales:</label>
                      <input type="number" name="cantidad_horas_sociales" min="0" class="@error('cantidad_horas_sociales') is-invalid @enderror">
                      <input type="text" name="obs_horas" placeholder="Observaciones sobre las horas sociales" class="@error('obs_horas') is-invalid @enderror">
                    </div>

                    <div class="form-group">
                      <label>¿Tiene alguna discapacidad?</label>
                      <select name="discapacidad" id="discapacidad" class="@error('discapacidad') is-invalid @enderror" required>
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                      </select>
                    </div>

                    <div class="form-group" id="tipo_discapacidad_div" style="display: none;">
                      <label>Tipo de discapacidad:</label>
                      <input type="text" name="tipo_discapacidad" class="@error('tipo_discapacidad') is-invalid @enderror">
                      <input type="text" name="obs_discapacidad" placeholder="Observaciones sobre la discapacidad" class="@error('obs_discapacidad') is-invalid @enderror">
                    </div>

                    <div class="form-group">
                      <label>¿Estudió en colegio público?</label>
                      <select name="colegio_publico" class="@error('colegio_publico') is-invalid @enderror" required>
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Nombre del colegio:</label>
                      <input type="text" name="nombre_colegio" class="@error('nombre_colegio') is-invalid @enderror" required>
                    </div>

                    <div class="form-group">
                      <label>¿Es madre cabeza de familia?</label>
                      <select name="madre_cabeza_familia" class="@error('madre_cabeza_familia') is-invalid @enderror" required>
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label>¿Es víctima del conflicto armado?</label>
                      <select name="victima_conflicto" class="@error('victima_conflicto') is-invalid @enderror" required>
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label class="checkbox-label">
                        <input type="checkbox" name="declaracion_juramentada" required>
                        <span>Declaro bajo juramento que toda la información proporcionada es verídica</span>
                      </label>
                      @error('declaracion_juramentada')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                </fieldset>

                <h6 class="form-section-title">Documentos Requeridos</h6>
                <div class="form-grid">
                  @foreach(['documento_identidad', 'certificado_sisben', 'acta_grado', 'certificado_notas', 'comprobante_domicilio', 'certificado_discapacidad'] as $tipoDoc)
                    <div class="form-group">
                      <label>{{ ucfirst(str_replace('_', ' ', $tipoDoc)) }}:</label>
                      @if(isset($ultimaPostulacion) && $ultimaPostulacion->documentos->where('tipo_documento', $tipoDoc)->first())
                        <div class="documento-previo">
                          <span class="texto-documento">Documento anterior disponible</span>
                          <input type="file" name="{{ $tipoDoc }}" accept=".pdf" class="styled-file-input">
                          <small class="form-text text-muted">Sube un nuevo archivo solo si deseas reemplazar el anterior.</small>
                        </div>
                      @else
                        <input type="file" name="{{ $tipoDoc }}" accept=".pdf" class="styled-file-input" 
                          {{ in_array($tipoDoc, ['documento_identidad', 'comprobante_domicilio']) ? 'required' : '' }}>
                        @if(!in_array($tipoDoc, ['documento_identidad', 'comprobante_domicilio']))
                          <small class="form-text text-muted">Este documento es opcional según tu caso.</small>
                        @endif
                      @endif
                      @error($tipoDoc)
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                  @endforeach
                </div>

                <div class="form-actions" style="margin-top: 20px; text-align: center;">
                  <button type="submit" class="btn-submit" {{ !$estaActiva ? 'disabled' : '' }}>
                    Guardar Información y Postular
                  </button>
                </div>
              </form>
            
          </div>
        </section>
      </div>

      <div class="card" id="card-documentos">
        <!-- Eliminamos el checkbox -->
        <section id="documentos" class="section collapsed">
          <div class="section-header" onclick="toggleSection('documentos')">
            <h3>Comprobación de Documentos</h3>
          </div>
          <div class="section-body">
            <h5>Subheading</h5>
            <p>Texto de ejemplo para la comprobación de documentos.</p>
          </div>
        </section>
      </div>

      <div class="card" id="card-respuesta">
        <section id="respuesta" class="section collapsed">
          <div class="section-header" onclick="toggleSection('respuesta')">
            <h3>Respuesta</h3>
          </div>
          <div class="section-body">
            @if(isset($postulacion) && $postulacion && $postulacion->resultado)
              @if($postulacion->resultado->aprobado)
                <div class="alert alert-success">
                  <strong>¡Felicidades!</strong> Tu postulación fue <strong>aprobada</strong>.<br>
                  <small>Fecha de evaluación: {{ $postulacion->resultado->fecha_evaluacion }}</small>
                </div>
                
                <div class="detalles-postulacion">
                  <button class="btn-toggle-details" onclick="toggleDetalles('detalles-beneficiario')">
                    Ver detalles de la postulación
                  </button>
                  
                  <div id="detalles-beneficiario" class="detalles-content" style="display: none;">
                    <div class="detalles-grid">
                      <h4>Información del Beneficio</h4>
                      <div class="info-grupo">
                        <label>Tipo de Beneficio:</label>
                        <span>{{ $postulacion->tipoBeneficio->nombre }}</span>
                      </div>
                      <div class="info-grupo">
                        <label>Universidad:</label>
                        <span>{{ $postulacion->universidad->nombre }}</span>
                      </div>
                      <div class="info-grupo">
                        <label>Programa:</label>
                        <span>{{ $postulacion->programa->nombre }}</span>
                      </div>
                      <div class="info-grupo">
                        <label>Semestre:</label>
                        <span>{{ $postulacion->semestre }}</span>
                      </div>
                      <div class="info-grupo">
                        <label>Puntaje Total:</label>
                        <span>{{ $postulacion->resultado->puntaje_total }}</span>
                      </div>
                      
                      @if($postulacion->beneficiario)
                        <h4>Estado del Beneficio</h4>
                        <div class="info-grupo">
                          <label>Fecha de Inicio:</label>
                          <span>{{ $postulacion->beneficiario->fecha_inicio }}</span>
                        </div>
                        @if($postulacion->beneficiario->fecha_fin)
                          <div class="info-grupo">
                            <label>Fecha de Finalización:</label>
                            <span>{{ $postulacion->beneficiario->fecha_fin }}</span>
                          </div>
                        @endif
                        <div class="info-grupo">
                          <label>Estado:</label>
                          <span class="badge {{ $postulacion->beneficiario->vigente ? 'bg-success' : 'bg-secondary' }}">
                            {{ $postulacion->beneficiario->vigente ? 'Vigente' : 'No Vigente' }}
                          </span>
                        </div>
                      @endif
                      
                      <h4>Documentos Presentados</h4>
                      @foreach($postulacion->documentos as $documento)
                        <div class="info-grupo">
                          <label>{{ ucfirst(str_replace('_', ' ', $documento->tipo_documento)) }}:</label>
                          <span class="badge {{ $documento->verificado ? 'bg-success' : 'bg-warning' }}">
                            {{ $documento->verificado ? 'Verificado' : 'Pendiente de verificación' }}
                          </span>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              @else
                <div class="alert alert-danger">
                  <strong>Postulación rechazada.</strong> Lamentablemente, tu postulación no fue aprobada.<br>
                  <small>Fecha de evaluación: {{ $postulacion->resultado->fecha_evaluacion }}</small>
                </div>
              @endif
            @else
              <h5>Sin respuesta aún</h5>
              <p>Cuando tu postulación sea evaluada, verás la respuesta aquí.</p>
            @endif
          </div>
        </section>
      </div>
    </main>
  </div>

  <style>
    .detalles-postulacion {
      margin-top: 20px;
    }
    
    .btn-toggle-details {
      width: 100%;
      padding: 10px;
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 5px;
      cursor: pointer;
      text-align: left;
      font-weight: 500;
    }
    
    .btn-toggle-details:hover {
      background-color: #e9ecef;
    }
    
    .detalles-content {
      margin-top: 15px;
      padding: 20px;
      border: 1px solid #dee2e6;
      border-radius: 5px;
    }
    
    .detalles-grid {
      display: grid;
      gap: 15px;
    }
    
    .detalles-grid h4 {
      margin-top: 20px;
      margin-bottom: 10px;
      color: #495057;
      border-bottom: 2px solid #e9ecef;
      padding-bottom: 5px;
    }
    
    .info-grupo {
      display: grid;
      grid-template-columns: 200px 1fr;
      gap: 10px;
      align-items: center;
    }
    
    .info-grupo label {
      font-weight: 500;
      color: #6c757d;
    }
    
    .badge {
      display: inline-block;
      padding: 5px 10px;
      border-radius: 4px;
      font-weight: 500;
    }
  </style>

  <script>
    function toggleSection(id) {
      const section = document.getElementById(id);
      
      // Cerrar todas las secciones primero
      document.querySelectorAll('.section').forEach(s => {
        if (s.id !== id) {
          s.classList.add('collapsed');
        }
      });
      
      // Alternar la sección actual
      section.classList.toggle('collapsed');
      
      // Si la sección está expandida, desplazarse a ella
      if (!section.classList.contains('collapsed')) {
        setTimeout(() => {
          section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
      }
    }

    function toggleDetalles(id) {
      const detalles = document.getElementById(id);
      if (detalles.style.display === 'none') {
        detalles.style.display = 'block';
      } else {
        detalles.style.display = 'none';
      }
    }

    // Cargar programas según la universidad seleccionada
    document.addEventListener('DOMContentLoaded', function() {
      const universidadSelect = document.querySelector('select[name="id_universidad"]');
      const programaSelect = document.querySelector('select[name="id_programa"]');
      
      if (!universidadSelect || !programaSelect) {
        console.error('No se encontraron los elementos select necesarios');
        return;
      }

      function loadProgramas(universidadId) {
        if (!universidadId) {
          programaSelect.innerHTML = '<option value="">Seleccione primero una universidad</option>';
          programaSelect.disabled = true;
          return;
        }

        programaSelect.disabled = true;
        programaSelect.innerHTML = '<option value="">Cargando programas...</option>';

        fetch(`/api/universidades/${universidadId}/programas`)
          .then(response => {
            if (!response.ok) {
              throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
          })
          .then(programas => {
            programaSelect.innerHTML = '<option value="">Seleccione un programa</option>';
            programas.forEach(programa => {
              const option = document.createElement('option');
              option.value = programa.id_programa;
              option.textContent = programa.nombre;
              programaSelect.appendChild(option);
            });
            
            // Si hay un valor previo seleccionado, intentar restaurarlo
            const oldValue = programaSelect.getAttribute('data-old-value');
            if (oldValue) {
              programaSelect.value = oldValue;
            }
          })
          .catch(error => {
            console.error('Error al cargar programas:', error);
            programaSelect.innerHTML = '<option value="">Error al cargar programas</option>';
          })
          .finally(() => {
            programaSelect.disabled = false;
          });
      }

      // Evento change para universidad
      universidadSelect.addEventListener('change', function() {
        loadProgramas(this.value);
      });

      // Cargar programas inicialmente si hay una universidad seleccionada
      if (universidadSelect.value) {
        loadProgramas(universidadSelect.value);
      }

      // Guardar el valor anterior si existe
      const oldProgramaValue = '{{ old("id_programa") }}';
      if (oldProgramaValue) {
        programaSelect.setAttribute('data-old-value', oldProgramaValue);
      }
    });

    // Mostrar/ocultar campos adicionales según selección
    document.addEventListener('DOMContentLoaded', function() {
      const horasSociales = document.getElementById('horas_sociales');
      const cantidadHorasDiv = document.getElementById('cantidad_horas_div');
      const discapacidad = document.getElementById('discapacidad');
      const tipoDiscapacidadDiv = document.getElementById('tipo_discapacidad_div');

      if (horasSociales && cantidadHorasDiv) {
        horasSociales.addEventListener('change', function() {
          cantidadHorasDiv.style.display = this.value === '1' ? 'block' : 'none';
        });
        // Establecer estado inicial
        cantidadHorasDiv.style.display = horasSociales.value === '1' ? 'block' : 'none';
      }

      if (discapacidad && tipoDiscapacidadDiv) {
        discapacidad.addEventListener('change', function() {
          tipoDiscapacidadDiv.style.display = this.value === '1' ? 'block' : 'none';
        });
        // Establecer estado inicial
        tipoDiscapacidadDiv.style.display = discapacidad.value === '1' ? 'block' : 'none';
      }
    });
  </script>
</body>
</html>


@endsection