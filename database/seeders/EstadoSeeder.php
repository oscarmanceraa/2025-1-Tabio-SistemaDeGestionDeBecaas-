<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    public function run()
    {
        $estados = [
            ['id_estado' => 1, 'nombre' => 'Activo'],
            ['id_estado' => 2, 'nombre' => 'Inactivo'],
        ];

        foreach ($estados as $estado) {
            DB::table('estados')->updateOrInsert(
                ['id_estado' => $estado['id_estado']],
                $estado
            );
        }
    }
}