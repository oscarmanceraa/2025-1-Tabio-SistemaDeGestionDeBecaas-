<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\TipoBeneficio;
use App\Models\Universidad;
use App\Models\Programa;
use App\Models\Sisben;
use App\Models\Nota;
use App\Models\Postulacion;
use App\Models\Pregunta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostulacionController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra el formulario para crear una nueva postulación
     */
    public function create()
    {
        // Automatizar la creación de nota si no existe para el usuario autenticado
        $idPersona = Auth::user()->id_persona;
        // $nota = Nota::where('id_persona', $idPersona)->first();
        // if (!$nota) {
        //     $nota = Nota::create([
        //         'id_persona' => $idPersona,
        //         'promedio' => 0,
        //         'observaciones' => 'Nota generada automáticamente para permitir postulación'
        //     ]);
        // }

        $personas = Persona::all();
        $tiposBeneficio = TipoBeneficio::all();
        $universidades = Universidad::all();
        $sisben = Sisben::orderBy('letra')->orderBy('numero')->get();
        // $notas = Nota::where('id_persona', $idPersona)->get();

        return view('user.postulacion-form', compact(
            'personas',
            'tiposBeneficio',
            'universidades',
            'sisben'
            // , 'notas'
        ));
    }

    /**
     * Almacena una nueva postulación en la base de datos
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario y los archivos
        $request->validate([
            'id_persona' => 'required|exists:personas,id_persona',
            'id_tipo_beneficio' => 'required|exists:tipos_beneficio,id_tipo_beneficio',
            'cantidad_postulaciones' => 'required|integer|min:1|max:10',
            'semestre' => 'required|integer|min:1|max:12',
            'id_universidad' => 'required|exists:universidades,id_universidad',
            'id_programa' => 'required|exists:programas,id_programa',
            'id_sisben' => 'required|exists:sisben,id_sisben',
            'id_nota' => 'required|exists:notas,id_nota',
            'fecha_postulacion' => 'required|date',
            'horas_sociales' => 'sometimes|boolean',
            'cantidad_horas_sociales' => 'nullable|required_if:horas_sociales,1|integer',
            'obs_horas' => 'nullable|string|max:255',
            'discapacidad' => 'sometimes|boolean',
            'tipo_discapacidad' => 'nullable|required_if:discapacidad,1|string|max:100',
            'obs_discapacidad' => 'nullable|string|max:255',
            'colegio_publico' => 'sometimes|boolean',
            'nombre_colegio' => 'nullable|required_if:colegio_publico,1|string|max:255',
            'madre_cabeza_familia' => 'sometimes|boolean',
            'victima_conflicto' => 'sometimes|boolean',
            'declaracion_juramentada' => 'required|accepted',
            // Validación de archivos opcionales
            'certificado_sisben' => 'nullable|file|mimes:pdf|max:10240',
            'acta_grado' => 'nullable|file|mimes:pdf|max:10240',
            'certificado_discapacidad' => 'nullable|file|mimes:pdf|max:10240',
            'certificado_notas' => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'id_nota.required' => 'Debes seleccionar una nota promedio registrada para postularte.',
            'id_nota.exists' => 'La nota seleccionada no es válida.'
        ]);
        // Guardar archivos si existen
        $archivos = [];
        $nombresCampos = [
            'certificado_sisben',
            'acta_grado',
            'certificado_discapacidad',
            'certificado_notas',
        ];
        foreach ($nombresCampos as $campo) {
            if ($request->hasFile($campo)) {
                $archivos[$campo] = $request->file($campo)->store('archivos');
            }
        }

        // Iniciar una transacción de base de datos
        DB::beginTransaction();
        try {
            // Crear primero la pregunta
            $pregunta = new Pregunta();
            $pregunta->horas_sociales = $request->has('horas_sociales') ? 1 : 0;
            $pregunta->cantidad_horas_sociales = $request->cantidad_horas_sociales;
            $pregunta->obs_horas = $request->obs_horas;
            $pregunta->discapacidad = $request->has('discapacidad') ? 1 : 0;
            $pregunta->tipo_discapacidad = $request->tipo_discapacidad;
            $pregunta->obs_discapacidad = $request->obs_discapacidad;
            $pregunta->colegio_publico = $request->has('colegio_publico') ? 1 : 0;
            $pregunta->nombre_colegio = $request->nombre_colegio;
            $pregunta->madre_cabeza_familia = $request->has('madre_cabeza_familia') ? 1 : 0;
            $pregunta->victima_conflicto = $request->has('victima_conflicto') ? 1 : 0;
            $pregunta->declaracion_juramentada = $request->has('declaracion_juramentada') ? 1 : 0;
            $pregunta->save();

            // Crear la postulación
            $postulacion = new Postulacion();
            $postulacion->id_persona = $request->id_persona;
            $postulacion->id_tipo_beneficio = $request->id_tipo_beneficio;
            $postulacion->cantidad_postulaciones = $request->cantidad_postulaciones;
            $postulacion->semestre = $request->semestre;
            $postulacion->id_universidad = $request->id_universidad;
            $postulacion->id_programa = $request->id_programa;
            $postulacion->id_sisben = $request->id_sisben;
            $postulacion->id_nota = $request->id_nota;
            $postulacion->id_pregunta = $pregunta->id_pregunta;
            $postulacion->fecha_postulacion = $request->fecha_postulacion;
            $postulacion->save();

            // Confirmar la transacción
            DB::commit();

            // Puedes guardar las rutas de los archivos en la base de datos si lo necesitas

            return redirect()->route('user.dashboard')
                ->with('success', 'Postulación enviada correctamente. Archivos subidos: ' . implode(', ', array_values($archivos)));

        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al guardar la postulación: ' . $e->getMessage()]);
        }
    }

    /**
     * Obtiene los programas de una universidad específica (para AJAX)
     */
    public function getProgramasByUniversidad($id_universidad)
    {
        $programas = Programa::where('id_universidad', $id_universidad)->get();
        return response()->json($programas);
    }
}