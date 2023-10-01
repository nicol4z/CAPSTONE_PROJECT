<?php

namespace App\Http\Controllers;

use App\Models\Tabla_Parametro_Metodologia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TablaParametroMetodologiaController extends Controller
{

    public function crear_Relacion_Tabla_Parametro_Metodologia ($id_tabla, $id_parametro, $id_metodologia)
    {

        $tabla_parametro_metodologia = new Tabla_Parametro_Metodologia();

        $tabla_parametro_metodologia->id_tabla = $id_tabla;

        $tabla_parametro_metodologia->id_parametro = $id_parametro;

        $tabla_parametro_metodologia->id_metodologia = $id_metodologia;

        $tabla_parametro_metodologia->save();

    }

}
