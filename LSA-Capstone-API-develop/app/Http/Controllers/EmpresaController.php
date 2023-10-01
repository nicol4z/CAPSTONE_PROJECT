<?php

namespace App\Http\Controllers;

use App\Models\Empresa;

use Illuminate\Http\Request;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\CiudadController;

class EmpresaController extends Controller
{

    public function empresas ()
    {

        $empresas = Empresa::all();

        return $empresas;

    }

    public function detalles_Empresa ($rut_empresa)
    {

        $empresa = Empresa::find($rut_empresa);

        if($empresa){

            $ciudades = $empresa->ciudades;

            return $empresa;

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function crear_Empresa (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Agregar_Empresa());

        if(!is_array($validacion)){

            Empresa::create($request->all());

            $this->crear_Ciudades($request);

            return response()->json('success', 200);

        }else{

            return response()->json($validacion);

        }

    }

    public function actualizar_Empresa (Request $request)
    {

        $validacion = $this->validacion($request->all(), $this->validacion_Actualizar_Empresa());

        $empresa = Empresa::find($request->rut_empresa);

        if(!is_array($validacion)){

            if ($empresa){

                $empresa->update($request->all());

                $this->actualizar_Ciudades($request);

                return response()->json('success', 200);

            }else{

                return response()->json(['error' => 'bad request'], 400);

            }

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }

    public function eliminar_Empresa ($rut_empresa)
    {

        $empresa = Empresa::find($rut_empresa);

        if ($empresa){

            (new CiudadController)->eliminar_Ciudades($rut_empresa);

            $empresa->delete();

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }
    public function existe_Correo (Request $request)
    {

        $existe_empresa = Empresa::where('correo',$request->correo)->first();

        if ($existe_empresa != null)
        {

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);

        }

    }
    
    public function existe_Empresa (Request $request)
    {

        $rut_empresa = $request->rut_empresa;

        $rut_sin_puntos_ni_guion = Rut::parse($rut_empresa)->normalize();

        $existe_empresa = Empresa::where('rut_empresa',$rut_empresa)->first();

        if ( ($rut_empresa == $rut_sin_puntos_ni_guion) && ($existe_empresa != null) ){

            return response()->json('success', 200);

        }else{

            return response()->json(['error' => 'bad request'], 400);
        }

    }

    public function crear_Ciudades (Request $request)
    {

        $ciudades_creadas = (new CiudadController)->crear_Ciudades($request);

        return $ciudades_creadas;

    }

    public function actualizar_Ciudades (Request $request)
    {

        (new CiudadController)->actualizar_Ciudades($request);

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

    public function validacion_Agregar_Empresa ()
    {

        return [

            'rut_empresa' => 'bail|required|unique:Empresa|cl_rut|min:8|max:12',
            'nombre_empresa' => 'required|min:3|max:255',
            'nombre_abreviado' => 'required|min:3|max:255',
            'correo' => 'required|email|max:255',
            'razon_social' => 'required|max:255',
            'giro' => 'required|max:255',

        ];

    }

    public function validacion_Actualizar_Empresa ()
    {

        return [

            'rut_empresa' => 'unique:Empresa|cl_rut|min:8|max:12',
            'nombre_empresa' => 'min:3|max:255',
            'nombre_abreviado' => 'min:3|max:255',
            'correo' => 'email|max:255',
            'razon_social' => 'max:255',
            'giro' => 'max:255',

        ];

    }

}
