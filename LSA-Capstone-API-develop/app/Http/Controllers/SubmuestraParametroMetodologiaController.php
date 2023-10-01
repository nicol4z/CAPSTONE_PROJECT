<?php

namespace App\Http\Controllers;

use App\Models\Submuestra;
use App\Models\Submuestra_Parametro_Metodologia;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubmuestraParametroMetodologiaController extends Controller
{

    public function crear_Relacion_Submuestra_Parametro_Metodologia ($id_submuestra, $parametros_agregar)
    {

        $submuestra = Submuestra::find($id_submuestra);

        if (($parametros_agregar != null) && ($submuestra->exists())){

            foreach($parametros_agregar as $parametro_metodologia){

                $submuestra_parametro_metodologia = new Submuestra_Parametro_Metodologia();

                $submuestra_parametro_metodologia->id_submuestra = $id_submuestra;

                $submuestra_parametro_metodologia->id_parametro = $parametro_metodologia['id_parametro'];

                $submuestra_parametro_metodologia->id_metodologia = $parametro_metodologia['id_metodologia'];

                $submuestra_parametro_metodologia->save();

            }

        }

    }

    public function crear_Relacion_Para_Actualizacion_Submuestra_Parametro_Metodologia ($parametros)
    {

        for ($i = 0, $largo = count($parametros); $i < $largo; $i += 3){

            $submuestra = Submuestra::find($parametros[$i]);

            if ($submuestra->exists()){

                $submuestra_parametro_metodologia = new Submuestra_Parametro_Metodologia();

                $submuestra_parametro_metodologia->id_submuestra = $parametros[$i];

                $submuestra_parametro_metodologia->id_parametro = $parametros[$i+1];

                $submuestra_parametro_metodologia->id_metodologia = $parametros[$i+2];

                $submuestra_parametro_metodologia->save();

            }

        }

    }

    public function actualizar_Relacion_Submuestra_Parametro_Metodologia(Request $request)
    {

        if(($request->parametro_submuestra_agregar != null) && ($request->parametro_submuestra_eliminar == null)){

            $this->crear_Relacion_Para_Actualizacion_Submuestra_Parametro_Metodologia(Arr::flatten($request->parametro_submuestra_agregar));

        }else if(($request->parametro_submuestra_agregar == null) && ($request->parametro_submuestra_eliminar != null)){

            $this->eliminar_Relacion_Submuestra_Parametro_Metodologia(Arr::flatten($request->parametro_submuestra_eliminar));

        }else if(($request->parametro_submuestra_agregar != null) && ($request->parametro_submuestra_eliminar != null)){

            $this->crear_Relacion_Para_Actualizacion_Submuestra_Parametro_Metodologia(Arr::flatten($request->parametro_submuestra_agregar));

            $this->eliminar_Relacion_Submuestra_Parametro_Metodologia(Arr::flatten($request->parametro_submuestra_eliminar));

        }

    }

    public function eliminar_Relacion_Submuestra_Parametro_Metodologia(array $submuestra_parametro_metodologia_eliminar)
    {

        if ($submuestra_parametro_metodologia_eliminar != null){

            for ($i = 0, $largo = count($submuestra_parametro_metodologia_eliminar); $i < $largo; $i += 3){

                Submuestra_Parametro_Metodologia::where([
                                                        ['id_submuestra', '=', $submuestra_parametro_metodologia_eliminar[$i]],
                                                        ['id_parametro', '=', $submuestra_parametro_metodologia_eliminar[$i+1]],
                                                        ['id_metodologia', '=', $submuestra_parametro_metodologia_eliminar[$i+2]],
                                                        ])->delete();

            }

        }

    }

}
