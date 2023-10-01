<?php

namespace App\Http\Controllers;

use App\Models\Empresa_Ciudad_Solicitante;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class EmpresaCiudadSolicitanteController extends Controller
{
    public function crear_Relacion_Empresa_Ciudad_Solicitante ($rut_solicitante, Request $request)
    {

        $empresas_ciudades = Arr::flatten($request->empresas_agregar);

        if ($empresas_ciudades != null){

            for ($i = 0, $largo = count($empresas_ciudades); $i < $largo; $i += 2){

                $empresa_ciudad_solicitante = new Empresa_Ciudad_Solicitante();

                $empresa_ciudad_solicitante->rut_empresa = $empresas_ciudades[$i];

                $empresa_ciudad_solicitante->id_ciudad = $empresas_ciudades[$i+1];

                $empresa_ciudad_solicitante->rut_solicitante = $rut_solicitante;

                $empresa_ciudad_solicitante->save();

            }

        }

    }

    public function eliminar_Relacion_Empresa_Ciudad_Solicitante (Request $request)
    {

        $empresas_ciudades = Arr::flatten($request->empresas_eliminar);

        if ($empresas_ciudades != null){

            for ($i = 0, $largo = count($empresas_ciudades); $i < $largo; $i += 2){

                $empresa_ciudad_solicitante_a_eliminar = Empresa_Ciudad_Solicitante::where([
                                                                                          ['rut_empresa', $empresas_ciudades[$i]],
                                                                                          ['id_ciudad', $empresas_ciudades[$i+1]],
                                                                                          ['rut_solicitante', $request->rut_solicitante]
                                                                                          ]);

                $empresa_ciudad_solicitante_a_eliminar->delete();

            }

        }
    }

}
