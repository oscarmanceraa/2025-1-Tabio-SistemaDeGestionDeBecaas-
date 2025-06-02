<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';
    protected $primaryKey = 'id_persona';
    
    protected $fillable = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'id_tipo_documento',
        'numero_documento',
        'fecha_exp_documento',
        'direccion'
    ];

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipo_documento', 'id_tipo_documento');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id_persona', 'id_persona');
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'id_persona', 'id_persona');
    }
}