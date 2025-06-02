<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Convocatoria extends Model
{
    use HasFactory;
    
    protected $table = 'convocatorias';
    protected $primaryKey = 'id_convocatoria';
    
    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'activa',
        'descripcion'
    ];

    protected $casts = [
        'activa' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date'
    ];

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'id_convocatoria');
    }
} 