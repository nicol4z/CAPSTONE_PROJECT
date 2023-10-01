<?php

namespace App\Http\Controllers;

use App\Models\Matriz_Metodologia_Parametro;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MatrizMetodologiaParametroController extends Controller
{
    public function crear_Relacion_Matriz_Metodologia_Parametro($id_matriz, Request $request)
    {

        $parametros_metodologias = Arr::flatten($request->parametros_agregar);

        if ($parametros_metodologias != null){

            for ($i = 0, $largo = count($parametros_metodologias); $i < $largo; $i += 2){

                $matriz_metodologia_parametro = new Matriz_Metodologia_Parametro();

                $matriz_metodologia_parametro->id_matriz = $id_matriz;

                $matriz_metodologia_parametro->id_parametro = $parametros_metodologias[$i];

                $matriz_metodologia_parametro->id_metodologia = $parametros_metodologias[$i+1];

                $matriz_metodologia_parametro->save();

            }

        }

    }

    public function eliminar_Relacion_Matriz_Metodologia_Parametro (Request $request)
    {

        $parametros_metodologias = Arr::flatten($request->parametros_eliminar);

        if ($parametros_metodologias != null){

            for ($i = 0, $largo = count($parametros_metodologias); $i < $largo; $i += 2){

                $parametros_metodologias_a_eliminar = Matriz_Metodologia_Parametro::where([
                                                                                          ['id_matriz', $request->id_matriz],
                                                                                          ['id_metodologia', $parametros_metodologias[$i+1]],
                                                                                          ['id_parametro', $parametros_metodologias[$i]]
                                                                                          ]);

                $parametros_metodologias_a_eliminar->delete();

            }

        }

    }

}
