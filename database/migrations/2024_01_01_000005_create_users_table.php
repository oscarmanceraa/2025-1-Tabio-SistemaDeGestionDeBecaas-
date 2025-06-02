<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->unsignedBigInteger('id_persona');
            $table->tinyInteger('id_rol');
            $table->unsignedBigInteger('id_estado')->default(1);
            $table->string('codigo', 30)->unique();
            $table->string('email')->unique();
            $table->string('username', 100)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_persona')
                  ->references('id_persona')
                  ->on('personas')
                  ->onDelete('restrict');

            $table->foreign('id_rol')
                  ->references('id_rol')
                  ->on('roles')
                  ->onDelete('restrict');

            $table->foreign('id_estado')
                  ->references('id_estado')
                  ->on('estados')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};