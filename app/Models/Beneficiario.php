<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    use HasFactory;

    protected $table = 'beneficiarios';
    protected $primaryKey = 'id_beneficiario';

    protected $fillable = [
        'id_postulacion',
        'id_resultado',
        'monto_beneficio',
        'fecha_inicio',
        'fecha_fin',
        'vigente',
        'id_estado',
    ];

    public function postulacion()
    {
        return $this->belongsTo(Postulacion::class, 'id_postulacion');
    }

    public function resultado()
    {
        return $this->belongsTo(Resultado::class, 'id_resultado');
    }
}
