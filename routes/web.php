<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\EstudiosController;

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

//Importar Excel
Route::get('/subir-archivo',[EstudiosController::class,'index'])->name('subirEstudio.index');
Route::post('/import-list-excel',[EstudiosController::class,'importExcel'])->name('subirReporte.import');