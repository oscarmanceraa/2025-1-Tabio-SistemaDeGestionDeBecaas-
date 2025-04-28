<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id('id_pregunta');
            $table->boolean('horas_sociales')->default(false);
            $table->integer('cantidad_horas_sociales')->nullable();
            $table->string('obs_horas')->nullable();
            $table->boolean('discapacidad')->default(false);
            $table->string('tipo_discapacidad', 100)->nullable();
            $table->string('obs_discapacidad')->nullable();
            $table->boolean('colegio_publico')->default(false);
            $table->string('nombre_colegio')->nullable();
            $table->boolean('madre_cabeza_familia')->default(false);
            $table->boolean('victima_conflicto')->default(false);
            $table->boolean('declaracion_juramentada')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('preguntas');
    }
};