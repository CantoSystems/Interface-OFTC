<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\EstudiosController;
use App\Http\Controllers\DetalleCController;
use App\Http\Controllers\CobranzaController;
use App\Http\Controllers\ComisionesController;
use App\Http\Controllers\EmpleadoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::get('/inicio',[PrincipalController::class,'index'])->name('index')->middleware('auth');

//Rutas Estudios Temporales
Route::get('/importar-cobranza',[EstudiosController::class,'index'])->name('importarCobranza.index')->middleware('auth');
Route::get('/mostrar-data-cobranza',[EstudiosController::class,'create'])->name('importarCobranza.create')->middleware('auth');
Route::get('/mostrar-data-cobranza/{id}',[EstudiosController::class,'show'])->name('importarCobranza.show')->middleware('auth');
Route::delete('/eliminar-data-cobranza',[EstudiosController::class,'destroy'])->name('importarCobranza.destroy')->middleware('auth');

//Reportes Cobranza
Route::post('/actualizar-data-cobranza',[CobranzaController::class,'store'])->name('importarCobranza.update')->middleware('auth');
Route::get('/reportes-cobranza',[CobranzaController::class,'show'])->name('importarCobranza.verTabla')->middleware('auth');
Route::get('/reportes-cobranza-info',[CobranzaController::class,'showCobranza'])->name('importarCobranza.showData')->middleware('auth');

//Citas
Route::get('/subir-citas',[CitaController::class,'index'])->name('importarCitas.index')->middleware('auth');

//Excel
Route::post('/importar-cobranza-excel',[EstudiosController::class,'importExcel'])->name('importarCobranza.import')->middleware('auth');
Route::post('/importar-citas-excel',[CitaController::class,'importExcel'])->name('importarCitas.import')->middleware('auth');
Route::post('/exportar-cobranza-excel',[CobranzaController::class,'exportExcel'])->name('importarCobranza.export')->middleware('auth');
Route::get('/exportar-comisiones-excel',[ComisionesController::class,'exportExcel'])->name('exportarComisiones.export')->middleware('auth');

//Importar Excel Detalle de Consumo
Route::get('/subir-detalle-consumo',[DetalleCController::class,'index'])->name('subirarchivoD.index')->middleware('auth');
Route::post('/import-detalle-excel',[DetalleCController::class,'importExcel'])->name('subirDetalle.import')->middleware('auth');
Route::get('/extraer-data-consumo',[DetalleCController::class,'show'])->name('extraerDetalle.show')->middleware('auth');
Route::get('/guardar-info-consumo',[DetalleCController::class,'create'])->name('guardarDetalle.create')->middleware('auth');
Route::get('/historico-hojas-consumo',[DetalleCController::class,'viewHojas'])->name('viewHojas.show')->middleware('auth');
Route::get('/ver-hojas-consumo',[DetalleCController::class,'mostrarHojas'])->name('mostrarHojas.show')->middleware('auth');
Route::get('/editar-hoja-consumo/{id}',[DetalleCController::class,'editHojaConsumo'])->name('editHojaConsumo.edit')->middleware('auth');
Route::post('/guardar-hoja-consumo',[DetalleCController::class,'updtHoja'])->name('updtHoja.edit')->middleware('auth');
Route::delete('/eliminar-hoja-estudio',[DetalleCController::class,'deleteHoja'])->name('deleteHoja.destroy')->middleware('auth');
Route::get('/exportar-hoja-consumo/{id}',[DetalleCController::class,'exportarPDF'])->name('exportPDF.create')->middleware('auth');

//Usuarios
Route::get('/usuarios',[UserController::class,'index'])->name('usuarios.index');
Route::post('/store-usuarios',[UserController::class,'store'])->name('usuarios.store');
Route::post('/login',[UserController::class,'create'])->name('usuarios.login');
Route::post('/logout',[UserController::class,'show'])->name('usuarios.logout');

//Calculo de Comisiones
Route::get('/comisiones',[ComisionesController::class,'showComisiones'])->name('comisiones.index')->middleware('auth');
Route::get('/generar-comisiones',[ComisionesController::class,'calcularComision'])->name('comisiones.show')->middleware('auth');

//Catálogo Estudios inviduales
Route::get('/catalogo-estudios',[EstudiosController::class,'showCatalogo'])->name('mostrarCatalogo.show')->middleware('auth');
Route::get('/editar-estudio/{id}',[EstudiosController::class,'mostrarEstudio'])->name('editCatalogo.update')->middleware('auth');
Route::post('/guardar-estudio',[EstudiosController::class,'updateEstudio'])->name('updateEstudio.update')->middleware('auth');
Route::post('/agregar-estudio',[EstudiosController::class,'nvoEstudio'])->name('nvoEstudio.create')->middleware('auth');
Route::delete('/eliminar-estudio',[EstudiosController::class,'deleteEstudio'])->name('dltEstudio.destroy')->middleware('auth');

//Catálogo Estudios Generales
Route::get('/catalogo-estudios-generales',[EstudiosController::class,'showEstudiosGrales'])->name('mostrarCatalogoGral.show')->middleware('auth');
Route::post('/agregar-estudio-general',[EstudiosController::class,'nvoEstudioGral'])->name('nvoEstudioGral.create')->middleware('auth');
Route::get('/editar-estudio-general/{id}',[EstudiosController::class,'mostrarEstudioGral'])->name('editCatalogoGral.update')->middleware('auth');
Route::post('/guardar-estudio-general',[EstudiosController::class,'updateEstudioGral'])->name('updateEstudioGral.update')->middleware('auth');
Route::delete('/eliminar-estudio-general',[EstudiosController::class,'deleteEstudioGral'])->name('dltEstudioGral.destroy')->middleware('auth');

//Catálogo Comisiones
Route::get('/catalogo-comisiones',[ComisionesController::class,'index'])->name('mostrarComisiones.index')->middleware('auth');
Route::post('/agregar-comision',[ComisionesController::class,'create'])->name('nvaComision.create')->middleware('auth');
Route::post('/guardar-comision',[ComisionesController::class,'update'])->name('updtComision.update')->middleware('auth');
Route::get('/editar-comision/{id}',[ComisionesController::class,'show'])->name('editComision.show')->middleware('auth');
Route::delete('/eliminar-comision',[ComisionesController::class,'destroy'])->name('dltComision.delete')->middleware('auth');

//Catalogo Empleados
Route::get('/catalogo-empleados',[EmpleadoController::class,'index'])->name('mostrarEmpleados.index')->middleware('auth');
Route::post('/agregar-empleado',[EmpleadoController::class,'create'])->name('nvoEmpleado.create')->middleware('auth');
Route::post('/guardar-empleado',[EmpleadoController::class,'update'])->name('updtEmpleado.update')->middleware('auth');
Route::get('/editar-empleado/{id}',[EmpleadoController::class,'show'])->name('updtEmpleado.show')->middleware('auth');
Route::delete('/eliminar-empleado',[EmpleadoController::class,'destroy'])->name('dltEmpleado.destroy')->middleware('auth');

//Catálogo Porcentajes
Route::get('/catalogo-porcentajes',[DetalleCController::class,'showPorcentajes'])->name('mostrarPorcentajes.show')->middleware('auth');
Route::post('/agregar-porcentaje',[DetalleCController::class,'createPorcentaje'])->name('nvoPorcentaje.create')->middleware('auth');
Route::post('/guardar-porcentaje',[DetalleCController::class,'updtPorcentaje'])->name('updtPorcentaje.update')->middleware('auth');
Route::get('/editar-porcentaje/{id}',[DetalleCController::class,'showPorcentaje'])->name('updtPorcentaje.show')->middleware('auth');
Route::delete('/eliminar-porcentaje',[DetalleCController::class,'deletePorcentaje'])->name('dltPorcentaje.destroy')->middleware('auth');