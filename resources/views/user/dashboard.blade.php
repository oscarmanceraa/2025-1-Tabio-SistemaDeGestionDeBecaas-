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
                <h6 class="form-section-title">Datos Personales</h6>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Primer Nombre:</label>
                        <input type="text" name="primer_nombre" 
                               value="{{ old('primer_nombre', Auth::user()->persona->primer_nombre) }}" readonly />
                    </div>
                    <div class="form-group">
                        <label>Segundo Nombre:</label>
                        <input type="text" name="segundo_nombre" 
                               value="{{ old('segundo_nombre', Auth::user()->persona->segundo_nombre) }}" readonly />
                    </div>
                    <div class="form-group">
                        <label>Primer Apellido:</label>
                        <input type="text" name="primer_apellido" 
                               value="{{ old('primer_apellido', Auth::user()->persona->primer_apellido) }}" readonly />
                    </div>
                    <div class="form-group">
                        <label>Segundo Apellido:</label>
                        <input type="text" name="segundo_apellido" 
                               value="{{ old('segundo_apellido', Auth::user()->persona->segundo_apellido) }}" readonly />
                    </div>
                    <div class="form-group">
                        <label>Tipo de documento:</label>
                        <select name="tipo_documento" disabled>
                            @php
                                $tipos_documento = \App\Models\TipoDocumento::all();
                            @endphp
                            @foreach($tipos_documento as $tipo)
                                <option value="{{ $tipo->id_tipo_documento }}" {{ Auth::user()->persona->id_tipo_documento == $tipo->id_tipo_documento ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Número de Documento:</label>
                        <input type="text" name="numero_documento" 
                               value="{{ old('numero_documento', Auth::user()->persona->numero_documento) }}" readonly />
                    </div>
                    <div class="form-group">
                        <label>Dirección:</label>
                        <input type="text" name="direccion" 
                               value="{{ old('direccion', Auth::user()->persona->direccion) }}" required />
                    </div>
                    <div class="form-group">
                        <label>Fecha de Expedición:</label>
                        <input type="date" name="fecha_exp_documento" 
                               value="{{ old('fecha_exp_documento', Auth::user()->persona->fecha_exp_documento ? \Carbon\Carbon::parse(Auth::user()->persona->fecha_exp_documento)->format('Y-m-d') : '') }}" required />
                    </div>
                    <div class="file-input-group">
                        <label>Certificado Sisbén:</label>
                        <input type="file" name="certificado_sisben" accept=".pdf" class="styled-file-input" 
                               placeholder="Subir PDF del certificado Sisbén actualizado">
                    </div>
                    <div class="file-input-group">
                        <label>Acta de Grado:</label>
                        <input type="file" name="acta_grado" accept=".pdf" class="styled-file-input" 
                               placeholder="Subir PDF del acta de grado legalizada">
                    </div>
                    
                    <div class="file-input-group">
                        <label>Certificación de Discapacidad:</label>
                        <input type="file" name="certificado_discapacidad" accept=".pdf" class="styled-file-input" 
                               placeholder="Subir PDF del certificado médico vigente">
                    </div>
                    <div class="file-input-group">
                        <label>Certificado de Notas:</label>
                        <input type="file" name="certificado_notas" accept=".pdf" class="styled-file-input" 
                               placeholder="Subir PDF del certificado Notas vigente">
                    </div>
                    
                </div>
                
                <h6 class="form-section-title">Datos de Postulación</h6>
                <div class="form-grid">
                    <!-- Campos de postulación -->
                    <input type="hidden" name="id_persona" value="{{ Auth::user()->persona->id_persona }}">
                    
                    <div class="form-group">
                        <label>Tipo de Beneficio:</label>
                        <select name="id_tipo_beneficio" class="@error('id_tipo_beneficio') is-invalid @enderror" required>
                            <option value="">Seleccione un tipo de beneficio</option>
                            @php
                                $tiposBeneficio = \App\Models\TipoBeneficio::all();
                            @endphp
                            @foreach($tiposBeneficio as $tipo)
                                <option value="{{ $tipo->id_tipo_beneficio }}" {{ old('id_tipo_beneficio') == $tipo->id_tipo_beneficio ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_tipo_beneficio')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Cantidad de Postulaciones:</label>
                        <input type="number" name="cantidad_postulaciones" min="1" max="10" value="{{ old('cantidad_postulaciones', 1) }}" class="@error('cantidad_postulaciones') is-invalid @enderror" required>
                        @error('cantidad_postulaciones')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Semestre:</label>
                        <input type="number" name="semestre" min="1" max="12" value="{{ old('semestre', 1) }}" class="@error('semestre') is-invalid @enderror" required>
                        @error('semestre')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Universidad:</label>
                        <select name="id_universidad" id="id_universidad" class="@error('id_universidad') is-invalid @enderror" required>
                            <option value="">Seleccione una universidad</option>
                            @php
                                $universidades = \App\Models\Universidad::all();
                            @endphp
                            @foreach($universidades as $universidad)
                                <option value="{{ $universidad->id_universidad }}" {{ old('id_universidad') == $universidad->id_universidad ? 'selected' : '' }}>{{ $universidad->nombre }}</option>
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
                            <!-- Los programas se cargarán dinámicamente según la universidad seleccionada -->
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
                                <option value="{{ $categoria->id_sisben }}" {{ old('id_sisben') == $categoria->id_sisben ? 'selected' : '' }}>{{ $categoria->letra }}{{ $categoria->numero }}</option>
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
                    
                    <!-- Campos adicionales requeridos por el controlador -->
                    <input type="hidden" name="id_nota" value="{{ \App\Models\Nota::where('id_persona', Auth::user()->persona->id_persona)->first()->id_nota ?? '' }}">
                    <input type="hidden" name="horas_sociales" value="0">
                    <input type="hidden" name="discapacidad" value="0">
                    <input type="hidden" name="colegio_publico" value="0">
                    <input type="hidden" name="madre_cabeza_familia" value="0">
                    <input type="hidden" name="victima_conflicto" value="0">
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="declaracion_juramentada" required>
                            <span>Declaro bajo juramento que toda la información proporcionada es verídica</span>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label>Promedio ponderado: (en este formato "4.5" "5.0")</label>
                        @php
                            $nota = \App\Models\Nota::where('id_persona', Auth::user()->persona->id_persona)->first();
                        @endphp
                        <input type="text" name="promedio" value="{{ $nota ? $nota->promedio : 'Sin promedio registrado' }}" readonly>
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
                </div>
                </div>
                    <div class="form-actions" style="margin-top: 20px; text-align: center;">
                  <button type="submit" class="btn-submit">Guardar Información y Postular</button>
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
        <!-- Eliminamos el checkbox -->
        <section id="respuesta" class="section collapsed">
          <div class="section-header" onclick="toggleSection('respuesta')">
            <h3>Respuesta</h3>
          </div>
          <div class="section-body">
            <h5>Subheading</h5>
            <p>Texto de ejemplo para la respuesta final.</p>
          </div>
        </section>
      </div>
    </main>
  </div>

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

    // Cargar programas según la universidad seleccionada
    document.addEventListener('DOMContentLoaded', function() {
      const universidadSelect = document.getElementById('id_universidad');
      const programaSelect = document.getElementById('id_programa');
      const oldProgramaId = @json(old('id_programa')); // Get old programa ID, will be null if not set

      function loadProgramas(universidadId, programaParaSeleccionar = null) {
        if (!universidadId) {
          programaSelect.innerHTML = '<option value="">Seleccione primero una universidad</option>';
          programaSelect.disabled = true; // Disable if no university is selected
          return;
        }

        programaSelect.innerHTML = '<option value="">Cargando programas...</option>';
        programaSelect.disabled = true; // Disable while loading

        fetch(`/api/universidades/${universidadId}/programas`)
          .then(response => {
            if (!response.ok) {
              throw new Error(`Error en la red o respuesta no OK: ${response.statusText}`);
            }
            return response.json();
          })
          .then(data => {
            programaSelect.innerHTML = '<option value="">Seleccione un programa</option>'; // Reset before populating
            if (data && data.length > 0) {
              data.forEach(programa => {
                const option = document.createElement('option');
                option.value = programa.id_programa;
                option.textContent = programa.nombre;
                programaSelect.appendChild(option);
              });
              // Attempt to select the 'programaParaSeleccionar' if provided
              if (programaParaSeleccionar !== null) {
                const selectedValue = String(programaParaSeleccionar);
                // Check if the option exists before setting the value
                if (Array.from(programaSelect.options).some(opt => opt.value === selectedValue)) {
                  programaSelect.value = selectedValue;
                } else {
                  console.warn(`El programa con ID ${programaParaSeleccionar} no se encontró en la lista para la universidad ID ${universidadId}.`);
                }
              }
            } else {
              programaSelect.innerHTML = '<option value="">No hay programas disponibles</option>';
            }
          })
          .catch(error => {
            console.error('Error al cargar programas:', error);
            programaSelect.innerHTML = '<option value="">Error al cargar programas</option>';
          })
          .finally(() => {
              programaSelect.disabled = false; // Re-enable after loading or error
          });
      }

      if (universidadSelect && programaSelect) {
        universidadSelect.addEventListener('change', function() {
          loadProgramas(this.value); // On change, selected program comes from user interaction, not old input
        });

        // Initial load if a university is already selected (e.g., from old input on page refresh)
        if (universidadSelect.value) {
          loadProgramas(universidadSelect.value, oldProgramaId);
        } else {
          // Ensure programaSelect is in a sensible default state if no university is initially selected
          programaSelect.innerHTML = '<option value="">Seleccione primero una universidad</option>';
          programaSelect.disabled = true; // Initially disabled if no university selected
        }
      } else {
          if (!universidadSelect) console.error("Elemento 'id_universidad' no encontrado.");
          if (!programaSelect) console.error("Elemento 'id_programa' no encontrado.");
      }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const horasSociales = document.getElementById('horas_sociales');
        const cantidadHorasDiv = document.getElementById('cantidad_horas_div');
        const discapacidad = document.getElementById('discapacidad');
        const tipoDiscapacidadDiv = document.getElementById('tipo_discapacidad_div');

        horasSociales.addEventListener('change', function() {
            cantidadHorasDiv.style.display = this.value === '1' ? 'block' : 'none';
        });

        discapacidad.addEventListener('change', function() {
            tipoDiscapacidadDiv.style.display = this.value === '1' ? 'block' : 'none';
        });
    });
  </script>
</body>
</html>


@endsection