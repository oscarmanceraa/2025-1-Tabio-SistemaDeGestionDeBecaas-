<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id('id_nota');
            $table->unsignedBigInteger('id_persona');
            $table->decimal('promedio', 4, 2);
            $table->string('observaciones', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_persona')
                  ->references('id_persona')  // Changed from 'id' to 'id_persona'
                  ->on('personas')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notas');
    }
};