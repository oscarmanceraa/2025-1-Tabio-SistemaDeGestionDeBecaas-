<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Postulacion;
use App\Models\TipoBeneficio;
use App\Models\Universidad;
use App\Models\Programa;
use App\Models\Sisben;

class UserDashboardController extends Controller
{
    public function dashboard()
    {
        // Obtener el usuario autenticado y su persona
        $user = Auth::user();
        $persona = $user->persona;

        // Obtener la última postulación del usuario usando id_persona
        $ultimaPostulacion = Postulacion::where('id_persona', $persona->id_persona)
            ->with(['pregunta', 'documentos', 'resultado', 'beneficiario', 'tipoBeneficio', 'universidad', 'programa'])
            ->latest()
            ->first();

        // Obtener datos necesarios para el formulario
        $universidades = Universidad::all();
        $tiposBeneficio = TipoBeneficio::all();
        $sisben = Sisben::orderBy('letra')->orderBy('numero')->get();

        return view('user.dashboard', compact(
            'ultimaPostulacion',
            'persona',
            'universidades',
            'tiposBeneficio',
            'sisben'
        ));
    }
}
