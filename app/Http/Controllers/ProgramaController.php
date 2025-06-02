<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use App\Models\Universidad;
use Illuminate\Http\Request;

class ProgramaController extends Controller
{
    /**
     * Obtiene los programas asociados a una universidad.
     *
     * @param Universidad $universidad
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProgramasByUniversidad(Universidad $universidad)
    {
        $programas = Programa::where('id_universidad', $universidad->id_universidad)
            ->orderBy('nombre')
            ->get();

        return response()->json($programas);
    }
} 