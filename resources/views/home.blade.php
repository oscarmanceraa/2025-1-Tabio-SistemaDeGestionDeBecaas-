@extends('layouts.app')

@section('content')
<nav class="navbar navbar-expand-lg barra-superior-govco">
    <div class="container">
        <a class="navbar-brand" href="#">SGBTABIO</a>
        <div class="ml-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-govco outline-btn-govco">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h2>Bienvenido, {{ Auth::user()->username }}</h2>
            <p>Has iniciado sesión correctamente.</p>
        </div>
    </div>
</div>
@endsection