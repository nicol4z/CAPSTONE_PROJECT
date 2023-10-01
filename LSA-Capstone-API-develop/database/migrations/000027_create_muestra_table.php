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
        Schema::create('muestra', function (Blueprint $table) {

            $table->id('RUM');

            $table->string('rut_empresa', 255);

            $table->string('nombre_empresa', 255);

            $table->unsignedBigInteger('id_ciudad');

            $table->string('direccion_empresa', 255);

            $table->string('muestreado_por', 255);

            $table->integer('cantidad_muestras');

            $table->string('prioridad', 255);

            $table->date('fecha_muestreo')
                  ->nullable();

            $table->time('hora_muestreo')
                  ->nullable();

            $table->float('temperatura_transporte', 4, 2)
                  ->nullable();

            $table->date('fecha_entrega');

            $table->date('fecha_ingreso');

            $table->time('hora_ingreso');

            $table->string('rut_transportista', 255)
                  ->nullable();

            $table->string('nombre_transportista', 255)
                  ->nullable();

            $table->string('patente_vehiculo', 255)
                  ->nullable();

            $table->string('tipo_pago', 255)
                  ->nullable();

            $table->float('valor_neto')
                  ->nullable();

            $table->string('estado')
                  ->nullable();

            $table->string('rut_empleado', 255);

            $table->unsignedBigInteger('id_matriz');

            $table->unsignedBigInteger('id_norma')
                  ->nullable();

            $table->unsignedBigInteger('id_orden_compra')
                  ->nullable();

            $table->unsignedBigInteger('id_encuesta')
                  ->nullable();

            $table->foreign('rut_empleado')->references('rut_empleado')->on('Empleado')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_matriz')->references('id_matriz')->on('Matriz')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_norma')->references('id_norma')->on('Norma')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_orden_compra')->references('id_orden_compra')->on('Orden_Compra')
                  ->onUpdate('cascade')
                  ->onDelete('set null');

            $table->foreign('id_encuesta')->references('id_encuesta')->on('Encuesta')
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
        Schema::dropIfExists('muestra');
    }
};
