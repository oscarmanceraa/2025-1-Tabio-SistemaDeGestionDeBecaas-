@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGBTABIO - Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/register.css')}}">
</head>
<body>
    <nav class="navbar navbar-expand-lg barra-superior-govco" aria-label="Barra superior">
        <a href="https://www.gov.co/" target="_blank" aria-label="Portal del Estado Colombiano - GOV.CO"></a>
        <button class="idioma-icon-barra-superior-govco float-right" aria-label="Button to change the language of the page to English"></button>
    </nav>
    <div class="container-logo-header-govco">
        <span class="logo-header-govco"></span>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Registro de Usuario</h2>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <!-- Nombres -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="entradas-de-texto-govco">
                                        <label for="primer_nombre">Primer Nombre<span aria-required="true">*</span></label>
                                        <input type="text" name="primer_nombre" id="primer_nombre" placeholder="Ingrese su primer nombre" required/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="entradas-de-texto-govco">
                                        <label for="segundo_nombre">Segundo Nombre</label>
                                        <input type="text" name="segundo_nombre" id="segundo_nombre" placeholder="Ingrese su segundo nombre"/>
                                    </div>
                                </div>
                            </div>

                            <!-- Apellidos -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="entradas-de-texto-govco">
                                        <label for="primer_apellido">Primer Apellido<span aria-required="true">*</span></label>
                                        <input type="text" name="primer_apellido" id="primer_apellido" placeholder="Ingrese su primer apellido" required/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="entradas-de-texto-govco">
                                        <label for="segundo_apellido">Segundo Apellido</label>
                                        <input type="text" name="segundo_apellido" id="segundo_apellido" placeholder="Ingrese su segundo apellido"/>
                                    </div>
                                </div>
                            </div>

                            <!-- Documento -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="entradas-de-texto-govco">
                                        <label for="id_tipo_documento">Tipo de Documento<span aria-required="true">*</span></label>
                                        <div class="container-input-texto-govco">
                                            <select class="form-select" name="id_tipo_documento" id="id_tipo_documento" required>
                                                <option value="" disabled selected>Seleccione tipo de documento</option>
                                                <option value="1">Cédula de Ciudadanía</option>
                                                <option value="2">Cédula de Extranjería</option>
                                                <option value="3">Tarjeta de Identidad</option>
                                                <option value="4">Pasaporte</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="entradas-de-texto-govco">
                                        <label for="numero_documento">Número de Documento<span aria-required="true">*</span></label>
                                        <input type="text" name="numero_documento" id="numero_documento" placeholder="Ingrese su número de documento" required/>
                                    </div>
                                </div>
                            </div>

                            <!-- Fecha de Expedición -->
                            <div class="entradas-de-texto-govco">
                                <label for="fecha_exp_documento">Fecha de Expedición<span aria-required="true">*</span></label>
                                <input type="date" name="fecha_exp_documento" id="fecha_exp_documento" required/>
                            </div>

                            <!-- Dirección -->
                            <div class="entradas-de-texto-govco">
                                <label for="direccion">Dirección<span aria-required="true">*</span></label>
                                <input type="text" name="direccion" id="direccion" placeholder="Ingrese su dirección" required/>
                            </div>

                            <!-- Email -->
                            <div class="entradas-de-texto-govco">
                                <label for="email">Correo Electrónico<span aria-required="true">*</span></label>
                                <input type="email" name="email" id="email" placeholder="ejemplo@correo.com" required/>
                            </div>

                            <!-- Usuario -->
                            <div class="entradas-de-texto-govco">
                                <label for="username">Nombre de Usuario<span aria-required="true">*</span></label>
                                <input type="text" name="username" id="username" placeholder="Ingrese su nombre de usuario" required/>
                            </div>

                            <!-- Contraseña -->
                            <div class="entradas-de-texto-govco">
                                <label for="password">Contraseña<span aria-required="true">*</span></label>
                                <div class="container-input-texto-govco">
                                    <input type="password" name="password" id="password" placeholder="Ingrese su contraseña" required minlength="8"/>
                                    <button type="button" class="icon-entradas-de-texto-govco eye-entradas-de-texto-govco none" aria-label="Ocultar contraseña"></button>   
                                    <button type="button" class="icon-entradas-de-texto-govco eye-slash-entradas-de-texto-govco" aria-label="Mostrar contraseña"></button>
                                </div>
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div class="entradas-de-texto-govco">
                                <label for="password_confirmation">Confirmar Contraseña<span aria-required="true">*</span></label>
                                <div class="container-input-texto-govco">
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirme su contraseña" required minlength="8"/>
                                    <button type="button" class="icon-entradas-de-texto-govco eye-entradas-de-texto-govco none" aria-label="Ocultar contraseña"></button>   
                                    <button type="button" class="icon-entradas-de-texto-govco eye-slash-entradas-de-texto-govco" aria-label="Mostrar contraseña"></button>
                                </div>
                            </div>

                            <!-- Botón de registro -->
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn-govco fill-btn-govco">Registrarse</button>
                            </div>

                            <!-- Link a login -->
                            <div class="text-center mt-3">
                                <p>¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="govco-link">Inicia sesión aquí</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="govco-footer mt-5">
        <div class="row govco-portales-contenedor m-0">
            <div class="col-4 govco-footer-logo-portal">
                <div class="govco-logo-container-portal">
                    <span class="govco-logo"></span>
                    <span class="govco-separator"></span>
                    <span class="govco-co"></span>
                </div>
            </div>
            <div class="col-4 govco-info-datos-portal">
                <div class="govco-separator-rows"></div>
                <div class="govco-texto-datos-portal">
                    <p class="govco-text-header-portal-1">
                        Nombre completo del portal
                    </p>
                    <p>Dirección: xxxxxx xxx xxx Departamento y municipio. <br>
                        Código Postal: xxxx <br>
                        Horario de atención: Lunes a viernes xx:xx a.m. - xx:xx p.m.</p>
                </div>
                <div class="govco-network extramt-network">
                    <div class="govco-iconContainer">
                        <span class="icon-portal govco-twitter-square"></span>
                        <span class="govco-link-portal">@Entidad</span>
                    </div>
                    <div class="govco-iconContainer">
                        <span class="icon-portal govco-instagram-square"></span>
                        <span class="govco-link-portal">@Entidad</span>
                    </div>
                    <div class="govco-iconContainer">
                        <span class="icon-portal govco-facebook-square"></span>
                        <span class="govco-link-portal">@Entidad</span>
                    </div>
                </div>
            </div>

            <div class="col-4 govco-info-telefonos">
                <div class="govco-separator-rows"></div>
                <div class="govco-texto-telefonos">
                    <p class="govco-text-header-portal-1">
                        <span class="govco-phone-alt"></span>
                        Contacto
                    </p>
                    <p>Teléfono conmutador: <br>
                        +57(xx) xxx xx xx <br>
                        Línea gratuita: 01-800-xxxxxxxx <br>
                        Línea anticorrupción: 01-800-xxxxxxxx <br>
                        Correo institucional: <br>
                        entidad@entidad.gov.co</p>
                </div>

                <div class="govco-links-portal-container">
                    <div class="col-12 m-0 mt-2">
                        <a class="govco-link-portal" href="#">Políticas</a>
                        <a class="govco-link-portal" href="#">Mapa del sitio</a>
                    </div>
                    <div class="col-12 m-0 mt-2">
                        <a class="govco-link-portal" href="#">Términos y condiciones</a> <br>
                    </div>
                    <div class="col-12 m-0 mt-2">
                        <a class="govco-link-portal" href="#">Accesibilidad</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection