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
        Schema::create('muestra_telefono_transportista', function (Blueprint $table) {

            $table->unsignedBigInteger('RUM');

            $table->string('telefono_transportista', 255)
                  ->nullable();

            $table->foreign('RUM')->references('RUM')->on('Muestra')
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
        Schema::dropIfExists('muestra_telefono_transportista');
    }
};
