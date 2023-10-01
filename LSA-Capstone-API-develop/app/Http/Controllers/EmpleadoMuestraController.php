<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Empleado_Muestra;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class EmpleadoMuestraController extends Controller
{

    // TODO: Optimizar

    public function crear_Relacion_Empleado_Muestra($RUM, $fecha_entrega, array $empleados_agregar)
    {
        $orden = 1;

        $empleados_agregados = [];

        if ($empleados_agregar != null){

            for ($i = 0, $largo = count($empleados_agregar); $i < $largo; $i += 2){

                if(in_array($empleados_agregar[$i], $empleados_agregados) == true){

                    $empleado_muestra = new Empleado_Muestra();

                    $empleado_muestra->rut_empleado = $empleados_agregar[$i];

                    $empleado_muestra->RUM = $RUM;

                    $empleado_muestra->orden_de_analisis = $this->buscar_Orden_De_Analisis($empleados_agregar[$i], $RUM);

                    $empleado_muestra->id_parametro = $empleados_agregar[$i+1];

                    $empleado_muestra->fecha_entrega = Carbon::createFromFormat('Y-m-d', $fecha_entrega)->subDay(3)->format('Y-m-d');

                    $empleado_muestra->save();

                }else{

                    $empleado_muestra = new Empleado_Muestra();

                    $empleado_muestra->rut_empleado = $empleados_agregar[$i];

                    $empleado_muestra->RUM = $RUM;

                    $empleado_muestra->orden_de_analisis = $orden;

                    $empleado_muestra->id_parametro = $empleados_agregar[$i+1];

                    $empleado_muestra->fecha_entrega = Carbon::createFromFormat('Y-m-d', $fecha_entrega)->subDay(3)->format('Y-m-d');

                    $empleado_muestra->save();

                    $orden++;

                    array_push($empleados_agregados, $empleados_agregar[$i]);

                }

            }
        }

        return $empleados_agregados;

    }

    public function crear_Relacion_Para_Actualizacion_Empleado_Muestra($RUM, array $empleados_agregar)
    {

        if ($empleados_agregar != null){

            for ($i = 0, $largo = count($empleados_agregar); $i < $largo; $i += 4){

                $empleado_muestra = new Empleado_Muestra();

                $empleado_muestra->rut_empleado = $empleados_agregar[$i];

                $empleado_muestra->RUM = $RUM;

                $empleado_muestra->orden_de_analisis = $empleados_agregar[$i+1];

                $empleado_muestra->id_parametro = $empleados_agregar[$i+2];

                $empleado_muestra->fecha_entrega = $empleados_agregar[$i+3];

                $empleado_muestra->save();

            }

        }

    }

    public function actualizar_Relacion_Empleado_Muestra(Request $request)
    {

        if(($request->empleados_agregar != null) && ($request->empleados_eliminar == null)){

            $this->crear_Relacion_Para_Actualizacion_Empleado_Muestra($request->RUM, Arr::flatten($request->empleados_agregar));

        }else if(($request->empleados_agregar == null) && ($request->empleados_eliminar != null)){

            $this->eliminar_Relacion_Empleado_Muestra($request->RUM, Arr::flatten($request->empleados_eliminar));

        }else if(($request->empleados_agregar != null) && ($request->empleados_eliminar != null)){
            
            $this->eliminar_Relacion_Empleado_Muestra($request->RUM, Arr::flatten($request->empleados_eliminar));

            $this->crear_Relacion_Para_Actualizacion_Empleado_Muestra($request->RUM, Arr::flatten($request->empleados_agregar));

          

        }

    }

    public function eliminar_Relacion_Empleado_Muestra($RUM, array $empleados_eliminar)
    {

        if ($empleados_eliminar != null){

            for ($i = 0, $largo = count($empleados_eliminar); $i < $largo; $i += 5){

                Empleado_Muestra::where([
                                        ['rut_empleado', $empleados_eliminar[$i]],
                                        ['RUM', $RUM],
                                        ['orden_de_analisis', $empleados_eliminar[$i+1]],
                                        ['id_parametro', $empleados_eliminar[$i+2]],
                                        ['fecha_entrega', $empleados_eliminar[$i+3]],
                                        ['estado', $empleados_eliminar[$i+4]]
                                        ])->delete();

            }

        }

    }

    public function buscar_Orden_De_Analisis ($rut_empleado, $RUM)
    {

        $empleado = Empleado::find($rut_empleado);

        if ($empleado){

            $orden_de_analisis = Empleado_Muestra::where([
                                                         ['rut_empleado', '=', $rut_empleado],
                                                         ['RUM', '=', $RUM]
                                                         ])
                                                 ->value('orden_de_analisis');

            return $orden_de_analisis;

        }else{

            return null;

        }

    }

}
