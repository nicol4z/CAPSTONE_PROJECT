<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Empleado_Documentos;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class EmpleadoDocumentosController extends Controller
{

    public function guardar_Documentos (Request $request){

        $fecha_actual = Carbon::now()->format('Y/m/d');

        foreach ($request->documentos as $file){

            $path = Storage::putFile('documentos_empleados', $file);

            $nombre_original_documento = $file->getClientOriginalName();

            $nombre_Documento = $file->hashName();

            $documento = new Empleado_Documentos();



            $rut = Empleado::find($request->rut_empleado);

            $documento->fecha_subida = $fecha_actual;

            $documento->path_documento = $path;

            $documento->nombre_original_documento = $nombre_original_documento;

            $documento->nombre_documento = $nombre_Documento;

            $rut->documentos()->save($documento);

        }

    }

    public function descargar_Documento ($rut_empleado, $nombre_original_documento){

        $documento = Empleado_Documentos::where([
                                                ['rut_empleado', $rut_empleado],
                                                ['nombre_original_documento', $nombre_original_documento],
                                                ])->first();

        return Storage::download('documentos_empleados/'.$documento->nombre_documento);

    }

    public function eliminar_Documento ($rut_empleado, $nombre_original_documento, $nombre_documento){

        $documento = Empleado_Documentos::where([
                                                ['rut_empleado', $rut_empleado],
                                                ['nombre_documento', $nombre_documento],
                                                ])->first();

        Storage::delete('documentos_empleados/'.$documento->nombre_documento);

        $documento->delete();

        return true;

    }

}
