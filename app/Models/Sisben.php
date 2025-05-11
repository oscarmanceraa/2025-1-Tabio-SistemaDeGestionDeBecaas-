<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sisben extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'sisben';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_sisben';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'letra',
        'numero',
        'puntaje',
    ];

    /**
     * Obtiene las postulaciones asociadas a esta categoría de sisben.
     */
    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'id_sisben');
    }

    /**
     * Obtiene la representación completa de la categoría sisben.
     *
     * @return string
     */
    public function getCategoriaCompletaAttribute()
    {
        return $this->letra . $this->numero . ' - Puntaje: ' . $this->puntaje;
    }
}