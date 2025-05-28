<?php
// Script para eliminar duplicados en documentos_postulacion dejando solo uno por tipo_documento y postulacion

use Illuminate\Support\Facades\DB;
use App\Models\DocumentosPostulacion;

require __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Agrupa por id_postulacion y tipo_documento, dejando solo el más reciente (mayor id)
$duplicados = DB::table('documentos_postulacion')
    ->select('id_postulacion', 'tipo_documento', DB::raw('COUNT(*) as total'))
    ->groupBy('id_postulacion', 'tipo_documento')
    ->having('total', '>', 1)
    ->get();

foreach ($duplicados as $dup) {
    $docs = DocumentosPostulacion::where('id_postulacion', $dup->id_postulacion)
        ->where('tipo_documento', $dup->tipo_documento)
        ->orderByDesc('id')
        ->get();
    // Mantener el más reciente, eliminar los demás
    $toDelete = $docs->slice(1); // omite el primero
    foreach ($toDelete as $doc) {
        $doc->delete();
        echo "Eliminado duplicado: id={$doc->id}, tipo={$doc->tipo_documento}, postulación={$doc->id_postulacion}\n";
    }
}
echo "Limpieza de duplicados completada.\n";
