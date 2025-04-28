<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['id_rol' => 1, 'nombre' => 'Administrador'],
            ['id_rol' => 2, 'nombre' => 'Usuario'],
        ];

        foreach ($roles as $rol) {
            DB::table('roles')->updateOrInsert(
                ['id_rol' => $rol['id_rol']],
                $rol
            );
        }
    }
}