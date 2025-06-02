<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Convocatoria;
use Carbon\Carbon;

class ConvocatoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Convocatoria::create([
            'nombre' => 'Convocatoria 2024-1',
            'fecha_inicio' => Carbon::now(),
            'fecha_fin' => Carbon::now()->addMonths(3),
            'activa' => true,
            'descripcion' => 'Convocatoria para el primer semestre de 2024'
        ]);
    }
} 