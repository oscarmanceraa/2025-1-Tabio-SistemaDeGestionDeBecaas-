<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('postulaciones', function (Blueprint $table) {
            $table->id('id_postulacion');
            $table->unsignedBigInteger('id_persona');
            $table->tinyInteger('id_tipo_beneficio');
            $table->tinyInteger('cantidad_postulaciones')->default(1);
            $table->tinyInteger('semestre');
            $table->unsignedBigInteger('id_universidad');
            $table->unsignedBigInteger('id_programa');
            $table->tinyInteger('id_sisben');
            $table->unsignedBigInteger('id_nota');
            $table->unsignedBigInteger('id_pregunta');
            $table->date('fecha_postulacion')->useCurrent();
            $table->timestamps();

            $table->foreign('id_persona')
                  ->references('id_persona')
                  ->on('personas')
                  ->onDelete('restrict');

            $table->foreign('id_tipo_beneficio')
                  ->references('id_tipo_beneficio')
                  ->on('tipos_beneficio')
                  ->onDelete('restrict');

            $table->foreign('id_universidad')
                  ->references('id_universidad')
                  ->on('universidades')
                  ->onDelete('restrict');

            $table->foreign('id_programa')
                  ->references('id_programa')
                  ->on('programas')
                  ->onDelete('restrict');

            $table->foreign('id_sisben')
                  ->references('id_sisben')
                  ->on('sisben')
                  ->onDelete('restrict');

            $table->foreign('id_nota')
                  ->references('id_nota')
                  ->on('notas')
                  ->onDelete('restrict');

            $table->foreign('id_pregunta')
                  ->references('id_pregunta')
                  ->on('preguntas')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('postulaciones');
    }
};