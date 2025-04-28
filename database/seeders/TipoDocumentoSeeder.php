<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoDocumentoSeeder extends Seeder
{
    public function run()
    {
        $tipos = [
            ['id_tipo_documento' => 1, 'nombre' => 'Cédula de Ciudadanía'],
            ['id_tipo_documento' => 2, 'nombre' => 'Cédula de Extranjería'],
            ['id_tipo_documento' => 3, 'nombre' => 'Tarjeta de Identidad'],
            ['id_tipo_documento' => 4, 'nombre' => 'Pasaporte'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipos_documento')->updateOrInsert(
                ['id_tipo_documento' => $tipo['id_tipo_documento']],
                $tipo
            );
        }
    }
}