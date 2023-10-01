<?php

namespace App\Http\Controllers;

use App\Models\Muestra;
use App\Models\Submuestra;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmpleadoMuestraController;
use App\Http\Controllers\EmpleadoSubmuestraController;
use App\Http\Controllers\MuestraObservacionesController;
use App\Http\Controllers\RecepcionIngresoMuestraController;
use App\Http\Controllers\AdministrarMuestraAnalistaYQuimicoController;

class AdministrarMuestraJefeLaboratorioSupervisorController extends Controller
{

    public function muestras ()
    {

        $muestras = Muestra::select('RUM', 'fecha_ingreso', 'hora_ingreso', 'fecha_entrega', 'prioridad', 'estado', 'id_matriz')
                           ->with('matriz')->get();

        foreach ($muestras as $muestra){

            $muestra->fecha_ingreso_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($muestra->fecha_ingreso);

            $muestra->hora_ingreso_formateada = $this->cambiar_Formato_Hora_A_H_M($muestra->hora_ingreso);

            $muestra->fecha_entrega_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($muestra->fecha_entrega);

        }

        return $muestras;

    }

    public function empleados ()
    {

        $empleados = DB::table('empleado')
                       ->where(function ($query){
                            $query->where('empleado.rol', '=', 'Supervisor(a)')
                                  ->orWhere('empleado.rol', '=', 'Analista Químico')
                                  ->orWhere('empleado.rol', '=', 'Químico');
                       })
                       ->select('empleado.rut_empleado', 'empleado.nombre', 'empleado.apellido')
                       ->get();

        return $empleados;

    }

    public function parametros ($RUM)
    {

        $muestra = Muestra::find($RUM);

        if($muestra){

            $parametros = DB::table('muestra')
                            ->join('muestra_parametro_metodologia', 'muestra.RUM', 'muestra_parametro_metodologia.RUM')
                            ->join('parametro', 'muestra_parametro_metodologia.id_parametro', 'parametro.id_parametro')
                            ->select('parametro.*')
                            ->where('muestra.RUM', '=', $RUM)
                            ->get();

            return $parametros;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function submuestras ($RUM)
    {

        $muestra = Muestra::find($RUM);

        if($muestra){

            $submuestras = DB::table('submuestra')
                             ->select('submuestra.*')
                             ->where('submuestra.RUM', '=', $RUM)
                             ->get();

            foreach($submuestras as $submuestra){

                $parametros = DB::table('submuestra')
                                ->join('submuestra_parametro_metodologia', 'submuestra.id_submuestra', '=', 'submuestra_parametro_metodologia.id_submuestra')
                                ->join('parametro', 'submuestra_parametro_metodologia.id_parametro', '=', 'parametro.id_parametro')
                                ->select('parametro.*')
                                ->where('submuestra.id_submuestra', '=', $submuestra->id_submuestra)
                                ->get();

                $submuestra->parametros = $parametros;

            }

            return $submuestras;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function resultados_Analisis ($RUM)
    {

        $muestra = Muestra::find($RUM);

        if($muestra){

            $resultados = DB::table('submuestra')
                            ->join('empleado_submuestra', 'submuestra.identificador', '=', 'empleado_submuestra.identificador')
                            ->select('empleado_submuestra.*')
                            ->where('submuestra.RUM', '=', $RUM)
                            ->get();

            return $resultados;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function detalles_Muestra ($RUM)
    {


        $muestra = Muestra::select('RUM', 'cantidad_muestras', 'muestreado_por', 'tipo_pago', 'valor_neto', 'fecha_ingreso', 'hora_ingreso', 'fecha_entrega', 'prioridad', 'id_matriz', 'id_norma')
                           ->with('matriz', 'norma')
                           ->where('RUM', '=', $RUM)
                           ->first();

        if($muestra){


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

            $muestra->fecha_ingreso_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($muestra->fecha_ingreso);

            $muestra->hora_ingreso_formateada = $this->cambiar_Formato_Hora_A_H_M($muestra->hora_ingreso);

            $muestra->fecha_entrega_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($muestra->fecha_entrega);

            $muestra->empleados = $empleados;

            $muestra->parametros_metodologias = $parametros_metodologias;

            return $muestra;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function ingresar_Muestra (Request $request)
    {

        return (new RecepcionIngresoMuestraController)->ingresar_Muestra($request);

    }

    public function modificar_Fecha_Entrega (Request $request)
    {

        $muestra = Muestra::find($request->RUM);

        if($muestra){

            $muestra->update(['fecha_entrega'=> $request->nueva_fecha_entrega]);

            return response()->json('success', 200);

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

    public function crear_Observacion (Request $request)
    {

        $rut_empleado = Auth::user()->rut;

        $muestra = Muestra::find($request->RUM);

        if($muestra){

            (new MuestraObservacionesController)->crear_Observacion($request->RUM, $rut_empleado, $request->observaciones);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function modificar_Observacion (Request $request)
    {

        $rut_empleado = Auth::user()->rut;

        $muestra = Muestra::find($request->RUM);

        if($muestra){

            (new MuestraObservacionesController)->actualizar_Observaciones($request->RUM, $rut_empleado, $request->observaciones);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function analistas_Designados ($RUM)
    {

        $muestra = Muestra::find($RUM);

        if($muestra){

            $analistas = DB::table('empleado_muestra')
                       ->join('empleado', 'empleado_muestra.rut_empleado', '=', 'empleado.rut_empleado')
                       ->join('parametro', 'empleado_muestra.id_parametro', '=', 'parametro.id_parametro')
                       ->select('empleado.nombre', 'empleado.apellido', 'empleado_muestra.*', 'parametro.*')
                       ->where('RUM', '=', $RUM)
                       ->orderBy('empleado_muestra.orden_de_analisis')
                       ->get();

            foreach($analistas as $analista){

                $analista->fecha_entrega_formateada = $this->cambiar_Formato_Fecha_A_D_M_Y($analista->fecha_entrega);

            }

        return $analistas;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function modificar_Analistas (Request $request)
    {

        $muestra = Muestra::find($request->RUM);

        if($muestra){

            (new EmpleadoMuestraController)->actualizar_Relacion_Empleado_Muestra($request);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function marcar_Tarea_Como_Completada ($RUM)
    {

        return (new AdministrarMuestraAnalistaYQuimicoController)->marcar_Como_Completado_Un_Analisis($RUM);

    }

    public function marcar_Analisis_Como_Completado ($RUM)
    {

        $muestra = Muestra::find($RUM);

        if($muestra){

            DB::table('empleado_muestra')
              ->where('RUM', '=', $RUM)
              ->update(['estado' => 'Completado']);

            $this->actualizar_Estado_Muestra($RUM, 'Finalizado');

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function ingresar_Resultados_Analisis (Request $request)
    {

        $rut_empleado = Auth::user()->rut;

        $muestra = Muestra::find($request->RUM);

        if($muestra){

            (new EmpleadoSubmuestraController)->crear_Relacion_Empleado_Submuestra($rut_empleado, $request);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function rehacer_Analisis (Request $request)
    {

        $muestra = Muestra::find($request->RUM);

        if($muestra){

            $this->modificar_Fecha_Entrega($request);

            $this->eliminar_Resultados_Analisis($request->RUM);

            (new EmpleadoMuestraController)->actualizar_Relacion_Empleado_Muestra($request->RUM);

            $this->actualizar_Estado_Muestra($request->RUM, 'En Análisis');

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function descargar_Informe ()
    {

        //TODO: Hacer referencia al controlador Informe.

    }

    public function actualizar_Estado_Muestra ($RUM, string $estado)
    {

        $muestra = Muestra::find($RUM);

        if($muestra){

            DB::table('muestra')
              ->where('RUM', '=', $RUM)
              ->update(['estado' => $estado]);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function eliminar_Resultados_Analisis($RUM)
    {

        $submuestras = Submuestra::where('RUM', '=', $RUM)
                                ->select('id_submuestra')
                                ->get();

        foreach($submuestras as $submuestra){

            DB::table('empleado_submuestra')
              ->where('id_submuestra', '=', $submuestra->id_submuestra)
              ->delete();

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
