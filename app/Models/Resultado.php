<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function beneficiarios()
    {
        return $this->hasMany(Beneficiario::class, 'id_resultado');
    }
}
?>