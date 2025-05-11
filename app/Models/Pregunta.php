<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'preguntas';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_pregunta';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'horas_sociales',
        'cantidad_horas_sociales',
        'obs_horas',
        'discapacidad',
        'tipo_discapacidad',
        'obs_discapacidad',
        'colegio_publico',
        'nombre_colegio',
        'madre_cabeza_familia',
        'victima_conflicto',
        'declaracion_juramentada',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'horas_sociales' => 'boolean',
        'discapacidad' => 'boolean',
        'colegio_publico' => 'boolean',
        'madre_cabeza_familia' => 'boolean',
        'victima_conflicto' => 'boolean',
        'declaracion_juramentada' => 'boolean',
    ];

    /**
     * Obtiene la postulación asociada a estas preguntas.
     */
    public function postulacion()
    {
        return $this->hasOne(Postulacion::class, 'id_pregunta');
    }
}