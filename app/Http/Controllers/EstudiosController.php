<?php

namespace App\Http\Controllers;

use DataTables;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ReportesImport;

use App\Models\Estudiostemp;
use App\Models\Doctor;
use App\Models\TipoPaciente;
use App\Models\Empleado;
use App\Models\Estudios;
use App\Models\Cobranza;

use App\Http\Requests\ImportCobranzaRequest;

class EstudiosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('estudios.import-cobranza');

        $estudios = Estudios::join('cat_estudios','cat_estudios.id','=','id_estudio_fk')
                            ->join('tipo_ojos','tipo_ojos.id','=','id_ojo_fk')
                            ->select('estudios.id','descripcion','nombretipo_ojo')
                            ->orderBy('estudios.id','ASC')
                            ->get();
        
        return view('estudios.cobranzaTbl',compact('estudios'));
    }

    public function verTabla(){
        $estudios = Estudios::join('cat_estudios','cat_estudios.id','=','id_estudio_fk')
                            ->join('tipo_ojos','tipo_ojos.id','=','id_ojo_fk')
                            ->select('estudios.id','descripcion','nombretipo_ojo')
                            ->orderBy('estudios.id','ASC')
                            ->get();
        
        return view('estudios.cobranzaTbl',compact('estudios'));
    }

    public function showData(Request $request){
        $cobranza = Cobranza::join('estudios','estudios.id','=','id_estudio_fk')
                            ->join('cat_estudios','cat_estudios.id','=','estudios.id')
                            ->join('doctors','doctors.id','=','id_doctor_fk')
                            ->select('cobranza.id','doctor_nombre','doctor_apellidop','descripcion','transcripcion','interpretado','interpretacion')
                            ->orderBy('cobranza.fecha','ASC')
                            ->get();
        
        dd($cobranza);
        //return view('estudios.cobranzaTbl',compact('cobranza'));
    }

    public function importExcel(ImportCobranzaRequest $request){
        if($request->hasFile('file')){
            $file = $request->file('file');
            Excel::import(new ReportesImport, $file);
            return redirect()->route('importarCobranza.index');
        }
        return "No ha adjuntado ningun archivo";
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        //$estudioCobranza = Estudiostemp::all();
        //return $estudioCobranza;
        return datatables()
                ->eloquent(Estudiostemp::where('estudiostemps_status',0))
                ->addColumn('btn','estudios.btnCobranza-ver')
                ->addColumn('on-off','estudios.btnCobranza-status')
                ->rawColumns(['btn','on-off'])
                ->toJson();
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
    public function show($id){
        $datosPaciente = Estudiostemp::find($id);
        $doctores = Doctor::all();
        $tipoPac = TipoPaciente::all();
        $empTrans = Empleado::join('puestos','puestos.id','=','puesto_id')
                              ->select('id','empleado_nombre','empleado_apellidop','empleado_apellidom')
                              ->where('puestos.actividad','=','TRANSCRIBE')
                              ->get();
        $doctorInter = Doctor::all();

        return view('estudios.cobranza-paciente',compact('datosPaciente','doctores','tipoPac','empTrans','doctorInter'));
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
    public function update(Request $request){
        //dd($request);
        $validator = Validator::make($request->all(),[
            'registroC' => 'required'
        ],[
            'registroC.required' => 'Selecciona si el registro ya está completo.'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }else{
            if($request['registroC']=='S'){
                $estUpd = Estudios::where('dscrpMedicosPro',$request['estudioCbr'])->first();

                $fechaInsert = now()->toDateString();
                DB::table('cobranza')->insert([
                    'id_estudio_fk' => $estUpd->id,
                    'id_doctor_fk' => $request["drRequiere"],
                    'id_empTrans_fk' => $request["drTransc"],
                    'id_empInt_fk' => $request["drInterpreta"],
                    'folio' => $request['folioCbr'],
                    'fecha' => $request['fchCbr'],
                    'paciente' => $request['pacienteCbr'],
                    'tipoPaciente' => $request['tipoPaciente'],
                    'formaPago' => $request['formaPago'],
                    'transcripcion' => $request['transRd'],
                    'interpretacion' => $request['intRd'],
                    'escaneado' => $request['escRd'],
                    'cantidadCbr' => $request['cantidadCbr'],
                    'observaciones' => $request['obsCobranza'],
                    'created_at' => $fechaInsert,
                    'updated_at' => $fechaInsert
                ]);

                $updateStatusC = Estudiostemp::where('folio',$request['folioCbr'])
                                            ->update(
                                                ['estudiostemps_status' => 1]
                                            );
            }else{
                $updateStatusC = Estudiostemp::where('folio',$request['folioCbr'])
                                            ->update([
                                                'id_empTrans_fk' => $request["drTransc"],                                                
                                                'id_doctor_fk' => $request["drRequiere"],
                                                'id_empInt_fk' => $request["drInterpreta"],
                                                'tipoPaciente' => $request['tipoPaciente'],
                                                'transcripcion' => $request['transRd'],
                                                'interpretacion' => $request['intRd'],
                                                'escaneado' => $request['escRd'],
                                                'observaciones' => $request['observaciones']
                                            ]);
            }
        }

        return redirect()->route('importarCobranza.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $dataCobranza = Estudiostemp::where('estudiostemps_status',1)->delete();
        return redirect()->route('importarCobranza.index');
    }
}