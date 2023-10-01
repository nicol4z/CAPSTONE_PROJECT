<?php

namespace App\Http\Controllers;

use App\Models\Matriz;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\MatrizMetodologiaParametroController;

class MatrizController extends Controller
{
    public function matrices ()
    {

        $matrices = Matriz::all();

        return $matrices;

    }

    public function matrices_Parametros ()
    {

        $matrices = DB::table('metodologia')
                    ->join('matriz_metodologia_parametro', 'metodologia.id_metodologia', '=', 'matriz_metodologia_parametro.id_metodologia')
                    ->join('parametro', 'parametro.id_parametro', '=', 'matriz_metodologia_parametro.id_parametro')
                    ->join('matriz', 'matriz.id_matriz', '=', 'matriz_metodologia_parametro.id_matriz')
                    ->select('matriz.*', 'parametro.*', 'metodologia.*')
                    ->get();

        return $matrices;

    }

    public function detalles_Matriz ($id_matriz)
    {

        $matriz = Matriz::find($id_matriz);

        if ($matriz){

            $detalles = DB::table('metodologia')
                            ->join('matriz_metodologia_parametro', 'metodologia.id_metodologia', '=', 'matriz_metodologia_parametro.id_metodologia')
                            ->join('parametro', 'parametro.id_parametro', '=', 'matriz_metodologia_parametro.id_parametro')
                            ->join('matriz', 'matriz.id_matriz', '=', 'matriz_metodologia_parametro.id_matriz')
                            ->select('matriz.*', 'parametro.*', 'metodologia.*')
                            ->where('matriz.id_matriz','=', $id_matriz)
                            ->get();

            return $detalles;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function crear_Matriz (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Agregar_Matriz());

        $existe_Matriz = $this->existe_Matriz_Busqueda_Por_Nombre($request->nombre_matriz);

        if((!is_array($validacion)) && ($existe_Matriz == false)){

            $matriz = Matriz::create($request->all());

            (new MatrizMetodologiaParametroController)->crear_Relacion_Matriz_Metodologia_Parametro($matriz->id_matriz, $request);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function actualizar_Matriz (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Actualizar_Matriz());

        $matriz = Matriz::find($request->id_matriz);

        if(!is_array($validacion)){

            $matriz->update($request->all());

            (new MatrizMetodologiaParametroController)->crear_Relacion_Matriz_Metodologia_Parametro($matriz->id_matriz, $request);

            (new MatrizMetodologiaParametroController)->eliminar_Relacion_Matriz_Metodologia_Parametro($request);

            return response()->json('success', 200);

        }else{

            return response()->json($validacion);

        }

    }

    public function existe_Matriz_Busqueda_Por_Nombre($nombre_matriz)
    {

        $matriz = Matriz::where('nombre_matriz', '=', $nombre_matriz)->first();

        if( $matriz != null){

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

    public function validacion_Agregar_Matriz ()
    {

        return [

            'nombre_matriz' => 'unique:Matriz|bail|required|min:3|max:255',
            'id_parametro.*' => 'required|min:3|max:255',
            'id_metodologia.*' => 'required|min:3|max:255',

        ];

    }

    public function validacion_Actualizar_Matriz ()
    {

        return [

            'nombre_matriz' => 'unique:Matriz|min:3|max:255',

        ];

    }

}
