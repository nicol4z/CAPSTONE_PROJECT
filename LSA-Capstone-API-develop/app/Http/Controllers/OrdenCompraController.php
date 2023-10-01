<?php

namespace App\Http\Controllers;

use App\Models\Muestra;
use App\Models\Orden_Compra;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrdenCompraController extends Controller
{
    public function agregar_Orden_Compra (Request $request){

        $fecha_actual = Carbon::now()->format('Y/m/d');

        $fecha_emision = Carbon::createFromFormat('d/m/Y', $request->fecha_emision)->format('Y/m/d');

        $documento_orden_compra = $request->documento_orden_compra;

        if($documento_orden_compra != null){

            $nombre_original_documento = $documento_orden_compra->getClientOriginalName();

            $nombre_documento = $documento_orden_compra->hashName();

            $existe_Orden_Compra = $this->existe_Orden_Compra($request->RUM, $nombre_original_documento);

            if ($existe_Orden_Compra != true){

                $path = Storage::putFile('ordenes_de_compra', $documento_orden_compra);

                $orden_compra = new Orden_Compra();

                $orden_compra->fecha_ingreso = $fecha_actual;

                $orden_compra->fecha_emision = $fecha_emision;

                $orden_compra->nombre_original_documento = $nombre_original_documento;

                $orden_compra->nombre_documento = $nombre_documento;

                $orden_compra->path_documento = $path;

                $orden_compra->save();

                return $orden_compra;

            }

        }

    }

    public function actualizar_Relacion_Muestra_Orden_Compra ($RUM, $id_orden_compra)
    {

        $muestra = Muestra::find($RUM);

        if($muestra){

            $muestra->update(['id_orden_compra' => $id_orden_compra]);

        }

    }

    public function descargar_Orden_Compra($id_orden_compra){

        $orden_compra = Orden_Compra::find($id_orden_compra);

        if($orden_compra){

            return Storage::download('ordenes_de_compra/'.$orden_compra->nombre_documento);

        }else{

            return false;

        }

    }

    public function eliminar_Orden_Compra ($id_orden_compra){

        $orden_compra = Orden_Compra::find($id_orden_compra);

        if ($orden_compra){

            Storage::delete('ordenes_de_compra/'.$orden_compra->nombre_documento);

            $orden_compra->delete();

            return true;

        }else{

            return false;

        }

    }

    public function existe_Orden_Compra ($RUM, $nombre_original_documento){

        $existe = DB::table('orden_compra')
                    ->join('muestra', 'orden_compra.id_orden_compra', '=', 'muestra.id_orden_compra')
                    ->where([
                            ['muestra.RUM', '=', $RUM],
                            ['orden_compra.nombre_original_documento', '=', $nombre_original_documento]
                            ])
                    ->first();

        if ($existe != null){

            return true;

        }else{

            return false;
        }

    }

}
