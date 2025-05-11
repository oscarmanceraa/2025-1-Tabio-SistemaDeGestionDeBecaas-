<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Redirigir la raíz al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas de registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rutas protegidas por rol
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
    Route::middleware(['role:2'])->group(function () {
        Route::get('/postulaciones/crear', [App\Http\Controllers\PostulacionController::class, 'create'])->name('postulaciones.create');
        Route::post('/postulaciones', [App\Http\Controllers\PostulacionController::class, 'store'])->name('postulaciones.store');
    });
    
    // Ruta para obtener programas por universidad (AJAX)
    Route::get('/api/universidades/{id_universidad}/programas', [App\Http\Controllers\PostulacionController::class, 'getProgramasByUniversidad']);

    // Rutas de Evaluador
    Route::get('/evaluador/dashboard', function () {
        return view('evaluador.dashboard');
    })->middleware('role:3')->name('evaluador.dashboard');

    Route::middleware(['auth'])->group(function () {
        Route::get('/documentos/{file}', function ($file) {
            return view('evaluador.dashboard');
        })->name('secure.download')->middleware('can:view-document');
        Route::post('/user/upload', [UserDocumentController::class, 'store'])->name('user.upload');
    });
});
