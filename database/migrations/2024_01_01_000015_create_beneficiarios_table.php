<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('beneficiarios', function (Blueprint $table) {
            $table->id('id_beneficiario');
            $table->unsignedBigInteger('id_postulacion');
            $table->unsignedBigInteger('id_resultado');
            $table->decimal('monto_beneficio', 12, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->boolean('vigente')->default(true);
            $table->unsignedBigInteger('id_estado');
            $table->timestamps();

            $table->foreign('id_postulacion')
                  ->references('id_postulacion')  // Changed from 'id' to 'id_postulacion'
                  ->on('postulaciones')
                  ->onDelete('restrict');

            $table->foreign('id_resultado')
                  ->references('id_resultado')  // Changed from 'id' to 'id_resultado'
                  ->on('resultados')
                  ->onDelete('restrict');

            $table->foreign('id_estado')
                  ->references('id_estado')
                  ->on('estados')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('beneficiarios');
    }
};