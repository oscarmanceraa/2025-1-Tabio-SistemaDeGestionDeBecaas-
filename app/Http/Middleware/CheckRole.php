<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Logging para depuración
        Log::info('CheckRole middleware: usuario autenticado', [
            'user' => Auth::user(),
            'session_id' => session()->getId(),
            'session_data' => session()->all(),
        ]);

        $user = Auth::user();
        if (!$user) {
            Log::warning('CheckRole middleware: usuario no autenticado, redirigiendo a login');
            return redirect()->route('login');
        }
        $userRole = DB::table('users')->where('id_user', $user->id_user)->value('id_rol');
        Log::info('CheckRole middleware: rol obtenido', [
            'user_id' => $user->id_user,
            'userRole' => $userRole,
            'expectedRole' => $role
        ]);
        if ($userRole != $role) {
            Log::warning('CheckRole middleware: rol incorrecto, redirigiendo a login', [
                'user_id' => $user->id_user,
                'userRole' => $userRole,
                'expectedRole' => $role
            ]);
            return redirect()->route('login')->with('error', 'No tienes permiso para acceder a esta página.');
        }
        return $next($request);
    }
}