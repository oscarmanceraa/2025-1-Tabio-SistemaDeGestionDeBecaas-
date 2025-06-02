<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Convocatoria;
use App\Models\Postulacion;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckConvocatoriaAbierta
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Verificar si hay una convocatoria activa
            $convocatoriaActiva = Convocatoria::where('activa', true)
                ->where('fecha_inicio', '<=', now())
                ->where('fecha_fin', '>=', now())
                ->first();
            
            if (!$convocatoriaActiva) {
                return redirect()->back()->with('error', 'No hay convocatorias abiertas actualmente.');
            }

            // Si es una solicitud POST (creación de postulación), verificar si ya existe una postulación
            if ($request->isMethod('post')) {
                $existePostulacion = Postulacion::where('id_persona', Auth::user()->persona->id_persona)
                    ->where('id_convocatoria', $convocatoriaActiva->id_convocatoria)
                    ->exists();

                if ($existePostulacion) {
                    return redirect()->back()->with('error', 'Ya has realizado una postulación para esta convocatoria.');
                }
            }

            // Agregar la convocatoria activa a la solicitud para usarla en el controlador
            $request->merge(['convocatoria_activa' => $convocatoriaActiva]);

            return $next($request);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al verificar la convocatoria: ' . $e->getMessage());
        }
    }
} 