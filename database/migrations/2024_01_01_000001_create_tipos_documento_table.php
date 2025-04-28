<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tipos_documento', function (Blueprint $table) {
            $table->tinyInteger('id_tipo_documento')->primary();
            $table->string('nombre', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipos_documento');
    }
};