<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\EvaluadorController;
use App\Http\Controllers\PostulacionController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminEvaluadorController;
use App\Http\Controllers\ConvocatoriaController;
use App\Http\Controllers\UserDocumentController;

// Redirigir la raíz según si el usuario está logueado o no
Route::get('/', function () {
    if (Auth::check()) {
        $rol = Auth::user()->id_rol;
        switch ($rol) {
            case 1: return redirect()->route('admin.dashboard');
            case 2: return redirect()->route('user.dashboard');
            case 3: return redirect()->route('evaluador.dashboard');
        }
    }
    return redirect()->route('login');
});

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Rutas de Evaluador
    Route::middleware('role:3')->prefix('evaluador')->name('evaluador.')->group(function () {
        Route::get('/dashboard', [EvaluadorController::class, 'dashboard'])->name('dashboard');
        Route::post('/toggle-convocatoria', [EvaluadorController::class, 'toggleConvocatoria'])->name('toggleConvocatoria');
        Route::post('/seleccionar-beneficiario/{id_postulacion}', [EvaluadorController::class, 'seleccionarBeneficiario'])->name('seleccionarBeneficiario');
        Route::patch('/documentos/{id}/verificar', [DocumentoController::class, 'verificar'])->name('verificarDocumento');
        
        // Nueva ruta para crear convocatoria activa
        Route::get('/crear-convocatoria-activa', [ConvocatoriaController::class, 'crearConvocatoriaActiva'])
            ->name('crearConvocatoriaActiva');
    });

    // Rutas de Usuario
    Route::middleware('role:2')->group(function () {
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/dashboard', [UserDashboardController::class, 'dashboard'])->name('dashboard');
        });
        
        // Rutas de Postulaciones
        Route::prefix('postulaciones')->name('postulaciones.')->group(function () {
            Route::middleware(\App\Http\Middleware\CheckConvocatoriaAbierta::class)->group(function () {
                Route::get('/crear', [PostulacionController::class, 'create'])->name('create');
                Route::post('/', [PostulacionController::class, 'store'])->name('store');
            });
        });
    });

    // Rutas de Administrador
    Route::middleware('role:1')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        // Gestión de Evaluadores
        Route::get('/evaluadores', [AdminEvaluadorController::class, 'index'])->name('evaluadores.index');
        Route::post('/evaluadores', [AdminEvaluadorController::class, 'store'])->name('evaluadores.store');
        Route::delete('/evaluadores/{id}', [AdminEvaluadorController::class, 'destroy'])->name('evaluadores.destroy');
    });

    // Ruta para obtener programas por universidad (AJAX)
    Route::get('/api/universidades/{id_universidad}/programas', [PostulacionController::class, 'getProgramasByUniversidad']);

    // Rutas para documentos y carga de archivos
    Route::get('/documentos/{file}', function ($file) {
        // Permitir tanto 'archivos/filename.pdf' como 'filename.pdf'
        $filename = $file;
        if (strpos($filename, 'archivos/') === 0) {
            $filename = substr($filename, strlen('archivos/'));
        }
        $path = storage_path('app/private/archivos/' . $filename);
        
        // DEBUG: Mostrar información de autenticación y permisos
        if (!Auth::check()) {
            abort(403, 'No autenticado. Usuario no logueado.');
        }
        
        // Protección: solo evaluadores o el dueño del documento pueden ver
        $user = Auth::user();
        $isEvaluator = ($user->id_rol ?? null) == 3;
        $isOwner = false;
        
        // Buscar si el usuario es dueño del documento
        $documento = \App\Models\DocumentosPostulacion::where('ruta', $file)
            ->orWhere('ruta', 'archivos/' . $file)
            ->first();
            
        if ($documento) {
            $postulacion = $documento->postulacion;
            if ($postulacion && $postulacion->id_usuario == $user->id_user) {
                $isOwner = true;
            }
        }
        
        if (!$isEvaluator && !$isOwner) {
            abort(403, 'No autorizado. Solo evaluadores o dueño del documento.');
        }
        
        if (!file_exists($path)) {
            abort(404, 'Archivo no encontrado: ' . $path);
        }
        
        return response()->file($path, [
            'Content-Type' => 'application/pdf'
        ]);
    })->where('file', '.*')->name('secure.download');

    Route::post('/user/upload', [UserDocumentController::class, 'store'])->name('user.upload');
});
