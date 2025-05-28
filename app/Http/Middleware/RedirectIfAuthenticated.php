<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                if ($user && property_exists($user, 'id_rol')) {
                    switch ($user->id_rol) {
                        case 1:
                            return redirect('/admin/dashboard');
                        case 2:
                            return redirect('/user/dashboard');
                        case 3:
                            return redirect('/evaluador/dashboard');
                        default:
                            Auth::logout();
                            return redirect('/login')->with('error', 'Tu usuario no tiene un rol válido.');
                    }
                } else {
                    Auth::logout();
                    return redirect('/login')->with('error', 'Tu usuario no tiene un rol asignado.');
                }
            }
        }

        return $next($request);
    }
}
