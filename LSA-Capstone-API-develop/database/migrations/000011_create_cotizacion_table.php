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
        Schema::create('cotizacion', function (Blueprint $table) {

            $table->id('id_cotizacion');

            $table->string('numero_cotizacion', 255);

            $table->date('fecha_ingreso');

            $table->date('fecha_emision');

            $table->string('nombre_original_documento');

            $table->string('nombre_documento');

            $table->string('path_documento');

            $table->string('rut_solicitante', 255);

            $table->foreign('rut_solicitante')->references('rut_solicitante')->on('Solicitante')
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
        Schema::dropIfExists('cotizacion');
    }
};
