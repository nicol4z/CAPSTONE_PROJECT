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
        Schema::create('metodologia', function (Blueprint $table) {

            $table->id('id_metodologia');

            $table->string('nombre_metodologia', 255)
                  ->unique();

            $table->string('detalle_metodologia', 512)
                  ->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metodologia');
    }
};
