<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentosPostulacion;
use App\Models\Resultado;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DocumentoController extends Controller
{
    public function verificar($id)
    {
        Log::info('Iniciando verificación de documento', ['id' => $id]);
        
        DB::beginTransaction();
        try {
            $documento = DocumentosPostulacion::findOrFail($id);
            Log::info('Documento encontrado', [
                'id' => $id,
                'tipo' => $documento->tipo_documento,
                'verificado_antes' => $documento->verificado
            ]);

            $documento->verificado = true;
            $documento->save();

            Log::info('Documento marcado como verificado', [
                'id' => $id,
                'verificado_despues' => $documento->verificado
            ]);

            // Obtener o crear el resultado de la postulación
            $resultado = Resultado::firstOrCreate(
                ['id_postulacion' => $documento->id_postulacion],
                [
                    'puntaje_total' => 0,
                    'aprobado' => false,
                    'fecha_evaluacion' => now(),
                    'observaciones' => 'Evaluación en proceso'
                ]
            );

            // Calcular puntaje de preguntas
            $postulacion = $documento->postulacion;
            $puntaje_preguntas = 0;
            
            if ($postulacion->pregunta) {
                $pregunta = $postulacion->pregunta;
                // Solo sumar puntos si las respuestas son verdaderas (1 o true)
                $puntaje_preguntas += $pregunta->horas_sociales == 1 ? 10 : 0;
                $puntaje_preguntas += $pregunta->cantidad_horas_sociales > 0 ? min($pregunta->cantidad_horas_sociales, 100) * 0.1 : 0;
                $puntaje_preguntas += $pregunta->discapacidad == 1 ? 10 : 0;
                $puntaje_preguntas += $pregunta->colegio_publico == 1 ? 5 : 0;
                $puntaje_preguntas += $pregunta->madre_cabeza_familia == 1 ? 5 : 0;
                $puntaje_preguntas += $pregunta->victima_conflicto == 1 ? 5 : 0;
                $puntaje_preguntas += $pregunta->declaracion_juramentada == 1 ? 5 : 0;
            }

            // Agregar puntaje del Sisben
            if ($postulacion->sisben) {
                // El puntaje del Sisben ya viene calculado de la base de datos
                $puntaje_preguntas += $postulacion->sisben->puntaje ?? 0;
            }

            // Calcular puntaje de documentos (10 puntos por cada documento verificado)
            $documentosVerificados = $postulacion->documentos->where('verificado', true)->count();
            $puntaje_documentos = $documentosVerificados * 10;

            // Actualizar el puntaje total (suma exacta de los dos puntajes)
            $resultado->puntaje_total = $puntaje_preguntas + $puntaje_documentos;
            
            // Si el puntaje supera 60, marcar como aprobado
            if ($resultado->puntaje_total >= 60) {
                $resultado->aprobado = true;
            } else {
                $resultado->aprobado = false;
            }
        
            $resultado->save();

            DB::commit();

            Log::info('Documento verificado y puntos actualizados:', [
                'id' => $id,
                'tipo' => $documento->tipo_documento,
                'puntaje_preguntas' => $puntaje_preguntas,
                'puntaje_documentos' => $puntaje_documentos,
                'puntaje_total' => $resultado->puntaje_total
            ]);

            return response()->json([
                'success' => true,
                'message' => "Documento verificado correctamente. Se agregaron 10 puntos al puntaje de documentos.",
                'puntaje_total' => $resultado->puntaje_total,
                'puntaje_preguntas' => $puntaje_preguntas,
                'puntaje_documentos' => $puntaje_documentos,
                'documento' => [
                    'id' => $documento->id,
                    'verificado' => $documento->verificado
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al verificar documento:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calcularPuntosPorDocumento($tipo_documento)
    {
        // Asignar puntos según el tipo de documento
        switch ($tipo_documento) {
            case 'documento_identidad':
                return 15;
            case 'comprobante_domicilio':
                return 10;
            case 'certificado_sisben':
                return 20;
            case 'acta_grado':
                return 15;
            case 'certificado_notas':
                return 20;
            case 'certificado_discapacidad':
                return 20;
            default:
                return 10;
        }
    }
} 