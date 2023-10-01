<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\Cotizacion;

class CotizacionController extends Controller
{
    public function agregar_Cotizacion (Request $request){

        $fecha_actual = Carbon::now()->format('Y/m/d');

        $validacion = $this->validacion($request->all(), $this->validar_Fecha_De_Emision());

        $fecha_emision = Carbon::createFromFormat('d/m/Y', $request->fecha_emision)->format('Y/m/d');

        $documento_cotizacion = $request->documento_cotizacion;

        if(!is_array($validacion)){

            $nombre_original_documento = $documento_cotizacion->getClientOriginalName();

            $nombre_documento = $documento_cotizacion->hashName();

            if ($this->existe_Cotizacion($request->rut_solicitante, $nombre_original_documento) != true){

                $path = Storage::putFile('cotizaciones_solicitantes', $documento_cotizacion);

                $cotizacion = new Cotizacion();

                $cotizacion->numero_cotizacion = $request->numero_cotizacion;

                $cotizacion->fecha_ingreso = $fecha_actual;

                $cotizacion->fecha_emision = $fecha_emision;

                $cotizacion->nombre_original_documento = $nombre_original_documento;

                $cotizacion->nombre_documento = $nombre_documento;

                $cotizacion->path_documento = $path;

                $cotizacion->rut_solicitante = $request->rut_solicitante;

                $cotizacion->save();

                return true;

            }else{

                return false;

            }

        }else{

            return false;

        }

    }

    public function descargar_Cotizacion($rut_solicitante, $nombre_original_documento){

        $cotizacion = Cotizacion::where([
                                        ['rut_solicitante', $rut_solicitante],
                                        ['nombre_original_documento', $nombre_original_documento],
                                        ])->first();

        return Storage::download('cotizaciones_solicitantes/'.$cotizacion->nombre_documento);

    }

    public function eliminar_Cotizacion ($rut_solicitante, $nombre_original_documento){

        $cotizacion = Cotizacion::where([
                                        ['rut_solicitante', $rut_solicitante],
                                        ['nombre_original_documento', $nombre_original_documento],
                                        ])->first();

        Storage::delete('cotizaciones_solicitantes/'.$cotizacion->nombre_documento);

        $cotizacion->delete();

        return true;

    }

    public function existe_Cotizacion ($rut_solicitante, $nombre_original_documento){

        $existe = Cotizacion::where([
                                    ['rut_solicitante', $rut_solicitante],
                                    ['nombre_original_documento', $nombre_original_documento],
                                    ])->get();

        if (!$existe->isEmpty()){

            return true;

        }else{

            return false;
        }

    }

    public function validacion ($parametros, $tipo_validacion)
    {

        $respuesta = [];

        $validacion = Validator::make($parametros, $tipo_validacion);

        if ($validacion->fails()){

            array_push($respuesta, ['status' => 'error']);

            array_push($respuesta, ['errors' => $validacion->errors()]);

            return $respuesta;
        }
        else{

            return true;
        }

    }

    public function validar_Fecha_De_Emision ()
    {

        return [

            'fecha_emision' => 'date_format:d/m/Y',

        ];
    }

}
