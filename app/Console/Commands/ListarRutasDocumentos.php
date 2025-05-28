<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DocumentosPostulacion;

class ListarRutasDocumentos extends Command
{
    protected $signature = 'documentos:listar-rutas';
    protected $description = 'Lista los valores de ruta de documentos_postulacion y verifica si el archivo existe físicamente';

    public function handle()
    {
        $documentos = DocumentosPostulacion::all();
        $basePath = storage_path('app/private/archivos/');
        $this->info("ID | id_postulacion | tipo_documento | ruta | existe");
        foreach ($documentos as $doc) {
            $ruta = $doc->ruta;
            // Si la ruta tiene 'archivos/', quitarlo
            if (strpos($ruta, 'archivos/') === 0) {
                $ruta = substr($ruta, strlen('archivos/'));
            }
            $existe = file_exists($basePath . $ruta) ? 'SI' : 'NO';
            $this->line("{$doc->id} | {$doc->id_postulacion} | {$doc->tipo_documento} | {$doc->ruta} | {$existe}");
        }
    }
}
