<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Empresa_Ciudad;

class EmpresaCiudadController extends Controller
{
    public function crear_Relacion_Empresa_Ciudad ($rut_empresa, $id_ciudad)
    {

        $empresa_ciudad = new Empresa_Ciudad();

        $empresa = Empresa::find($rut_empresa);

        $empresa_ciudad->rut_empresa = $rut_empresa;

        $empresa_ciudad->id_ciudad = $id_ciudad;

        $empresa->ciudades()->sync($empresa_ciudad, false);

    }
}
