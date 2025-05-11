<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Universidad extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'universidades';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_universidad';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'nit',
        'caracter',
    ];

    /**
     * Obtiene los programas asociados a esta universidad.
     */
    public function programas()
    {
        return $this->hasMany(Programa::class, 'id_universidad');
    }

    /**
     * Obtiene las postulaciones asociadas a esta universidad.
     */
    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'id_universidad');
    }
}