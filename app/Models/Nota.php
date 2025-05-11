<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'notas';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_nota';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'id_persona',
        'promedio',
        'observaciones',
    ];

    /**
     * Obtiene la persona asociada a esta nota.
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    /**
     * Obtiene las postulaciones asociadas a esta nota.
     */
    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'id_nota');
    }
}