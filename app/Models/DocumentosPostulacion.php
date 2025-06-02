<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentosPostulacion extends Model
{
    use HasFactory;

    protected $table = 'documentos_postulacion';

    protected $fillable = [
        'id_postulacion',
        'tipo_documento',
        'ruta',
        'verificado',
    ];

    protected $casts = [
        'verificado' => 'boolean',
    ];

    public function postulacion()
    {
        return $this->belongsTo(Postulacion::class, 'id_postulacion');
    }
}
