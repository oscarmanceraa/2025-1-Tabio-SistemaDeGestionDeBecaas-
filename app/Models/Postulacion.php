<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Persona;
use App\Models\TipoBeneficio;
use App\Models\Universidad;
use App\Models\Programa;
use App\Models\Sisben;
use App\Models\Nota;
use App\Models\Pregunta;
use App\Models\Resultado;
use App\Models\Beneficiario;

class Postulacion extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'postulaciones';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_postulacion';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'id_persona',
        'id_tipo_beneficio',
        'cantidad_postulaciones',
        'semestre',
        'id_universidad',
        'id_programa',
        'id_sisben',
        'id_nota',
        'id_pregunta',
        'fecha_postulacion',
    ];

    /**
     * Obtiene la persona asociada a la postulación.
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    /**
     * Obtiene el tipo de beneficio asociado a la postulación.
     */
    public function tipoBeneficio()
    {
        return $this->belongsTo(TipoBeneficio::class, 'id_tipo_beneficio');
    }

    /**
     * Obtiene la universidad asociada a la postulación.
     */
    public function universidad()
    {
        return $this->belongsTo(Universidad::class, 'id_universidad');
    }

    /**
     * Obtiene el programa asociado a la postulación.
     */
    public function programa()
    {
        return $this->belongsTo(Programa::class, 'id_programa');
    }

    /**
     * Obtiene la categoría de sisben asociada a la postulación.
     */
    public function sisben()
    {
        return $this->belongsTo(Sisben::class, 'id_sisben');
    }

    /**
     * Obtiene la nota asociada a la postulación.
     */
    public function nota()
    {
        return $this->belongsTo(Nota::class, 'id_nota');
    }

    /**
     * Obtiene las preguntas asociadas a la postulación.
     */
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'id_pregunta');
    }

    /**
     * Obtiene el resultado asociado a la postulación.
     */
    public function resultado()
    {
        return $this->hasOne(Resultado::class, 'id_postulacion');
    }

    /**
     * Obtiene el beneficiario asociado a la postulación.
     */
    public function beneficiario()
    {
        return $this->hasOne(Beneficiario::class, 'id_postulacion');
    }
}