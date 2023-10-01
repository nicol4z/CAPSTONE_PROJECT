<?php

namespace App\Http\Controllers;

use App\Models\Parametro;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\MetodologiaParametroController;

class ParametroController extends Controller
{

    public function parametros()
    {

        $parametros = Parametro::with('metodologias')->get();

        return $parametros;

    }

    public function detalles_Parametros($id_parametro){

        $parametro = Parametro::find($id_parametro);

        if ($parametro){

            $detalles = Parametro::with('metodologias')->find($parametro->id_parametro);

            return $detalles;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function crear_Parametro (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Agregar_Parametro());

        $existe_Parametro = $this->existe_Parametro_Busqueda_Por_Nombre($request->nombre_parametro);

        if((!is_array($validacion)) && ($existe_Parametro == false)){

            $parametro = Parametro::create($request->all());

            (new MetodologiaParametroController)->crear_Relacion_Metodologia_Parametro($parametro->id_parametro, $request);

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function actualizar_Parametro (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Actualizar_Parametro());

        $parametro = Parametro::find($request->id_parametro);

        if(!is_array($validacion)){

            $parametro->update($request->all());

            (new MetodologiaParametroController)->crear_Relacion_Metodologia_Parametro($request->id_parametro, $request);

            (new MetodologiaParametroController)->eliminar_Relacion_Metodologia_Parametro($request);

            return response()->json('success', 200);

        }else{

            return response()->json($validacion);

        }

    }

    public function existe_Parametro_Busqueda_Por_Nombre($nombre_parametro)
    {

        $parametro = Parametro::where('nombre_parametro', '=', $nombre_parametro)->first();

        if( $parametro != null){

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

    public function validacion_Agregar_Parametro ()
    {

        return [

            'nombre_parametro' => 'unique:Parametro|bail|required|min:3|max:255',
            'nombre_metodologias.*' => 'min:3|max:255',

        ];

    }

    public function validacion_Actualizar_Parametro ()
    {

        return [

            'nombre_parametro' => 'unique:Parametro|bail|min:3|max:255',

        ];

    }

}
