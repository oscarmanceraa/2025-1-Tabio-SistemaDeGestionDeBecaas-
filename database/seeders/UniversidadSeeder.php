<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversidadSeeder extends Seeder
{
    public function run()
    {
        $universidades = [
            [
                'id_universidad' => 1,
                'nombre' => 'Universidad Nacional',
                'nit' => '800123456-1',
                'caracter' => 'Pública',
            ],
            [
                'id_universidad' => 2,
                'nombre' => 'Universidad de los Andes',
                'nit' => '800654321-2',
                'caracter' => 'Privada',
            ],
            [
                'id_universidad' => 3,
                'nombre' => 'Universidad Distrital',
                'nit' => '900111222-3',
                'caracter' => 'Pública',
            ],
            [
                'id_universidad' => 4,
                'nombre' => 'Universidad de Cundinamarca',
                'nit' => '900222333-4',
                'caracter' => 'Pública',
            ],
            [
                'id_universidad' => 5,
                'nombre' => 'Universidad Javeriana',
                'nit' => '900333444-5',
                'caracter' => 'Privada',
            ],
        ];
        foreach ($universidades as $uni) {
            DB::table('universidades')->updateOrInsert(
                ['id_universidad' => $uni['id_universidad']],
                $uni
            );
        }
    }
}
