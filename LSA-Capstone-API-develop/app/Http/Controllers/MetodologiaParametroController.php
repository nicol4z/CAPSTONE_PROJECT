<?php

namespace App\Http\Controllers;

use App\Models\Parametro;
use App\Models\Metodologia_Parametro;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class MetodologiaParametroController extends Controller
{

    public function crear_Relacion_Metodologia_Parametro ($id_parametro, Request $request)
    {

        $parametro = Parametro::find($id_parametro);

        $metodologias = Arr::flatten($request->metodologias_agregar);

        if ($metodologias != null){

            for ($i = 0, $largo = count($metodologias); $i < $largo; $i += 1){

                $metodologia_parametro = new Metodologia_Parametro();

                $metodologia_parametro->id_parametro = $id_parametro;

                $metodologia_parametro->id_metodologia = $metodologias[$i];

                $parametro->metodologias()->sync($metodologia_parametro, false);

            }

        }

    }

    public function eliminar_Relacion_Metodologia_Parametro (Request $request)
    {
        $parametro = Parametro::find($request->id_parametro);

        $metodologias = Arr::flatten($request->metodologias_eliminar);

        if ($metodologias != null){

            for ($i = 0, $largo = count($metodologias); $i < $largo; $i += 1){

                $parametro->metodologias()->detach($metodologias[$i]);

            }

        }

    }

}
