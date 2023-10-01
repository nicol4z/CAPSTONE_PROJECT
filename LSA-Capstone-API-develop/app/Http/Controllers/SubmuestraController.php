<?php

namespace App\Http\Controllers;

use App\Models\Submuestra;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\SubmuestraParametroMetodologiaController;

class SubmuestraController extends Controller
{

    public function crear_Submuestras ($RUM, $submuestras)
    {

        if($submuestras != null){

            foreach($submuestras as $submuestra){

                $nueva_submuestra = new Submuestra();

                $nueva_submuestra->identificador = $submuestra['identificador'];

                $nueva_submuestra->orden = $submuestra['orden'];

                $nueva_submuestra->RUM = $RUM;

                $nueva_submuestra->save();

                (new SubmuestraParametroMetodologiaController)->crear_Relacion_Submuestra_Parametro_Metodologia($nueva_submuestra->id_submuestra, $submuestra['parametros_agregar']);

            }

        }

    }

    public function ingresar_Submuestras(Request $request)
    {

        if(($request->submuestras_agregar != null) && ($request->submuestras_eliminar == null)){

            $this->crear_Submuestras($request->RUM, $request->submuestras_agregar);

        }else if(($request->submuestras_agregar == null) && ($request->submuestras_eliminar != null)){

            $this->eliminar_Submuestra(Arr::flatten($request->submuestras_eliminar));

        }else if(($request->submuestras_agregar != null) && ($request->submuestras_eliminar != null)){

            $this->crear_Submuestras($request->RUM, $request->submuestras_agregar);

            $this->eliminar_Submuestra(Arr::flatten($request->submuestras_eliminar));

        }

    }

    public function actualizar_Submuestras($submuestras)
    {

        if($submuestras != null){

            foreach($submuestras as $submuestra){

                Submuestra::where('id_submuestra', '=', $submuestra['id_submuestra'])
                        ->update([
                                    'identificador' => $submuestra['identificador'],
                                    'orden' => $submuestra['orden']
                                ]);

            }

        }

    }

    public function eliminar_Submuestra(array $submuestras_eliminar)
    {

        if ($submuestras_eliminar != null){

            for ($i = 0, $largo = count($submuestras_eliminar); $i < $largo; $i += 1){

                $submuestra_eliminar = Submuestra::find($submuestras_eliminar[$i]);

                if($submuestra_eliminar != null){

                    $submuestra_eliminar->delete();

                }

            }

        }

    }

    public function existe_Submuestra ($RUM, $id_submuestra)
    {

        $existe_submuestra = Submuestra::where([
                                         ['RUM', '=', $RUM],
                                         ['id_submuestra', '=', $id_submuestra]
                                        ])
                                 ->get();

        if(!$existe_submuestra->isEmpty()){

            return true;

        }else{

            return false;

        }

    }

}
