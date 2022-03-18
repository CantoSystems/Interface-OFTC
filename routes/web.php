<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\EstudiosController;
use App\Http\Controllers\DetalleCController;

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

//Importar Excel Cobranza
Route::get('/importar-cobranza',[EstudiosController::class,'index'])->name('importarCobranza.index');
Route::get('/reportes-cobranza',[EstudiosController::class,'verTabla'])->name('importarCobranza.verTabla');
Route::get('/reportes-cobranza-info',[EstudiosController::class,'showData'])->name('importarCobranza.showData');
Route::post('/importar-cobranza-excel',[EstudiosController::class,'importExcel'])->name('importarCobranza.import');
Route::get('/mostrar-data-cobranza',[EstudiosController::class,'create'])->name('importarCobranza.create');
Route::get('/mostrar-data-cobranza/{id}',[EstudiosController::class,'show'])->name('importarCobranza.show');
Route::delete('/eliminar-data-cobranza',[EstudiosController::class,'destroy'])->name('importarCobranza.destroy');
Route::get('/actualizar-data-cobranza',[EstudiosController::class,'update'])->name('importarCobranza.update');

//Importar Excel Detalle de Consumo
Route::get('/subir-detalle-consumo',[DetalleCController::class,'index'])->name('subirarchivoD.index');
Route::post('/import-detalle-excel',[DetalleCController::class,'importExcel'])->name('subirDetalle.import');
Route::get('/extraer-data-consumo',[EstudiosController::class,'create'])->name('extraerDetalle.create');