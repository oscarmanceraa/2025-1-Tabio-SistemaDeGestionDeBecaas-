<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('funcionalidades', function (Blueprint $table) {
            $table->integer('id_funcionalidad')->primary();
            $table->string('nombre', 100);
            $table->string('descripcion')->nullable();
            $table->string('modulo', 50);
            $table->string('ruta')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('funcionalidades');
    }
};