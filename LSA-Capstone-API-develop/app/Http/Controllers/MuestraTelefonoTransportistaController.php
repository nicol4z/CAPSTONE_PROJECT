<?php

namespace App\Http\Controllers;

use App\Models\Muestra_Telefono_Transportista;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class MuestraTelefonoTransportistaController extends Controller
{
    public function crear_Telefonos_Transportista ($RUM, $telefonos_agregar)
    {

        if ($telefonos_agregar != null){

            $telefonos = Arr::flatten($telefonos_agregar);

            for ($i = 0, $largo = count($telefonos); $i < $largo; $i += 1){

                $telefono_transportista = new Muestra_Telefono_Transportista();

                $telefono_transportista->RUM = $RUM;

                $telefono_transportista->telefono_transportista = $telefonos[$i];

                $telefono_transportista->save();

            }

        }
    }

    public function actualizar_Telefonos_Transportista(Request $request)
    {

        if(($request->telefonos_agregar != null) && ($request->telefonos_eliminar == null)){

            $this->crear_Telefonos_Transportista($request->RUM, $request->telefonos_agregar);

        }else if(($request->telefonos_agregar == null) && ($request->telefonos_eliminar != null)){

            $this->eliminar_Telefonos_Transportista($request->RUM, Arr::flatten($request->telefonos_eliminar));

        }else if(($request->telefonos_agregar != null) && ($request->telefonos_eliminar != null)){

            $this->crear_Telefonos_Transportista($request->RUM, $request->telefonos_agregar);

            $this->eliminar_Telefonos_Transportista($request->RUM, Arr::flatten($request->telefonos_eliminar));

        }

    }

    public function eliminar_Telefonos_Transportista($RUM, array $telefonos_eliminar)
    {

        if ($telefonos_eliminar != null){

            for ($i = 0, $largo = count($telefonos_eliminar); $i < $largo; $i += 1){

                Muestra_Telefono_Transportista::where([
                                                      ['RUM', $RUM],
                                                      ['telefono_transportista', $telefonos_eliminar[$i]]
                                                      ])->delete();

            }

        }

    }

}

