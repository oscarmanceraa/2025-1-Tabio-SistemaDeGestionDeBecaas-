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

    // Rutas de Evaluador
    Route::get('/evaluador/dashboard', function () {
        return view('evaluador.dashboard');
    })->middleware('role:3')->name('evaluador.dashboard');
});
