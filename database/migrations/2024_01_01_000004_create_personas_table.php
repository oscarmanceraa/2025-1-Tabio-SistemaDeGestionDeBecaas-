<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id('id_persona');
            $table->tinyInteger('id_tipo_documento');
            $table->string('primer_nombre', 50);
            $table->string('segundo_nombre', 50)->nullable();
            $table->string('primer_apellido', 50);
            $table->string('segundo_apellido', 50)->nullable();
            $table->string('numero_documento', 20);
            $table->date('fecha_exp_documento');
            $table->string('direccion', 255);
            $table->string('observaciones', 255)->nullable();
            $table->timestamps();

            $table->unique('numero_documento');
            $table->foreign('id_tipo_documento')
                  ->references('id_tipo_documento')
                  ->on('tipos_documento')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('personas');
    }
};