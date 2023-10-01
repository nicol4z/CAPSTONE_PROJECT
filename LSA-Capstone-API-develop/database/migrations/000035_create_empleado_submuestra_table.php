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
        Schema::create('empleado_submuestra', function (Blueprint $table) {

            $table->string('rut_empleado', 255);

            $table->unsignedBigInteger('id_submuestra');

            $table->unsignedBigInteger('id_parametro');

            $table->float('valor_resultado')
                  ->nullable();

            $table->string('unidad', 255)
                  ->nullable();

            $table->date('fecha_inicio_analisis')
                  ->nullable();

            $table->time('hora_inicio_analisis')
                  ->nullable();

            $table->date('fecha_termino_analisis')
                  ->nullable();

            $table->time('hora_termino_analisis')
                  ->nullable();

            $table->foreign('rut_empleado')->references('rut_empleado')->on('Empleado')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_submuestra')->references('id_submuestra')->on('Submuestra')
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
        Schema::dropIfExists('empleado_submuestra');
    }
};
