<?php

namespace App\Http\Controllers;

use App\Models\Matriz;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class VisualizarDatosController extends Controller
{

    public function visualizar_Datos()
    {

        $datos = Matriz::with('parametros.metodologias.empleados:rut_empleado,nombre,apellido')->get();

        return $datos;

    }

    public function detalles_Datos($id_matriz)
    {

        $matriz = Matriz::find($id_matriz);

        if ($matriz){

            $parametros = DB::table('matriz')
                            ->join('matriz_metodologia_parametro', 'matriz.id_matriz', '=', 'matriz_metodologia_parametro.id_matriz')
                            ->join('parametro', 'matriz_metodologia_parametro.id_parametro', '=', 'parametro.id_parametro')
                            ->select('parametro.*')
                            ->where('matriz.id_matriz', '=', $id_matriz)
                            ->get();

            foreach($parametros as $parametro){

                $metodologias = DB::table('metodologia')
                                  ->join('matriz_metodologia_parametro', 'metodologia.id_metodologia', '=', 'matriz_metodologia_parametro.id_metodologia')
                                  ->select('metodologia.*')
                                  ->where([
                                          ['matriz_metodologia_parametro.id_matriz', '=', $id_matriz],
                                          ['matriz_metodologia_parametro.id_parametro', '=', $parametro->id_parametro]
                                          ])
                                  ->get();

                $parametro->metodologias = $metodologias;

                foreach($metodologias as $metodologia){

                    $empleados = DB::table('empleado')
                                   ->join('empleado_metodologia', 'empleado.rut_empleado', '=', 'empleado_metodologia.rut_empleado')
                                   ->join('matriz_metodologia_parametro', 'empleado_metodologia.id_metodologia', '=', 'matriz_metodologia_parametro.id_metodologia')
                                   ->select('empleado.rut_empleado', 'empleado.nombre', 'empleado.apellido')
                                   ->where([
                                           ['matriz_metodologia_parametro.id_matriz', '=', $id_matriz],
                                           ['matriz_metodologia_parametro.id_parametro', '=', $parametro->id_parametro],
                                           ['matriz_metodologia_parametro.id_metodologia', '=', $metodologia->id_metodologia]
                                           ])
                                   ->get();

                    $metodologia->empleados = $empleados;

                }

            }

            return $parametros;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

}
