<?php

namespace App\Http\Controllers;

use App\Models\Muestra;
use App\Models\Empleado;
use App\Models\Empleado_Muestra;
use App\Models\Muestra_Observaciones;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AdministrarMuestraAnalistaYQuimicoController extends Controller
{
    public function muestras()
    {

        $rut_empleado = Auth::user()->rut;

        $muestras = DB::table('empleado')
                       ->join('empleado_muestra', 'empleado.rut_empleado', '=', 'empleado_muestra.rut_empleado')
                       ->join('muestra', 'empleado_muestra.RUM', '=', 'muestra.RUM')
                       ->select('muestra.RUM', 'muestra.fecha_ingreso', 'muestra.hora_ingreso', 'muestra.prioridad')
                       ->where('empleado.rut_empleado', '=', $rut_empleado)
                       ->distinct()
                       ->get();

        foreach($muestras as $muestra){

            $fecha_entrega = DB::table('empleado_muestra')
                               ->join('empleado', 'empleado_muestra.rut_empleado', '=', 'empleado.rut_empleado')
                               ->select('empleado_muestra.fecha_entrega')
                               ->where([
                                       ['empleado_muestra.RUM', '=', $muestra->RUM],
                                       ['empleado_muestra.rut_empleado', '=', $rut_empleado]
                                       ])
                               ->get();

            $estado = DB::table('empleado_muestra')
                        ->join('empleado', 'empleado_muestra.rut_empleado', '=', 'empleado.rut_empleado')
                        ->where([
                                ['empleado_muestra.RUM', '=', $muestra->RUM],
                                ['empleado_muestra.rut_empleado', '=', $rut_empleado]
                                ])
                        ->value('empleado_muestra.estado');

            $matriz = DB::table('muestra')
                        ->join('matriz', 'muestra.id_matriz', '=', 'matriz.id_matriz')
                        ->where('muestra.RUM', '=', $muestra->RUM)
                        ->select('matriz.*')
                        ->get();

            $parametros_metodologias = DB::table('empleado_muestra')
                                         ->join('parametro', 'empleado_muestra.id_parametro', '=', 'parametro.id_parametro')
                                         ->join('muestra_parametro_metodologia', 'parametro.id_parametro', '=', 'muestra_parametro_metodologia.id_parametro')
                                         ->join('metodologia', 'muestra_parametro_metodologia.id_metodologia', '=', 'metodologia.id_metodologia')
                                         ->where([
                                                 ['muestra_parametro_metodologia.RUM', '=', $muestra->RUM],
                                                 ['empleado_muestra.RUM', '=', $muestra->RUM],
                                                 ['empleado_muestra.rut_empleado', '=', $rut_empleado]
                                                 ])
                                         ->select('parametro.*', 'metodologia.*')
                                         ->get();

            $muestra->fecha_entrega = $fecha_entrega->value('fecha_entrega');

            $muestra->estado = $estado;

            $muestra->fecha_ingreso_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($muestra->fecha_ingreso);

            $muestra->hora_ingreso_formateada = $this->cambiar_Formato_Hora_A_H_M($muestra->hora_ingreso);

            $muestra->fecha_entrega_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($fecha_entrega->value('fecha_entrega'));

            $muestra->dias_faltantes = Carbon::parse($muestra->fecha_entrega)->diffInDays(Carbon::now()->format('Y-m-d'))+1;

            $muestra->matriz = $matriz;

            $muestra->parametros_metodologias = $parametros_metodologias;

        }

        return $muestras;

    }

    public function detalles_Muestra_Para_Analista_Y_Quimico ($RUM)
    {

        $muestra = Muestra::select('RUM', 'cantidad_muestras', 'muestreado_por', 'fecha_ingreso', 'hora_ingreso', 'prioridad', 'fecha_entrega', 'id_matriz', 'id_norma')
                          ->with('norma', 'matriz')->find($RUM);

        if ($muestra){

            $muestra->fecha_ingreso_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($muestra->fecha_ingreso);

            $muestra->hora_ingreso_formateada = $this->cambiar_Formato_Hora_A_H_M($muestra->hora_ingreso);

            $muestra->fecha_entrega_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($muestra->fecha_entrega);

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

            $muestra->parametros_metodologias = $parametros_metodologias;

            $muestra->empleados = $empleados;

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

    public function crear_Observacion_Analista_O_Quimico (Request $request)
    {

        $fecha_actual = Carbon::now()->format('Y/m/d');

        $hora_actual = Carbon::now()->format('H:i:s');

        Muestra_Observaciones::create($request->all() + ['fecha_observacion' => $fecha_actual] + ['hora_observacion' => $hora_actual]);

        return response()->json('success', 200);

    }

    // TODO: Optimizar.

    public function marcar_Como_Entregado_Un_Analisis ($RUM)
    {

        $rut_empleado = Auth::user()->rut;

        $empleados_asociados_a_muestra = DB::table('empleado_muestra')
                                           ->orderBy('orden_de_analisis', 'asc')
                                           ->where('empleado_muestra.RUM', '=', $RUM)
                                           ->pluck('empleado_muestra.rut_empleado')
                                           ->toArray();

        $index_empleado_anterior = array_search($rut_empleado, $empleados_asociados_a_muestra)-1;

        $orden_estado_empleado_actual = $this->buscar_Orden_Y_Estado($rut_empleado, $RUM);

        if($orden_estado_empleado_actual->estado == 'Entregado'){

            return response()->json(['error' => 'bad request'], 208);

        }else{

            if($index_empleado_anterior < 0){

                $this->actualizar_Estado($rut_empleado, $RUM);

            }else{

                $rut_empleado_anterior = $empleados_asociados_a_muestra[$index_empleado_anterior];

                $orden_estado_empleado_anterior = $this->buscar_Orden_Y_Estado($rut_empleado_anterior, $RUM);


                if($orden_estado_empleado_anterior->orden_de_analisis == $orden_estado_empleado_actual->orden_de_analisis){

                    $this->actualizar_Estado($rut_empleado, $RUM);

                }else if(($orden_estado_empleado_anterior->orden_de_analisis < $orden_estado_empleado_actual->orden_de_analisis)
                        && ($orden_estado_empleado_anterior->estado != 'Entregado')){

                    return response()->json(['error' => 'bad request'], 428);

                }else if(($orden_estado_empleado_anterior->orden_de_analisis < $orden_estado_empleado_actual->orden_de_analisis)
                        && ($orden_estado_empleado_anterior->estado == 'Entregado') &&
                        ($this->existe_Un_Orden_De_Analisis_Repetido($RUM, $orden_estado_empleado_anterior->orden_de_analisis) == false)){

                    $this->actualizar_Estado($rut_empleado, $RUM);

                }else if(($orden_estado_empleado_anterior->orden_de_analisis < $orden_estado_empleado_actual->orden_de_analisis)
                        && ($orden_estado_empleado_anterior->estado == 'Entregado') &&
                        ($this->existe_Un_Orden_De_Analisis_Repetido($RUM, $orden_estado_empleado_anterior->orden_de_analisis) == true)){

                    if($this->existen_Orden_De_Analisis_Repetidos_Sin_Terminar($RUM, $orden_estado_empleado_anterior->orden_de_analisis) == true){

                        return response()->json(['error' => 'bad request'], 428);

                    }else{

                        $this->actualizar_Estado($rut_empleado, $RUM);

                    }

                }else{

                    return response()->json(['error' => 'bad request'], 400);

                }

            }

        }

    }

    public function buscar_Orden_Y_Estado ($rut_empleado, $RUM)
    {

        $empleado = Empleado::find($rut_empleado);

        if ($empleado){

            $orden_estado = Empleado_Muestra::where([
                                                    ['rut_empleado', '=', $rut_empleado],
                                                    ['RUM', '=', $RUM]
                                                    ])
                                            ->select('estado', 'orden_de_analisis')
                                            ->first();

            return $orden_estado;

        }else{

            return null;

        }

    }

    public function existe_Un_Orden_De_Analisis_Repetido($RUM, $orden_de_analisis)
    {

        $muestra = Muestra::find($RUM);

        if($muestra){

            $existe = DB::table('empleado_muestra')
                        ->where([
                                ['RUM', '=', $RUM],
                                ['orden_de_analisis', '=', $orden_de_analisis]
                                ])
                        ->get();

            if($existe->count() > 1){

                return true;

            }else{

                return false;

            }

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function existen_Orden_De_Analisis_Repetidos_Sin_Terminar($RUM, $orden_de_analisis)
    {

        $muestra = Muestra::find($RUM);

        if($muestra){

            $existe_analisis_sin_iniciar = DB::table('empleado_muestra')
                                             ->where([
                                                     ['RUM', '=', $RUM],
                                                     ['orden_de_analisis', '=', $orden_de_analisis],
                                                     ['estado', '=', 'Sin iniciar']
                                                     ])
                                             ->get();

            if($existe_analisis_sin_iniciar->count() >= 1){

                return true;

            }else{

                return false;

            }

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function actualizar_Estado ($rut_empleado, $RUM){

        $empleado = Empleado::find($rut_empleado);

        $muestra = Muestra::find($RUM);

        if(($empleado != null) && ($muestra != null)){

            DB::table('empleado_muestra')
              ->where([
                      ['rut_empleado', '=', $rut_empleado],
                      ['RUM', '=', $RUM]
                      ])
              ->update(['estado' => 'Entregado']);

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

}
