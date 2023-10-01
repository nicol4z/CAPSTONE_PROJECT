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
        Schema::create('norma_matriz', function (Blueprint $table) {

            $table->unsignedBigInteger('id_norma');

            $table->unsignedBigInteger('id_matriz');

            $table->foreign('id_norma')->references('id_norma')->on('Norma')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_matriz')->references('id_matriz')->on('Matriz')
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
        Schema::dropIfExists('norma_matriz');
    }
};
