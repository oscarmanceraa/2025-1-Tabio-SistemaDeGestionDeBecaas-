<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Models\Persona;
use App\Models\TipoBeneficio;
use App\Models\Universidad;
use App\Models\Programa;
use App\Models\Sisben;
use App\Models\Nota;
use App\Models\Postulacion;
use App\Models\Pregunta;
use App\Models\DocumentosPostulacion;

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
        // Obtener la última postulación del usuario
        $ultimaPostulacion = Postulacion::where('id_usuario', Auth::id())
            ->with(['pregunta', 'documentos'])
            ->latest()
            ->first();

        // Obtener información de la persona
        $persona = Auth::user()->persona;

        return view('user.dashboard', compact('ultimaPostulacion', 'persona'));
    }

    /**
     * Almacena una nueva postulación en la base de datos
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validar los campos requeridos
            $request->validate([
                'id_universidad' => 'required|exists:universidades,id_universidad',
                'id_programa' => 'required|exists:programas,id_programa',
                'id_sisben' => 'required|exists:sisben,id_sisben',
                'fecha_postulacion' => 'required|date',
                'promedio' => 'required|numeric|min:0|max:5',
                'declaracion_juramentada' => 'required|accepted'
            ]);

            // Obtener el usuario autenticado y su persona
            $user = Auth::user();
            $persona = $user->persona;

            // Crear la pregunta primero
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
            $postulacion->id_persona = $persona->id_persona;
            $postulacion->id_tipo_beneficio = 1; // Por defecto el primer tipo
            $postulacion->cantidad_postulaciones = 1;
            $postulacion->semestre = 1; // Por defecto primer semestre
            $postulacion->id_universidad = $request->id_universidad;
            $postulacion->id_programa = $request->id_programa;
            $postulacion->id_sisben = $request->id_sisben;
            $postulacion->id_pregunta = $pregunta->id_pregunta;
            $postulacion->fecha_postulacion = $request->fecha_postulacion;
            $postulacion->promedio = $request->promedio;
            $postulacion->id_convocatoria = $request->convocatoria_activa->id_convocatoria;
            $postulacion->save();

            // Procesar documentos
            $tiposDocumento = [
                'documento_identidad',
                'certificado_sisben',
                'acta_grado',
                'certificado_notas',
                'comprobante_domicilio',
                'certificado_discapacidad'
            ];

            foreach ($tiposDocumento as $tipo) {
                if ($request->hasFile($tipo)) {
                    $this->guardarDocumento($request->file($tipo), $tipo, $postulacion->id_postulacion);
                } elseif ($request->has('ultima_postulacion')) {
                    // Copiar el último documento si existe
                    $this->copiarUltimoDocumento($tipo, $postulacion->id_postulacion);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Postulación creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al crear la postulación: ' . $e->getMessage())->withInput();
        }
    }

    private function guardarDocumento($archivo, $tipo, $idPostulacion)
    {
        $ruta = $archivo->store('archivos', 'secure');
        
        DocumentosPostulacion::create([
            'id_postulacion' => $idPostulacion,
            'tipo_documento' => $tipo,
            'ruta' => $ruta,
            'verificado' => false
        ]);
    }

    private function copiarUltimoDocumento($tipo, $idPostulacionNueva)
    {
        $ultimoDocumento = DocumentosPostulacion::where('tipo_documento', $tipo)
            ->whereHas('postulacion', function($query) {
                $query->where('id_usuario', Auth::id());
            })
            ->latest()
            ->first();

        if ($ultimoDocumento) {
            DocumentosPostulacion::create([
                'id_postulacion' => $idPostulacionNueva,
                'tipo_documento' => $tipo,
                'ruta' => $ultimoDocumento->ruta,
                'verificado' => false
            ]);
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