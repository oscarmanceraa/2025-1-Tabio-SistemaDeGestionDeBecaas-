<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIdNotaFromPostulacionesTable extends Migration
{
    public function up()
    {
        Schema::table('postulaciones', function (Blueprint $table) {
            // Eliminar la restricción de clave foránea si existe
            if (Schema::hasColumn('postulaciones', 'id_nota')) {
                $table->dropForeign(['id_nota']);
                $table->dropColumn('id_nota');
            }
        });
    }

    public function down()
    {
        Schema::table('postulaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('id_nota')->nullable();
            $table->foreign('id_nota')->references('id_nota')->on('notas')->onDelete('restrict');
        });
    }
};
