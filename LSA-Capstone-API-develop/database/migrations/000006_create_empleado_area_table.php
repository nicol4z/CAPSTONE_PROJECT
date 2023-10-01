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
        Schema::create('empleado_area', function (Blueprint $table) {

            $table->string('rut_empleado');

            $table->unsignedBigInteger('id_area');

            $table->string('tipo_analisis', 512);

            $table->foreign('rut_empleado')->references('rut_empleado')->on('Empleado')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_area')->references('id_area')->on('Area')
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
        Schema::dropIfExists('empleado_area');
    }
};
