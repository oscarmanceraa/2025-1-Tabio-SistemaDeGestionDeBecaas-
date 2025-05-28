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
            'pregunta',
            'documentos'
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

        // Buscar el resultado asociado a la postulación
        // Siempre calcular el puntaje en base a las preguntas y sisben al momento de seleccionar beneficiario
        $pregunta = \App\Models\Pregunta::find($postulacion->id_pregunta);
        $puntaje = 0;
        if ($pregunta) {
            $puntaje += $pregunta->horas_sociales ? 10 : 0;
            $puntaje += $pregunta->cantidad_horas_sociales ? min($pregunta->cantidad_horas_sociales, 100) * 0.1 : 0;
            $puntaje += $pregunta->discapacidad ? 10 : 0;
            $puntaje += $pregunta->colegio_publico ? 5 : 0;
            $puntaje += $pregunta->madre_cabeza_familia ? 5 : 0;
            $puntaje += $pregunta->victima_conflicto ? 5 : 0;
            $puntaje += $pregunta->declaracion_juramentada ? 5 : 0;
        }
        $sisben = \App\Models\Sisben::find($postulacion->id_sisben);
        if ($sisben) {
            $puntaje += max(0, 20 - $sisben->numero);
        }

        try {
            // Buscar o crear el resultado asociado a la postulación y actualizar puntaje
            $resultado = \App\Models\Resultado::where('id_postulacion', $id_postulacion)->first();
            if (!$resultado) {
                $resultado = new \App\Models\Resultado();
                $resultado->id_postulacion = $id_postulacion;
            }
            $resultado->puntaje_total = $puntaje;
            // Forzar aprobación siempre que el evaluador seleccione beneficiario
            $resultado->aprobado = 1;
            $resultado->fecha_evaluacion = now()->toDateString();
            $resultado->observaciones = 'Resultado generado automáticamente al seleccionar beneficiario.';
            $resultado->save();

            // Crear el beneficiario usando el id_resultado encontrado o recién creado
            $beneficiario = new \App\Models\Beneficiario();
            $beneficiario->id_postulacion = $postulacion->id_postulacion;
            $beneficiario->id_resultado = $resultado->id_resultado;
            $beneficiario->monto_beneficio = 0; // Ajusta según lógica
            $beneficiario->fecha_inicio = now()->toDateString();
            $beneficiario->fecha_fin = null;
            $beneficiario->vigente = 1;
            $beneficiario->id_estado = 1; // Ajusta según tus estados
            $beneficiario->save();

            return redirect()->route('evaluador.dashboard')->with('success', 'Beneficiario seleccionado correctamente. El resultado fue generado automáticamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al guardar resultado o beneficiario: ' . $e->getMessage());
        }
    }
}
