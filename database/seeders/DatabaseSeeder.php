<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            TipoDocumentoSeeder::class,
            RolSeeder::class,
            EstadoSeeder::class,
            SisbenSeeder::class,
            UniversidadSeeder::class,
            ProgramaSeeder::class,
            TipoBeneficioSeeder::class,
            EvaluadorSeeder::class, // Usuario evaluador
            AdminUserSeeder::class, // Usuario administrador
        ]);
    }
}
