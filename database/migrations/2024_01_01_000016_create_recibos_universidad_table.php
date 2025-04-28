<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recibos_universidad', function (Blueprint $table) {
            $table->id('id_recibo');
            $table->unsignedBigInteger('id_beneficiario');
            $table->string('numero_recibo', 50);
            $table->date('fecha_recibo');
            $table->decimal('valor_recibo', 12, 2);
            $table->decimal('valor_pagado', 12, 2);
            $table->string('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('id_beneficiario')
                  ->references('id_beneficiario')
                  ->on('beneficiarios')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('recibos_universidad');
    }
};