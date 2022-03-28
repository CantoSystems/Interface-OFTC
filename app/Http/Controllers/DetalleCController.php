<?php

namespace App\Http\Controllers;

use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DetalleCImport;

use App\Models\DetalleTemp;
use App\Models\Doctor;
use App\Mail\MessageReceived;

class DetalleCController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $doctores = Doctor::where('id','<>','1')
                            ->get();

        return view('detalleC.subirarchivoD', compact('doctores'));
    }

    public function importExcel(Request $request){
        if($request->hasFile('file')){
            $file = $request->file('file');
            Excel::import(new DetalleCImport, $file);
            return redirect()->route('subirarchivoD.index');
        }
        return "No ha adjuntado ningun archivo";
    }   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        return datatables()
                ->eloquent(DetalleTemp::where('codigo','!=','null'))
                ->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $prueba = 'Esta es una prueba';

        Mail::to('jpomprime@gmail.com')->queue(new MessageReceived($prueba));

        //return new MessageReceived($prueba);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}