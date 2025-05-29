<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sisben', function (Blueprint $table) {
            $table->tinyIncrements('id_sisben');
            $table->char('letra', 1);
            $table->tinyInteger('numero');
            $table->decimal('puntaje', 5, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sisben');
    }
};