<?php

namespace App\Http\Controllers;

use App\Models\Metodologia;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\EmpleadoMetodologiaController;

class MetodologiaController extends Controller
{
    public function metodologias()
    {

        $metodologias = Metodologia::with('empleados:rut_empleado,nombre,apellido')->get();

        return $metodologias;

    }

    public function detalles_Metodologia($id_metodologia){

        $metodologia = Metodologia::find($id_metodologia);

        if ($metodologia){

            $detalles = Metodologia::with('empleados:rut_empleado,nombre,apellido')->find($id_metodologia);

            return $detalles;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function crear_Metodologia (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Agregar_Metodologia());

        $existe_metodologia = $this->existe_Metodologia_Busqueda_Por_Nombre($request->nombre_metodologia);

        if((!is_array($validacion)) && ($existe_metodologia == false)){

            $metodologia = Metodologia::create($request->all());

            (new EmpleadoMetodologiaController)->crear_Relacion_Empleado_Metodologia($metodologia->id_metodologia, $request);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function actualizar_Metodologia (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Actualizar_Metodologia());

        $metodologia = Metodologia::find($request->id_metodologia);

        if(!is_array($validacion)){

            $metodologia->update($request->all());

            (new EmpleadoMetodologiaController)->crear_Relacion_Empleado_Metodologia($request->id_metodologia, $request);

            (new EmpleadoMetodologiaController)->eliminar_Relacion_Empleado_Metodologia($request);

            return response()->json('success', 200);

        }else{

            return response()->json($validacion);

        }

    }

    public function existe_Metodologia_Busqueda_Por_Nombre($nombre_metodologia)
    {

        $metodologia = Metodologia::where('nombre_metodologia', '=', $nombre_metodologia)->first();

        if( $metodologia != null){

            return true;

        }else{

            return false;
        }

    }

    public function validacion ($parametros, $tipo_validacion)
    {

        $validacion = Validator::make($parametros, $tipo_validacion);

        if ($validacion->fails()){

            return response()->json(['error' => 'bad request'], 400);
        }
        else{

            return true;
        }

    }

    public function validacion_Agregar_Metodologia ()
    {

        return [

            'nombre_metodologia' => 'bail|unique:Metodologia|required|min:3|max:255',

        ];

    }

    public function validacion_Actualizar_Metodologia ()
    {

        return [

            'nombre_metodologia' => 'unique:Metodologia|min:3|max:255',

        ];

    }

}
