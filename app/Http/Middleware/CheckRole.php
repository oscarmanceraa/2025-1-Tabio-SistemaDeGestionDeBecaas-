<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Use the Auth facade
use Illuminate\Support\Facades\DB;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if a user is authenticated
        if (!Auth::check()) {
            // Redirect or return an error if not authenticated
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }

        // Now you can safely access the authenticated user
        $user = Auth::user();
        $userRole = DB::table('users')->where('id_user', $user->id_user)->value('id_rol');

        if ($userRole != $role) {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}
