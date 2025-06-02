<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('postulaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('id_convocatoria')->after('id_persona');
            
            $table->foreign('id_convocatoria')
                  ->references('id_convocatoria')
                  ->on('convocatorias')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('postulaciones', function (Blueprint $table) {
            $table->dropForeign(['id_convocatoria']);
            $table->dropColumn('id_convocatoria');
        });
    }
}; 