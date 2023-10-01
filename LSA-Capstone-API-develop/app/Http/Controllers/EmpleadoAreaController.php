<?php

namespace App\Http\Controllers;

use App\Models\Empleado_Area;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class EmpleadoAreaController extends Controller
{
    public function crear_Relacion_Empleado_Area ($rut_empleado, array $areas_agregar)
    {

        if ($areas_agregar != null){

            for ($i = 0, $largo = count($areas_agregar); $i < $largo; $i += 2){

                $empleado_area = new Empleado_Area();

                $empleado_area->rut_empleado = $rut_empleado;

                $empleado_area->id_area = $areas_agregar[$i];

                $empleado_area->tipo_analisis = $areas_agregar[$i+1];

                $empleado_area->save();

            }
        }

    }

    public function actualizar_Relacion_Empleado_Area (Request $request)
    {
        if(($request->areas_agregar != null) && ($request->areas_eliminar == null)){

            $this->crear_Relacion_Empleado_Area($request->rut_empleado, Arr::flatten($request->areas_agregar));

        }else if(($request->areas_agregar == null) && ($request->areas_eliminar != null)){

            $this->eliminar_Relacion_Empleado_Area($request->rut_empleado, Arr::flatten($request->areas_eliminar));

        }else if(($request->areas_agregar != null) && ($request->areas_eliminar != null)){

            $this->crear_Relacion_Empleado_Area($request->rut_empleado, Arr::flatten($request->areas_agregar));

            $this->eliminar_Relacion_Empleado_Area($request->rut_empleado, Arr::flatten($request->areas_eliminar));

        }

    }

    public function eliminar_Relacion_Empleado_Area ($rut_empleado, $areas_eliminar)
    {

        if ($areas_eliminar != null){

            for ($i = 0, $largo = count($areas_eliminar); $i < $largo; $i += 3){

                Empleado_Area::where([
                                     ['rut_empleado', $rut_empleado],
                                     ['id_area', $areas_eliminar[$i]],
                                     ['tipo_analisis', $areas_eliminar[$i+2]]
                                     ])->delete();

            }

        }

    }

}
