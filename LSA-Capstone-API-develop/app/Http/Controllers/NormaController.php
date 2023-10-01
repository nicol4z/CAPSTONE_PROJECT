<?php

namespace App\Http\Controllers;

use App\Models\Norma;
use App\Models\Matriz;

use Illuminate\Support\Arr;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\TablaController;
use App\Http\Controllers\NormaMatrizController;

class NormaController extends Controller
{

    public function normas ()
    {

        $normas = Norma::with('matrices')->get();

        return $normas;

    }

    public function normas_Matriz ($id_matriz)
    {

        $norma = Matriz::with('normas')->find($id_matriz);

        return $norma;
    }

    public function detalles_Normas ($id_norma)
    {

        $norma = Norma::find($id_norma);

        if ($norma){

            $detalles = DB::table('norma')
                          ->join('norma_tabla', 'norma.id_norma', '=', 'norma_tabla.id_norma')
                          ->join('tabla', 'norma_tabla.id_tabla', '=', 'tabla.id_tabla')
                          ->join('matriz', 'tabla.id_matriz', '=', 'matriz.id_matriz')
                          ->join('tabla_parametro_metodologia', 'tabla.id_tabla', '=', 'tabla_parametro_metodologia.id_tabla')
                          ->join('parametro', 'tabla_parametro_metodologia.id_parametro', '=', 'parametro.id_parametro')
                          ->join('metodologia', 'tabla_parametro_metodologia.id_metodologia', '=', 'metodologia.id_metodologia')
                          ->select('norma.*', 'tabla.*', 'matriz.*', 'parametro.*', 'metodologia.*')
                          ->where('norma.id_norma','=', $id_norma)
                          ->get();

            return $detalles;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function crear_Norma (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Agregar_Norma());

        $existe_Norma = $this->existe_Norma_Busqueda_Por_Nombre($request->nombre_norma);

        if((!is_array($validacion)) && ($existe_Norma == false)){

            $norma = Norma::create($request->all());

            (new NormaMatrizController)->crear_Relacion_Norma_Matriz($norma->id_norma, $request->matrices_agregar);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function actualizar_Norma (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Actualizar_Norma());

        $norma = Norma::find($request->id_norma);

        $existe_nombre_norma = $this->existe_Norma_Busqueda_Por_Nombre($request->norma);

        if((!is_array($validacion))){

            if(($request->nombre_norma != $norma->nombre_norma) && ($existe_nombre_norma != true)){

                $norma->update($request->all());

                $this->actualizar_Relaciones_Asociadas($request);

            }else{

                $this->actualizar_Relaciones_Asociadas($request);

            }

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function actualizar_Relaciones_Asociadas (Request $request)
    {

        (new TablaController)->actualizar_Tablas($request);

        (new NormaMatrizController)->actualizar_Matrices($request);

    }

    public function existe_Norma_Busqueda_Por_Nombre($nombre_norma)
    {

        $norma = Norma::where('nombre_norma', '=', $nombre_norma)->first();

        if($norma != null){

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

    public function validacion_Agregar_Norma ()
    {

        return [

            'nombre_norma' => 'unique:Norma|bail|required|min:3|max:255',

        ];

    }

    public function validacion_Actualizar_Norma ()
    {

        return [

            'nombre_norma' => 'min:3|max:255',

        ];

    }

}
