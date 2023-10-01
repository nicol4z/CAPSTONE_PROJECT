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
        Schema::create('empleado_parametro', function (Blueprint $table) {

            $table->string('rut_empleado', 255);

            $table->unsignedBigInteger('id_parametro');

            $table->foreign('rut_empleado')->references('rut_empleado')->on('Empleado')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_parametro')->references('id_parametro')->on('Parametro')
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
        Schema::dropIfExists('empleado_parametro');
    }
};
