<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rol_funcionalidad', function (Blueprint $table) {
            $table->integer('id_rol_funcionalidad')->primary();
            $table->tinyInteger('id_rol');
            $table->integer('id_funcionalidad');
            $table->timestamps();

            $table->foreign('id_rol')->references('id_rol')->on('roles');
            $table->foreign('id_funcionalidad')->references('id_funcionalidad')->on('funcionalidades');

            // Composite unique key
            $table->unique(['id_rol', 'id_funcionalidad']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rol_funcionalidad');
    }
};