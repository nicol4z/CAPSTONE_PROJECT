<?php

namespace App\Http\Controllers;

use App\Models\Solicitante_Encuesta;

use Illuminate\Http\Request;

class SolicitanteEncuestaController extends Controller
{
    public function crear_Relacion_Solicitante_Encuesta ($rut_solicitante, $id_encuesta)
    {

        $solicitante_encuesta = new Solicitante_Encuesta();

        $solicitante_encuesta->rut_solicitante = $rut_solicitante;

        $solicitante_encuesta->id_encuesta = $id_encuesta;

        $solicitante_encuesta->save();

    }

}
