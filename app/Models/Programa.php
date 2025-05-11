<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'programas';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_programa';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'id_universidad',
        'valor_matricula',
        'puntaje',
    ];

    /**
     * Obtiene la universidad a la que pertenece este programa.
     */
    public function universidad()
    {
        return $this->belongsTo(Universidad::class, 'id_universidad');
    }

    /**
     * Obtiene las postulaciones asociadas a este programa.
     */
    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'id_programa');
    }
}