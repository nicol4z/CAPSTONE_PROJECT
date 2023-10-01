<?php

namespace App\Http\Controllers;

use App\Models\Muestra_Parametro_Metodologia;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MuestraParametroMetodologiaController extends Controller
{

    public function crear_Relacion_Muestra_Parametro_Metodologia($RUM, array $parametros_agregar)
    {

        if ($parametros_agregar != null){

            for ($i = 0, $largo = count($parametros_agregar); $i < $largo; $i += 2){

                $muestra_parametro_metodologia = new Muestra_Parametro_Metodologia();

                $muestra_parametro_metodologia->RUM = $RUM;

                $muestra_parametro_metodologia->id_parametro = $parametros_agregar[$i];

                $muestra_parametro_metodologia->id_metodologia = $parametros_agregar[$i+1];

                $muestra_parametro_metodologia->save();

            }

        }

    }

    public function actualizar_Relacion_Muestra_Parametro_Metodologia(Request $request)
    {

        if(($request->parametros_agregar != null) && ($request->parametros_eliminar == null)){

            $this->crear_Relacion_Muestra_Parametro_Metodologia($request->RUM, Arr::flatten($request->parametros_agregar));

        }else if(($request->parametros_agregar == null) && ($request->parametros_eliminar != null)){

            $this->eliminar_Relacion_Muestra_Parametro_Metodologia($request->RUM, Arr::flatten($request->parametros_eliminar));

        }else if(($request->parametros_agregar != null) && ($request->parametros_eliminar != null)){

            $this->crear_Relacion_Muestra_Parametro_Metodologia($request->RUM, Arr::flatten($request->parametros_agregar));

            $this->eliminar_Relacion_Muestra_Parametro_Metodologia($request->RUM, Arr::flatten($request->parametros_eliminar));

        }

    }

    public function eliminar_Relacion_Muestra_Parametro_Metodologia($RUM, array $parametros_eliminar)
    {

        if ($parametros_eliminar != null){

            for ($i = 0, $largo = count($parametros_eliminar); $i < $largo; $i += 2){

                Muestra_Parametro_Metodologia::where([
                                                     ['RUM', $RUM],
                                                     ['id_parametro', $parametros_eliminar[$i]],
                                                     ['id_metodologia', $parametros_eliminar[$i+1]]
                                                     ])->delete();

            }

        }

    }

}
