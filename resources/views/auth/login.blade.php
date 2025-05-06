@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
        <h1>Inicio de Sesión</h1>
                    
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

                        <div>
                            <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Nombre de Usuario" required autofocus>
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
                            <p>¿No tienes una cuenta? <a href="{{ route('register') }}" class="govco-link">Regístrate aquí</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
