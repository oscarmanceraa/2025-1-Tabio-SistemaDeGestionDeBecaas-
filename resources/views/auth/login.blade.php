<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGBTABIO - Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/login.css')}}">
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
                        <h2 class="text-center mb-4">Inicio de Sesión</h2>
                        
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="entradas-de-texto-govco">
                                <label for="username">Nombre de Usuario<span aria-required="true">*</span></label>
                                <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Ingrese su nombre de usuario" required autofocus />
                            </div>

                            <div class="entradas-de-texto-govco">
                                <label for="password">Contraseña<span aria-required="true">*</span></label>
                                <div class="container-input-texto-govco">
                                    <input type="password" name="password" id="password" placeholder="Ingrese su contraseña" required />
                                    <button type="button" class="icon-entradas-de-texto-govco eye-entradas-de-texto-govco none" aria-label="Ocultar contraseña"></button>   
                                    <button type="button" class="icon-entradas-de-texto-govco eye-slash-entradas-de-texto-govco" aria-label="Mostrar contraseña"></button>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Recordarme
                                </label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn-govco fill-btn-govco">
                                    Iniciar Sesión
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                @if (Route::has('password.request'))
                                    <a class="govco-link" href="{{ route('password.request') }}">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                @endif
                                <p class="mt-2">¿No tienes una cuenta? <a href="{{ route('register') }}" class="govco-link">Regístrate aquí</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        </div>
    </div>
</body>
</html>
