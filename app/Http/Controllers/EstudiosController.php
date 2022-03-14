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
                ->eloquent(Estudiostemp::query())
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
        $validator = Validator::make($request->all(),[
            'drRequiere' => 'required',
            'tipoPaciente' => 'required',
            'transRd' => 'required',
            'intRd' => 'required',
            'escRd' => 'required'
        ]);

        if($request['transRd'] == 'on'){
            $transcripcion = 'S';
        }else{
            $transcripcion = 'N';
        }

        if($request['intRd'] == 'on'){
            $interpretacion = 'S';
        }else{
            $interpretacion = 'N';
        }

        if($request['escRd'] == 'on'){
            $escaneado = 'S';
        }else{
            $escaneado = 'N';
        }

        if($validator->fails()){
            $updateDataC = Estudiostemp::where('folio',$request['folioCbr'])
                                         ->update(
                                            ['id_doctor_fk' => $request['drRequiere']],
                                            ['tipoPaciente' => $request['tipoPaciente']],
                                            ['transcripcion' => $request['transRd']],
                                            ['interpretacion' => $request['intRd']],
                                            ['escaneado' => $request['escRd']],
                                            ['observaciones' => $request['obsCobranza']]
                                         );
        }else{
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
                'transcripcion' => $transcripcion,
                'interpretacion' => $interpretacion,
                'escaneado' => $escaneado,
                'cantidadCbr' => $request['cantidadCbr'],
                'observaciones' => $request['obsCobranza'],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert
            ]);

            $updateStatusC = Estudiostemp::where('folio',$request['folioCbr'])
                                           ->update(
                                              ['estudiostemps_status' => 1]
                                           );
        }
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