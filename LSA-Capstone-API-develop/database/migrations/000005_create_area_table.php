<?php

use App\Models\Area;

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
        Schema::create('area', function (Blueprint $table) {

            $table->id('id_area');

            $table->string('nombre_area', 255);

        });

        $this->crear_Areas('Instrumental', 'Área de pretratamiento', 'Área de suelos', 'Área de aguas', 'Área de microbiología');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('area');
    }


    private function crear_Areas (string ...$areas)
    {

        foreach ($areas as $area) {

            $model = new Area();

            $model->nombre_area = $area;

            $model->save();

        }
    }

};
