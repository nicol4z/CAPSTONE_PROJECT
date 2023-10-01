<?php

namespace App\Http\Controllers;

use App\Models\Muestra;
use App\Models\Encuesta;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\SolicitanteEncuestaController;

class AdministrarMuestraSolicitanteController extends Controller
{

    public function muestras ()
    {

        $rut_solicitante = Auth::user()->rut_solicitante;

        $muestras = DB::table('solicitante')
                      ->join('cotizacion', 'solicitante.rut_solicitante', '=', 'cotizacion.rut_solicitante')
                      ->join('muestra_cotizacion', 'cotizacion.id_cotizacion', '=', 'muestra_cotizacion.id_cotizacion')
                      ->join('muestra', 'muestra_cotizacion.RUM', '=', 'muestra.RUM')
                      ->join('matriz', 'muestra.id_matriz', '=', 'matriz.id_matriz')
                      ->select('muestra.RUM', 'muestra.fecha_entrega', 'muestra.id_encuesta', 'cotizacion.id_cotizacion', 'muestra.estado', 'matriz.nombre_matriz')
                      ->where('solicitante.rut_solicitante', '=', $rut_solicitante)
                      ->get();

        foreach ($muestras as $muestra){

            $muestra->fecha_entrega = $this->cambiar_Formato_Fecha($muestra->fecha_entrega);

        }

        return $muestras;

    }

    public function detalles_muestra ($RUM)
    {

        $muestra = Muestra::select('RUM', 'cantidad_muestras', 'muestreado_por', 'id_matriz', 'id_norma', 'fecha_entrega', 'tipo_pago', 'valor_neto')
                          ->with('norma', 'matriz')
                          ->find($RUM);

        $rut_solicitante = Auth::user()->rut_solicitante;

        if ($muestra){

            $norma = DB::table('muestra')
                       ->join('muestra_cotizacion', 'muestra.RUM', '=', 'muestra_cotizacion.RUM')
                       ->join('cotizacion', 'muestra_cotizacion.id_cotizacion', '=', 'cotizacion.id_cotizacion')
                       ->join('solicitante', 'cotizacion.rut_solicitante', '=', 'solicitante.rut_solicitante')
                       ->join('norma', 'muestra.id_norma', '=', 'norma.id_norma')
                       ->select('norma.*')
                       ->where([
                               ['muestra.RUM', '=', $RUM],
                               ['solicitante.rut_solicitante', '=', $rut_solicitante]
                               ])
                       ->get();

            $parametros_metodologias = DB::table('muestra')
                                         ->join('muestra_cotizacion', 'muestra.RUM', '=', 'muestra_cotizacion.RUM')
                                         ->join('cotizacion', 'muestra_cotizacion.id_cotizacion', '=', 'cotizacion.id_cotizacion')
                                         ->join('solicitante', 'cotizacion.rut_solicitante', '=', 'solicitante.rut_solicitante')
                                         ->join('muestra_parametro_metodologia', 'muestra.RUM', 'muestra_parametro_metodologia.RUM')
                                         ->join('parametro', 'muestra_parametro_metodologia.id_parametro', 'parametro.id_parametro')
                                         ->join('metodologia', 'muestra_parametro_metodologia.id_metodologia', 'metodologia.id_metodologia')
                                         ->select('parametro.*', 'metodologia.*')
                                         ->where([
                                            ['muestra.RUM', '=', $RUM],
                                            ['solicitante.rut_solicitante', '=', $rut_solicitante]
                                            ])
                                         ->get();

            $muestra->cotizacion = $this->obtener_Cotizaciones($RUM);

            $muestra->norma = $norma;

            $muestra->parametros_metodologias = $parametros_metodologias;

            return $muestra;
        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function responder_Encuesta (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Responder_Encuesta());

        $rut_solicitante = Auth::user()->rut_solicitante;

        if((!is_array($validacion)) && ($rut_solicitante != null)){

            $encuesta = Encuesta::create($request->all());

            $this->crear_Relacion_Encuesta_Muestra($request->RUM, $encuesta->id_encuesta);

            (new SolicitanteEncuestaController)->crear_Relacion_Solicitante_Encuesta($rut_solicitante, $encuesta->id_encuesta);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function descargar_Informe ()
    {

        //TODO: Hacer referencia al controlador Informe.

    }

    public function crear_Relacion_Encuesta_Muestra($RUM, $id_encuesta)
    {

        $muestra = Muestra::find($RUM);

        if($muestra){

            $muestra->update(['id_encuesta' => $id_encuesta]);

        }

    }

    public function obtener_Cotizaciones ($RUM)
    {

        $muestra = Muestra::find($RUM);

        $cotizaciones = $muestra->cotizaciones;

        foreach ($cotizaciones as $cotizacion){

            $cotizacion->fecha_ingreso = $this->cambiar_Formato_Fecha($cotizacion->fecha_ingreso);

            $cotizacion->fecha_emision = $this->cambiar_Formato_Fecha($cotizacion->fecha_emision);

        }

        return $cotizaciones;

    }

    public function cambiar_Formato_Fecha ($fecha_a_cambiar)
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

    public function validacion_Responder_Encuesta ()
    {

        return [

            'puntaje' => 'required',
            'observaciones' => 'min:3|max:255',

        ];

    }

}
