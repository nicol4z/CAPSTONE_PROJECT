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
        Schema::create('solicitante_encuesta', function (Blueprint $table) {

            $table->string('rut_solicitante', 255);

            $table->unsignedBigInteger('id_encuesta');

            $table->foreign('rut_solicitante')->references('rut_solicitante')->on('Solicitante')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

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
        Schema::dropIfExists('solicitante_encuesta');
    }
};
