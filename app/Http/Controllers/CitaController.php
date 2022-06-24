<?php

namespace App\Http\Controllers;

use DataTables;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Facades\Excel;

use App\Imports\CitasImport;

use App\Models\Cobranza;

use App\Http\Requests\imporCitasRequest;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $citasEstudios = DB::table('cobranza')
                                    ->join('estudios','estudios.id','=','cobranza.id_estudio_fk')
                                    ->select('cobranza.folio'
                                            ,'cobranza.paciente'
                                            ,'estudios.dscrpMedicosPro'
                                            ,'cobranza.fecha'
                                            ,'cobranza.statusCita')
                                    ->get();

        return view('citas.import-citas',compact('citasEstudios'));
    }

    public function importExcel(imporCitasRequest $request){
        if($request->hasFile('file')){
            $file = $request->file('file');
            
            try {
                Excel::import(new CitasImport, $file);
                $this->checkCitas();
                $citasEstudios = DB::table('cobranza')
                                    ->join('estudios','estudios.id','=','cobranza.id_estudio_fk')
                                    ->select('cobranza.folio'
                                            ,'cobranza.paciente'
                                            ,'estudios.dscrpMedicosPro'
                                            ,'cobranza.fecha'
                                            ,'cobranza.statusCita')
                                    ->get();
            } catch (\Illuminate\Database\QueryException $e) {
                return "Folios duplicados";
            }
            
            return view('citas.import-citas',compact('citasEstudios'));
        }
        return "No ha adjuntado ningun archivo";
    }

    public function checkCitas(){
        $cobranza = DB::table('cobranza')->select('paciente','fecha')->get();

        foreach($cobranza as $cobro){
            $fechaEstudio = date('d/M/Y',strtotime($cobro->fecha));
            $citas = DB::table('citas_temps')
                        ->where([
                            ['paciente','=',$cobro->paciente],
                            ['fechaCita','=',$fechaEstudio]
                        ])->count();

            if($citas > 0){
                $slctCitas = DB::table('citas_temps')
                                ->select('statusCita','id')
                                ->where([
                                    ['paciente','=',$cobro->paciente],
                                    ['fechaCita','=',$fechaEstudio]
                                ])->first();

                $fechaEstudio = date('Y-m-d',strtotime($cobro->fecha));

                $updtCitas = DB::table('cobranza')
                                ->where([
                                    ['paciente','=',$cobro->paciente],
                                    ['fecha','=',$fechaEstudio]
                                ])
                                ->update(['statusCita' => $slctCitas->statusCita]);

                $dltCita = DB::table('citas_temps')->where('id','=',$slctCitas->id)->delete();
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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