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
        Schema::create('carta_control', function (Blueprint $table) {

            $table->id('id_carta_control');

            $table->string('nombre_original_documento');

            $table->string('nombre_documento');

            $table->string('path_documento');

            $table->unsignedBigInteger('RUM');

            $table->unsignedBigInteger('id_parametro');

            $table->foreign('RUM')->references('RUM')->on('Muestra')
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
        Schema::dropIfExists('carta_control');
    }
};
