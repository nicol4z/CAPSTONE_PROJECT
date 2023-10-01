<?php

namespace App\Http\Controllers;


use App\Models\Empleado_Submuestra;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class EmpleadoSubmuestraController extends Controller
{

    public function crear_Relacion_Empleado_Submuestra ($rut_empleado, Request $request)
    {

        if ($request->resultados_agregar != null){

            $resultados = Arr::flatten($request->resultados_agregar);

            for ($i = 0, $largo = count($resultados); $i < $largo; $i += 8){

                $empleado_submuestra = new Empleado_Submuestra();

                $empleado_submuestra->rut_empleado = $rut_empleado;

                $empleado_submuestra->id_submuestra = $resultados[$i];

                $empleado_submuestra->id_parametro = $resultados[$i+1];

                $empleado_submuestra->valor_resultado = $resultados[$i+2];

                $empleado_submuestra->unidad = $resultados[$i+3];

                $empleado_submuestra->fecha_inicio_analisis = $resultados[$i+4];

                $empleado_submuestra->hora_inicio_analisis = $resultados[$i+5];

                $empleado_submuestra->fecha_termino_analisis = $resultados[$i+6];

                $empleado_submuestra->hora_termino_analisis = $resultados[$i+7];

                $empleado_submuestra->save();

            }

        }

    }

    public function eliminar_Relacion_Empleado_Submuestra(array $empleados_eliminar)
    {

        if ($empleados_eliminar != null){

            for ($i = 0, $largo = count($empleados_eliminar); $i < $largo; $i += 9){

                Empleado_Submuestra::where([
                                           ['rut_empleado', $empleados_eliminar[$i]],
                                           ['id_submuestra', $empleados_eliminar[$i+1]],
                                           ['id_parametro', $empleados_eliminar[$i+2]],
                                           ['valor_resultado', $empleados_eliminar[$i+3]],
                                           ['unidad', $empleados_eliminar[$i+4]],
                                           ['fecha_inicio_analisis', $empleados_eliminar[$i+5]],
                                           ['hora_inicio_analisis', $empleados_eliminar[$i+6]],
                                           ['fecha_termino_analisis', $empleados_eliminar[$i+7]],
                                           ['hora_termino_analisis', $empleados_eliminar[$i+8]],
                                           ])->delete();

            }

        }

    }


}
