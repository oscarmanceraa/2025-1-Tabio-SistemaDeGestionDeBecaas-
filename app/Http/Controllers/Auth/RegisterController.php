<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $messages = [
                'numero_documento.unique' => 'El número de documento ya está registrado.',
                'email.unique' => 'El correo electrónico ya está registrado.',
                'username.unique' => 'El nombre de usuario ya está en uso.',
                'password.confirmed' => 'Las contraseñas no coinciden.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            ];
    
            $validated = $request->validate([
                'primer_nombre' => 'required|string|max:50',
                'segundo_nombre' => 'nullable|string|max:50',
                'primer_apellido' => 'required|string|max:50',
                'segundo_apellido' => 'nullable|string|max:50',
                'id_tipo_documento' => 'required|exists:tipos_documento,id_tipo_documento',
                'numero_documento' => 'required|string|max:20|unique:personas,numero_documento',
                'fecha_exp_documento' => 'required|date',
                'direccion' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'username' => 'required|string|max:100|unique:users,username',
                'password' => 'required|string|min:8|confirmed',
            ], $messages);
    
            DB::beginTransaction();
    
            // Debug information
            \Log::info('Validation passed, creating persona with data:', [
                'request_data' => $request->all()
            ]);
    
            $persona = Persona::create([
                'id_tipo_documento' => $request->id_tipo_documento,
                'primer_nombre' => $request->primer_nombre,
                'segundo_nombre' => $request->segundo_nombre,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'numero_documento' => $request->numero_documento,
                'fecha_exp_documento' => $request->fecha_exp_documento,
                'direccion' => $request->direccion,
            ]);
    
            \Log::info('Persona created:', ['persona' => $persona->toArray()]);
    
            $user = User::create([
                'id_persona' => $persona->id_persona,
                'id_rol' => 2,
                'id_estado' => 1,
                'codigo' => 'USR' . str_pad($persona->id_persona, 7, '0', STR_PAD_LEFT),
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);
    
            \Log::info('User created:', ['user' => $user->toArray()]);
    
            DB::commit();
            auth()->login($user);
    
            return redirect()->route('home')
                ->with('success', 'Registro exitoso');
    
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Registration error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
    
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al registrar usuario: ' . $e->getMessage()]);
        }
    }
}
