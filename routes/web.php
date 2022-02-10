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
Route::get('/subir-archivo',[EstudiosController::class,'index'])->name('subirEstudio.index');
Route::post('/import-list-excel',[EstudiosController::class,'importExcel'])->name('subirReporte.import');
Route::get('/mostrar-data-cobranza',[EstudiosController::class,'create'])->name('mostarReporte.create');

//Importar Excel Detalle de Consumo
Route::get('/subir-detalle-consumo',[DetalleCController::class,'index'])->name('subirarchivoD.index');
Route::post('/import-detalle-excel',[DetalleCController::class,'importExcel'])->name('subirDetalle.import');