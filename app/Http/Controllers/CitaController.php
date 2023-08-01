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
}