// Rutas para gestión de evaluadores por el admin
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/admin/evaluadores', [App\Http\Controllers\AdminEvaluadorController::class, 'index'])->name('admin.evaluadores.index');
    Route::post('/admin/evaluadores', [App\Http\Controllers\AdminEvaluadorController::class, 'store'])->name('admin.evaluadores.store');
    Route::delete('/admin/evaluadores/{id}', [App\Http\Controllers\AdminEvaluadorController::class, 'destroy'])->name('admin.evaluadores.destroy');
});
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
    Route::get('/user/dashboard', [App\Http\Controllers\UserDashboardController::class, 'dashboard'])->middleware('role:2')->name('user.dashboard');

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

    Route::post('/user/upload', [\App\Http\Controllers\UserDocumentController::class, 'store'])->name('user.upload');
});
