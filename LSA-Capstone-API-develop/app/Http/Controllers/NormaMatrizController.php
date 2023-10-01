<?php

namespace App\Http\Controllers;

use App\Models\Norma_Matriz;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TablaController;

class NormaMatrizController extends Controller
{

    public function crear_Relacion_Norma_Matriz ($id_norma, $matrices)
    {

        foreach($matrices as $matriz){

            $norma_matriz = new Norma_Matriz();

            $norma_matriz->id_norma = $id_norma;

            $norma_matriz->id_matriz = $matriz['id_matriz'];

            $norma_matriz->save();

            (new TablaController)->crear_Tablas($id_norma, $matriz['id_matriz'], $matriz['tablas_agregar']);

        }

    }

    public function actualizar_Matrices (Request $request)
    {

        if(($request->matrices_agregar != null) && ($request->matrices_eliminar == null)){

            $this->crear_Relacion_Norma_Matriz($request->id_norma, $request->matrices_agregar);

        }else if(($request->matrices_agregar == null) && ($request->matrices_eliminar != null)){

            $this->eliminar_Relacion_Norma_Matriz($request->id_norma, $request->matrices_eliminar);

        }else if(($request->matrices_agregar != null) && ($request->matrices_eliminar != null)){

            $this->crear_Relacion_Norma_Matriz($request->id_norma, $request->matrices_agregar);

            $this->eliminar_Relacion_Norma_Matriz($request->id_norma, $request->matrices_eliminar);

        }

    }

    public function eliminar_Relacion_Norma_Matriz ($id_norma, $matrices)
    {

        foreach($matrices as $matriz){

            $norma_matriz_a_eliminar = Norma_Matriz::where([
                                                            ['id_norma', '=', $id_norma],
                                                            ['id_matriz', '=', $matriz['id_matriz']]
                                                            ]);

            if($norma_matriz_a_eliminar){

                $norma_matriz_a_eliminar->delete();

                (new TablaController)->eliminar_Tablas_Segun_Matriz($matriz['tablas_agregar']);

            }else{

                return response()->json(['error' => 'bad request'], 400);

            }

        }

    }

}
