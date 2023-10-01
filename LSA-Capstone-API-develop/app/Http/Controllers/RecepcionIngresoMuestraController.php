<?php
namespace App\Http\Controllers;

use App\Models\Matriz;
use App\Models\Norma;
use App\Models\Muestra;
use App\Models\Parametro;
use App\Models\Empresa;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\SubmuestraController;
use App\Http\Controllers\EmpleadoMuestraController;
use App\Http\Controllers\MuestraCotizacionController;
use App\Http\Controllers\MuestraObservacionesController;
use App\Http\Controllers\MuestraParametroMetodologiaController;
use App\Http\Controllers\MuestraTelefonoTransportistaController;
use App\Http\Controllers\SubmuestraParametroMetodologiaController;

class RecepcionIngresoMuestraController extends Controller
{

    public function empresas_Ciudades_Direcciones ()
    {

        $empresas = Empresa::where('estado', '=', 1)->select('rut_empresa', 'nombre_empresa')->get();

        foreach($empresas as $empresa){

            $ciudades_direcciones = DB::table('empresa')
                                      ->join('empresa_ciudad_solicitante', 'empresa.rut_empresa', '=', 'empresa_ciudad_solicitante.rut_empresa')
                                      ->join('ciudad', 'empresa_ciudad_solicitante.id_ciudad', '=', 'ciudad.id_ciudad')
                                      ->select('ciudad.*')
                                      ->where('empresa.rut_empresa', '=', $empresa->rut_empresa)
                                      ->get();

            $empresa->ciudades_direcciones = $ciudades_direcciones;

        }

        return $empresas;

    }

    public function cotizaciones ($rut_empresa)
    {

        $empresa = Empresa::find($rut_empresa);

        if ($empresa){

            $cotizaciones = DB::table('empresa')
                              ->join('empresa_ciudad_solicitante', 'empresa.rut_empresa', '=', 'empresa_ciudad_solicitante.rut_empresa')
                              ->join('cotizacion', 'empresa_ciudad_solicitante.rut_solicitante', '=', 'cotizacion.rut_solicitante')
                              ->select('cotizacion.id_cotizacion', 'cotizacion.numero_cotizacion', 'cotizacion.nombre_original_documento')
                              ->where('empresa.rut_empresa', '=', $rut_empresa)
                              ->where('empresa.estado', '=', 1)
                              ->get();

            return $cotizaciones;

        }

    }

    public function matrices ()
    {

        $matrices = Matriz::get();

        return $matrices;

    }

    public function normas ()
    {

        $normas = Norma::get();

        return $normas;

    }

    public function tablas ($id_norma)
    {

        $norma = Norma::find($id_norma);

        if($norma){

            $tablas = DB::table('tabla')
                        ->join('norma_tabla', 'tabla.id_tabla', '=', 'norma_tabla.id_norma')
                        ->select('tabla.id_tabla', 'tabla.nombre_tabla')
                        ->where('norma_tabla.id_norma', '=', $id_norma)
                        ->get();

            foreach($tablas as $tabla){

                $parametros_metodologias = DB::table('parametro')
                                             ->join('tabla_parametro_metodologia', 'parametro.id_parametro', '=', 'tabla_parametro_metodologia.id_parametro')
                                             ->join('metodologia', 'tabla_parametro_metodologia.id_metodologia', '=', 'metodologia.id_metodologia')
                                             ->select('parametro.*', 'metodologia.*')
                                             ->where('tabla_parametro_metodologia.id_tabla', '=', $tabla->id_tabla)
                                             ->get();

                $tabla->parametros_metodologias = $parametros_metodologias;

            }

            return $tablas;

        }

    }

    public function parametros_metodologias ()
    {

        $parametros_metodologias = Parametro::with('metodologias')->get();

        return $parametros_metodologias;

    }

    public function recepcion_Muestra (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Recepcion_Muestra());

        if(!is_array($validacion)){

            $muestra = Muestra::create($request->all());

            (new MuestraCotizacionController)->crear_Relacion_Muestra_Cotizacion($muestra->RUM, $request->id_cotizacion);

            (new MuestraObservacionesController)->crear_Observacion($muestra->RUM, $request->rut_empleado, $request->observaciones);

            (new MuestraTelefonoTransportistaController)->crear_Telefonos_Transportista($muestra->RUM, $request->telefonos_agregar);

            (new MuestraParametroMetodologiaController)->crear_Relacion_Muestra_Parametro_Metodologia($muestra->RUM, Arr::flatten($request->parametros_agregar));

            (new EmpleadoMuestraController)->crear_Relacion_Empleado_Muestra($muestra->RUM, $request->fecha_entrega, $this->obtener_Empleados_Para_Asociar($muestra->RUM));

            (new SubmuestraController)->crear_Submuestras($muestra->RUM, $request->submuestras_agregar);

            return response()->json(['status' => 200, "RUM" => $muestra->RUM], 200);

        }else{

            return response()->json(['error' => $validacion], 400);

        }

    }

    public function obtener_Detalles_Para_Ingresar_Muestra ($RUM)
    {

        $muestra = Muestra::with('observaciones')->find($RUM);

        if($muestra){

            $id_cotizacion = DB::table('muestra')
                               ->join('muestra_cotizacion', 'muestra.RUM', '=', 'muestra_cotizacion.RUM')
                               ->select('muestra_cotizacion.id_cotizacion')
                               ->where('muestra.RUM', '=', $RUM)
                               ->get();

            $telefonos_transportista = DB::table('muestra')
                                         ->join('muestra_telefono_transportista', 'muestra.RUM', '=', 'muestra_telefono_transportista.RUM')
                                         ->select('muestra_telefono_transportista.telefono_transportista')
                                         ->where('muestra_telefono_transportista.RUM', '=', $RUM)
                                         ->get();

            $parametros_metodologias = DB::table('muestra')
                                         ->join('muestra_parametro_metodologia', 'muestra.RUM', 'muestra_parametro_metodologia.RUM')
                                         ->join('parametro', 'muestra_parametro_metodologia.id_parametro', 'parametro.id_parametro')
                                         ->join('metodologia', 'muestra_parametro_metodologia.id_metodologia', 'metodologia.id_metodologia')
                                         ->select('parametro.*', 'metodologia.*')
                                         ->where('muestra.RUM', '=', $RUM)
                                         ->get();

            $submuestras = DB::table('submuestra')
                             ->join('submuestra_parametro_metodologia', 'submuestra.id_submuestra', '=', 'submuestra_parametro_metodologia.id_submuestra')
                             ->join('parametro', 'submuestra_parametro_metodologia.id_parametro', '=', 'parametro.id_parametro')
                             ->join('metodologia', 'submuestra_parametro_metodologia.id_metodologia', '=', 'metodologia.id_metodologia')
                             ->select('submuestra.*', 'parametro.*', 'metodologia.*')
                             ->where('submuestra.RUM', '=', $RUM)
                             ->get();

            $empleados = DB::table('empleado_muestra')
                           ->join('parametro', 'empleado_muestra.id_parametro', '=', 'parametro.id_parametro')
                           ->select('empleado_muestra.*', 'parametro.nombre_parametro')
                           ->where('empleado_muestra.RUM', '=', $RUM)
                           ->orderBy('empleado_muestra.orden_de_analisis')
                           ->get();

            $muestra->id_cotizacion = $id_cotizacion->value('id_cotizacion');

            $muestra->telefonos_transportista = $telefonos_transportista;

            $muestra->parametros_metodologias = $parametros_metodologias;

            $muestra->submuestras = $submuestras;

            $muestra->empleados = $empleados;

            return $muestra;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function ingresar_Muestra (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Ingreso_Muestra());

        $muestra = Muestra::find($request->RUM);

        if(!is_array($validacion)){

            if ($muestra){

                $muestra->update($request->all());

                $this->actualizar_Relaciones_Ingreso_Muestra($request);

                return response()->json('success', 200);

            }else{

                return response()->json(['error' => 'bad request'], 400);

            }

        }else{

            return $validacion;

        }

    }

    public function obtener_Empleados_Para_Asociar ($RUM)
    {

        $parametros_muestra = DB::table('muestra')
                                ->join('muestra_parametro_metodologia', 'muestra.RUM', '=', 'muestra_parametro_metodologia.RUM')
                                ->join('parametro', 'muestra_parametro_metodologia.id_parametro', '=', 'parametro.id_parametro')
                                ->where('muestra.RUM', '=', $RUM)
                                ->pluck('parametro.nombre_parametro');

        $empleados_asociados = [];

        for($i = 0; $i < count($parametros_muestra); $i+=1){

            $empleados_parametros = DB::table('empleado_metodologia')
                                      ->join('empleado', 'empleado_metodologia.rut_empleado', '=', 'empleado.rut_empleado')
                                      ->join('metodologia_parametro', 'empleado_metodologia.id_metodologia', '=', 'metodologia_parametro.id_metodologia')
                                      ->join('parametro', 'metodologia_parametro.id_parametro', '=', 'parametro.id_parametro')
                                      ->where('parametro.nombre_parametro', '=', $parametros_muestra[$i])
                                      ->where('empleado.estado', '=', 1)
                                      ->where(function ($query){
                                                        $query->where('empleado.rol', '=', 'Supervisor(a)')
                                                              ->orWhere('empleado.rol', '=', 'Analista Químico')
                                                              ->orWhere('empleado.rol', '=', 'Químico');
                                             })
                                      ->select('empleado_metodologia.rut_empleado', 'parametro.id_parametro')
                                      ->first();

            if($empleados_parametros != null){

                array_push($empleados_asociados, Arr::flatten($empleados_parametros));

            }

        }

        return Arr::flatten($empleados_asociados);

    }

    public function actualizar_Relaciones_Ingreso_Muestra(Request $request)
    {

        (new MuestraCotizacionController)->actualizar_Relacion_Muestra_Cotizacion($request);

        (new MuestraObservacionesController)->actualizar_Observaciones($request->RUM, $request->rut_empleado, $request->observaciones);

        (new MuestraTelefonoTransportistaController)->actualizar_Telefonos_Transportista($request);

        (new MuestraParametroMetodologiaController)->actualizar_Relacion_Muestra_Parametro_Metodologia($request);

        (new SubmuestraController)->actualizar_Submuestras($request->submuestras_editar);

        (new SubmuestraController)->ingresar_Submuestras($request);

        (new SubmuestraParametroMetodologiaController)->actualizar_Relacion_Submuestra_Parametro_Metodologia($request);

        (new EmpleadoMuestraController)->actualizar_Relacion_Empleado_Muestra($request);

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

    public function validacion_Recepcion_Muestra ()
    {
        return [

            'rut_empresa' => 'required|cl_rut|min:8|max:12',
            'nombre_empresa' => 'required|min:3|max:255',
            'id_ciudad' => 'required|integer',
            'direccion_empresa' => 'required|min:3|max:255',
            'muestreado_por' => [
                'required',
                Rule::in(['UCN-LSA', 'Cliente']),
            ],
            'cantidad_muestras' => 'required|integer',
            'prioridad' => [
                'required',
                Rule::in(['Normal', 'Alta', 'Urgente']),
            ],
            'fecha_muestreo' => 'nullable|date_format:Y-m-d',
            'hora_muestreo' => 'nullable|date_format:H:i:s',
            'temperatura_transporte' => 'nullable|decimal:0,3',
            'fecha_entrega' => 'required|date_format:Y-m-d',
            'rut_transportista' => 'cl_rut|min:8|max:12',
            'nombre_transportista' => 'min:3|max:255',
            'patente_vehiculo' => 'min:3|max:255',
            'rut_empleado' => 'required|cl_rut|min:8|max:12',
            'fecha_entrega' => 'required|date_format:Y-m-d|after:yesterday',
            'rut_transportista' => 'nullable|cl_rut|min:8|max:12',
            'nombre_transportista' => 'nullable|min:3|max:255',
            'patente_vehiculo' => 'nullable|min:3|max:255',
            'id_matriz' => 'required|integer',

        ];
    }

    public function validacion_Ingreso_Muestra ()
    {
        return [

            'rut_empresa' => 'required|cl_rut|min:8|max:12',
            'nombre_empresa' => 'required|min:3|max:255',
            'id_ciudad' => 'required|integer',
            'direccion_empresa' => 'required|min:3|max:255',
            'muestreado_por' => [
                'required',
                Rule::in(['UCN-LSA', 'Cliente']),
            ],
            'cantidad_muestras' => 'required|integer',
            'prioridad' => [
                'required',
                Rule::in(['Normal', 'Alta', 'Urgente']),
            ],
            'fecha_muestreo' => 'nullable|date_format:Y-m-d',
            'hora_muestreo' => 'nullable|date_format:H:i:s',
            'temperatura_transporte' => 'nullable|decimal:0,3',
            'fecha_entrega' => 'required|date_format:Y-m-d',
            'tipo_pago' => [
                'nullable',
                Rule::in(['Efectivo', 'Tarjeta de crédito', 'Transferencia bancaria', 'Cheque', 'Otro']),
            ],
            'valor_neto' => 'required|decimal:0,4',
            'rut_transportista' => 'cl_rut|min:8|max:12',
            'nombre_transportista' => 'min:3|max:255',
            'patente_vehiculo' => 'min:3|max:255',
            'rut_empleado' => 'required|cl_rut|min:8|max:12',
            'fecha_entrega' => 'required|date_format:Y-m-d|after:yesterday',
            'rut_transportista' => 'nullable|cl_rut|min:8|max:12',
            'nombre_transportista' => 'nullable|min:3|max:255',
            'patente_vehiculo' => 'nullable|min:3|max:255',
            'id_matriz' => 'required|integer',

        ];
    }

}
