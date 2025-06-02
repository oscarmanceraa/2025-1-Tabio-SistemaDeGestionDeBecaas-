@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

      @if($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Panel de Control --}}
      <div class="control-panel mb-4">
        <div class="row">
          <div class="col-md-6">
            <div class="stats-card">
              <h4>Estadísticas Generales</h4>
              <div class="stats-grid">
                <div class="stat-item">
                  <span class="stat-label">Total Postulaciones:</span>
                  <span class="stat-value">{{ $postulaciones->count() }}</span>
                </div>
                <div class="stat-item">
                  <span class="stat-label">Total Beneficiarios:</span>
                  <span class="stat-value">{{ \App\Models\Beneficiario::count() }}</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="convocatory-control">
              <h4>Control de Convocatoria</h4>
              @php
                $convocatoriaActiva = \App\Models\Convocatoria::where('activa', true)
                    ->where('fecha_inicio', '<=', now())
                    ->where('fecha_fin', '>=', now())
                    ->first();
              @endphp
              <form id="toggleConvocatoriaForm" method="POST">
                @csrf
                @if($convocatoriaActiva)
                  <input type="hidden" name="action" value="cerrar">
                  <button type="submit" class="btn btn-danger" id="toggleButton">
                    Cerrar Convocatoria
                  </button>
                @else
                  <input type="hidden" name="action" value="abrir">
                  <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Convocatoria</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                  </div>
                  <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                  </div>
                  <div class="mb-3">
                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                  </div>
                  <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                  </div>
                  <button type="submit" class="btn btn-success" id="toggleButton">
                    Abrir Nueva Convocatoria
                  </button>
                @endif
              </form>

              <div id="loadingSpinner" style="display: none;" class="mt-2">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Cargando...</span>
                </div>
              </div>

              @if($convocatoriaActiva)
                <div class="convocatoria-info mt-3">
                  <p><strong>Convocatoria actual:</strong> {{ $convocatoriaActiva->nombre }}</p>
                  <p><strong>Fecha inicio:</strong> {{ \Carbon\Carbon::parse($convocatoriaActiva->fecha_inicio)->format('d/m/Y') }}</p>
                  <p><strong>Fecha fin:</strong> {{ \Carbon\Carbon::parse($convocatoriaActiva->fecha_fin)->format('d/m/Y') }}</p>
                  @if($convocatoriaActiva->descripcion)
                    <p><strong>Descripción:</strong> {{ $convocatoriaActiva->descripcion }}</p>
                  @endif
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="card" id="card-postulaciones">
        <section id="postulaciones" class="section">
          <div class="section-header" onclick="toggleSection('postulaciones')">
            <h3>Postulaciones</h3>
            <span class="badge bg-primary">{{ $postulaciones->count() }}</span>
          </div>
          <div class="section-body">
            @forelse($postulaciones as $postulacion)
              @if(!$postulacion->beneficiario)
                <div class="card mb-4 postulacion-card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                      <span class="postulacion-title">Postulación #{{ $postulacion->id_postulacion }}</span>
                      <span class="postulacion-name">{{ $postulacion->persona->primer_nombre }} {{ $postulacion->persona->primer_apellido }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge bg-primary">{{ $postulacion->tipoBeneficio->nombre }}</span>
                      <button class="btn btn-outline-primary btn-sm toggle-details" 
                              onclick="toggleDetallesPostulacion('detalles-postulacion-{{ $postulacion->id_postulacion }}')"
                              data-target="detalles-postulacion-{{ $postulacion->id_postulacion }}">
                        <i class="bi bi-eye"></i> Ver Detalles
                      </button>
                    </div>
                  </div>
                  <div class="detalles-postulacion" id="detalles-postulacion-{{ $postulacion->id_postulacion }}" style="display: none;">
                    <div class="row">
                      <div class="col-md-6">
                        <h5>Información Personal</h5>
                        <ul class="list-unstyled">
                          <li><strong>Documento:</strong> {{ $postulacion->persona->numero_documento }}</li>
                          <li><strong>Dirección:</strong> {{ $postulacion->persona->direccion }}</li>
                        </ul>
                      </div>
                      <div class="col-md-6">
                        <h5>Información Académica</h5>
                        <ul class="list-unstyled">
                          <li><strong>Universidad:</strong> {{ $postulacion->universidad->nombre }}</li>
                          <li><strong>Programa:</strong> {{ $postulacion->programa->nombre }}</li>
                          <li><strong>Promedio:</strong> {{ $postulacion->promedio }}</li>
                        </ul>
                      </div>
                    </div>

                    <div class="documentos-list mt-4">
                      <h5>Documentos</h5>
                      @foreach($postulacion->documentos as $documento)
                        <div class="documento-item">
                          <div class="documento-info">
                            <span class="documento-tipo">{{ ucfirst(str_replace('_', ' ', $documento->tipo_documento)) }}</span>
                            <span class="badge {{ $documento->verificado ? 'bg-success' : 'bg-warning' }}">
                              {{ $documento->verificado ? 'Verificado' : 'Pendiente' }}
                            </span>
                          </div>
                          <div class="documento-actions">
                            <a href="{{ route('secure.download', $documento->ruta) }}" class="btn btn-sm btn-info" onclick="return openDocumentModal(this.href)">
                              <i class="bi bi-file-earmark-pdf"></i> Ver
                            </a>
                            @if(!$documento->verificado)
                              <button type="button" class="btn btn-sm btn-success" onclick="verifyDocument({{ $documento->id }}, this)">
                                <i class="bi bi-check-lg"></i> Verificar
                              </button>
                            @endif
                          </div>
                        </div>
                      @endforeach
                    </div>

                    <div class="actions mt-4">
                      <form method="POST" action="{{ route('evaluador.seleccionarBeneficiario', $postulacion->id_postulacion) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                          <i class="bi bi-check-circle"></i> Seleccionar como Beneficiario
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              @endif
            @empty
              <div class="alert alert-info">
                No hay postulaciones pendientes de evaluación.
              </div>
            @endforelse
          </div>
        </section>
      </div>

      <div class="card" id="card-beneficiarios">
        <section id="beneficiarios" class="section collapsed">
          <div class="section-header" onclick="toggleSection('beneficiarios')">
            <h3>Beneficiarios</h3>
            <span class="badge bg-success">{{ \App\Models\Beneficiario::count() }}</span>
          </div>
          <div class="section-body">
            <!-- Resumen estadístico -->
            <div class="beneficiarios-stats mb-4">
              <h4>Resumen de Beneficiarios</h4>
              <div class="stats-grid">
                <div class="stat-item">
                  <span class="stat-label">Total Beneficiarios</span>
                  <span class="stat-value">{{ \App\Models\Beneficiario::count() }}</span>
                </div>
                @php
                  $tiposBeneficio = \App\Models\TipoBeneficio::withCount(['postulaciones' => function($query) {
                    $query->whereHas('beneficiario');
                  }])->get();
                @endphp
                @foreach($tiposBeneficio as $tipo)
                  <div class="stat-item">
                    <span class="stat-label">{{ $tipo->nombre }}</span>
                    <span class="stat-value">{{ $tipo->postulaciones_count }}</span>
                  </div>
                @endforeach
              </div>
            </div>

            <!-- Filtros -->
            <div class="beneficiarios-filters mb-4">
              <div class="row">
                <div class="col-md-6">
                  <label for="filterTipoBeneficio" class="form-label">Filtrar por Tipo de Beneficio:</label>
                  <select id="filterTipoBeneficio" class="form-select">
                    <option value="">Todos los tipos</option>
                    @foreach($tiposBeneficio as $tipo)
                      <option value="{{ $tipo->id_tipo_beneficio }}">{{ $tipo->nombre }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="searchBeneficiario" class="form-label">Buscar por nombre o documento:</label>
                  <input type="text" id="searchBeneficiario" class="form-control" placeholder="Ingrese nombre o documento...">
                </div>
              </div>
            </div>

            <!-- Lista de Beneficiarios -->
            <div class="beneficiarios-list">
              @foreach($postulaciones as $postulacion)
                @if($postulacion->beneficiario)
                  <div class="beneficiario-item" data-tipo-beneficio="{{ $postulacion->id_tipo_beneficio }}">
                    <div class="beneficiario-header d-flex justify-content-between align-items-center">
                      <div>
                        <span class="beneficiario-name">{{ $postulacion->persona->primer_nombre }} {{ $postulacion->persona->segundo_nombre }} {{ $postulacion->persona->primer_apellido }} {{ $postulacion->persona->segundo_apellido }}</span>
                        <span class="badge bg-success">{{ $postulacion->tipoBeneficio->nombre }}</span>
                        @if($postulacion->beneficiario->vigente)
                          <span class="badge bg-primary">Vigente</span>
                        @else
                          <span class="badge bg-secondary">No Vigente</span>
                        @endif
                      </div>
                      <button class="btn btn-outline-primary btn-sm" 
                              onclick="toggleDetallesPostulacion('detalles-beneficiario-{{ $postulacion->id_postulacion }}')">
                        <i class="bi bi-eye"></i> Ver Detalles
                      </button>
                    </div>
                    <div class="detalles-content" id="detalles-beneficiario-{{ $postulacion->id_postulacion }}" style="display: none;">
                      <div class="row">
                        <!-- Información Personal -->
                        <div class="col-md-6">
                          <h5>Información Personal</h5>
                          <ul class="list-unstyled">
                            <li><strong>Documento:</strong> {{ $postulacion->persona->tipoDocumento->nombre }} {{ $postulacion->persona->numero_documento }}</li>
                            <li><strong>Dirección:</strong> {{ $postulacion->persona->direccion }}</li>
                            <li><strong>Fecha Exp. Documento:</strong> {{ \Carbon\Carbon::parse($postulacion->persona->fecha_exp_documento)->format('d/m/Y') }}</li>
                            <li><strong>Categoría Sisben:</strong> {{ $postulacion->sisben->letra }}{{ $postulacion->sisben->numero }}</li>
                          </ul>
                        </div>

                        <!-- Información Académica -->
                        <div class="col-md-6">
                          <h5>Información Académica</h5>
                          <ul class="list-unstyled">
                            <li><strong>Universidad:</strong> {{ $postulacion->universidad->nombre }}</li>
                            <li><strong>Programa:</strong> {{ $postulacion->programa->nombre }}</li>
                            <li><strong>Promedio:</strong> {{ $postulacion->promedio }}</li>
                            <li><strong>Colegio:</strong> {{ $postulacion->nombre_colegio }} ({{ $postulacion->colegio_publico ? 'Público' : 'Privado' }})</li>
                          </ul>
                        </div>

                        <!-- Información del Beneficio -->
                        <div class="col-md-6">
                          <h5>Información del Beneficio</h5>
                          <ul class="list-unstyled">
                            <li><strong>Tipo de Beneficio:</strong> {{ $postulacion->tipoBeneficio->nombre }}</li>
                            <li><strong>Fecha de Inicio:</strong> {{ \Carbon\Carbon::parse($postulacion->beneficiario->fecha_inicio)->format('d/m/Y') }}</li>
                            @if($postulacion->beneficiario->fecha_fin)
                              <li><strong>Fecha de Fin:</strong> {{ \Carbon\Carbon::parse($postulacion->beneficiario->fecha_fin)->format('d/m/Y') }}</li>
                            @endif
                            <li><strong>Estado:</strong> {{ $postulacion->beneficiario->vigente ? 'Vigente' : 'No Vigente' }}</li>
                          </ul>
                        </div>

                        <!-- Información Adicional -->
                        <div class="col-md-6">
                          <h5>Información Adicional</h5>
                          <ul class="list-unstyled">
                            @if($postulacion->horas_sociales)
                              <li><strong>Horas Sociales:</strong> {{ $postulacion->cantidad_horas_sociales }} horas</li>
                              @if($postulacion->obs_horas)
                                <li><strong>Observaciones:</strong> {{ $postulacion->obs_horas }}</li>
                              @endif
                            @endif
                            @if($postulacion->discapacidad)
                              <li><strong>Discapacidad:</strong> {{ $postulacion->tipo_discapacidad }}</li>
                              @if($postulacion->obs_discapacidad)
                                <li><strong>Observaciones:</strong> {{ $postulacion->obs_discapacidad }}</li>
                              @endif
                            @endif
                            @if($postulacion->madre_cabeza_familia)
                              <li><strong>Madre Cabeza de Familia:</strong> Sí</li>
                            @endif
                            @if($postulacion->victima_conflicto)
                              <li><strong>Víctima del Conflicto:</strong> Sí</li>
                            @endif
                          </ul>
                        </div>

                        <!-- Documentos -->
                        <div class="col-12 mt-3">
                          <h5>Documentos</h5>
                          <div class="documentos-grid">
                            @foreach($postulacion->documentos as $documento)
                              <div class="documento-item">
                                <div class="documento-info">
                                  <span class="documento-tipo">{{ ucfirst(str_replace('_', ' ', $documento->tipo_documento)) }}</span>
                                  <span class="badge {{ $documento->verificado ? 'bg-success' : 'bg-warning' }}">
                                    {{ $documento->verificado ? 'Verificado' : 'Pendiente' }}
                                  </span>
                                </div>
                                <div class="documento-actions">
                                  <a href="{{ route('secure.download', $documento->ruta) }}" class="btn btn-sm btn-info" onclick="return openDocumentModal(this.href)">
                                    <i class="bi bi-file-earmark-pdf"></i> Ver
                                  </a>
                                </div>
                              </div>
                            @endforeach
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              @endforeach
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>

  <style>
    .section {
      background-color: #fff;
      margin-bottom: 20px;
    }

    .section-header {
      padding: 15px;
      background-color: #f8f9fa;
      cursor: pointer;
      border-bottom: 1px solid #dee2e6;
    }

    .section-header h3 {
      margin: 0;
      color: #2c3e50;
    }

    .section-body {
      padding: 15px;
    }

    .postulacion-card, .beneficiario-item {
      background: #fff;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      margin-bottom: 1rem;
      overflow: hidden;
    }

    .card-header, .beneficiario-header {
      background-color: #f8f9fa;
      padding: 1rem;
      border-bottom: 1px solid #dee2e6;
    }

    .detalles-postulacion, .detalles-content {
      padding: 1.5rem;
      border-top: 1px solid #dee2e6;
      background-color: #fff;
    }

    .toggle-details {
      transition: all 0.3s ease;
    }

    .toggle-details.active i {
      transform: rotate(180deg);
    }

    .toggle-details:hover {
      background-color: #0d6efd;
      color: white;
    }

    .postulacion-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: #2c3e50;
      margin-right: 1rem;
    }

    .postulacion-name {
      font-size: 1rem;
      color: #34495e;
    }

    .documento-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 5px;
      margin-bottom: 8px;
    }

    .documento-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .documento-tipo {
      font-weight: 500;
      color: #2c3e50;
    }

    .documento-actions {
      display: flex;
      gap: 10px;
    }

    .documentos-list {
      margin-top: 15px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .gap-2 {
      gap: 0.5rem;
    }

    .form-section-title {
      color: #2c3e50;
      margin: 1rem 0;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid #e9ecef;
    }

    .control-panel {
      background-color: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }

    .stats-card, .convocatory-control {
      background-color: #f8f9fa;
      border-radius: 6px;
      padding: 15px;
      height: 100%;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin-top: 10px;
    }

    .stat-item {
      background-color: #fff;
      padding: 10px;
      border-radius: 4px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .stat-label {
      display: block;
      color: #6c757d;
      font-size: 0.9rem;
      margin-bottom: 5px;
    }

    .stat-value {
      display: block;
      font-size: 1.5rem;
      font-weight: bold;
      color: #0d6efd;
    }

    .convocatory-control {
      text-align: center;
    }

    .convocatory-control button {
      margin-top: 10px;
      padding: 10px 20px;
    }

    .beneficiarios-filters {
      margin-bottom: 20px;
    }

    .beneficiarios-filters .btn-group {
      width: 100%;
    }

    .beneficiarios-filters .btn {
      flex: 1;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .section-header .badge {
      font-size: 1rem;
    }

    .modal-dialog,
    .modal-content,
    .modal-body,
    #documentViewer {
      /* Eliminar estos estilos si no se usarán */
    }

    .beneficiarios-stats {
      background-color: #f8f9fa;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
    }

    .documentos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 15px;
      margin-top: 10px;
    }

    .beneficiario-item {
      background: #fff;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      margin-bottom: 1rem;
      overflow: hidden;
    }

    .beneficiario-header {
      background-color: #f8f9fa;
      padding: 1rem;
      border-bottom: 1px solid #dee2e6;
    }

    .beneficiario-name {
      font-size: 1.1rem;
      font-weight: 500;
      margin-right: 10px;
    }

    .detalles-content {
      padding: 1.5rem;
    }

    .documento-item {
      background: #f8f9fa;
      padding: 1rem;
      border-radius: 6px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .documento-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .documento-tipo {
      font-weight: 500;
    }

    .documento-actions {
      display: flex;
      gap: 8px;
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('toggleConvocatoriaForm');
      const loadingSpinner = document.getElementById('loadingSpinner');
      
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        loadingSpinner.style.display = 'block';
        
        fetch('{{ route('evaluador.toggleConvocatoria') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify(Object.fromEntries(new FormData(form)))
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            window.location.reload();
          } else {
            alert(data.message || 'Error al procesar la solicitud');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error al procesar la solicitud');
        })
        .finally(() => {
          loadingSpinner.style.display = 'none';
        });
      });

      // Función para abrir el documento en una ventana emergente
      window.openDocumentModal = function(url) {
        // Configurar dimensiones y opciones de la ventana
        const width = 800;
        const height = 600;
        const left = (screen.width - width) / 2;
        const top = (screen.height - height) / 2;
        
        const options = `
          width=${width},
          height=${height},
          top=${top},
          left=${left},
          location=no,
          menubar=no,
          toolbar=no,
          status=no,
          scrollbars=yes,
          resizable=yes
        `;
        
        window.open(url, 'DocumentViewer', options);
        return false; // Prevenir la navegación en la ventana actual
      }

      // Función para verificar documento con AJAX
      window.verifyDocument = function(documentId, button) {
        // Mostrar spinner de carga en el botón
        const originalContent = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Verificando...';
        button.disabled = true;

        // Construir la URL usando la ruta base
        const url = '{{ url("/evaluador/documentos") }}/' + documentId + '/verificar';

        fetch(url, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Actualizar la interfaz
            const documentoItem = button.closest('.documento-item');
            const badge = documentoItem.querySelector('.badge');
            badge.classList.remove('bg-warning');
            badge.classList.add('bg-success');
            badge.textContent = 'Verificado';
            
            // Remover el botón
            button.remove();

            // Mostrar notificación de éxito
            const alert = document.createElement('div');
            alert.className = 'alert alert-success mt-2';
            alert.textContent = data.message;
            documentoItem.appendChild(alert);

            // Remover la alerta después de 3 segundos
            setTimeout(() => alert.remove(), 3000);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          button.innerHTML = originalContent;
          button.disabled = false;
          
          // Mostrar mensaje de error
          const alert = document.createElement('div');
          alert.className = 'alert alert-danger mt-2';
          alert.textContent = 'Error al verificar el documento';
          button.parentNode.appendChild(alert);
          
          // Remover la alerta después de 3 segundos
          setTimeout(() => alert.remove(), 3000);
        });
      }

      // Actualizar los enlaces de documentos y botones de verificación
      document.querySelectorAll('.documento-item').forEach(item => {
        const verLink = item.querySelector('a[href*="secure.download"]');
        if (verLink) {
          verLink.onclick = function(e) {
            e.preventDefault();
            return openDocumentModal(this.href);
          };
        }

        const verifyForm = item.querySelector('form[action*="verificarDocumento"]');
        if (verifyForm) {
          verifyForm.onsubmit = function(e) {
            e.preventDefault();
            const documentId = this.action.split('/').pop();
            verifyDocument(documentId, this.querySelector('button'));
          };
        }
      });

      // Filtrado de beneficiarios
      const filterTipoBeneficio = document.getElementById('filterTipoBeneficio');
      const searchBeneficiario = document.getElementById('searchBeneficiario');
      const beneficiarioItems = document.querySelectorAll('.beneficiario-item');

      function filterBeneficiarios() {
        const tipoBeneficio = filterTipoBeneficio.value;
        const searchText = searchBeneficiario.value.toLowerCase();

        beneficiarioItems.forEach(item => {
          const matchesTipo = !tipoBeneficio || item.dataset.tipoBeneficio === tipoBeneficio;
          const beneficiarioText = item.textContent.toLowerCase();
          const matchesSearch = !searchText || beneficiarioText.includes(searchText);

          item.style.display = matchesTipo && matchesSearch ? 'block' : 'none';
        });
      }

      filterTipoBeneficio.addEventListener('change', filterBeneficiarios);
      searchBeneficiario.addEventListener('input', filterBeneficiarios);
    });

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

    function toggleDetallesPostulacion(id) {
      const detalles = document.getElementById(id);
      const button = document.querySelector(`[data-target="${id}"]`);
      const allDetalles = document.querySelectorAll('.detalles-postulacion, .detalles-content');
      const allButtons = document.querySelectorAll('.toggle-details');
      
      // Cerrar todos los demás detalles
      allDetalles.forEach(d => {
        if (d.id !== id) {
          d.style.display = 'none';
        }
      });
      
      // Resetear todos los botones
      allButtons.forEach(b => {
        if (b !== button) {
          b.classList.remove('active');
          const icon = b.querySelector('i');
          if (icon) {
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
          }
        }
      });
      
      // Toggle el detalle seleccionado
      if (detalles.style.display === 'none') {
        detalles.style.display = 'block';
        if (button) {
          button.classList.add('active');
          const icon = button.querySelector('i');
          if (icon) {
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
          }
        }
      } else {
        detalles.style.display = 'none';
        if (button) {
          button.classList.remove('active');
          const icon = button.querySelector('i');
          if (icon) {
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
          }
        }
      }
    }
  </script>
</body>
</html>
@endsection