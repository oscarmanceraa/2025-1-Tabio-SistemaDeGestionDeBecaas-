@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel Evaluador</title>
  @vite(['resources/css/user.css'])
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <h2>Evaluador</h2>
      <ul class="menu">
        <li onclick="toggleSection('postulaciones')">Postulaciones</li>
        <li onclick="toggleSection('beneficiarios')">Beneficiarios</li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Cerrar Sesión</button>
          </form>
        </li>
      </ul>
    </aside>

    <main class="content">
      {{-- Mensajes de sesión --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif
      @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
      @endif
      <div class="card" id="card-postulaciones">
        <section id="postulaciones" class="section">
          <div class="section-header" onclick="toggleSection('postulaciones')">
            <h3>Postulaciones</h3>
          </div>
          <div class="section-body">
            @forelse($postulaciones as $postulacion)
              @if(!$postulacion->beneficiario)
                <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <span>Postulación #{{ $postulacion->id_postulacion }} - {{ $postulacion->persona->primer_nombre }} {{ $postulacion->persona->primer_apellido }}</span>
                  <span class="badge bg-primary">{{ $postulacion->tipoBeneficio->nombre }}</span>
                </div>
                <div class="card-body">
                  <h5 class="form-section-title">Caracterización del Postulante</h5>
                  <div class="form-grid">
                    {{-- Mostrar puntaje calculado antes del botón --}}
                    @php
                      $puntaje_preview = 0;
                      $pregunta = $postulacion->pregunta;
                      if ($pregunta) {
                        $puntaje_preview += $pregunta->horas_sociales ? 10 : 0;
                        $puntaje_preview += $pregunta->cantidad_horas_sociales ? min($pregunta->cantidad_horas_sociales, 100) * 0.1 : 0;
                        $puntaje_preview += $pregunta->discapacidad ? 10 : 0;
                        $puntaje_preview += $pregunta->colegio_publico ? 5 : 0;
                        $puntaje_preview += $pregunta->madre_cabeza_familia ? 5 : 0;
                        $puntaje_preview += $pregunta->victima_conflicto ? 5 : 0;
                        $puntaje_preview += $pregunta->declaracion_juramentada ? 5 : 0;
                      }
                      $sisben = $postulacion->sisben;
                      if ($sisben) {
                        $puntaje_preview += max(0, 20 - $sisben->numero);
                      }
                    @endphp
                    <div class="mb-2">
                      <span class="badge bg-info">Puntaje estimado: {{ $puntaje_preview }}</span>
                    </div>
                    <div class="form-group">
                      <label>Primer Nombre:</label>
                      <span>{{ $postulacion->persona->primer_nombre }}</span>
                    </div>
                    <div class="form-group">
                      <label>Segundo Nombre:</label>
                      <span>{{ $postulacion->persona->segundo_nombre ?? '-' }}</span>
                    </div>
                    <div class="form-group">
                      <label>Primer Apellido:</label>
                      <span>{{ $postulacion->persona->primer_apellido }}</span>
                    </div>
                    <div class="form-group">
                      <label>Segundo Apellido:</label>
                      <span>{{ $postulacion->persona->segundo_apellido ?? '-' }}</span>
                    </div>
                    <div class="form-group">
                      <label>Tipo de Documento:</label>
                      <span>{{ $postulacion->persona->tipoDocumento->nombre ?? '-' }}</span>
                    </div>
                    <div class="form-group">
                      <label>Número de Documento:</label>
                      <span>{{ $postulacion->persona->numero_documento }}</span>
                    </div>
                    <div class="form-group">
                      <label>Dirección:</label>
                      <span>{{ $postulacion->persona->direccion }}</span>
                    </div>
                    <div class="form-group">
                      <label>Fecha de Expedición:</label>
                      <span>{{ $postulacion->persona->fecha_exp_documento }}</span>
                    </div>
                  </div>
                  <hr>
                  <div class="form-section-title" style="margin-bottom:10px;">Datos de Postulación</div>
                  <div class="form-grid">
                    <div class="form-group">
                      <label>Universidad:</label>
                      <span>{{ $postulacion->universidad->nombre }}</span>
                    </div>
                    <div class="form-group">
                      <label>Programa:</label>
                      <span>{{ $postulacion->programa->nombre }}</span>
                    </div>
                    <div class="form-group">
                      <label>Semestre:</label>
                      <span>{{ $postulacion->semestre }}</span>
                    </div>
                    <div class="form-group">
                      <label>Promedio Ponderado:</label>
                      <span>{{ $postulacion->promedio ?? 'No registrado' }}</span>
                    </div>
                    <div class="form-group">
                      <label>Fecha de Postulación:</label>
                      <span>{{ $postulacion->fecha_postulacion }}</span>
                    </div>
                    <div class="form-group">
                      <label>Categoría Sisben:</label>
                      <span>{{ $postulacion->sisben->letra ?? '' }}{{ $postulacion->sisben->numero ?? '' }}</span>
                    </div>
                    <div class="form-group">
                      <label>Colegio Público:</label>
                      <span>{{ $postulacion->colegio_publico ? 'Sí' : 'No' }}</span>
                    </div>
                    <div class="form-group">
                      <label>Madre Cabeza de Familia:</label>
                      <span>{{ $postulacion->madre_cabeza_familia ? 'Sí' : 'No' }}</span>
                    </div>
                    <div class="form-group">
                      <label>Discapacidad:</label>
                      <span>{{ $postulacion->discapacidad ? 'Sí' : 'No' }}</span>
                      @if($postulacion->discapacidad)
                        <br><strong>Tipo:</strong> {{ $postulacion->tipo_discapacidad }}
                      @endif
                    </div>
                    <div class="form-group">
                      <label>Horas Sociales:</label>
                      <span>{{ $postulacion->horas_sociales ? 'Sí' : 'No' }}</span>
                      @if($postulacion->horas_sociales)
                        <br><strong>Cantidad:</strong> {{ $postulacion->cantidad_horas_sociales }}
                      @endif
                    </div>
                    <div class="form-group">
                      <label>Víctima de Conflicto:</label>
                      <span>{{ $postulacion->victima_conflicto ? 'Sí' : 'No' }}</span>
                    </div>
                    <div class="form-group">
                      <label>Declaración Juramentada:</label>
                      <span>{{ $postulacion->declaracion_juramentada ? 'Sí' : 'No' }}</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Documentos Cargados:</label>
                    <ul>
                      @foreach($postulacion->documentos as $documento)
                        <li>
                          <strong>ID:</strong> {{ $documento->id }} |
                          <strong>Tipo:</strong> {{ ucfirst(str_replace('_', ' ', $documento->tipo_documento)) }} |
                          <strong>Ruta:</strong> {{ $documento->ruta }}
                          <a href="{{ route('secure.download', ['file' => $documento->ruta]) }}" target="_blank" class="btn btn-outline-primary btn-sm ms-2" onclick="event.preventDefault(); window.open(this.href, '_blank', 'width=800,height=600,toolbar=no,menubar=no,location=no');">Ver</a>
                          <button type="button" class="btn btn-outline-success btn-sm ms-1" onclick="window.open('{{ route('secure.download', ['file' => $documento->ruta]) }}', '_blank', 'width=800,height=600,toolbar=no,menubar=no,location=no');">Verificar</button>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                  <div class="form-actions">
                    <form method="POST" action="{{ route('evaluador.seleccionarBeneficiario', $postulacion->id_postulacion) }}" style="display:inline">
                      @csrf
                      <button type="submit" class="btn-submit">Seleccionar como Beneficiario</button>
                    </form>
                    @php
                      $resultado = \App\Models\Resultado::where('id_postulacion', $postulacion->id_postulacion)->first();
                    @endphp
                    @if($resultado)
                      <div class="mt-2">
                        <span class="badge bg-info">Puntaje: {{ $resultado->puntaje_total }}</span>
                        <span class="badge {{ $resultado->aprobado ? 'bg-success' : 'bg-danger' }} ms-2">
                          {{ $resultado->aprobado ? 'Aprobado' : 'No aprobado' }}
                        </span>
                      </div>
                    @else
                      <div class="mt-2">
                        <span class="badge bg-info">Puntaje: {{ $puntaje_preview }}</span>
                        <span class="badge {{ $puntaje_preview >= 60 ? 'bg-success' : 'bg-danger' }} ms-2">
                          {{ $puntaje_preview >= 60 ? 'Aprobado' : 'No aprobado' }}
                        </span>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
              @endif
            @empty
              <div class="alert alert-info text-center">No hay postulaciones registradas.</div>
            @endforelse
          </div>
        </section>
      </div>

      <div class="card" id="card-beneficiarios">
        <section id="beneficiarios" class="section collapsed">
          <div class="section-header" onclick="toggleSection('beneficiarios')">
            <h3>Beneficiarios Seleccionados</h3>
          </div>
          <div class="section-body">
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
        </section>
      </div>
    </main>
  </div>

  <script>
    function toggleSection(id) {
      const section = document.getElementById(id);
      document.querySelectorAll('.section').forEach(s => {
        if (s.id !== id) {
          s.classList.add('collapsed');
        }
      });
      section.classList.toggle('collapsed');
      if (!section.classList.contains('collapsed')) {
        setTimeout(() => {
          section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
      }
    }
  </script>
</body>
</html>
@endsection