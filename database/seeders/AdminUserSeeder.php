<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Persona;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Eliminar usuario y persona admin si existen para evitar duplicados y problemas de unicidad
        $adminUser = User::where('username', 'admin')->first();
        if ($adminUser) {
            $adminPersona = Persona::find($adminUser->id_persona);
            $adminUser->delete();
            if ($adminPersona) {
                $adminPersona->delete();
            }
        }

        $persona = Persona::create([
            'primer_nombre' => 'Admin',
            'segundo_nombre' => null,
            'primer_apellido' => 'Principal',
            'segundo_apellido' => null,
            'id_tipo_documento' => 1,
            'numero_documento' => 'admin001',
            'fecha_exp_documento' => now(),
            'direccion' => 'Oficina',
            'observaciones' => null,
        ]);

        User::create([
            'id_persona' => $persona->id_persona,
            'id_rol' => 1, // 1 = Administrador
            'id_estado' => 1,
            'codigo' => 'ADMIN001',
            'email' => 'admin@sgbtabio.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'), // Cambia la contraseña si quieres
        ]);
    }
}
