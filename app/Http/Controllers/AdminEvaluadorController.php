<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Support\Facades\Hash;

class AdminEvaluadorController extends Controller
{
    public function index()
    {
        // Listar todos los usuarios con rol evaluador (id_rol = 3)
        $evaluadores = User::where('id_rol', 3)->with('persona')->get();
        return view('admin.usuarios-evaluadores', compact('evaluadores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        // Crear persona asociada
        $nombres = explode(' ', $request->nombre, 2);
        $primer_nombre = $nombres[0];
        $primer_apellido = isset($nombres[1]) ? $nombres[1] : '';
        $persona = Persona::create([
            'primer_nombre' => $primer_nombre,
            'primer_apellido' => $primer_apellido,
            'id_tipo_documento' => 1, // Por defecto, puedes ajustar
            'numero_documento' => uniqid(), // Temporal, puedes ajustar
            'fecha_exp_documento' => now(),
            'direccion' => 'N/A',
        ]);

        // Crear usuario evaluador
        $user = User::create([
            'id_persona' => $persona->id_persona,
            'id_rol' => 3,
            'id_estado' => 1,
            'codigo' => uniqid('EVAL'),
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.evaluadores.index')->with('success', 'Evaluador creado correctamente.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.evaluadores.index')->with('success', 'Evaluador eliminado correctamente.');
    }
}
