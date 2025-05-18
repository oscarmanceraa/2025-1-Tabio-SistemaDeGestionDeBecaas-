<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = 'tipos_documento';
    protected $primaryKey = 'id_tipo_documento';
    
    protected $fillable = [
        'id_tipo_documento',
        'nombre'
    ];

    public function personas()
    {
        return $this->hasMany(Persona::class, 'id_tipo_documento');
    }
}