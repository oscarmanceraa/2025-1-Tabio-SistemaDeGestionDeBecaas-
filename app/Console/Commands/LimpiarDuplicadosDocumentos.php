<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DocumentosPostulacion;
use Illuminate\Support\Facades\DB;

class LimpiarDuplicadosDocumentos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documentos:limpiar-duplicados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina duplicados en documentos_postulacion dejando solo uno por tipo_documento y postulación';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $duplicados = DB::table('documentos_postulacion')
            ->select('id_postulacion', 'tipo_documento', DB::raw('COUNT(*) as total'))
            ->groupBy('id_postulacion', 'tipo_documento')
            ->having('total', '>', 1)
            ->get();

        $totalEliminados = 0;
        foreach ($duplicados as $dup) {
            $docs = DocumentosPostulacion::where('id_postulacion', $dup->id_postulacion)
                ->where('tipo_documento', $dup->tipo_documento)
                ->orderByDesc('id')
                ->get();
            // Mantener el más reciente, eliminar los demás
            $toDelete = $docs->slice(1); // omite el primero
            foreach ($toDelete as $doc) {
                $doc->delete();
                $this->info("Eliminado duplicado: id={$doc->id}, tipo={$doc->tipo_documento}, postulación={$doc->id_postulacion}");
                $totalEliminados++;
            }
        }
        $this->info("Limpieza de duplicados completada. Total eliminados: $totalEliminados");
    }
}
