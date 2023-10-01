<?php

namespace App\Http\Controllers;

use App\Models\Metodologia;
use App\Models\Empleado_Metodologia;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class EmpleadoMetodologiaController extends Controller
{

    public function crear_Relacion_Empleado_Metodologia ($id_metodologia, Request $request){

        $metodologia = Metodologia::find($id_metodologia);

        $empleados = Arr::flatten($request->empleados_agregar);

        if ($empleados != null){

            for ($i = 0, $largo = count($empleados); $i < $largo; $i += 1){

                $empleado_metodologia = new Empleado_Metodologia();

                $empleado_metodologia->rut_empleado = $empleados[$i];

                $empleado_metodologia->id_metodologia = $metodologia->id_metodologia;

                $metodologia->empleados()->sync($empleado_metodologia, false);

            }
        }

    }

    public function eliminar_Relacion_Empleado_Metodologia (Request $request)
    {

        $metodologia = Metodologia::find($request->id_metodologia);

        $empleados_eliminar = Arr::flatten($request->empleados_eliminar);

        if ($empleados_eliminar != null){

            for ($i = 0, $largo = count($empleados_eliminar); $i < $largo; $i += 1){

                $metodologia->empleados()->detach($empleados_eliminar[$i]);

            }
        }

    }


}
