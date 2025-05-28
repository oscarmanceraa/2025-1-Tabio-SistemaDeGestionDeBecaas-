use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documentos_postulacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_postulacion');
            $table->string('tipo_documento');
            $table->string('ruta');
            $table->boolean('verificado')->default(false);
            $table->timestamps();

            $table->foreign('id_postulacion')->references('id_postulacion')->on('postulaciones')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('documentos_postulacion');
    }
};
