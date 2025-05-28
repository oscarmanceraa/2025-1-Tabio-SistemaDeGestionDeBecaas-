<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resultado extends Model
{
    use HasFactory;

    protected $table = 'resultados';
    protected $primaryKey = 'id_resultado';
    protected $fillable = [
        'id_postulacion',
        'puntaje_total',
        'aprobado',
        'fecha_evaluacion',
        'observaciones',
    ];

    public function postulacion()
    {
        return $this->belongsTo(Postulacion::class, 'id_postulacion');
    }
}
