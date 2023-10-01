<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\EmpresaController;

use App\Http\Controllers\CiudadController;

use App\Http\Controllers\RoleController;

use App\Http\Controllers\SolicitanteController;

use App\Http\Controllers\EmpleadoController;

use App\Http\Controllers\AdministrarDisponibilidadPersonalController;

use App\Http\Controllers\AdministrarMuestraJefeLaboratorioSupervisorController;

use App\Http\Controllers\AdministrarMuestraGerenteFinanzasController;

use App\Http\Controllers\AdministrarMuestraAnalistaYQuimicoController;

use App\Http\Controllers\AdministrarMuestraSolicitanteController;

use App\Http\Controllers\MuestraController;

use App\Http\Controllers\RecepcionIngresoMuestraController;

use App\Http\Controllers\MatrizController;

use App\Http\Controllers\ParametroController;

use App\Http\Controllers\MetodologiaController;

use App\Http\Controllers\VisualizarDatosController;

use App\Http\Controllers\NormaController;

use App\Http\Controllers\TablaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Rutas USU001

Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware'=>['checkIfBanned']], function () {

    Route::post('/login', [AuthController::class, 'login']);
});


Route::group(['middleware'=>['auth:sanctum','checkIfBanned']], function () {


//Rutas MUS003
Route::get('/usuario/detallesUsuario', [AuthController::class, 'detalles_Usuario']);

Route::get('/muestras-analista-quimico', [AdministrarMuestraAnalistaYQuimicoController::class, 'muestras']);

Route::get('/muestras-analista-quimico/detallesMuestra/{RUM}', [AdministrarMuestraAnalistaYQuimicoController::class, 'detalles_Muestra_Para_Analista_Y_Quimico']);

Route::get('/muestras-analista-quimico/observacionesMuestra/{RUM}', [AdministrarMuestraAnalistaYQuimicoController::class, 'observaciones_Muestra']);

Route::post('/muestras-analista-quimico/crearObservacionMuestra', [AdministrarMuestraAnalistaYQuimicoController::class, 'crear_Observacion_Analista_O_Quimico']);

Route::get('/muestras-analista-quimico/completarAnalisis/{RUM}', [AdministrarMuestraAnalistaYQuimicoController::class, 'marcar_Como_Entregado_Un_Analisis']);



Route::get('/muestras-jefe-laboratorio', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'muestras']);

Route::get('/muestras-jefe-laboratorio/empleados', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'empleados']);

Route::get('/muestras-jefe-laboratorio/parametros/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'parametros']);

Route::get('/muestras-jefe-laboratorio/submuestras/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'submuestras']);

Route::get('/muestras-jefe-laboratorio/resultadosAnalisis/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'resultados_Analisis']);

Route::get('/muestras-jefe-laboratorio/detallesMuestra/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'detalles_Muestra']);

Route::get('/muestras-jefe-laboratorio/observacionesMuestra/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'observaciones_Muestra']);

Route::get('/muestras-jefe-laboratorio/analistasDesignados/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'analistas_Designados']);

Route::get('/muestras-jefe-laboratorio/descargarInforme', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'descargar_Informe']);

Route::put('/muestras-jefe-laboratorio/ingresoMuestra', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'ingresar_Muestra']);

Route::put('/muestras-jefe-laboratorio/marcarAnalisisCompletado/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'marcar_Analisis_Como_Completado']);

Route::put('/muestras-jefe-laboratorio/modificarFechaDeEntrega', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'modificar_Fecha_Entrega']);

Route::put('/muestras-jefe-laboratorio/modificarObservacion', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'modificar_Observacion']);

Route::put('/muestras-jefe-laboratorio/modificar_Analistas', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'modificar_Analistas']);

Route::put('/muestras-jefe-laboratorio/rehacerAnalisis', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'rehacer_Analisis']);

Route::post('/muestras-jefe-laboratorio/crearObservacion', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'crear_Observacion']);

Route::post('/muestras-jefe-laboratorio/ingresarResultadosAnalisis', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'ingresar_Resultados_Analisis']);



Route::get('/muestras-supervisor', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'muestras']);

Route::get('/muestras-supervisor/empleados', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'empleados']);

Route::get('/muestras-supervisor/parametros/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'parametros']);

Route::get('/muestras-supervisor/submuestras/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'submuestras']);

Route::get('/muestras-supervisor/resultadosAnalisis/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'resultados_Analisis']);

Route::get('/muestras-supervisor/detallesMuestra/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'detalles_Muestra']);

Route::get('/muestras-supervisor/observacionesMuestra/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'observaciones_Muestra']);

Route::get('/muestras-supervisor/analistasDesignados/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'analistas_Designados']);

Route::get('/muestras-supervisor/descargarInforme', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'descargar_Informe']);

Route::put('/muestras-supervisor/ingresoMuestra', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'ingresar_Muestra']);

Route::put('/muestras-supervisor/marcarTareaComoCompletada/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'marcar_Tarea_Como_Completada']);

Route::put('/muestras-supervisor/marcarAnalisisCompletado/{RUM}', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'marcar_Analisis_Como_Completado']);

Route::put('/muestras-supervisor/modificarFechaDeEntrega', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'modificar_Fecha_Entrega']);

Route::put('/muestras-supervisor/modificarObservacion', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'modificar_Observacion']);

Route::put('/muestras-supervisor/modificar_Analistas', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'modificar_Analistas']);

Route::put('/muestras-supervisor/rehacerAnalisis', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'rehacer_Analisis']);

Route::post('/muestras-supervisor/crearObservacion', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'crear_Observacion']);

Route::post('/muestras-supervisor/ingresarResultadosAnalisis', [AdministrarMuestraJefeLaboratorioSupervisorController::class, 'ingresar_Resultados_Analisis']);


//Rutas MUS001

Route::get('/recepcion-muestra/empresa-ciudades-direcciones', [RecepcionIngresoMuestraController::class, 'empresas_Ciudades_Direcciones']);

Route::get('/recepcion-muestra/cotizaciones/{rut_empresa}', [RecepcionIngresoMuestraController::class, 'cotizaciones']);

Route::get('/recepcion-muestra/matrices', [RecepcionIngresoMuestraController::class, 'matrices']);

Route::get('/recepcion-muestra/normas', [RecepcionIngresoMuestraController::class, 'normas']);

Route::get('/recepcion-muestra/tablas/{id_norma}', [RecepcionIngresoMuestraController::class, 'tablas']);

Route::get('/recepcion-muestra/parametros', [RecepcionIngresoMuestraController::class, 'parametros_Metodologias']);

Route::post('/recepcion-muestra', [RecepcionIngresoMuestraController::class, 'recepcion_Muestra']);


//Rutas MUS002
Route::get('/muestras/{RUM}', [RecepcionIngresoMuestraController::class, 'obtener_Detalles_Para_Ingresar_Muestra']);

Route::put('/ingreso-muestra', [RecepcionIngresoMuestraController::class, 'ingresar_Muestra']);


//Rutas MUS006

Route::get('/muestras-gerente', [AdministrarMuestraGerenteFinanzasController::class, 'muestras']);

Route::get('/muestras-gerente/detallesMuestra/{RUM}', [AdministrarMuestraGerenteFinanzasController::class, 'detalles_Muestra_Para_Gerente']);



//Rutas ANA001

Route::get('/visualizarDatos', [VisualizarDatosController::class, 'visualizar_Datos']);

Route::get('/visualizarDatos/detalles/{id_matriz}', [VisualizarDatosController::class, 'detalles_Datos']);


//Rutas ANA002

Route::get('/matrices', [MatrizController::class, 'matrices']);

Route::get('/matrices-parametros', [MatrizController::class, 'matrices_Parametros']);

Route::get('/matrices/detallesMatriz/{id_matriz}', [MatrizController::class, 'detalles_Matriz']);

Route::post('/matrices/agregarMatriz', [MatrizController::class, 'crear_Matriz']);

Route::put('/matrices/actualizarMatriz', [MatrizController::class, 'actualizar_Matriz']);


//Rutas ANA003

Route::get('/parametros', [ParametroController::class, 'parametros']);

Route::get('/parametros/{id_parametro}', [ParametroController::class, 'detalles_Parametros']);

Route::post('/parametros/agregarParametro', [ParametroController::class, 'crear_Parametro']);

Route::put('/parametros/actualizarParametro', [ParametroController::class, 'actualizar_Parametro']);


//Rutas ANA004

Route::get('/metodologias', [MetodologiaController::class, 'metodologias']);

Route::get('/metodologias/{id_metodologia}', [MetodologiaController::class, 'detalles_Metodologia']);

Route::post('/metodologias/agregarMetodologia', [MetodologiaController::class, 'crear_Metodologia']);

Route::put('/metodologias/actualizarMetodologia', [MetodologiaController::class, 'actualizar_Metodologia']);


//Rutas ANA005

Route::get('/normas', [NormaController::class, 'normas']);

Route::get('/normas-matrices/{id_matriz}', [NormaController::class, 'normas_Matriz']);

Route::get('/normas/detallesNorma/{id_norma}', [NormaController::class, 'detalles_Normas']);

Route::post('/normas/agregarNorma', [NormaController::class, 'crear_Norma']);

Route::put('/normas/actualizarNorma', [NormaController::class, 'actualizar_Norma']);



//Rutas MUS007

Route::get('/muestras-administrador-finanzas', [AdministrarMuestraGerenteFinanzasController::class, 'muestras']);

Route::get('/muestras-administrador-finanzas/detallesMuestra/{RUM}', [AdministrarMuestraGerenteFinanzasController::class, 'detalles_Muestra_Para_Admnistrador_Finanzas']);

Route::get('/muestras-administrador-finanzas/observacionesMuestra/{RUM}', [AdministrarMuestraGerenteFinanzasController::class, 'observaciones_Muestra']);

Route::post('/muestras-administrador-finanzas/crearObservacionMuestra', [AdministrarMuestraGerenteFinanzasController::class, 'crear_Observacion_Administrador_Finanzas']);


//Rutas MUS005

Route::get('/muestras-solicitante', [AdministrarMuestraSolicitanteController::class, 'muestras']);

Route::get('/muestras-solicitante/detallesMuestra/{RUM}', [AdministrarMuestraSolicitanteController::class, 'detalles_muestra']);

Route::post('/muestras-solicitante/responderEncuesta', [AdministrarMuestraSolicitanteController::class, 'responder_Encuesta']);


    Route::post('/cambiarPassword', [AuthController::class, 'updatePassword']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/roles', [RoleController::class, 'roles']);

    Route::group(['middleware'=>['restrictRole:0']], function () {

        Route::get('/usuarios', [AuthController::class, 'usuarios']);
        Route::post('/cambiarPasswordAdmin', [AuthController::class, 'updatePasswordAdmin']);
        Route::post('/cambiarEstadoUsuario', [AuthController::class, 'updateStatus']);
        Route::post('/existeEmpleado', [AuthController::class, 'existe_Empleado']);
        Route::post('/syncUsuarios', [AuthController::class, 'syncUsers']);
    });


// Rutas USU002

Route::group(['middleware'=>['restrictRole:2,3,4,6,0,1']], function () {

Route::get('/empresas', [EmpresaController::class, 'empresas']);

Route::get('/empresas/{id}', [EmpresaController::class, 'detalles_Empresa']);

Route::post('/empresas/agregarEmpresa', [EmpresaController::class, 'crear_Empresa']);

Route::post('empresas/existeCorreo',[EmpresaController::class,'existe_Correo']);

Route::post('/empresas/existeEmpresa',[EmpresaController::class,'existe_Empresa']);

Route::put('/empresas/actualizarEmpresa/{rut_empresa}', [EmpresaController::class, 'actualizar_Empresa']);

Route::delete('/empresas/eliminarEmpresa/{rut_empresa}', [EmpresaController::class, 'eliminar_Empresa']);

Route::get('/ciudades', [CiudadController::class, 'ciudades']);

Route::post('/empresas/ciudades/agregarCiudades', [EmpresaController::class, 'crear_Ciudades']);

Route::delete('/empresas/ciudades/eliminarCiudad', [EmpresaController::class, 'eliminar_Ciudad']);


//Rutas USU003

Route::get('/solicitantes', [SolicitanteController::class, 'solicitantes']);

Route::get('/solicitantes/{rut_solicitante}', [SolicitanteController::class, 'detalles_Solicitante']);

Route::get('/solicitantes/detalles/{rut_solicitante}', [SolicitanteController::class, 'detalles_completo_Solicitante']);

Route::get('/solicitantes/obtenerCotizaciones/{rut_solicitante}', [SolicitanteController::class, 'obtener_Cotizaciones']);

Route::post('/solicitantes/agregarSolicitante', [SolicitanteController::class, 'crear_Solicitante']);

Route::post('/solicitantes/cambiarEstado',[SolicitanteController::class,'actualizar_Estado_Solicitante']);

Route::post('solicitantes/existeCorreo',[SolicitanteController::class,'existe_Correo']);

Route::post('solicitantes/existeSolicitante',[SolicitanteController::class,'existe_Solicitante']);

Route::post('/solicitantes/agregarCotizacion', [SolicitanteController::class, 'agregar_Cotizacion']);

Route::post('/solicitantes/descargarCotizacion', [SolicitanteController::class, 'descargar_Cotizacion']);

Route::put('/solicitantes/actualizarSolicitante/{rut_solicitante}', [SolicitanteController::class, 'actualizar_Solicitante']);

Route::delete('/solicitantes/eliminarSolicitante/{rut_solicitante}', [SolicitanteController::class, 'eliminar_Solicitante']);

Route::post('/solicitantes/eliminarCotizacion/{rut_solicitante}', [SolicitanteController::class, 'eliminar_Cotizacion']);


});


Route::group(['middleware'=>['restrictRole:1,2,3,4,6,0,7']], function () {

//Rutas USU004

Route::get('/empleados', [EmpleadoController::class, 'empleados']);

// TODO: Cambiar nombre o evaluar existencia debido a conflicto con empleados/areas y redundancia con detallesEmpleado/{id}
//Route::get('/empleados/{id}', [EmpleadoController::class, 'empleado'])

Route::get('/empleados/areas', [EmpleadoController::class, 'obtener_Areas']);

Route::get('/empleados/detallesEmpleado/{id}', [EmpleadoController::class, 'detalles_empleado']);

Route::post('/empleados/agregarEmpleado', [EmpleadoController::class, 'crear_Empleado']);

Route::post('empleados/editarEmpleado',[EmpleadoController::class,'actualizar_Empleado']);

Route::post('empleados/cambiarEstado',[EmpleadoController::class,'actualizar_Estado_Empleado']);

Route::post('empleados/existeEmpleado',[EmpleadoController::class,'existe_Empleado']);

Route::post('empleados/existeCorreo',[EmpleadoController::class,'existe_Correo']);

Route::post('/empleados/descargarDocumento', [EmpleadoController::class, 'descargar_Documento']);

Route::put('empleados/actualizarEmpleado/{id}', [EmpleadoController::class, 'actualizar_Empleado']);

Route::put('empleados/actualizarFechas/{id}', [EmpleadoController::class, 'actualizar_Fechas_Vacaciones']);

Route::delete('empleados/eliminarEmpleado/{id}', [EmpleadoController::class, 'eliminar_Empleado']);
Route::delete('empleados/eliminarDocumentoEmpleado', [EmpleadoController::class, 'eliminar_Documento_Empleado']);

Route::resource('empleados/documentos', App\Http\Controllers\EmpleadoDocumentosController::class);


});

Route::group(['middleware'=>['restrictRole:1,2,7,0,6']], function () {


//Rutas USU005

Route::get('/personal', [AdministrarDisponibilidadPersonalController::class, 'personal']);

Route::get('/personal/{rut_personal}', [AdministrarDisponibilidadPersonalController::class, 'detalles_Personal']);

Route::put('/personal/modificarFechasVacaciones/{rut_personal}', [AdministrarDisponibilidadPersonalController::class, 'modificar_Fechas_Vacaciones']);

Route::put('/personal/modificarDiasDisponibles/{rut_personal}', [AdministrarDisponibilidadPersonalController::class, 'modificar_Dias_Disponibles']);


});


});

Route::get('/recepcion-muestra/solicitantes', [RecepcionIngresoMuestraController::class, 'solicitantes']);

Route::get('/recepcion-muestra/empresas/{rut_solicitante}', [RecepcionIngresoMuestraController::class, 'empresas']);

Route::get('/recepcion-muestra/matrices', [RecepcionIngresoMuestraController::class, 'matrices']);

Route::get('/recepcion-muestra/normas', [RecepcionIngresoMuestraController::class, 'normas']);

Route::get('/recepcion-muestra/tablas/{id_norma}', [RecepcionIngresoMuestraController::class, 'tablas']);

Route::get('/recepcion-muestra/parametros', [RecepcionIngresoMuestraController::class, 'parametros_Metodologias']);

Route::post('/recepcion-muestra', [RecepcionIngresoMuestraController::class, 'recepcion_Muestra']);


//Rutas MUS002

Route::get('/ingreso-muestra/detallesMuestra/{RUM}', [RecepcionIngresoMuestraController::class, 'obtener_detalles_Para_Ingresar_Muestra']);

Route::put('/ingreso-muestra', [RecepcionIngresoMuestraController::class, 'ingresar_Muestra']);



//Rutas MUS004


//Rutas MUS006

Route::get('/muestras-gerente', [AdministrarMuestraGerenteFinanzasController::class, 'muestras_Para_Gerente']);

Route::get('/muestras-gerente/detallesMuestra/{RUM}', [AdministrarMuestraGerenteFinanzasController::class, 'detalles_Muestra_Para_Gerente']);

Route::get('/muestras-gerente/observacionesMuestra/{RUM}', [AdministrarMuestraGerenteFinanzasController::class, 'observaciones_Muestra']);


//Rutas MUS007

Route::get('/muestras-administrador-finanzas', [AdministrarMuestraGerenteFinanzasController::class, 'muestras_Para_Administrador_Finanzas']);

Route::get('/muestras-administrador-finanzas/detallesMuestra/{RUM}', [AdministrarMuestraGerenteFinanzasController::class, 'detalles_Muestra_Para_Admnistrador_Finanzas']);

Route::get('/muestras-administrador-finanzas/observacionesMuestra/{RUM}', [AdministrarMuestraGerenteFinanzasController::class, 'observaciones_Muestra']);

Route::post('/muestras-administrador-finanzas/crearObservacionMuestra', [AdministrarMuestraGerenteFinanzasController::class, 'crear_Observacion_Administrador_Finanzas']);

Route::post('/muestras-administrador-finanzas/agregarOrdenCompra', [AdministrarMuestraGerenteFinanzasController::class, 'agregar_Orden_De_Compra']);

Route::post('/muestras-administrador-finanzas/descargarOrdenCompra', [AdministrarMuestraGerenteFinanzasController::class, 'descargar_Orden_De_Compra']);

Route::post('/muestras-administrador-finanzas/eliminarOrdenCompra', [AdministrarMuestraGerenteFinanzasController::class, 'eliminar_Orden_De_Compra']);


//Rutas ANA001

Route::get('/visualizarDatos', [VisualizarDatosController::class, 'visualizar_Datos']);

Route::get('/visualizarDatos/detalles/{id_matriz}', [VisualizarDatosController::class, 'detalles_Datos']);


//Rutas ANA002

Route::get('/matrices', [MatrizController::class, 'matrices']);

Route::get('/matrices-parametros', [MatrizController::class, 'matrices_Parametros']);

Route::get('/matrices/detallesMatriz/{id_matriz}', [MatrizController::class, 'detalles_Matriz']);

Route::post('/matrices/agregarMatriz', [MatrizController::class, 'crear_Matriz']);

Route::put('/matrices/actualizarMatriz', [MatrizController::class, 'actualizar_Matriz']);


//Rutas ANA003

Route::get('/parametros', [ParametroController::class, 'parametros']);

Route::get('/parametros/{id_parametro}', [ParametroController::class, 'detalles_Parametros']);

Route::post('/parametros/agregarParametro', [ParametroController::class, 'crear_Parametro']);

Route::put('/parametros/actualizarParametro', [ParametroController::class, 'actualizar_Parametro']);


//Rutas ANA004

Route::get('/metodologias', [MetodologiaController::class, 'metodologias']);

Route::get('/metodologias/{id_metodologia}', [MetodologiaController::class, 'detalles_Metodologia']);

Route::post('/metodologias/agregarMetodologia', [MetodologiaController::class, 'crear_Metodologia']);

Route::put('/metodologias/actualizarMetodologia', [MetodologiaController::class, 'actualizar_Metodologia']);


//Rutas ANA005

Route::get('/normas', [NormaController::class, 'normas']);

Route::get('/normas-matrices/{id_matriz}', [NormaController::class, 'normas_Matriz']);

Route::get('/normas/detallesNorma/{id_norma}', [NormaController::class, 'detalles_Normas']);

Route::post('/normas/agregarNorma', [NormaController::class, 'crear_Norma']);

Route::put('/normas/actualizarNorma', [NormaController::class, 'actualizar_Norma']);
