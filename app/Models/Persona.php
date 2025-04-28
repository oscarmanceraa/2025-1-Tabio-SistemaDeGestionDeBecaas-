<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $primaryKey = 'id_persona';
    
    protected $fillable = [
        'id_tipo_documento',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'numero_documento',
        'fecha_exp_documento',
        'direccion',
        'observaciones'
    ];
}