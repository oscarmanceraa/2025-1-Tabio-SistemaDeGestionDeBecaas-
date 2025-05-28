<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoBeneficioSeeder extends Seeder
{
    public function run()
    {
        $tipos = [
            ['id_tipo_beneficio' => 1, 'nombre' => 'Matrícula'],
            ['id_tipo_beneficio' => 2, 'nombre' => 'Sostenimiento'],
            ['id_tipo_beneficio' => 3, 'nombre' => 'Alimentación'],
            ['id_tipo_beneficio' => 4, 'nombre' => 'Transporte'],
        ];
        foreach ($tipos as $tipo) {
            DB::table('tipos_beneficio')->updateOrInsert(
                ['id_tipo_beneficio' => $tipo['id_tipo_beneficio']],
                $tipo
            );
        }
    }
}
