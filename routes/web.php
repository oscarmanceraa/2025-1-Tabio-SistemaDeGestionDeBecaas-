<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Redirigir la raíz según si el usuario está logueado o no
Route::get('/', function () {
    if (Auth::check()) {
        $rol = Auth::user()->id_rol;

        switch ($rol) {
            case 1:
                return redirect()->route('admin.dashboard');
            case 2:
                return redirect()->route('user.dashboard');
            case 3:
                return redirect()->route('evaluador.dashboard');
        }
    }

    return redirect()->route('login');
});

// Rutas de autenticación (solo para invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/register', [RegisterController::class, 'register']);

// Rutas protegidas por autenticación y rol
Route::middleware(['auth'])->group(function () {
    // Rutas de Administrador
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('role:1')->name('admin.dashboard');

    // Rutas de Usuario
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->middleware('role:2')->name('user.dashboard');

    // Rutas de Postulaciones para Usuarios
    Route::middleware('role:2')->group(function () {
        Route::get('/postulaciones/crear', [App\Http\Controllers\PostulacionController::class, 'create'])->name('postulaciones.create');
        Route::post('/postulaciones', [App\Http\Controllers\PostulacionController::class, 'store'])->name('postulaciones.store');
    });

    // Ruta para obtener programas por universidad (AJAX)
    Route::get('/api/universidades/{id_universidad}/programas', [App\Http\Controllers\PostulacionController::class, 'getProgramasByUniversidad']);

    // Rutas de Evaluador
    Route::get('/evaluador/dashboard', [\App\Http\Controllers\EvaluadorController::class, 'dashboard'])->middleware('role:3')->name('evaluador.dashboard');
    Route::post('/evaluador/seleccionar-beneficiario/{id_postulacion}', [\App\Http\Controllers\EvaluadorController::class, 'seleccionarBeneficiario'])->middleware('role:3')->name('evaluador.seleccionarBeneficiario');

    // Rutas para documentos y carga de archivos
    Route::get('/documentos/{file}', function ($file) {
        // Aquí deberías implementar la lógica real de descarga segura
        return view('evaluador.dashboard');
    })->name('secure.download')->middleware('can:view-document');

    Route::post('/user/upload', [\App\Http\Controllers\UserDocumentController::class, 'store'])->name('user.upload');
});
