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
        <input type="checkbox" class="section-checkbox" />
        <section id="terminos" class="section collapsed">
          <div class="section-header">
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
        <input type="checkbox" class="section-checkbox" />
        <section id="caracterizacion" class="section collapsed">
          <div class="section-header">
            <h3>Caracterización de Usuario</h3>
          </div>
          <div class="section-body">
            <h5>Subheading</h5>
            <p>Texto de ejemplo para la caracterización del usuario.</p>
           
              <div class="form-grid">
                <input type="text" placeholder="Primer Nombre" />
                <input type="text" placeholder="Segundo Nombre" />
                <input type="text" placeholder="Primer Apellido" />
                <input type="text" placeholder="Segundo Apellido" />
                <select>
                  <option>Tipo de Documento</option>
                  <option>Cédula</option>
                  <option>Tarjeta de Identidad</option>
                </select>
                <input type="text" placeholder="Número de Documento" />
                <input type="date" placeholder="Fecha de Expedición" />
                <input type="text" placeholder="Lugar de Expedición" />
                <input type="date" placeholder="Fecha de Nacimiento" />
                <input type="text" placeholder="Lugar de Nacimiento" />
                <input type="text" placeholder="Categoría Sisbén" />
                <input type="text" placeholder="Certificado Sisbén" />
                <input type="text" placeholder="Acta de Grado"/> 
                <input type="text" placeholder="Institución Educativa" />
                <input type="text" placeholder="Programa Académico" />
                <input type="text" placeholder="Certificado de Notas" />
                <select>
                  <option>Grupo de Interés</option>
                  <option>Jóvenes</option>
                  <option>Discapacidad</option>
                </select>
                <input type="text" placeholder="Certificación de Discapacidad" />
              </div>
            
        </section>
      </div>

      <div class="card" id="card-documentos">
        <input type="checkbox" class="section-checkbox" />
        <section id="documentos" class="section collapsed">
          <div class="section-header">
            <h3>Comprobación de Documentos</h3>
          </div>
          <div class="section-body">
            <h5>Subheading</h5>
            <p>Texto de ejemplo para la comprobación de documentos.</p>
          </div>
        </section>
      </div>

      <div class="card" id="card-respuesta">
        <input type="checkbox" class="section-checkbox" />
        <section id="respuesta" class="section collapsed">
          <div class="section-header">
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
      section.classList.toggle('collapsed');
      section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  </script>
</body>
</html>


@endsection