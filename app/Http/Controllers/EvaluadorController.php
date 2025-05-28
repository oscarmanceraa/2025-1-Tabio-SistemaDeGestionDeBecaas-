<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\Beneficiario;
use App\Models\Postulacion;
use App\Models\Persona;
use App\Models\TipoBeneficio;
use App\Models\Universidad;
use App\Models\Programa;
use App\Models\Sisben;
use App\Models\Nota;
use App\Models\Pregunta;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postulacion;
use App\Models\Persona;
use App\Models\TipoBeneficio;
use App\Models\Universidad;
use App\Models\Programa;
use App\Models\Sisben;
use App\Models\Nota;
use App\Models\Pregunta;

class EvaluadorController extends Controller
{
    public function dashboard()
    {
        // Traer todas las postulaciones con relaciones para mostrar al evaluador
        $postulaciones = Postulacion::with([
            'persona',
            'tipoBeneficio',
            'universidad',
            'programa',
            'sisben',
            'nota',
            'pregunta'
        ])->orderBy('created_at', 'desc')->get();

        return view('evaluador.dashboard', compact('postulaciones'));
    }
    /**
     * Selecciona una postulación como beneficiario
     */
    public function seleccionarBeneficiario($id_postulacion)
    {
        // Verifica si ya existe un beneficiario para esta postulación
        $postulacion = \App\Models\Postulacion::findOrFail($id_postulacion);
        if ($postulacion->beneficiario) {
            return redirect()->route('evaluador.dashboard')->with('info', 'Esta postulación ya es beneficiario.');
        }

        // Crear el beneficiario (puedes ajustar los campos según tu lógica de negocio)
        $beneficiario = new \App\Models\Beneficiario();
        $beneficiario->id_postulacion = $postulacion->id_postulacion;
        $beneficiario->id_resultado = $postulacion->resultado->id_resultado ?? null;
        $beneficiario->monto_beneficio = 0; // Ajusta según lógica
        $beneficiario->fecha_inicio = now()->toDateString();
        $beneficiario->fecha_fin = null;
        $beneficiario->vigente = 1;
        $beneficiario->id_estado = 1; // Ajusta según tus estados
        $beneficiario->save();

        return redirect()->route('evaluador.dashboard')->with('success', 'Beneficiario seleccionado correctamente.');
    }
}
