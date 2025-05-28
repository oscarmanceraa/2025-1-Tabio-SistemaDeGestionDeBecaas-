<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPromedioToPostulacionesTable extends Migration
{
    public function up()
    {
        Schema::table('postulaciones', function (Blueprint $table) {
            $table->decimal('promedio', 4, 2)->nullable()->after('id_pregunta');
        });
    }

    public function down()
    {
        Schema::table('postulaciones', function (Blueprint $table) {
            $table->dropColumn('promedio');
        });
    }
}
