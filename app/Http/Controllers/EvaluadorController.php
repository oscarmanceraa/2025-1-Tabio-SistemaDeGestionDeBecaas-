<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Beneficiario;
use App\Models\Postulacion;
use App\Models\Persona;
use App\Models\TipoBeneficio;
use App\Models\Universidad;
use App\Models\Programa;
use App\Models\Sisben;
use App\Models\Nota;
use App\Models\Pregunta;
use App\Models\Convocatoria;
use App\Models\Resultado;

class EvaluadorController extends Controller
{
    public function dashboard()
    {
        $postulaciones = Postulacion::with(['persona', 'tipoBeneficio', 'documentos', 'resultado', 'beneficiario'])
            ->orderBy('fecha_postulacion', 'desc')
            ->get();

        return view('evaluador.dashboard', compact('postulaciones'));
    }

    public function toggleConvocatoria(Request $request)
    {
        try {
            DB::beginTransaction();

            // Log request information for debugging
            Log::info('Toggle Convocatoria Request', [
                'method' => $request->method(),
                'action' => $request->input('action'),
                'headers' => $request->headers->all()
            ]);

            // Buscar la convocatoria activa actual
            $convocatoriaActiva = Convocatoria::where('activa', true)->first();

            if ($request->input('action') === 'cerrar') {
                if ($convocatoriaActiva) {
                    $convocatoriaActiva->activa = false;
                    $convocatoriaActiva->save();
                    $mensaje = 'La convocatoria ha sido cerrada exitosamente.';
                } else {
                    throw new \Exception('No hay convocatoria activa para cerrar.');
                }
            } else {
                // Validar los campos del formulario
                $request->validate([
                    'nombre' => 'required|string|max:255',
                    'fecha_inicio' => 'required|date',
                    'fecha_fin' => 'required|date|after:fecha_inicio',
                    'descripcion' => 'nullable|string'
                ]);

                // Si hay una convocatoria activa, la desactivamos primero
                if ($convocatoriaActiva) {
                    $convocatoriaActiva->activa = false;
                    $convocatoriaActiva->save();
                }

                // Crear nueva convocatoria
                Convocatoria::create([
                    'nombre' => $request->nombre,
                    'fecha_inicio' => $request->fecha_inicio,
                    'fecha_fin' => $request->fecha_fin,
                    'activa' => true,
                    'descripcion' => $request->descripcion
                ]);
                $mensaje = 'La convocatoria ha sido abierta exitosamente.';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $mensaje
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error('Error de validación en toggleConvocatoria: ' . $e->getMessage(), [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error en toggleConvocatoria: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado de la convocatoria: ' . $e->getMessage()
            ], 500);
        }
    }

    public function seleccionarBeneficiario($id_postulacion)
    {
        $postulacion = Postulacion::findOrFail($id_postulacion);
        
        // Calcular puntaje basado en criterios
        $puntaje = 0;
        
        // Sisben
        $sisben = $postulacion->sisben;
        if ($sisben) {
            $puntaje += $sisben->puntaje;
        }
        
        // Promedio académico (asumiendo que está en escala de 0 a 5)
        $puntaje += $postulacion->promedio * 10; // Multiplicar por 10 para dar más peso
        
        // Preguntas adicionales
        $pregunta = $postulacion->pregunta;
        if ($pregunta) {
            if ($pregunta->horas_sociales) {
                $puntaje += 5;
            }
            if ($pregunta->discapacidad) {
                $puntaje += 10;
            }
            if ($pregunta->colegio_publico) {
                $puntaje += 5;
            }
            if ($pregunta->madre_cabeza_familia) {
                $puntaje += 8;
            }
            if ($pregunta->victima_conflicto) {
                $puntaje += 10;
            }
        }
        
        // Documentos verificados
        $documentosVerificados = $postulacion->documentos()->where('verificado', true)->count();
        $puntaje += $documentosVerificados * 2;

        try {
            DB::beginTransaction();

            // Buscar o crear el resultado asociado a la postulación y actualizar puntaje
            $resultado = Resultado::where('id_postulacion', $id_postulacion)->first();
            if (!$resultado) {
                $resultado = new Resultado();
                $resultado->id_postulacion = $id_postulacion;
            }
            $resultado->puntaje_total = $puntaje;
            // Forzar aprobación siempre que el evaluador seleccione beneficiario
            $resultado->aprobado = 1;
            $resultado->fecha_evaluacion = now()->toDateString();
            $resultado->observaciones = 'Resultado generado automáticamente al seleccionar beneficiario.';
            $resultado->save();

            // Crear el beneficiario usando el id_resultado encontrado o recién creado
            $beneficiario = new Beneficiario();
            $beneficiario->id_postulacion = $postulacion->id_postulacion;
            $beneficiario->id_resultado = $resultado->id_resultado;
            $beneficiario->monto_beneficio = 0; // Ajusta según lógica
            $beneficiario->fecha_inicio = now()->toDateString();
            $beneficiario->fecha_fin = null;
            $beneficiario->vigente = 1;
            $beneficiario->id_estado = 1; // Ajusta según tus estados
            $beneficiario->save();

            DB::commit();
            return redirect()->route('evaluador.dashboard')->with('success', 'Beneficiario seleccionado correctamente. El resultado fue generado automáticamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al guardar resultado o beneficiario: ' . $e->getMessage());
        }
    }
}
