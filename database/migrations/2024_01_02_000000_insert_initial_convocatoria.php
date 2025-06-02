<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('convocatorias')->insert([
            'nombre' => 'Convocatoria 2024-1',
            'fecha_inicio' => date('Y-m-d'),
            'fecha_fin' => date('Y-m-d', strtotime('+3 months')),
            'activa' => true,
            'descripcion' => 'Convocatoria para el primer semestre de 2024',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('convocatorias')
            ->where('nombre', 'Convocatoria 2024-1')
            ->delete();
    }
}; 