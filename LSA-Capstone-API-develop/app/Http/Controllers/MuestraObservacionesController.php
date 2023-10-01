<?php

namespace App\Http\Controllers;

use App\Models\Muestra_Observaciones;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class MuestraObservacionesController extends Controller
{

    public function crear_Observacion ($RUM, $rut_empleado, $observaciones)
    {
        if ($observaciones != null){

            $muestra_observaciones = new Muestra_Observaciones();

            $muestra_observaciones->RUM = $RUM;

            $muestra_observaciones->rut_empleado = $rut_empleado;

            $muestra_observaciones->observaciones = $observaciones;

            $muestra_observaciones->fecha_observacion = Carbon::now()->format('Y/m/d');

            $muestra_observaciones->hora_observacion = Carbon::now()->format('H:i:s');

            $muestra_observaciones->save();

        }

    }

    public function actualizar_Observaciones ($RUM, $rut_empleado, $observaciones)
    {

        if($observaciones != null){

            $observacion = Arr::flatten($observaciones);

            if ($this->existe_Observacion($RUM, $rut_empleado, $observacion) != false){

                for ($i = 0, $largo = count($observacion); $i < $largo; $i += 3){

                    Muestra_Observaciones::where([
                                                 ['RUM', $RUM],
                                                 ['rut_empleado', $rut_empleado],
                                                 ['fecha_observacion', $observacion[$i]],
                                                 ['hora_observacion', $observacion[$i+1]]
                                                 ])->update([
                                                             'fecha_observacion' => Carbon::now()->format('Y/m/d'),
                                                             'hora_observacion' => Carbon::now()->format('H:i:s'),
                                                             'observaciones' => $observacion[$i+2]
                                                            ]);

                }

            }else{

                $this->crear_Observacion($RUM, $rut_empleado, $observacion[2]);

            }

        }

    }

    public function existe_Observacion ($RUM, $rut_empleado, array $observacion)
    {

        if(!empty($observacion)){

            for ($i = 0, $largo = count($observacion); $i < $largo; $i += 3){

                $observacion = Muestra_Observaciones::where([
                                                            ['RUM', $RUM],
                                                            ['rut_empleado', $rut_empleado],
                                                            ['fecha_observacion', $observacion[$i]],
                                                            ['hora_observacion', $observacion[$i+1]]
                                                            ])->get();

                if(!$observacion->isEmpty()){

                    return true;

                }else{

                    return false;

                }

            }

        }

    }

}
