<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\TipoDocumento;

class AdminEvaluadorController extends Controller
{
    public function index()
    {
        // Listar todos los usuarios con rol evaluador (id_rol = 3) paginados
        $evaluadores = User::where('id_rol', 3)->with('persona')->orderByDesc('id_user')->paginate(10);
        $tipos_documento = TipoDocumento::all();
        return view('admin.usuarios-evaluadores', compact('evaluadores', 'tipos_documento'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'primer_nombre' => 'required|string|max:50',
            'segundo_nombre' => 'nullable|string|max:50',
            'primer_apellido' => 'required|string|max:50',
            'segundo_apellido' => 'nullable|string|max:50',
            'id_tipo_documento' => 'required|exists:tipos_documento,id_tipo_documento',
            'numero_documento' => 'required|string|max:20|unique:personas,numero_documento',
            'fecha_exp_documento' => 'required|date',
            'direccion' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:8',
        ]);

        // Crear persona asociada
        $persona = Persona::create([
            'primer_nombre' => $request->primer_nombre,
            'segundo_nombre' => $request->segundo_nombre,
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido,
            'id_tipo_documento' => $request->id_tipo_documento,
            'numero_documento' => $request->numero_documento,
            'fecha_exp_documento' => $request->fecha_exp_documento,
            'direccion' => $request->direccion,
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

        // Obtener el ID del admin autenticado de forma compatible con Intelephense
        $adminId = null;
        if (\Illuminate\Support\Facades\Auth::check()) {
            $admin = \Illuminate\Support\Facades\Auth::user();
            if ($admin && property_exists($admin, 'id_user')) {
                $adminId = $admin->id_user;
            }
        }
        Log::info('Evaluador creado por admin', [
            'admin_id' => $adminId,
            'evaluador_id' => $user->id_user,
            'persona_id' => $persona->id_persona,
            'username' => $user->username,
            'email' => $user->email,
        ]);

        return redirect()->route('admin.evaluadores.index')->with('success', 'Evaluador creado correctamente.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $persona = $user->persona;
        $username = $user->username;
        $email = $user->email;
        $persona_id = $persona ? $persona->id_persona : null;
        $user->delete();
        // Obtener el ID del admin autenticado de forma compatible con Intelephense
        $adminId = null;
        if (\Illuminate\Support\Facades\Auth::check()) {
            $admin = \Illuminate\Support\Facades\Auth::user();
            if ($admin && property_exists($admin, 'id_user')) {
                $adminId = $admin->id_user;
            }
        }
        Log::info('Evaluador eliminado por admin', [
            'admin_id' => $adminId,
            'evaluador_id' => $id,
            'persona_id' => $persona_id,
            'username' => $username,
            'email' => $email,
        ]);
        return redirect()->route('admin.evaluadores.index')->with('success', 'Evaluador eliminado correctamente.');
    }
}
