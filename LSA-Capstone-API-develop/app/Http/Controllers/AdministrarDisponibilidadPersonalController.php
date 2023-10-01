<?php

namespace App\Http\Controllers;

use App\Models\Empleado;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;

class AdministrarDisponibilidadPersonalController extends Controller

{
    public function personal () {

        $personal = Empleado::where('rol', '!=', 'Administrador')->get();

        foreach ($personal as $persona){

            $this->cambiar_Formato_Fechas_Vacaciones_Personal($persona);

        }

        return $personal;

    }

    public function detalles_Personal ($rut_personal) {

        $personal = Empleado::find($rut_personal);

        if($personal){

            $detalles = $this->obtener_Detalles($rut_personal);

            return $detalles;

        }else{

            return response()->json(['error' => 'bad requst'], 400);

        }

    }

    public function modificar_Fechas_Vacaciones (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validar_Fechas());

        $empleado = Empleado::find($request->rut_empleado);

        if(!is_array($validacion)){

            if ($empleado){

                $this->cambiar_Formato_Fecha_A_Y_M_D($request);

                // Si es que los campos de fechas de vacaciones tienen datos.
                if(($empleado->fecha_inicio_vacaciones != null) && ($empleado->fecha_termino_vacaciones != null)){

                    return $this->agregar_Fecha_Vacaciones_Cuando_No_Es_Null($request);

                // Si es que los campos de fechas de vacaciones no tienen datos.
                }else if (($empleado->fecha_inicio_vacaciones == null) && ($empleado->fecha_termino_vacaciones == null)){

                    return $this->agregar_Fecha_Vacaciones_Cuando_Es_Null($request);

                }else{

                    return response()->json(['error' => 'bad request'], 400);

                }

            }else{

                return response()->json(['error' => 'bad request'], 400);

            }

        }else{

            return response()->json(['error' => 'bad request'], 400);
        }

    }

    public function modificar_Dias_Disponibles (Request $request) {

        $validacion = $this->validacion($request->all(), $this->validar_Fechas());

        $empleado = Empleado::find($request->rut_empleado);

        if(!is_array($validacion)){

            if ($empleado){

                $empleado->update($request->all());

                return response()->json('success', 200);

            }else{

                return response()->json(['error' => 'bad request'], 400);

            }

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function calculo_Dias_Disponibles ($fecha_inicio_vacaciones, $fecha_termino_vacaciones)
    {

        $fecha_inicio_vacaciones = Carbon::parse($fecha_inicio_vacaciones);

        $fecha_termino_vacaciones = Carbon::parse($fecha_termino_vacaciones);

        $dias_vacaciones_disponibles = $fecha_termino_vacaciones->diffInDays($fecha_inicio_vacaciones) + 1;

        return $dias_vacaciones_disponibles;

    }

    public function agregar_Fecha_Vacaciones_Cuando_Es_Null (Request $request)
    {

        $empleado = Empleado::find($request->rut_empleado);

        $dias_a_vacacionar = $this->calculo_Dias_Disponibles($request->fecha_inicio_vacaciones, $request->fecha_termino_vacaciones);

        if(($dias_a_vacacionar <= $empleado->dias_vacaciones_disponibles) && ($empleado->dias_vacaciones_disponibles != null)){

            $empleado->dias_vacaciones_disponibles = $empleado->dias_vacaciones_disponibles - $dias_a_vacacionar;

            $empleado->update($request->all());

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function agregar_Fecha_Vacaciones_Cuando_No_Es_Null (Request $request){

        $empleado = Empleado::find($request->rut_empleado);

        $dias_vacaciones_existente = $this->calculo_Dias_Disponibles($empleado->fecha_inicio_vacaciones, $empleado->fecha_termino_vacaciones);

        $dias_vacaciones_nuevo = $this->calculo_Dias_Disponibles($request->fecha_inicio_vacaciones, $request->fecha_termino_vacaciones);

        // Si las nuevas fechas de vacaciones son más largas que las ya establecidas, y hay dias de vacaciones disponibles.
        if ( ($dias_vacaciones_existente - $dias_vacaciones_nuevo < 0) && ($dias_vacaciones_nuevo <= $empleado->dias_vacaciones_disponibles + $dias_vacaciones_existente)){

            $empleado->dias_vacaciones_disponibles =  $empleado->dias_vacaciones_disponibles + $dias_vacaciones_existente;

            $empleado->dias_vacaciones_disponibles = $empleado->dias_vacaciones_disponibles - $dias_vacaciones_nuevo;

            $empleado->update($request->all());

            return response()->json('success', 200);

        // Si las nuevas fechas de vacaciones son menores a las establecidas.
        }else if (($dias_vacaciones_existente - $dias_vacaciones_nuevo > 0)){

            $empleado->dias_vacaciones_disponibles =  $empleado->dias_vacaciones_disponibles + $dias_vacaciones_existente;

            $empleado->dias_vacaciones_disponibles = $empleado->dias_vacaciones_disponibles - $dias_vacaciones_nuevo;

            $empleado->update($request->all());

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function obtener_Detalles ($rut_empleado)
    {

        $empleado = Empleado::find($rut_empleado);

        if( ($empleado->fecha_inicio_vacaciones != null) && ($empleado->fecha_termino_vacaciones != null) ){

            $empleado->fecha_inicio_vacaciones = $this->cambiar_Formato_Fecha_A_D_M_Y($empleado->fecha_inicio_vacaciones);

            $empleado->fecha_termino_vacaciones = $this->cambiar_Formato_Fecha_A_D_M_Y($empleado->fecha_termino_vacaciones);

            $detalles = $this->obtener_Documentos($empleado);

            return $detalles;

        }else{

            $detalles = $this->obtener_Documentos($empleado);

            return $detalles;
        }

    }

    public function obtener_Documentos ($empleado)
    {

        $documentos = $empleado->documentos;

        foreach ($documentos as $documento){

            $documento->fecha_subida = $this->cambiar_Formato_Fecha_A_D_M_Y($documento->fecha_subida);

        }

        return $empleado;

    }

    public function cambiar_Formato_Fecha_A_D_M_Y ($fecha_a_cambiar)
    {

        $fecha_formateada = Carbon::createFromFormat('Y-m-d', $fecha_a_cambiar)->format('d/m/Y');

        return $fecha_formateada;

    }

    public function cambiar_Formato_Fecha_A_Y_M_D (Request $request)
    {

        $request['fecha_inicio_vacaciones'] = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio_vacaciones)->format('Y/m/d');

        $request['fecha_termino_vacaciones'] = Carbon::createFromFormat('d/m/Y', $request->fecha_termino_vacaciones)->format('Y/m/d');

    }

    public function cambiar_Formato_Fechas_Vacaciones_Personal ($personal)
    {

        if (($personal->fecha_inicio_vacaciones != null) && ($personal->fecha_termino_vacaciones != null)){

            $personal['fecha_inicio_vacaciones'] = $this->cambiar_Formato_Fecha_A_D_M_Y($personal->fecha_inicio_vacaciones);

            $personal['fecha_termino_vacaciones'] = $this->cambiar_Formato_Fecha_A_D_M_Y($personal->fecha_termino_vacaciones);

            return $personal;

        }else{

            return $personal;

        }

    }

    public function validacion ($parametros, $tipo_validacion)
    {

        $respuesta = [];

        $validacion = Validator::make($parametros, $tipo_validacion);

        if ($validacion->fails()){

            array_push($respuesta, ['status' => 'error']);

            array_push($respuesta, ['errors' => $validacion->errors()]);

            return $respuesta;
        }
        else{

            return true;
        }

    }


    public function validar_Fechas () {

        return [

            'estado' => 'boolean',
            'fecha_inicio_vacaciones' => 'date_format:d/m/Y|after:today',
            'fecha_termino_vacaciones' => 'date_format:d/m/Y|after:fecha_inicio_vacaciones',
            'dias_vacaciones_disponibles' => 'integer|min:0',
            'dias_administrativos' => 'integer|min:0',

        ];

    }

}
