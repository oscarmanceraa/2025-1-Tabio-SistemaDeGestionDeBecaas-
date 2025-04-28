<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('programas', function (Blueprint $table) {
            $table->id('id_programa');
            $table->string('nombre');
            $table->unsignedBigInteger('id_universidad');  // Changed to match universidades table id type
            $table->decimal('valor_matricula', 12, 2);
            $table->integer('puntaje');
            $table->timestamps();

            $table->foreign('id_universidad')
                  ->references('id_universidad')
                  ->on('universidades')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('programas');
    }
};