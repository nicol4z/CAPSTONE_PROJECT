<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use App\Models\Empleado;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Freshwork\ChileanBundle\Rut;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\EmpleadoDocumentosController;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{

    public function empleado ($id)
    {

        $empleado = Empleado::find($id);

        return $empleado;

    }

    public function empleados ()
    {

        $empleados = Empleado::where('rol', '!=', 'Administrador')->get();

        return $empleados;

    }

    public function obtener_Nombres_Empleados()
    {
        $empleados = Empleado::where('rol', '!=', 'Administrador')->pluck('nombre', 'rut_empleado');

        return $empleados;
    }

    public function obtener_Areas()
    {

        $areas = Area::get();

        return $areas;

    }

    public function detalles_empleado ($rut_empleado)
    {

        $empleado = Empleado::find($rut_empleado);

        if($empleado){

            $areas = DB::table('empleado_area')
                   ->join('area', 'empleado_area.id_area', 'area.id_area')
                   ->where('empleado_area.rut_empleado', '=', $rut_empleado)
                   ->get();

            $empleado->areas = $areas;

            $documentos = $this->obtener_Documentos($rut_empleado);

            $empleado->documentos = $documentos;

            return $empleado;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function crear_Empleado (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Agregar_Empleado());

        $rut_empleado = $request->rut_empleado;

        $rut_sin_puntos_ni_guion = Rut::parse($rut_empleado)->normalize();

        if( (!is_array($validacion)) && ($rut_empleado == $rut_sin_puntos_ni_guion) ){

            Empleado::create($request->all());

            (new AuthController)->registerFromController($request->nombre,$request->apellido,$request->correo,$rut_empleado,$request->id_rol);

            if($request->has('documentos'))
            {

                (new EmpleadoDocumentosController)->guardar_Documentos($request);

            }

            (new EmpleadoAreaController)->crear_Relacion_Empleado_Area($request->rut_empleado, Arr::flatten($request->areas_agregar));

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function actualizar_Empleado (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Actualizar_Empleado());

        $empleado = Empleado::find($request -> rut_empleado);

        if(!is_array($validacion)){

            if ($empleado){

                $empleado->update($request->all());

                (new AuthController)->updateStatusFromController($empleado->correo,$empleado->estado);

                if($request->has('documentos'))
                {

                    (new EmpleadoDocumentosController)->guardar_Documentos($request);

                }

                (new EmpleadoAreaController)->actualizar_Relacion_Empleado_Area($request);

                return response()->json('success', 200);

            }else{

                return response()->json(['error' => 'bad request'], 400);

            }

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function actualizar_Estado_Empleado (Request $request)
    {

        $empleado = Empleado::find($request->rut_empleado);

        if($empleado){

            DB::table('empleado')
              ->where('rut_empleado','=', $request->rut_empleado)
              ->update(['estado' => $request->estado]);

            $this->actualizar_Estado_Usuario($request);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function actualizar_Estado_Usuario (Request $request)
    {

        $usuario = User::where('rut', '=', $request->rut_empleado)->get();

        if($usuario){

            DB::table('users')
              ->where('rut','=', $request->rut_empleado)
              ->update(['estado' => $request->estado]);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function descargar_Documento (Request $request)
    {

        $documento = (new EmpleadoDocumentosController)->descargar_Documento($request->rut_empleado, $request->nombre_original_documento);

        return $documento;

    }

    public function eliminar_Documento_Empleado (Request $request)
    {

        $documento_eliminado = (new EmpleadoDocumentosController)->eliminar_Documento($request->rut_empleado, $request->nombre_original_documento, $request->nombre_documento);

        if($documento_eliminado == true){

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'forbidden'], 403);

        }

    }

    public function eliminar_Empleado ($rut_empleado)
    {

        $respuesta = [];

        $empleado = Empleado::find($rut_empleado);

        if ($empleado){

            (new EmpleadoDocumentosController)->borrar_Documentos($rut_empleado);

            $empleado->delete();

            array_push($respuesta, ['status' => 'El empleado ha sido eliminado.']);

        }else{

            array_push($respuesta, ['status' => 'error']);

            array_push($respuesta, ['errors' => 'Los datos ingresados son erróneos, intente nuevamente.']);

        }

        return response()->json($respuesta);

    }

    public function existe_Empleado (Request $request)
    {

        $rut_empleado = $request->rut_empleado;

        $rut_sin_puntos_ni_guion = Rut::parse($rut_empleado)->normalize();

        $existe_empleado = Empleado::find($rut_empleado)->exists();

        if ( ($rut_empleado == $rut_sin_puntos_ni_guion) && ($existe_empleado != false) ){

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);
        }

    }

    public function existe_Correo (Request $request)
    {

        $validacion = $this->validacion($request->all(), ['correo' => 'email|unique:Empleado|max:255']);

        if (is_array($validacion))
        {

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function obtener_Documentos ($rut_empleado)
    {

        $empleado = Empleado::find($rut_empleado);

        if($empleado){

            $documentos = DB::table('empleado_documentos')
                            ->select('empleado_documentos.*')
                            ->where('empleado_documentos.rut_empleado', '=', $rut_empleado)
                            ->get();

            foreach ($documentos as $documento){

                $documento->fecha_subida = $this->cambiar_Formato_Fecha_A_D_M_Y($documento->fecha_subida);

            }

            return $documentos;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function cambiar_Formato_Fecha_A_D_M_Y ($fecha_a_cambiar)
    {

        $fecha_formateada = Carbon::createFromFormat('Y-m-d', $fecha_a_cambiar)->format('d/m/Y');

        return $fecha_formateada;

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

    public function validacion_Agregar_Empleado ()
    {

        return [

            'rut_empleado' => 'bail|required|unique:Empleado|min:8|max:12',
            'nombre' => 'required|min:3|max:255',
            'apellido' => 'required|min:3|max:255',
            'correo' => 'required|email|max:255|unique:Empleado',
            'rol' => [

                'required',
                Rule::in(['Administrador', 'Gerente', 'Analista Químico', 'Químico', 'Jefe(a) de laboratorio',
                          'Supervisor(a)', 'Administrador(a) de Finanzas', 'Recepcionista']),

            ],
            'telefono_movil' => 'required|numeric|digits_between:1,10',
            'telefono_emergencia' => 'numeric|digits_between:1,10',
            'tipo_trabajador' =>[

                'required',
                Rule::in(['Practicante', 'Contrato Plazo Fijo', 'Contrato Plazo Indefinido', 'Contrato Honorario'])

            ],
            'estado' => 'required|boolean',
            'fecha_inicio_vacaciones' => 'nullable|date_format:d-m-Y',
            'fecha_termino_vacaciones' => 'nullable|date_format:d-m-Y|after:fecha_inicio_vacaciones',
            'dias_administrativos' => 'nullable|integer|min:1',
            'documentos.*' => 'mimes:jpeg,jpg,png,pdf,docx,doc,xsl,xslx',

        ];
    }

    public function validacion_Actualizar_Empleado ()
    {

        return [

            'nombre' => 'min:3|max:255',
            'apellido' => 'min:3|max:255',
            'correo' => 'email|max:255',
            'rol' => [
                Rule::in(['Administrador', 'Gerente', 'Analista Químico', 'Químico', 'Jefe(a) de Laboratorio',
                          'Supervisor(a)', 'Administrador(a) de Finanzas', 'Recepcionista']),
            ],
            'telefono_movil' => 'numeric|digits_between:1,10',
            'telefono_emergencia' => 'numeric|digits_between:1,10',
            'tipo_trabajador' =>[
                Rule::in(['Practicante', 'Contrato Plazo Fijo', 'Contrato Plazo Indefinido', 'Contrato Honorario'])
            ],
            'estado' => 'boolean',
            'fecha_inicio_vacaciones' => 'date_format:d-m-Y',
            'fecha_termino_vacaciones' => 'date_format:d-m-Y|after:fecha_inicio_vacaciones',
            'dias_administrativos' => 'integer|min:1',
            'documentos.*' => 'mimes:jpeg,jpg,png,pdf,docx,doc,xsl,xslx',

        ];
    }

}
