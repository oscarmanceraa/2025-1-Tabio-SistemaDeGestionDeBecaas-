<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = auth()->user();
        $userRole = DB::table('users')->where('id_user', $user->id_user)->value('id_rol');

        if ($userRole != $role) {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}