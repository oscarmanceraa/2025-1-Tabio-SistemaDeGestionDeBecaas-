<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Persona;
use App\Models\User;

class EvaluadorSeeder extends Seeder
{
    public function run()
    {
        // Crear persona asociada al evaluador
        $persona = Persona::create([
            'id_tipo_documento' => 1, // Ajusta según tus tipos
            'primer_nombre' => 'Eva',
            'segundo_nombre' => null,
            'primer_apellido' => 'Luador',
            'segundo_apellido' => null,
            'numero_documento' => '987654321',
            'fecha_exp_documento' => '2000-01-01',
            'direccion' => 'Calle Evaluador 123',
            'observaciones' => null,
        ]);

        // Crear usuario evaluador
        User::create([
            'id_persona' => $persona->id_persona,
            'id_rol' => 3, // ID del rol evaluador
            'id_estado' => 1, // Activo
            'codigo' => 'EVAL001',
            'email' => 'evaluador@ejemplo.com',
            'username' => 'evaluador',
            'password' => Hash::make('passwordseguro'),
        ]);
    }
}
