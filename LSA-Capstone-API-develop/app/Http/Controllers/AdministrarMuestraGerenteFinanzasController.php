<?php

namespace App\Http\Controllers;

use App\Models\Muestra;
use App\Models\Muestra_Observaciones;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\OrdenCompraController;

class AdministrarMuestraGerenteFinanzasController extends Controller
{

    public function muestras_Para_Gerente()
    {

        $muestras = Muestra::select('RUM', 'nombre_empresa', 'fecha_ingreso', 'fecha_entrega', 'id_matriz', 'estado', 'valor_neto', 'prioridad')
                           ->with('matriz')->get();

        foreach ($muestras as $muestra){

            $muestra->dias_transcurridos = Carbon::parse($muestra->fecha_ingreso)->diffInDays(Carbon::now()->format('Y-m-d'));

            $muestra->dias_faltantes = Carbon::parse($muestra->fecha_entrega)->diffInDays(Carbon::now()->format('Y-m-d'));

            $muestra->fecha_ingreso_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($muestra->fecha_ingreso);

            $muestra->fecha_entrega_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($muestra->fecha_entrega);

            $solicitante = DB::table('muestra')
                             ->join('muestra_cotizacion', 'muestra.RUM', '=', 'muestra_cotizacion.RUM')
                             ->join('cotizacion', 'muestra_cotizacion.id_cotizacion', '=', 'cotizacion.id_cotizacion')
                             ->join('solicitante', 'cotizacion.rut_solicitante', '=', 'solicitante.rut_solicitante')
                             ->select('solicitante.nombre', 'solicitante.primer_apellido')
                             ->where('muestra.RUM', '=', $muestra->RUM)
                             ->get();

            $muestra->solicitante = $solicitante;

        }

        return $muestras;

    }

    public function muestras_Para_Administrador_Finanzas()
    {

        $muestras = Muestra::select('RUM', 'nombre_empresa', 'fecha_entrega', 'id_matriz', 'estado', 'valor_neto', 'prioridad')
                           ->with('matriz')->get();

        foreach ($muestras as $muestra){

            $muestra->fecha_entrega_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($muestra->fecha_entrega);

            $solicitante = DB::table('muestra')
                             ->join('muestra_cotizacion', 'muestra.RUM', '=', 'muestra_cotizacion.RUM')
                             ->join('cotizacion', 'muestra_cotizacion.id_cotizacion', '=', 'cotizacion.id_cotizacion')
                             ->join('solicitante', 'cotizacion.rut_solicitante', '=', 'solicitante.rut_solicitante')
                             ->select('solicitante.nombre', 'solicitante.primer_apellido')
                             ->where('muestra.RUM', '=', $muestra->RUM)
                             ->get();

            $muestra->solicitante = $solicitante;

        }

        return $muestras;

    }

    public function detalles_Muestra_Para_Gerente($RUM)
    {

        $muestra = Muestra::select('RUM', 'nombre_empresa', 'cantidad_muestras', 'muestreado_por', 'fecha_entrega', 'tipo_pago', 'valor_neto', 'id_matriz', 'id_norma')
                          ->with('norma', 'matriz')->find($RUM);

        if ($muestra){

            $muestra->fecha_entrega_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($muestra->fecha_entrega);

            $informacion_solicitante = DB::table('muestra')
                                         ->join('muestra_cotizacion', 'muestra.RUM', '=', 'muestra_cotizacion.RUM')
                                         ->join('cotizacion', 'muestra_cotizacion.id_cotizacion', '=', 'cotizacion.id_cotizacion')
                                         ->join('solicitante', 'cotizacion.rut_solicitante', '=', 'solicitante.rut_solicitante')
                                         ->join('ciudad', 'muestra.id_ciudad', '=', 'ciudad.id_ciudad')
                                         ->select('solicitante.nombre', 'solicitante.primer_apellido', 'ciudad.*')
                                         ->where('muestra.RUM', '=', $RUM)
                                         ->get();

            $parametros_metodologias = DB::table('muestra')
                                         ->join('muestra_parametro_metodologia', 'muestra.RUM', 'muestra_parametro_metodologia.RUM')
                                         ->join('parametro', 'muestra_parametro_metodologia.id_parametro', 'parametro.id_parametro')
                                         ->join('metodologia', 'muestra_parametro_metodologia.id_metodologia', 'metodologia.id_metodologia')
                                         ->select('parametro.*', 'metodologia.*')
                                         ->where('muestra.RUM', '=', $RUM)
                                         ->get();

            $empleados = DB::table('muestra')
                           ->join('empleado_muestra', 'muestra.RUM', '=', 'empleado_muestra.RUM')
                           ->join('empleado', 'empleado_muestra.rut_empleado', '=', 'empleado.rut_empleado')
                           ->select('empleado.nombre', 'empleado.apellido')
                           ->where('muestra.RUM', '=', $RUM)
                           ->distinct()
                           ->get();

            $muestra->informacion_solicitante = $informacion_solicitante;

            $muestra->parametros_metodologias = $parametros_metodologias;

            $muestra->empleados = $empleados;

            return $muestra;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function detalles_Muestra_Para_Admnistrador_Finanzas($RUM)
    {

        $muestra = Muestra::select('RUM', 'nombre_empresa', 'fecha_entrega', 'estado', 'tipo_pago', 'valor_neto', 'prioridad', 'id_matriz', 'id_orden_compra')
                          ->with('orden_compra', 'matriz')->find($RUM);

        if ($muestra){

            $muestra->fecha_entrega_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($muestra->fecha_entrega);

            $solicitante = DB::table('muestra')
                             ->join('muestra_cotizacion', 'muestra.RUM', '=', 'muestra_cotizacion.RUM')
                             ->join('cotizacion', 'muestra_cotizacion.id_cotizacion', '=', 'cotizacion.id_cotizacion')
                             ->join('solicitante', 'cotizacion.rut_solicitante', '=', 'solicitante.rut_solicitante')
                             ->select('solicitante.nombre', 'solicitante.primer_apellido')
                             ->where('muestra.RUM', '=', $muestra->RUM)
                             ->get();

            $muestra->solicitante = $solicitante;

            return $muestra;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function observaciones_Muestra ($RUM)
    {

        $muestra = Muestra::find($RUM);

        if($muestra){

            $observaciones = DB::table('muestra')
                               ->join('muestra_observaciones', 'muestra.RUM', '=', 'muestra_observaciones.RUM')
                               ->join('empleado', 'muestra_observaciones.rut_empleado', '=', 'empleado.rut_empleado')
                               ->select('muestra_observaciones.*', 'empleado.nombre', 'empleado.apellido')
                               ->where('muestra.RUM', '=', $RUM)
                               ->get();

            foreach($observaciones as $observacion){

                $observacion->fecha_observacion_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($observacion->fecha_observacion);

                $observacion->hora_observacion_formateada = $this->cambiar_Formato_Hora_A_H_M($observacion->hora_observacion);

            }

            return $observaciones;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function crear_Observacion_Administrador_Finanzas (Request $request)
    {

        $fecha_actual = Carbon::now()->format('Y/m/d');

        $hora_actual = Carbon::now()->format('H:i:s');

        Muestra_Observaciones::create($request->all() + ['fecha_observacion' => $fecha_actual] + ['hora_observacion' => $hora_actual]);

        return response()->json('success', 200);

    }

    public function agregar_Orden_De_Compra(Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Documento_Orden_Compra());

        if(!is_array($validacion)){

            $orden_compra_creada = (new OrdenCompraController)->agregar_Orden_Compra($request);

            if($orden_compra_creada != null){

                (new OrdenCompraController)->actualizar_Relacion_Muestra_Orden_Compra($request->RUM, $orden_compra_creada->id_orden_compra);

                return response()->json('success', 200);

            }else{

                return response()->json(['error' => 'bad request'], 400);

            }
        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function descargar_Informe ()
    {

        //TODO: Hacer referencia al controlador Informe.

    }

    public function descargar_Orden_De_Compra(Request $request)
    {

        $orden_compra = (new OrdenCompraController)->descargar_Orden_Compra($request->id_orden_compra);

        if ($orden_compra == false){

            return response()->json(['error' => 'bad request'], 400);

        }else{

            return $orden_compra;

        }

    }

    public function eliminar_Orden_De_Compra(Request $request)
    {

        $orden_compra_eliminada = (new OrdenCompraController)->eliminar_Orden_Compra($request->id_orden_compra);

        if($orden_compra_eliminada == true){

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function cambiar_Formato_Fecha_A_D_M_Y ($fecha_a_cambiar)
    {

        $fecha_formateada = Carbon::createFromFormat('Y-m-d', $fecha_a_cambiar)->format('d/m/Y');

        return $fecha_formateada;

    }

    public function cambiar_Formato_Hora_A_H_M ($hora_a_cambiar)
    {

        $hora_formateada = Carbon::createFromFormat('H:i:s', $hora_a_cambiar)->format('H:i');

        return $hora_formateada;

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

    public function validacion_Documento_Orden_Compra() {

        return [

            'documento_orden_compra.*' => 'mimes:pdf,docx,doc',

        ];

    }

}
