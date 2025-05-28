<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TipoBeneficio;
use App\Models\Universidad;
use App\Models\Programa;
use App\Models\Sisben;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // Tipos de Beneficio (solo los dos tipos reales)
        TipoBeneficio::insert([
            ['nombre' => 'Beca de Transporte', 'descripcion' => 'Apoyo para transporte de estudiantes de educación superior'],
            ['nombre' => 'Beca de Apoyo Económico', 'descripcion' => 'Apoyo económico para estudiantes de educación superior'],
        ]);

        // Universidades (agregar NIT de ejemplo)
        Universidad::insert([
            ['nombre' => 'Universidad Nacional', 'nit' => '800123456-1', 'caracter' => 'Pública'],
            ['nombre' => 'Universidad de los Andes', 'nit' => '800654321-2', 'caracter' => 'Privada'],
            ['nombre' => 'Universidad Distrital', 'nit' => '900111222-3', 'caracter' => 'Pública'],
        ]);

        // Programas (asociados a la primera universidad)
        $universidad = Universidad::first();
        if ($universidad) {
            Programa::insert([
                ['nombre' => 'Ingeniería de Sistemas', 'id_universidad' => $universidad->id_universidad, 'valor_matricula' => 5000000, 'puntaje' => 95],
                ['nombre' => 'Derecho', 'id_universidad' => $universidad->id_universidad, 'valor_matricula' => 4500000, 'puntaje' => 90],
                ['nombre' => 'Medicina', 'id_universidad' => $universidad->id_universidad, 'valor_matricula' => 7000000, 'puntaje' => 98],
            ]);
        }

        // Categorías Sisben (con id_sisben explícito)
        Sisben::insert([
            ['id_sisben' => 1, 'letra' => 'A', 'numero' => 1, 'puntaje' => 30],
            ['id_sisben' => 2, 'letra' => 'B', 'numero' => 2, 'puntaje' => 40],
            ['id_sisben' => 3, 'letra' => 'C', 'numero' => 3, 'puntaje' => 50],
        ]);
    }
}
