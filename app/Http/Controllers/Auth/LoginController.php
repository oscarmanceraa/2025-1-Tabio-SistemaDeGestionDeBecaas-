<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

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
}
