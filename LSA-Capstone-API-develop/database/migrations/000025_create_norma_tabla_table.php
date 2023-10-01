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
        Schema::create('norma_tabla', function (Blueprint $table) {

            $table->unsignedBigInteger('id_norma');

            $table->unsignedBigInteger('id_tabla');

            $table->foreign('id_norma')->references('id_norma')->on('Norma')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_tabla')->references('id_tabla')->on('Tabla')
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
        Schema::dropIfExists('norma_tabla');
    }
};
