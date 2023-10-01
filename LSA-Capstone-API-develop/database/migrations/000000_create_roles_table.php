<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->integer('id_rol')->unique();
            $table->string('descripcion')->unique();;
            $table->timestamps();
        });

        // Data default para migración de acuerdo a roles ya existentes
        $data =  array(
            [
                'descripcion' => 'Administrador',
                'id_rol' => 0
            ],[
                'descripcion' => 'Gerente',
                'id_rol' => 1
            ],[
                'descripcion' => 'Jefe(a) de Laboratorio',
                'id_rol' => 2
            ],[
                'descripcion' => 'Químico',
                'id_rol' => 3
            ],[
                'descripcion' => 'Analista Químico',
                'id_rol' => 4
            ],[
                'descripcion' => 'Solicitante',
                'id_rol' => 5
            ],[
                'descripcion' => 'Supervisor(a)',
                'id_rol' => 6
            ],[
                'descripcion' => 'Administrador(a) de Finanzas',
                'id_rol' => 7
            ],
            [
                'descripcion' => 'Recepcionista',
                'id_rol' => 8
            ]
        );
        
            
      
        foreach ($data as $datum){
            $role = new Role(); //The Category is the model for your migration
            $role->descripcion =$datum['descripcion'];
            $role->id_rol =$datum['id_rol'];
            $role->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
