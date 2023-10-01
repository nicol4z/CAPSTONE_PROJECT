<?php

namespace App\Http\Controllers;

use App\Models\Muestra_Cotizacion;

use Illuminate\Http\Request;

class MuestraCotizacionController extends Controller
{

    public function crear_Relacion_Muestra_Cotizacion($RUM, $id_cotizacion)
    {
        if($id_cotizacion != null){

            $muestra_cotizacion = new Muestra_Cotizacion();

            $muestra_cotizacion->RUM = $RUM;

            $muestra_cotizacion->id_cotizacion = $id_cotizacion;

            $muestra_cotizacion->save();

        }

    }

    public function actualizar_Relacion_Muestra_Cotizacion(Request $request)
    {

        if($request->id_cotizacion != null){

            if($this->existe_Cotizacion($request->RUM, $request->id_cotizacion) != false){

                Muestra_Cotizacion::where([
                                          ['RUM', $request->RUM],
                                          ])->update([
                                                      'id_cotizacion' => $request->id_cotizacion
                                                     ]);

            }else{

                $this->crear_Relacion_Muestra_Cotizacion($request->RUM, $request->id_cotizacion);

            }

        }

    }

    public function eliminar_Relacion_Muestra_Cotizacion ($RUM, $id_cotizacion)
    {

        Muestra_Cotizacion::where([
                                  ['RUM', $RUM],
                                  ['id_cotizacion', $id_cotizacion]
                                  ])->delete();

    }

    public function existe_Cotizacion ($RUM, $id_cotizacion)
    {

        if($id_cotizacion != null){

            $cotizacion = Muestra_Cotizacion::where([
                                                    ['RUM', $RUM],
                                                    ['id_cotizacion', $id_cotizacion]
                                                    ])->get();

            if(!$cotizacion->isEmpty()){

                return true;

            }else{

                return false;

            }

        }

    }

}
