<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoBeneficioSeeder extends Seeder
{
    public function run()
    {
        $tipos = [
            ['id_tipo_beneficio' => 1, 'nombre' => 'Sostenimiento', 'descripcion' => 'Apoyo económico para sostenimiento'],
            ['id_tipo_beneficio' => 2, 'nombre' => 'Transporte', 'descripcion' => 'Apoyo económico para transporte'],
        ];
        foreach ($tipos as $tipo) {
            DB::table('tipos_beneficio')->updateOrInsert(
                ['id_tipo_beneficio' => $tipo['id_tipo_beneficio']],
                $tipo
            );
        }
    }
}
