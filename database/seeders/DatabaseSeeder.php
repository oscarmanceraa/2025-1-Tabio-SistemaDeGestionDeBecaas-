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
        ]);
    }
}
