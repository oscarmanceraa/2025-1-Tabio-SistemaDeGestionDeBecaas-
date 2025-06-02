<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Convocatoria;
use Carbon\Carbon;

class ConvocatoriaController extends Controller
{
    public function crearConvocatoriaActiva()
    {
        try {
            // Desactivar todas las convocatorias existentes
            Convocatoria::where('activa', true)->update(['activa' => false]);

            // Crear nueva convocatoria activa
            $convocatoria = new Convocatoria();
            $convocatoria->nombre = 'Convocatoria 2024-1';
            $convocatoria->fecha_inicio = Carbon::now();
            $convocatoria->fecha_fin = Carbon::now()->addMonths(3);
            $convocatoria->activa = true;
            $convocatoria->descripcion = 'Convocatoria para el primer semestre de 2024';
            $convocatoria->save();

            return response()->json([
                'success' => true,
                'message' => 'Convocatoria creada exitosamente',
                'data' => $convocatoria
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la convocatoria: ' . $e->getMessage()
            ], 500);
        }
    }
} 