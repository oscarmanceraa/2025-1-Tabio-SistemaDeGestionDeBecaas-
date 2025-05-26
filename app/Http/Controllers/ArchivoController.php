<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchivoController extends Controller
{
    /**
     * Recibe y almacena un archivo de forma segura en storage/app/archivos
     */
    public function store(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|max:10240', // Máx 10MB
        ]);

        // Guarda el archivo en storage/app/archivos
        $path = $request->file('archivo')->store('archivos');

        // Puedes guardar $path en la base de datos si lo necesitas

        return back()->with('success', 'Archivo subido correctamente.')->with('archivo_path', $path);
    }
}
