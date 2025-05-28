<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {

        $user = Auth::user();
        if (!$user) {
            Auth::logout();
            return redirect('/login')->with('error', 'Debes iniciar sesión.');
        }
        $userRole = DB::table('users')->where('id_user', $user->id_user)->value('id_rol');
        if (!$userRole || $userRole != $role) {
            Auth::logout();
            return redirect('/login')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}