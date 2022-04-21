<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitaController;
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
});

Route::get('/inicio',[PrincipalController::class,'index'])->name('index');

//Rutas Estudios Temporales
Route::get('/importar-cobranza',[EstudiosController::class,'index'])->name('importarCobranza.index');


Route::get('/mostrar-data-cobranza',[EstudiosController::class,'create'])->name('importarCobranza.create');
Route::get('/mostrar-data-cobranza/{id}',[EstudiosController::class,'show'])->name('importarCobranza.show');
Route::delete('/eliminar-data-cobranza',[EstudiosController::class,'destroy'])->name('importarCobranza.destroy');

//Reportes Cobranza
Route::post('/actualizar-data-cobranza',[CobranzaController::class,'store'])->name('importarCobranza.update');
Route::get('/reportes-cobranza',[CobranzaController::class,'show'])->name('importarCobranza.verTabla');
Route::get('/reportes-cobranza-info',[CobranzaController::class,'showCobranza'])->name('importarCobranza.showData');

//Citas
Route::get('/importar-citas',[CitaController::class,'index'])->name('importarCitas.index');

//Excel
Route::post('/importar-cobranza-excel',[EstudiosController::class,'importExcel'])->name('importarCobranza.import');
Route::post('/importar-citas-excel',[EstudiosController::class,'importExcelCitas'])->name('importarCitas.import');
Route::post('/exportar-cobranza-excel',[CobranzaController::class,'exportExcel'])->name('importarCobranza.export');

//Importar Excel Detalle de Consumo
Route::get('/subir-detalle-consumo',[DetalleCController::class,'index'])->name('subirarchivoD.index');
Route::post('/import-detalle-excel',[DetalleCController::class,'importExcel'])->name('subirDetalle.import');
Route::get('/extraer-data-consumo',[DetalleCController::class,'show'])->name('extraerDetalle.show');
Route::get('/guardar-info-consumo',[DetalleCController::class,'create'])->name('guardarDetalle.create');
Route::get('/ver-hojas-consumo',[DetalleCController::class,'mostrarHojas'])->name('mostrarHojas.show');