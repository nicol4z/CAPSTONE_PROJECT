<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Empresa;
use App\Models\Empresa_Ciudad;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\EmpresaCiudadController;

class CiudadController extends Controller
{

    public function ciudades ()
    {

        $ciudades = Ciudad::all();

        return $ciudades;

    }

    public function crear_Ciudad (Request $request, $nombre_ciudad, $direccion)
    {

        $ciudad = new Ciudad();

        $ciudad->nombre_ciudad = $nombre_ciudad;

        $ciudad->direccion = $direccion;

        $ciudad->save();

        (new EmpresaCiudadController)->crear_Relacion_Empresa_Ciudad($request->rut_empresa, $ciudad->id_ciudad);

    }

    public function crear_Ciudades (Request $request)
    {

        $direcciones = Arr::flatten($request->direcciones);

        $validacion = $this->validacion($request->direcciones, $this->validacion_Agregar_Ciudad());

        if(!is_array($validacion)){

            for ($i = 1, $largo = count($direcciones); $i < $largo; $i += 2){

                $nombre_ciudad = $direcciones[$i];

                $direccion = $direcciones[$i+1];

                if (!$this->existe_Ciudad($nombre_ciudad, $direccion)){

                    $ciudad = new Ciudad();

                    $ciudad->nombre_ciudad = $nombre_ciudad;

                    $ciudad->direccion = $direccion;

                    $ciudad->save();

                    (new EmpresaCiudadController)->crear_Relacion_Empresa_Ciudad($request->rut_empresa, $ciudad->id_ciudad);

                }else{

                    return response()->json(['error' => 'forbidden'], 403);

                }
            }

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function actualizar_Ciudad ($id_ciudad, $nombre_ciudad, $direccion)
    {

        $ciudad = Ciudad::find($id_ciudad);

        $ciudad->nombre_ciudad = $nombre_ciudad;

        $ciudad->direccion = $direccion;

        $ciudad->update();

    }

    public function actualizar_Ciudades (Request $request)
    {

        $direcciones = Arr::flatten($request->direcciones);

        $validacion = $this->validacion($request->all(), $this->validacion_Actualizar_Ciudad());

        if(!is_array($validacion)){

            for ($i = 1, $largo = count($direcciones); $i < $largo; $i += 3){

                $id_ciudad = $direcciones[$i];

                $nombre_ciudad = $direcciones[$i+1];

                $direccion = $direcciones[$i+2];

                $ciudad = $this->busqueda_Ciudad_Por_Nombre($nombre_ciudad);

                if($id_ciudad != null && $nombre_ciudad != null && $direccion != null){

                    $this->actualizar_Ciudad($id_ciudad, $nombre_ciudad, $direccion);

                }else if ($id_ciudad == null && $nombre_ciudad != null && $direccion != null){

                    $this->crear_Ciudad($request, $nombre_ciudad, $direccion);

                }else if ($id_ciudad != null && $nombre_ciudad == null && $direccion == null){

                    $this->eliminar_Ciudad($id_ciudad);

                }else{
                    return response()->json(['error' => 'bad request'], 400);
                }

            }

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function eliminar_Ciudad ($id_ciudad)
    {

        $ciudad = Ciudad::find($id_ciudad);

        return $ciudad->delete();

    }

    public function eliminar_Ciudades ($rut_empresa)
    {

        $id_ciudades = Empresa_Ciudad::where([
                                             ['rut_empresa', $rut_empresa]
                                            ])->get('id_ciudad');

        foreach( $id_ciudades as $id_ciudad){

            $ciudad = Ciudad::find($id_ciudad);

            $ciudad->each->delete();

        }

        return response()->json('success', 200);

    }

    public function existe_Ciudad ($nombre_ciudad, $direccion){

        $existe = Ciudad::where([
                                ['nombre_ciudad', $nombre_ciudad],
                                ['direccion', $direccion],
                               ])->get();

        if (!$existe->isEmpty()){

            return true;

        }else{

            return false;
        }

    }

    public function busqueda_Ciudad_Por_Nombre ($nombre_ciudad)
    {

        $ciudad = Ciudad::where('nombre_ciudad', '=', $nombre_ciudad)->first();

        return $ciudad;

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

    public function validacion_Agregar_Ciudad ()
    {

        return [

            'rut_empresa' => 'required|cl_rut|min:8|max:12',
            'nombre_ciudad' => 'required|max:255',
            'direccion' => 'required|max:255',

        ];

    }

    public function validacion_Actualizar_Ciudad ()
    {

        return [

            'rut_empresa' => 'cl_rut|min:8|max:12',
            'nombre_ciudad' => 'max:255',
            'direccion' => 'max:255',

        ];

    }

}
