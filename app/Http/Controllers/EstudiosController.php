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
use App\Imports\CitasImport;
use App\Exports\CobranzaExport;

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
    }

    public function indexC(){
        return view('estudios.import-citas');
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
        $cobranza = null;
        $estudios = Estudios::join('cat_estudios','cat_estudios.id','=','id_estudio_fk')
                            ->join('tipo_ojos','tipo_ojos.id','=','id_ojo_fk')
                            ->select('estudios.id','descripcion','nombretipo_ojo')
                            ->orderBy('estudios.id','ASC')
                            ->get();
        
        /*switch($request->statusSelect){
            case 'Escaneado':
                $cobranza = DB::table('cobranza')
                        ->join('estudios','estudios.id','=','cobranza.id_estudio_fk')
                        ->join('cat_estudios','cat_estudios.id','=','estudios.id_estudio_fk')
                        ->join('tipo_ojos','tipo_ojos.id','=','estudios.id_ojo_fk')
                        ->join('doctors','doctors.id','=','cobranza.id_doctor_fk')
                        ->select('cobranza.folio'
                                ,'cobranza.fecha'
                                ,'cobranza.paciente'
                                ,'cat_estudios.descripcion'
                                ,'tipo_ojos.nombretipo_ojo'
                                ,DB::raw("UPPER(CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop)) AS Doctor")
                                ,DB::raw('(CASE WHEN transcripcion = "S" THEN "SI" ELSE "NO" END) AS Transcripcion')
                                ,DB::raw('(CASE WHEN interpretacion = "S" THEN "SI" ELSE "NO" END) AS Interpretacion')
                                ,DB::raw('(CASE WHEN escaneado = "S" THEN "SI" ELSE "NO" END) AS Escaneado')
                                ,'cobranza.cantidadCbr')
                        ->where([
                            ['escaneado','=','S'],
                            ['cobranza.id_estudio_fk','=',$request->estudioSelect]
                        ])
                        ->orderBy('cobranza.fecha','ASC')
                        ->get();
                break;
            case 'Interpretado':
                $cobranza = DB::table('cobranza')
                        ->join('estudios','estudios.id','=','cobranza.id_estudio_fk')
                        ->join('cat_estudios','cat_estudios.id','=','estudios.id_estudio_fk')
                        ->join('tipo_ojos','tipo_ojos.id','=','estudios.id_ojo_fk')
                        ->join('doctors','doctors.id','=','cobranza.id_doctor_fk')
                        ->select('cobranza.folio'
                                ,'cobranza.fecha'
                                ,'cobranza.paciente'
                                ,'cat_estudios.descripcion'
                                ,'tipo_ojos.nombretipo_ojo'
                                ,DB::raw("UPPER(CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop)) AS Doctor")
                                ,DB::raw('(CASE WHEN transcripcion = "S" THEN "SI" ELSE "NO" END) AS Transcripcion')
                                ,DB::raw('(CASE WHEN interpretacion = "S" THEN "SI" ELSE "NO" END) AS Interpretacion')
                                ,DB::raw('(CASE WHEN escaneado = "S" THEN "SI" ELSE "NO" END) AS Escaneado')
                                ,'cobranza.cantidadCbr')
                        ->where([
                            ['interpretacion','=','S'],
                            ['cobranza.id_estudio_fk','=',$request->estudioSelect]
                        ])
                        ->orderBy('cobranza.fecha','ASC')
                        ->get();
                break;
            case 'Transcrito':
                $cobranza = DB::table('cobranza')
                        ->join('estudios','estudios.id','=','cobranza.id_estudio_fk')
                        ->join('cat_estudios','cat_estudios.id','=','estudios.id_estudio_fk')
                        ->join('tipo_ojos','tipo_ojos.id','=','estudios.id_ojo_fk')
                        ->join('doctors','doctors.id','=','cobranza.id_doctor_fk')
                        ->select('cobranza.folio'
                                ,'cobranza.fecha'
                                ,'cobranza.paciente'
                                ,'cat_estudios.descripcion'
                                ,'tipo_ojos.nombretipo_ojo'
                                ,DB::raw("UPPER(CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop)) AS Doctor")
                                ,DB::raw('(CASE WHEN transcripcion = "S" THEN "SI" ELSE "NO" END) AS Transcripcion')
                                ,DB::raw('(CASE WHEN interpretacion = "S" THEN "SI" ELSE "NO" END) AS Interpretacion')
                                ,DB::raw('(CASE WHEN escaneado = "S" THEN "SI" ELSE "NO" END) AS Escaneado')
                                ,'cobranza.cantidadCbr')
                        ->where([
                            ['transcripcion','=','S'],
                            ['cobranza.id_estudio_fk','=',$request->estudioSelect]
                        ])
                        ->orderBy('cobranza.fecha','ASC')
                        ->get();
                break;
            case 'Entregado':
                $cobranza = DB::table('cobranza')
                        ->join('estudios','estudios.id','=','cobranza.id_estudio_fk')
                        ->join('cat_estudios','cat_estudios.id','=','estudios.id_estudio_fk')
                        ->join('tipo_ojos','tipo_ojos.id','=','estudios.id_ojo_fk')
                        ->join('doctors','doctors.id','=','cobranza.id_doctor_fk')
                        ->select('cobranza.folio'
                                ,'cobranza.fecha'
                                ,'cobranza.paciente'
                                ,'cat_estudios.descripcion'
                                ,'tipo_ojos.nombretipo_ojo'
                                ,DB::raw("UPPER(CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop)) AS Doctor")
                                ,DB::raw('(CASE WHEN transcripcion = "S" THEN "SI" ELSE "NO" END) AS Transcripcion')
                                ,DB::raw('(CASE WHEN interpretacion = "S" THEN "SI" ELSE "NO" END) AS Interpretacion')
                                ,DB::raw('(CASE WHEN escaneado = "S" THEN "SI" ELSE "NO" END) AS Escaneado')
                                ,'cobranza.cantidadCbr')
                        ->where([
                            ['transcripcion','=','S'],
                            ['cobranza.id_estudio_fk','=',$request->estudioSelect]
                        ])
                        ->orderBy('cobranza.fecha','ASC')
                        ->get();
                break;
            case 'Todos':
                $cobranza = DB::table('cobranza')
                        ->join('estudios','estudios.id','=','cobranza.id_estudio_fk')
                        ->join('cat_estudios','cat_estudios.id','=','estudios.id_estudio_fk')
                        ->join('tipo_ojos','tipo_ojos.id','=','estudios.id_ojo_fk')
                        ->join('doctors','doctors.id','=','cobranza.id_doctor_fk')
                        ->select('cobranza.folio'
                                ,'cobranza.fecha'
                                ,'cobranza.paciente'
                                ,'cat_estudios.descripcion'
                                ,'tipo_ojos.nombretipo_ojo'
                                ,DB::raw("UPPER(CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop)) AS Doctor")
                                ,DB::raw('(CASE WHEN transcripcion = "S" THEN "SI" ELSE "NO" END) AS Transcripcion')
                                ,DB::raw('(CASE WHEN interpretacion = "S" THEN "SI" ELSE "NO" END) AS Interpretacion')
                                ,DB::raw('(CASE WHEN escaneado = "S" THEN "SI" ELSE "NO" END) AS Escaneado')
                                ,'cobranza.cantidadCbr')
                        ->where([
                            ['cobranza.id_estudio_fk','=',$request->estudioSelect]
                        ])
                        ->orderBy('cobranza.fecha','ASC')
                        ->get();
                break;
        }*/

        $cobranza = DB::table('cobranza')
                        ->join('estudios','estudios.id','=','cobranza.id_estudio_fk')
                        ->join('cat_estudios','cat_estudios.id','=','estudios.id_estudio_fk')
                        ->join('tipo_ojos','tipo_ojos.id','=','estudios.id_ojo_fk')
                        ->join('doctors','doctors.id','=','cobranza.id_doctor_fk')
                        ->select('cobranza.folio'
                                ,'cobranza.fecha'
                                ,'cobranza.paciente'
                                ,'cat_estudios.descripcion'
                                ,'tipo_ojos.nombretipo_ojo'
                                ,DB::raw("UPPER(CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop)) AS Doctor")
                                ,DB::raw('(CASE WHEN transcripcion = "S" THEN "SI" ELSE "NO" END) AS Transcripcion')
                                ,DB::raw('(CASE WHEN interpretacion = "S" THEN "SI" ELSE "NO" END) AS Interpretacion')
                                ,DB::raw('(CASE WHEN escaneado = "S" THEN "SI" ELSE "NO" END) AS Escaneado')
                                ,'cobranza.cantidadCbr')
                        ->where([
                            ['cobranza.id_estudio_fk','=',$request->estudioSelect]
                        ])
                        ->orderBy('cobranza.fecha','ASC')
                        ->get();
        
        return view('estudios.cobranzaTbl', compact('cobranza','estudios'));
    }

    public function importExcel(ImportCobranzaRequest $request){
        if($request->hasFile('file')){
            $file = $request->file('file');
            Excel::import(new ReportesImport, $file);
            return redirect()->route('importarCobranza.index');
        }
        return "No ha adjuntado ningun archivo";
    }

    public function importExcelCitas(Request $request){
        //if($request->hasFile('file')){
            $file = $request->file('file');
            Excel::import(new CitasImport, $file);
            //return redirect()->route('importarCobranza.index');
        //}
        //return "No ha adjuntado ningun archivo";
    }

    public function exportExcel(){
        return Excel::download(new CobranzaExport, 'ReporteCobranza.xlsx');
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
        $doctores = Doctor::where('id','<>','1')
                            ->get();
        $tipoPac = TipoPaciente::all();
        $empTrans = Empleado::join('puestos','puestos.id','=','puesto_id')
                              ->select('id','empleado_nombre','empleado_apellidop','empleado_apellidom')
                              ->where([
                                  ['puestos.actividad','=','TRANSCRIBE'],
                                  ['empleados.id_emp','<>','1']
                              ])->get();
        $doctorInter = Doctor::where('id','<>','1')
                               ->get();

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
            'registroC'  => 'required',
            'drRequiere' => 'required',
            'tipoPaciente' => 'required',
            /*'transRd' => 'required',
            'intRd' => 'required',
            'escRd' => 'required',
            'entRd' => 'required'*/
        ],[
            'registroC.required' => 'Selecciona si el registro ya está completo.',
            'drRequiere.required' => 'Selecciona el doctor al que requiere el estudio.',
            'tipoPaciente.required' => 'Selecciona si el paciente es interno o externo.',
            /*'transRd.required' => 'Selecciona el status de transcripción del estudio.',
            'intRd.required' => 'Selecciona el status de interpretación del estudio.',
            'escRd.required' => 'Selecciona el status de escaneado del estudio.',
            'entRd.required' => 'Selecciona el status de entregado del estudio.',*/
        ]);

        if($request['transRd'] == 'N'){
            $doctorTrans = '0';
        }else{
            $doctorTrans = $request["drTransc"];
        }

        if($request["drInterpreta"] == 'N'){
            $doctorInter = '0';
        }else{
            $doctorInter = $request["drInterpreta"];
        }

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }else{
            if($request['registroC']=='S'){
                $estUpd = Estudios::where('dscrpMedicosPro',$request['estudioCbr'])->first();

                $fechaInsert = now()->toDateString();
                DB::table('cobranza')->insert([
                    'id_estudio_fk' => $estUpd->id,
                    'id_doctor_fk' => $request["drRequiere"],
                    'id_empTrans_fk' => $doctorTrans,
                    'id_empInt_fk' => $doctorInter,
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
                    'entregado' => $request['entRd'],
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
                                                'entregado' => $request['entRd'],
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
    public function destroy(Request $request){
        $dataCobranza = Estudiostemp::where('estudiostemps_status',1)->delete();
        return redirect()->route('importarCobranza.index');
    }
}