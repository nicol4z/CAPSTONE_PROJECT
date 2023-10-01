<?php

namespace App\Http\Controllers;

use App\Models\Norma_Tabla;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NormaTablaController extends Controller
{

    public function crear_Relacion_Norma_Tabla($id_norma, $id_tabla)
    {

        $norma_tabla = new Norma_Tabla();

        $norma_tabla->id_norma = $id_norma;

        $norma_tabla->id_tabla = $id_tabla;

        $norma_tabla->save();

    }

}
