<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ConvocatoriaSeeder::class,
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
