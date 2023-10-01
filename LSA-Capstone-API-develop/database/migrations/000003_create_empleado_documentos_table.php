<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_documentos', function (Blueprint $table) {

            $table->id('id_documento');

            $table->date('fecha_subida');

            $table->string('rut_empleado', 255);

            $table->string('nombre_original_documento', 255);

            $table->string('nombre_documento', 255);

            $table->string('path_documento', 255);

            $table->foreign('rut_empleado')->references('rut_empleado')->on('Empleado')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleado_documentos');
    }
};
