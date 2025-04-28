<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGBTABIO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/login.css')}}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg barra-superior-govco" aria-label="Barra superior">
        <a href="https://www.gov.co/" target="_blank" aria-label="Portal del Estado Colombiano - GOV.CO"></a>
        <button class="idioma-icon-barra-superior-govco float-right" aria-label="Button to change the language of the page to English"></button>
    </nav>
    <div class="container-logo-header-govco">
        <span class="logo-header-govco"></span>
    </div>

    @yield('content')

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
                        Sistema de Gestión de Becas Tabio
                    </p>
                    <p>Dirección: Calle 7 # 6-31, Tabio - Cundinamarca<br>
                        Código Postal: 251580<br>
                        Horario de atención: Lunes a viernes 8:00 a.m. - 5:00 p.m.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
