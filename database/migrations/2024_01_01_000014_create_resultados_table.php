<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resultados', function (Blueprint $table) {
            $table->id('id_resultado');
            $table->unsignedBigInteger('id_postulacion');
            $table->decimal('puntaje_total', 5, 2);
            $table->boolean('aprobado')->default(false);
            $table->date('fecha_evaluacion')->default(now());
            $table->string('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('id_postulacion')
                  ->references('id_postulacion')  // Changed from 'id' to 'id_postulacion'
                  ->on('postulaciones')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('resultados');
    }
};