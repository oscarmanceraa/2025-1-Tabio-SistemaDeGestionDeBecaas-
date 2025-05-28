<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramaSeeder extends Seeder
{
    public function run()
    {
        $programas = [
            [
                'id_programa' => 1,
                'nombre' => 'Ingeniería de Sistemas',
                'id_universidad' => 1,
                'valor_matricula' => 5000000,
                'puntaje' => 95,
            ],
            [
                'id_programa' => 2,
                'nombre' => 'Ingeniería Industrial',
                'id_universidad' => 1,
                'valor_matricula' => 4800000,
                'puntaje' => 90,
            ],
            [
                'id_programa' => 3,
                'nombre' => 'Administración de Empresas',
                'id_universidad' => 2,
                'valor_matricula' => 6000000,
                'puntaje' => 92,
            ],
            [
                'id_programa' => 4,
                'nombre' => 'Psicología',
                'id_universidad' => 3,
                'valor_matricula' => 5500000,
                'puntaje' => 88,
            ],
            [
                'id_programa' => 5,
                'nombre' => 'Derecho',
                'id_universidad' => 2,
                'valor_matricula' => 6200000,
                'puntaje' => 93,
            ],
        ];
        foreach ($programas as $prog) {
            DB::table('programas')->updateOrInsert(
                ['id_programa' => $prog['id_programa']],
                $prog
            );
        }
    }
}
