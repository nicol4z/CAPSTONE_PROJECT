<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Solicitante;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\EmpresaCiudadSolicitanteController;

class SolicitanteController extends Controller
{

    public function solicitantes ()
    {

        $solicitantes = Solicitante::all();

        foreach ($solicitantes as $solicitante){

            $empresas = $solicitante->empresas;

        }
        return $solicitantes;

    }

    public function detalles_Solicitante ($rut_solicitante)
    {

        $solicitante = Solicitante::find($rut_solicitante);

        if($solicitante){

            $detalles_empresas = DB::table('solicitante')
                          ->join('empresa_ciudad_solicitante', 'solicitante.rut_solicitante', 'empresa_ciudad_solicitante.rut_solicitante')
                          ->join('ciudad', 'empresa_ciudad_solicitante.id_ciudad', 'ciudad.id_ciudad')
                          ->join('empresa', 'empresa_ciudad_solicitante.rut_empresa', 'empresa.rut_empresa')
                          ->select('solicitante.*', 'empresa.*', 'ciudad.*')
                          ->where('solicitante.rut_solicitante', '=', $rut_solicitante)
                          ->get();

            $solicitante->detalles_empresas = $detalles_empresas;

            return $solicitante;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }
    public function detalles_Completo_Solicitante ($rut_solicitante)
    {

        $solicitante = Solicitante::find($rut_solicitante);

        if($solicitante){

            $detalles_empresas = DB::table('solicitante')
                          ->join('empresa_ciudad_solicitante', 'solicitante.rut_solicitante', 'empresa_ciudad_solicitante.rut_solicitante')
                          ->join('ciudad', 'empresa_ciudad_solicitante.id_ciudad', 'ciudad.id_ciudad')
                          ->join('empresa', 'empresa_ciudad_solicitante.rut_empresa', 'empresa.rut_empresa')
                          ->select('solicitante.*', 'empresa.*', 'ciudad.*')
                          ->where('solicitante.rut_solicitante', '=', $rut_solicitante)
                          ->get();

            $solicitante->cotizaciones = $this->obtener_Cotizaciones($rut_solicitante);

            $solicitante->detalles_empresas = $detalles_empresas;

            return $solicitante;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function crear_Solicitante (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Agregar_Solicitante());

        $rut_solicitante = $request->rut_solicitante;

        $rut_sin_puntos_ni_guion = Rut::parse($rut_solicitante)->normalize();

        if( (!is_array($validacion)) && ($rut_solicitante == $rut_sin_puntos_ni_guion) ){

            Solicitante::create($request->all());

            (new AuthController)->registerSolicitanteFromController($request->nombre, $request->primer_apellido, $request->correo, $rut_solicitante, 5);

            (new EmpresaCiudadSolicitanteController)->crear_Relacion_Empresa_Ciudad_Solicitante($request->rut_solicitante, $request);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function agregar_Cotizacion (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Documento_Cotizacion());

        if(!is_array($validacion)){

            $cotizacion_creada = (new CotizacionController)->agregar_Cotizacion($request);

            if( $cotizacion_creada == true){

                return response()->json('success', 200);

            }else{

                return response()->json(['error' => 'bad request'], 400);

            }
        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function actualizar_Solicitante (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Actualizar_Solicitante());

        $solicitante = Solicitante::find($request->rut_solicitante);

        if(!is_array($validacion)){

            if ($solicitante){

                $solicitante->update($request->all());

                (new AuthController)->updateSolicitanteStatusFromController($solicitante->correo,$solicitante->estado);

                (new EmpresaCiudadSolicitanteController)->crear_Relacion_Empresa_Ciudad_Solicitante($request->rut_solicitante, $request);

                (new EmpresaCiudadSolicitanteController)->eliminar_Relacion_Empresa_Ciudad_Solicitante($request);

                return response()->json('success', 200);


            }else{

                return response()->json(['error' => 'bad request'], 400);

            }

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function actualizar_Estado_Solicitante (Request $request)
    {

        $solicitante = Solicitante::find($request->rut_solicitante);

        if($solicitante){

            DB::table('solicitante')
              ->where('rut_solicitante','=', $request->rut_solicitante)
              ->update(['estado' => $request->estado]);

            $this->actualizar_Estado_Usuario($request);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function actualizar_Estado_Usuario (Request $request)
    {

        $usuario = User::where('rut_solicitante', '=', $request->rut_empleado)->get();

        if($usuario){

            DB::table('users')
              ->where('rut_solicitante','=', $request->rut_solicitante)
              ->update(['estado' => $request->estado]);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function descargar_Cotizacion (Request $request)
    {

        $cotizacion = (new CotizacionController)->descargar_Cotizacion($request->rut_solicitante, $request->nombre_original_documento);

        return $cotizacion;

    }

    public function eliminar_Cotizacion (Request $request)
    {

        $cotizacion_eliminada = (new CotizacionController)->eliminar_Cotizacion($request->rut_solicitante, $request->nombre_original_documento);

        if( $cotizacion_eliminada == true){

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'forbidden'], 403);

        }

    }

    public function eliminar_Solicitante ($rut_solicitante)
    {

        $solicitante = Solicitante::find($rut_solicitante);

        if ($solicitante){

            $solicitante->delete();

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function existe_Correo (Request $request)
    {

        $validacion = $this->validacion($request->all(), ['correo' => 'email|unique:Solicitante|max:255']);

        if (is_array($validacion))
        {

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function existe_Solicitante (Request $request)
    {

        $rut_solicitante = $request->rut_solicitante;

        $existe_solicitante = Solicitante::find($rut_solicitante);

        if ($existe_solicitante != null){

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function obtener_Cotizaciones ($rut_solicitante)
    {

        $solicitante = Solicitante::find($rut_solicitante);

        $cotizaciones = $solicitante->cotizaciones;

        foreach ($cotizaciones as $cotizacion){

            $cotizacion->fecha_ingreso = $this->cambiar_Formato_Fecha($cotizacion->fecha_ingreso);

            $cotizacion->fecha_emision = $this->cambiar_Formato_Fecha($cotizacion->fecha_emision);

        }

        return $cotizaciones;

    }

    public function cambiar_Formato_Fecha ($fecha_emision)
    {

        $fecha_formateada = Carbon::createFromFormat('Y-m-d', $fecha_emision)->format('d/m/Y');

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

    public function validacion_Agregar_Solicitante ()
    {

        return [

            'rut_solicitante' => 'bail|required|unique:Solicitante|cl_rut|min:8|max:12',
            'nombre' => 'required|min:3|max:255',
            'primer_apellido' => 'required|min:3|max:255',
            'segundo_apellido' => 'required|min:3|max:255',
            'estado' => 'required|boolean',
            'correo' => 'required|email|max:255',
            'telefono' => 'required|numeric|digits_between:8,15',
            'direccion_contacto_proveedores' => 'required|min:3|max:255',
            'fono_contacto_proveedores' => 'required|numeric|digits_between:8,15',
            'direccion_envio_factura' => 'required|min:3|max:255',
            'tipo_cliente' =>[
                'required',
                Rule::in(['Convenio', 'Frecuente', 'Particular'])
            ],
            'rut_empresa.*' => 'nullable|cl_rut|min:8|max:12',
            'nombre_ciudad.*' => 'nullable|min:3|max:255',
            'direccion_ciudad.*' => 'nullable|min:3|max:255',

        ];

    }

    public function validacion_Actualizar_Solicitante ()
    {

        return [

            'rut_solicitante' => 'cl_rut|min:8|max:12',
            'nombre' => 'min:3|max:255',
            'primer_apellido' => 'min:3|max:255',
            'segundo_apellido' => 'min:3|max:255',
            'estado' => 'boolean',
            'correo' => 'email|max:255',
            'telefono' => 'numeric|digits_between:8,15',
            'direccion_contacto_proveedores' => 'min:3|max:255',
            'fono_contacto_proveedores' => 'numeric|digits_between:8,15',
            'direccion_envio_factura' => 'min:3|max:255',
            'tipo_cliente' =>[
                Rule::in(['Convenio', 'Frecuente', 'Particular'])
            ],
            'rut_empresa.*' => 'nullable|cl_rut|min:8|max:12',
            'nombre_ciudad.*' => 'nullable|min:3|max:255',
            'direccion_ciudad.*' => 'nullable|min:3|max:255',

        ];

    }

    public function validacion_Documento_Cotizacion() {

        return [

            'documento_cotizacion.*' => 'mimes:pdf,docx,doc',

        ];

    }

}
