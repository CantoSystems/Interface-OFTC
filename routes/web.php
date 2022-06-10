<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\EstudiosController;
use App\Http\Controllers\DetalleCController;
use App\Http\Controllers\CobranzaController;

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

//Importar Excel Detalle de Consumo
Route::get('/subir-detalle-consumo',[DetalleCController::class,'index'])->name('subirarchivoD.index')->middleware('auth');
Route::post('/import-detalle-excel',[DetalleCController::class,'importExcel'])->name('subirDetalle.import')->middleware('auth');
Route::get('/extraer-data-consumo',[DetalleCController::class,'show'])->name('extraerDetalle.show')->middleware('auth');
Route::get('/guardar-info-consumo',[DetalleCController::class,'create'])->name('guardarDetalle.create')->middleware('auth');
Route::get('/ver-hojas-consumo',[DetalleCController::class,'mostrarHojas'])->name('mostrarHojas.show')->middleware('auth');
Route::get('/exportar-hoja-consumo/{id}',[DetalleCController::class,'exportarPDF'])->name('exportPDF.create')->middleware('auth');

//Usuarios
Route::get('/usuarios',[UserController::class,'index'])->name('usuarios.index');
Route::post('/store-usuarios',[UserController::class,'store'])->name('usuarios.store');
Route::post('/login',[UserController::class,'create'])->name('usuarios.login');
Route::post('/logout',[UserController::class,'show'])->name('usuarios.logout');

//Catálogos (Estudios inviduales)
Route::get('/catalogo-estudios',[EstudiosController::class,'showCatalogo'])->name('mostrarCatalogo.show')->middleware('auth');
Route::get('/editar-estudio/{id}',[EstudiosController::class,'mostrarEstudio'])->name('editCatalogo.update')->middleware('auth');
Route::post('/guardar-estudio',[EstudiosController::class,'updateEstudio'])->name('updateEstudio.update')->middleware('auth');
Route::post('/agregar-estudio',[EstudiosController::class,'nvoEstudio'])->name('nvoEstudio.create')->middleware('auth');
Route::delete('/eliminar-estudio',[EstudiosController::class,'deleteEstudio'])->name('dltEstudio.destroy')->middleware('auth');

//Catálogos (Estudios Generales)
Route::get('/catalogo-estudios-generales',[EstudiosController::class,'showEstudiosGrales'])->name('mostrarCatalogoGral.show')->middleware('auth');
Route::post('/agregar-estudio-general',[EstudiosController::class,'nvoEstudioGral'])->name('nvoEstudioGral.create')->middleware('auth');
Route::get('/editar-estudio-general/{id}',[EstudiosController::class,'mostrarEstudioGral'])->name('editCatalogoGral.update')->middleware('auth');
Route::post('/guardar-estudio-general',[EstudiosController::class,'updateEstudioGral'])->name('updateEstudioGral.update')->middleware('auth');
Route::delete('/eliminar-estudio-general',[EstudiosController::class,'deleteEstudioGral'])->name('dltEstudioGral.destroy')->middleware('auth');