<?php

namespace App\Http\Controllers;

use App\Models\Tabla;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\NormaTablaController;
use App\Http\Controllers\TablaParametroMetodologiaController;

class TablaController extends Controller
{

    public function tablas()
    {

        $tablas = Tabla::all();

        return $tablas;

    }

    public function crear_Tablas($id_norma, $id_matriz, $tablas_agregar)
    {

        $validacion = $this->validacion($tablas_agregar, $this->validacion_Agregar_Tabla());

        if(!is_array($validacion)){

            $tablas_agregar = Arr::flatten($tablas_agregar);

            if ($tablas_agregar != null) {

                for ($i = 0, $largo = count($tablas_agregar); $i < $largo; $i+=3){

                    $existe_tabla = $this->existe_Tabla_Busqueda_Por_Nombre($tablas_agregar[$i]);

                    if($existe_tabla != true){

                        $tabla = new Tabla();

                        $tabla->nombre_tabla = $tablas_agregar[$i];

                        $tabla->id_matriz = $id_matriz;

                        $tabla->save();

                        (new NormaTablaController)->crear_Relacion_Norma_Tabla($id_norma, $tabla->id_tabla);

                        (new TablaParametroMetodologiaController)->crear_Relacion_Tabla_Parametro_Metodologia($tabla->id_tabla, $tablas_agregar[$i+1], $tablas_agregar[$i+2]);

                    }else{

                        $id_tabla = $this->devolver_Id_Tabla_Por_Nombre($tablas_agregar[$i]);

                        (new TablaParametroMetodologiaController)->crear_Relacion_Tabla_Parametro_Metodologia($id_tabla, $tablas_agregar[$i+1], $tablas_agregar[$i+2]);

                    }

                }
            }
        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function crear_Tablas_Para_Actualizacion ($id_norma, $tablas)
    {

        foreach($tablas as $tabla){

            $id_matriz = $tabla['id_matriz'];

            if($tabla['tablas_agregar'] != null){

                $validacion = $this->validacion($tabla['tablas_agregar'], $this->validacion_Agregar_Tabla());

                if(!is_array($validacion)){

                    $tablas_agregar = Arr::flatten($tabla['tablas_agregar']);

                    for ($i = 0, $largo = count($tablas_agregar); $i < $largo; $i+=3){

                        $tabla = new Tabla();

                        $tabla->nombre_tabla = $tablas_agregar[$i];

                        $tabla->id_matriz = $id_matriz;

                        $tabla->save();

                        (new NormaTablaController)->crear_Relacion_Norma_Tabla($id_norma, $tabla->id_tabla);

                        (new TablaParametroMetodologiaController)->crear_Relacion_Tabla_Parametro_Metodologia($tabla->id_tabla, $tablas_agregar[$i+1], $tablas_agregar[$i+2]);

                    }

                }else{

                    return response()->json(['error' => 'bad request'], 400);

                }

            }else{

                return response()->json(['error' => 'bad request'], 400);

            }

        }

    }

    public function actualizar_Tablas(Request $request)
    {

        if(($request->tablas_agregar != null) && ($request->tablas_eliminar == null)){

            $this->crear_Tablas_Para_Actualizacion($request->id_norma, $request->tablas_agregar);

        }else if(($request->tablas_agregar == null) && ($request->tablas_eliminar != null)){

            $this->eliminar_Tablas(Arr::flatten($request->tablas_eliminar));

        }else if(($request->tablas_agregar != null) && ($request->tablas_eliminar != null)){

            $this->crear_Tablas_Para_Actualizacion($request->id_norma, $request->tablas_agregar);

            $this->eliminar_Tablas(Arr::flatten($request->tablas_eliminar));

        }

    }

    public function eliminar_Tablas (array $tablas_a_eliminar)
    {

        if ($tablas_a_eliminar != null){

            for ($i = 0, $largo = count($tablas_a_eliminar); $i < $largo; $i += 1){

                $tabla_a_eliminar = Tabla::find($tablas_a_eliminar[$i]);

                if($tabla_a_eliminar != null){

                    $tabla_a_eliminar->delete();

                }

            }

        }

    }

    public function eliminar_Tablas_Segun_Matriz ($tablas_a_eliminar)
    {

        if ($tablas_a_eliminar != null){

            foreach($tablas_a_eliminar as $tabla_a_eliminar){

                $tabla_a_eliminar = Tabla::find($tabla_a_eliminar['id_tabla']);

                if($tabla_a_eliminar != null){

                    $tabla_a_eliminar->delete();

                }

            }

        }

    }


    public function devolver_Id_Tabla_Por_Nombre ($nombre_tabla)
    {

        $tabla = Tabla::where('nombre_tabla', '=', $nombre_tabla)->first();

        if($tabla != null){

            return $tabla->id_tabla;

        }else{

            return response()->json(['error' => 'bad request'], 400);
        }

    }


    public function existe_Tabla_Busqueda_Por_Nombre($nombre_tabla)
    {

        $tabla = Tabla::where('nombre_tabla', '=', $nombre_tabla)->first();

        if($tabla != null){

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

    public function validacion_Agregar_Tabla ()
    {

        return [

            'nombre_tabla.*' => 'required|min:3|max:255',

        ];

    }

}
