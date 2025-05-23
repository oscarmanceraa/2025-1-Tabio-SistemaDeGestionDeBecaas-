<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Añade esta línea

class LoginController extends Controller
{
    use AuthenticatesUsers;

    //redirecciona segun el rol
    protected function redirectTo()
    {
        $user = auth()->user();
        $rol = DB::table('users')->where('id_user', $user->id_user)->value('id_rol');

        switch ($rol) {
            case 1:
                return '/admin/dashboard';
            case 2:
                return '/user/dashboard';
            case 3:
                return '/evaluador/dashboard';
            default:
                return '/home';
        }
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Override the username method to use 'username' instead of 'email'
    public function username()
    {
        return 'username';
    }

    // Add custom validation messages
    protected function validateLogin(Request $request)
    {
        $messages = [
            'username.required' => 'El nombre de usuario es requerido',
            'password.required' => 'La contraseña es requerida',
        ];

        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ], $messages);
    }

    // Add custom failed login message
    protected function sendFailedLoginResponse(Request $request)
    {
        return back()
            ->withInput($request->only($this->username()))
            ->withErrors([
                $this->username() => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
