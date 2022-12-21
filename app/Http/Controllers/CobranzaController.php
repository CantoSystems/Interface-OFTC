<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CobranzaExport;

use App\Models\Doctor;
use App\Models\Empleado;
use App\Models\Estudios;
use App\Models\Cobranza;
use App\Models\Estudiostemp;
use App\Models\TipoPaciente;

class CobranzaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request){
        if($request['transRd'] == 'N'){
            $doctorTrans = '1';
        }else{
            $doctorTrans = $request["drTransc"];
        }

        if($request["intRd"] == 'N'){
            $doctorInter = '1';
        }else{
            $doctorInter = $request["drInt"];
        }

        if($request["entRd"] == 'N'){
            $empEntrega = '1';
        }else{
            $empEntrega = $request["empEnt"];
        }

        $fechaInsert = now();
        //Se verifica si el estudio está completo
        if($request['registroC']=='S'){
            $validator = Validator::make($request->all(),[
                'registroC'  => 'required',
                'drRequiere' => 'required',
                'tipoPaciente' => 'required',
                'transRd' => 'required',
                'intRd' => 'required',
                'escRd' => 'required',
                'entRd' => 'required',
                'empRealiza' => 'required'
            ],[
                'registroC.required' => 'Selecciona si el registro ya está completo.',
                'drRequiere.required' => 'Selecciona el doctor al que requiere el estudio.',
                'tipoPaciente.required' => 'Selecciona si el paciente es interno o externo.',
                'escRd.required' => 'Selecciona el status de escaneado del estudio.',
                'entRd.required' => 'Selecciona el status de entregado del estudio.',
                'transRd.required' => 'Selecciona el status de transcripción del estudio.',
                'intRd.required' => 'Selecciona el status de interpretación del estudio.',
                'empRealiza.required' => 'Selecciona el empleado que realizó el estudio.'
            ]);

            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }else{
                //Primera condicional encontrar la coincidencia de la descripción del estudio
                $estUpd = Estudios::where('dscrpMedicosPro',$request['estudioCbr'])->first();

                if(!is_null($estUpd)){
                    //Status del registro incompleto
                    if($request->status != 1){
                        $cobranzaFolio =  DB::table('cobranza')
                                                ->where([
                                                    ['folio',$request['folioCbr']],
                                                    ['paciente',$request['pacienteCbr']],
                                                    ['id_estudio_fk',$estUpd->id]
                                                ])->first();

                        //Se verifica que no se haya insertado antes
                        if(is_null($cobranzaFolio)){
                            DB::table('cobranza')->insert([
                                'id_estudio_fk' => $estUpd->id,
                                'id_doctor_fk' => $request["drRequiere"],
                                'id_empTrans_fk' => $doctorTrans,
                                'id_empRea_fk' => $request['empRealiza'],
                                'id_empEnt_fk' => $empEntrega,
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
                        }else if(isset($cobranzaFolio)){
                                //Código por si ya se encuentra el registro en la tavla de Cobranza
                                DB::table('cobranza')->where([
                                                    ['folio',$request['folioCbr']],
                                                    ['paciente',$request['pacienteCbr']],
                                                    ['id_estudio_fk',$estUpd->id]
                                ])->update([
                                    'id_estudio_fk' => $estUpd->id,
                                    'id_doctor_fk' => $request["drRequiere"],
                                    'id_empTrans_fk' => $doctorTrans,
                                    'id_empRea_fk' => $request['empRealiza'],
                                    'id_empEnt_fk' => $empEntrega,
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
                        }
                        
                        Estudiostemp::where([
                                                ['folio',$request['folioCbr']],
                                                ['paciente',$request['pacienteCbr']],
                                                ['servicio',$request['estudioCbr']],
                                            ])->update([
                                                    'id_empTrans_fk' => $doctorTrans,                                   
                                                    'id_doctor_fk' => $request["drRequiere"],
                                                    'id_empEnt_fk' => $empEntrega,
                                                    'id_empRea_fk' => $request['empRealiza'],
                                                    'id_empInt_fk' => $doctorInter,
                                                    'tipoPaciente' => $request['tipoPaciente'],
                                                    'transcripcion' => $request['transRd'],
                                                    'interpretacion' => $request['intRd'],
                                                    'escaneado' => $request['escRd'],
                                                    'entregado' => $request['entRd'],
                                                    'observaciones' => $request['obsCobranza'],
                                                    'estudiostemps_status' => 1,
                                                    'registroC' => $request['registroC'],
                                                    'updated_at' => $fechaInsert
                                                ]);

                        $statusCobComSLCT =  DB::table('status_cob_com')
                                                ->where([
                                                    ['folio',$request['folioCbr']],
                                                    ['paciente',$request['pacienteCbr']],
                                                    ['id_estudio_fk',$estUpd->id],
                                                ])
                                                ->whereIn('id', [1, 2, 3, 4, 5])->get();

                        dd($statusCobComSLCT);

                        //registro cuando ya se realizó
                        /*DB::table('status_cob_com')->insert([
                                    'id_estudio_fk' => $estUpd->id,
                                    'id_actividad_fk' => '5',
                                    'id_empleado_fk' => $request['empRealiza'],
                                    'paciente' => $request['pacienteCbr'],
                                ]);

                        //registro si se transcribió
                        if($request['transRd'] == 'S'){
                            DB::table('status_cob_com')->insert([
                                    'id_estudio_fk' => $estUpd->id,
                                    'id_actividad_fk' => '1',
                                    'id_empleado_fk' => $doctorTrans,
                                    'paciente' => $request['pacienteCbr'],
                                ]);
                        }

                        //registro si se interpretó
                        if($request['intRd'] == 'S'){
                            DB::table('status_cob_com')->insert([
                                    'id_estudio_fk' => $estUpd->id,
                                    'id_actividad_fk' => '2',
                                    'id_empleado_fk' => $doctorInter,
                                    'paciente' => $request['pacienteCbr'],
                                ]);
                        }

                        //registro si se escaneó
                        if($request['escRd'] == 'S'){
                            DB::table('status_cob_com')->insert([
                                    'id_estudio_fk' => $estUpd->id,
                                    'id_actividad_fk' => '3',
                                    'id_empleado_fk' => '1',
                                    'paciente' => $request['pacienteCbr'],
                                ]);
                        }

                        //registro si se entregó
                        if($request['entRd'] == 'S'){
                            DB::table('status_cob_com')->insert([
                                    'id_estudio_fk' => $estUpd->id,
                                    'id_actividad_fk' => '4',
                                    'id_empleado_fk' => $empEntrega,
                                    'paciente' => $request['pacienteCbr'],
                                ]);
                        }*/

                    //Insertar cobranza status completado
                    }elseif ($request->status == 1){
                        DB::table('cobranza')->where([
                                                        ['folio',$request['folioCbr']],
                                                        ['paciente',$request['pacienteCbr']],
                                                        ['id_estudio_fk',$estUpd->id]
                                                    ])
                                        ->update([
                                                'id_doctor_fk' => $request["drRequiere"],
                                                'id_empTrans_fk' => $doctorTrans,
                                                'id_empEnt_fk' => $empEntrega,
                                                'id_empRea_fk' => $request['empRealiza'],
                                                'id_empInt_fk' => $doctorInter,
                                                'tipoPaciente' => $request['tipoPaciente'],
                                                'transcripcion' => $request['transRd'],
                                                'interpretacion' => $request['intRd'],
                                                'escaneado' => $request['escRd'],
                                                'observaciones' => $request['obsCobranza'],
                                                'entregado' => $request['entRd'],
                                                'created_at' => $fechaInsert,
                                                'updated_at' => $fechaInsert
                                        ]);

                        Estudiostemp::where([
                                            ['folio',$request['folioCbr']],
                                            ['paciente',$request['pacienteCbr']],
                                            ['servicio',$request['estudioCbr']],

                                        ])
                                        ->update([
                                            'id_empTrans_fk' => $doctorTrans,                                                
                                            'id_doctor_fk' => $request["drRequiere"],
                                            'id_empEnt_fk' => $empEntrega,
                                            'id_empRea_fk' => $request['empRealiza'],
                                            'id_empInt_fk' => $doctorInter,
                                            'tipoPaciente' => $request['tipoPaciente'],
                                            'transcripcion' => $request['transRd'],
                                            'interpretacion' => $request['intRd'],
                                            'escaneado' => $request['escRd'],
                                            'entregado' => $request['entRd'],
                                            'observaciones' => $request['obsCobranza'],
                                            'registroC' => $request['registroC'],
                                            'updated_at' => $fechaInsert
                                        ]);

                        $statusCobComSLCT =  DB::table('status_cob_com')
                                                ->where([
                                                    ['folio',$request['folioCbr']],
                                                    ['paciente',$request['pacienteCbr']],
                                                    ['id_estudio_fk',$estUpd->id],
                                                ])
                                                ->whereIn('id', [1, 2, 3, 4, 5])->get();

                        dd($statusCobComSLCT);

                        //registro cuando ya se realizó
                        /*DB::table('status_cob_com')->insert([
                                    'id_estudio_fk' => $estUpd->id,
                                    'id_actividad_fk' => '5',
                                    'id_empleado_fk' => $request['empRealiza'],
                                    'paciente' => $request['pacienteCbr'],
                                ]);

                        //registro si se transcribió
                        if($request['transRd'] == 'S'){
                            DB::table('status_cob_com')->insert([
                                    'id_estudio_fk' => $estUpd->id,
                                    'id_actividad_fk' => '1',
                                    'id_empleado_fk' => $doctorTrans,
                                    'paciente' => $request['pacienteCbr'],
                                ]);
                        }

                        //registro si se interpretó
                        if($request['intRd'] == 'S'){
                            DB::table('status_cob_com')->insert([
                                    'id_estudio_fk' => $estUpd->id,
                                    'id_actividad_fk' => '2',
                                    'id_empleado_fk' => $doctorInter,
                                    'paciente' => $request['pacienteCbr'],
                                ]);
                        }

                        //registro si se escaneó
                        if($request['escRd'] == 'S'){
                            DB::table('status_cob_com')->insert([
                                    'id_estudio_fk' => $estUpd->id,
                                    'id_actividad_fk' => '3',
                                    'id_empleado_fk' => '1',
                                    'paciente' => $request['pacienteCbr'],
                                ]);
                        }

                        //registro si se entregó
                        if($request['entRd'] == 'S'){
                            DB::table('status_cob_com')->insert([
                                    'id_estudio_fk' => $estUpd->id,
                                    'id_actividad_fk' => '4',
                                    'id_empleado_fk' => $empEntrega,
                                    'paciente' => $request['pacienteCbr'],
                                ]);
                        }*/
                    //El registro se actualiza en estudiostemps y en cobranza 
                    }
                    //Fin para insertar o actualizar datos
                //Fin del registro cuando se encuentra el estudio
                }else{
                    //No coincide el  estudio
                    if($request->status == 3 && $request->estudioCorregido != null){
                        DB::table('cobranza')->insert([
                            'id_estudio_fk' => $request->estudioCorregido,
                            'id_doctor_fk' => $request["drRequiere"],
                            'id_empTrans_fk' => $doctorTrans,
                            'id_empEnt_fk' => $empEntrega,
                            'id_empRea_fk' => $request['empRealiza'],
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

                        $descripcion = Estudios::select('dscrpMedicosPro')
                                            ->where('id',$request->estudioCorregido)
                                            ->first();
                        
                        Estudiostemp::where([
                                                ['folio',$request['folioCbr']],
                                                ['paciente',$request['pacienteCbr']],
                                                ['servicio',$request['estudioCbr']]
                                            ])
                                            ->update([
                                                'id_empTrans_fk' => $doctorTrans,                                         
                                                'id_doctor_fk' => $request["drRequiere"],
                                                'id_empEnt_fk' => $empEntrega,
                                                'id_empRea_fk' => $request['empRealiza'],
                                                'id_empInt_fk' => $doctorInter,
                                                'tipoPaciente' => $request['tipoPaciente'],
                                                'servicio' => $descripcion->dscrpMedicosPro,
                                                'transcripcion' => $request['transRd'],
                                                'interpretacion' => $request['intRd'],
                                                'escaneado' => $request['escRd'],
                                                'entregado' => $request['entRd'],
                                                'observaciones' => $request['obsCobranza'],
                                                'estudiostemps_status' => 1,
                                                'registroC' => $request['registroC'],
                                                'updated_at' => $fechaInsert
                                            ]);
                    }else{
                        $updateStatusC = Estudiostemp::where([
                                                                ['folio',$request['folioCbr']],
                                                                ['paciente',$request['pacienteCbr']],
                                                                ['servicio',$request['estudioCbr']]
                                                            ])
                                        ->update([
                                                'id_empTrans_fk' => $doctorTrans,                                                
                                                'id_doctor_fk' => $request["drRequiere"],
                                                'id_empEnt_fk' => $empEntrega,
                                                'id_empRea_fk' => $request['empRealiza'],
                                                'id_empInt_fk' => $doctorInter,
                                                'tipoPaciente' => $request['tipoPaciente'],
                                                'transcripcion' => $request['transRd'],
                                                'interpretacion' => $request['intRd'],
                                                'escaneado' => $request['escRd'],
                                                'entregado' => $request['entRd'],
                                                'observaciones' => $request['obsCobranza'],
                                                'estudiostemps_status' => 3,
                                                'registroC' => $request['registroC'],
                                                'updated_at' => $fechaInsert
                                        ]);
                    }//Registro no se encuentran coincidencias
                }
            }//Fin registro contiene todos los datos          
        }else{
            $estUpd = Estudios::where('dscrpMedicosPro',$request['estudioCbr'])->first();
            
            //Registro faltante de datos
            $updateStatusC = Estudiostemp::where([
                                                    ['folio',$request['folioCbr']],
                                                    ['paciente',$request['pacienteCbr']],
                                                    ['servicio',$request['estudioCbr']],
                                                ])->update([
                                                    'id_empTrans_fk' => $doctorTrans,                                                
                                                    'id_doctor_fk' => $request["drRequiere"],
                                                    'id_empEnt_fk' => $empEntrega,
                                                    'id_empRea_fk' => $request['empRealiza'],
                                                    'id_empInt_fk' => $doctorInter,
                                                    'tipoPaciente' => $request['tipoPaciente'],
                                                    'transcripcion' => $request['transRd'],
                                                    'interpretacion' => $request['intRd'],
                                                    'escaneado' => $request['escRd'],
                                                    'entregado' => $request['entRd'],
                                                    'observaciones' => $request['obsCobranza'],
                                                    'estudiostemps_status' => 2,
                                                    'registroC' => $request['registroC'],
                                                    'updated_at' => $fechaInsert
                                                ]);
                                                

            if(Arr::has($request,'empRealiza')){
                DB::table('status_cob_com')->insert([
                        'id_estudio_fk' => $estUpd->id,
                        'folio' => $request['folioCbr'],
                        'id_actividad_fk' => '5',
                        'id_empleado_fk' => $request['empRealiza'],
                        'paciente' => $request['pacienteCbr'],
                    ]);
            }

            //registro si se transcribió
            if($request['transRd'] == 'S'){
                DB::table('status_cob_com')->insert([
                        'id_estudio_fk' => $estUpd->id,
                        'folio' => $request['folioCbr'],
                        'id_actividad_fk' => '1',
                        'id_empleado_fk' => $doctorTrans,
                        'paciente' => $request['pacienteCbr'],
                    ]);
            }

            //registro si se interpretó
            if($request['intRd'] == 'S'){
                DB::table('status_cob_com')->insert([
                        'id_estudio_fk' => $estUpd->id,
                        'folio' => $request['folioCbr'],
                        'id_actividad_fk' => '2',
                        'id_empleado_fk' => $doctorInter,
                        'paciente' => $request['pacienteCbr'],
                    ]);
            }

            //registro si se escaneó
            if($request['escRd'] == 'S'){
                DB::table('status_cob_com')->insert([
                        'id_estudio_fk' => $estUpd->id,
                        'folio' => $request['folioCbr'],
                        'id_actividad_fk' => '3',
                        'id_empleado_fk' => '1',
                        'paciente' => $request['pacienteCbr'],
                    ]);
            }

            //registro si se entregó
            if($request['entRd'] == 'S'){
                DB::table('status_cob_com')->insert([
                        'id_estudio_fk' => $estUpd->id,
                        'folio' => $request['folioCbr'],
                        'id_actividad_fk' => '4',
                        'id_empleado_fk' => $empEntrega,
                        'paciente' => $request['pacienteCbr'],
                    ]);
            }

            DB::delete("DELETE duplicados from status_cob_com as duplicados
                        INNER JOIN status_cob_com as temporales
                        WHERE duplicados.id > temporales.id
                        AND duplicados.folio = temporales.folio
                        AND duplicados.id_estudio_fk = temporales.id_estudio_fk
                        AND duplicados.paciente = temporales.paciente
                        AND duplicados.id_actividad_fk = temporales.id_actividad_fk");
           
        }//Fin contiene todos los datos

        return redirect()->route('importarCobranza.index');
    }

    /**
     * Display the specified resource.
     *update
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(){
        $estudios = Estudios::join('cat_estudios','cat_estudios.id','=','id_estudio_fk')
                            ->join('tipo_ojos','tipo_ojos.id','=','id_ojo_fk')
                            ->select('estudios.id','estudios.dscrpMedicosPro as descripcion','nombretipo_ojo')
                            ->orderBy('estudios.id','ASC')
                            ->get();
        
        return view('estudios.cobranzaTbl',compact('estudios'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCobranza(Request $request){
        if ($request->estudioSelect === null) {
            $busquedaEstudios = [];
        }
        $busquedaEstudios = $request->estudioSelect;
        $inicio = $request->historialInicio;
        $fin    = $request->historialFinal;

        $cobranza = DB::table('cobranza')
                    ->join('estudios','estudios.id','=','cobranza.id_estudio_fk')
                    ->join('cat_estudios','cat_estudios.id','=','estudios.id_estudio_fk')
                    ->join('tipo_ojos','tipo_ojos.id','=','estudios.id_ojo_fk')
                    ->join('doctors','doctors.id','=','cobranza.id_doctor_fk')
                    ->join('empleados','empleados.id_emp','=','cobranza.id_empRea_fk')
                    ->select('cobranza.folio'
                            ,'cobranza.fecha'
                            ,'cobranza.paciente'
                            ,'cat_estudios.descripcion'
                            ,'tipo_ojos.nombretipo_ojo'
                            ,DB::raw("UPPER(CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom)) AS EmpleadoRealiza")
                            ,DB::raw("UPPER(CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop)) AS Doctor")
                            ,DB::raw('(CASE WHEN transcripcion = "S" THEN "SI" ELSE "NO" END) AS Transcripcion')
                            ,DB::raw('(CASE WHEN interpretacion = "S" THEN "SI" ELSE "NO" END) AS Interpretacion')
                            ,DB::raw('(CASE WHEN escaneado = "S" THEN "SI" ELSE "NO" END) AS Escaneado')
                            ,'cobranza.cantidadCbr')
                    ->whereIn('cobranza.id_estudio_fk', $busquedaEstudios)
                    ->whereBetween('cobranza.fecha',[$inicio,$fin])
                    ->orderBy('cobranza.fecha','ASC')
                    ->get();

        $estudios = Estudios::join('cat_estudios','cat_estudios.id','=','id_estudio_fk')
                            ->join('tipo_ojos','tipo_ojos.id','=','id_ojo_fk')
                            ->select('estudios.id','descripcion','nombretipo_ojo')
                            ->orderBy('estudios.id','ASC')
                            ->get();

        return view('estudios.cobranzaTbl', compact('cobranza','estudios','busquedaEstudios','inicio','fin'));
    }

    public function exportExcel(Request $request){
        $busqueda = json_decode($request->clvEstudios);
        $incio = $request->inicio;
        $fin = $request->fin;
    
        return Excel::download(new CobranzaExport($busqueda,$incio,$fin), 'ReporteCobranza.xlsx');
    }

    public function storeInt(Request $request){
        //return $request::all();
        if (empty($request->all())) {
            return response()->json(["error" => "Sin data"]);
        }

        foreach ($request->only('info') as $value) {
            $data = json_decode($value);
        }

        foreach ($data as $value) {
            DB::table('intestudios')->insert([
                'id_cobranza_fk' => $value->folioE,
                'id_estudio_fk' => $value->estudioI,
                'id_doctor_fk' => $value->doctorI,
                'intEstudios_status' => 0
            ]);
        }

        $datosPaciente = Estudiostemp::where('folio','=',$request->folioE);
        $descripcionEstudios = Estudios::all();  
        $doctores = Doctor::where('id','<>','1')->get();
        $tipoPac = TipoPaciente::all();
        $empTrans = Empleado::join('puestos','puestos.id','=','puesto_id')
                                ->select('empleados.id_emp','empleado_nombre','empleado_apellidop','empleado_apellidom')
                                ->where([
                                    ['puestos.actividad','=','TRANSCRIBE'],
                                    ['empleados.id_emp','<>','1']
                                ])->get();

        $empRealiza = Empleado::select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado"),'id_emp')
                                    ->where('id_emp','!=',1)
                                    ->get();

        $empEnt = Empleado::select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado"),'id_emp')
                                    ->where('id_emp','!=',1)
                                    ->get();

        $doctorInter = Doctor::where([
                                        ['id','<>','1'],
                                        ['categoria_id',2]
                                    ])->get();
    }

    public function showInt($id){
        $interpretaciones = DB::table('intestudios')->where('id',$id)->first();
        $descripcionEstudios = Estudios::all();
        $doctorInter = Doctor::where([
                            ['id','<>','1'],
                            ['categoria_id',2]
                        ])->get();

        return view('estudios.editInterpretacion', compact('interpretaciones','descripcionEstudios','doctorInter'));
    }

    public function updateInt(Request $request){
        DB::table('intestudios')->where('id',$request['idIntEst'])
                        ->update([                                               
                            'id_estudio_fk' => $request["estudioInt"],
                            'id_doctor_fk' => $request['doctorInt']
                        ]);

        $datosPaciente = Estudiostemp::where('folio',$request['folioEst'])->first();
        $doctores = Doctor::where('id','<>','1')->get();
        $tipoPac = TipoPaciente::all();
        $descripcionEstudios = Estudios::all(); 
        $empTrans = Empleado::join('puestos','puestos.id','=','puesto_id')
                        ->select('empleados.id_emp','empleado_nombre','empleado_apellidop','empleado_apellidom')
                        ->where([
                            ['puestos.actividad','=','TRANSCRIBE'],
                            ['empleados.id_emp','<>','1']
                        ])->get();

        $empRealiza = Empleado::select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado"),'id_emp')
                                    ->where('id_emp','!=',1)
                                    ->get();

        $empEnt = Empleado::select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado"),'id_emp')
                                    ->where('id_emp','!=',1)
                                    ->get();

        $doctorInter = Doctor::where([
                                        ['id','<>','1'],
                                        ['categoria_id',2]
                                    ])->get();

        if($datosPaciente->estudiostemps_status == 0){
            $doctoresInt = DB::table('intestudios')
                            ->join('estudios','estudios.id','=','intestudios.id_estudio_fk')
                            ->join('doctors','doctors.id','=','intestudios.id_doctor_fk')
                            ->join('estudiostemps','estudiostemps.folio','=','intestudios.id_cobranza_fk')
                            ->select('intestudios.id','estudios.dscrpMedicosPro',DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop,' ',doctors.doctor_apellidom) AS doctor"))
                            ->where('estudiostemps.folio',$request['folioEst'])
                            ->get();
        }else{
            $doctoresInt = DB::table('intestudios')
                            ->join('estudios','estudios.id','=','intestudios.id_estudio_fk')
                            ->join('doctors','doctors.id','=','intestudios.id_doctor_fk')
                            ->join('cobranza','cobranza.folio','=','intestudios.id_cobranza_fk')
                            ->select('intestudios.id','estudios.dscrpMedicosPro',DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop,' ',doctors.doctor_apellidom) AS doctor"))
                            ->where('cobranza.folio',$request['folioEst'])
                            ->get();
        }

        return view('estudios.cobranza-paciente',compact('datosPaciente','doctores','tipoPac','empTrans','doctorInter','descripcionEstudios','empRealiza','empEnt','doctoresInt'));
    }

    public function delInt($id){
        $folioEst = DB::table('intestudios')->where('id',$id)->first();
        $delInterpretacion = DB::table('intestudios')->where('id',$id)->delete();

        $datosPaciente = Estudiostemp::where('folio',$folioEst->id_cobranza_fk)->first();
        $doctores = Doctor::where('id','<>','1')->get();
        $tipoPac = TipoPaciente::all();
        $descripcionEstudios = Estudios::all(); 
        $empTrans = Empleado::join('puestos','puestos.id','=','puesto_id')
                        ->select('empleados.id_emp','empleado_nombre','empleado_apellidop','empleado_apellidom')
                        ->where([
                            ['puestos.actividad','=','TRANSCRIBE'],
                            ['empleados.id_emp','<>','1']
                        ])->get();

        $empRealiza = Empleado::select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado"),'id_emp')
                                    ->where('id_emp','!=',1)
                                    ->get();

        $empEnt = Empleado::select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado"),'id_emp')
                                    ->where('id_emp','!=',1)
                                    ->get();

        $doctorInter = Doctor::where([
                                        ['id','<>','1'],
                                        ['categoria_id',2]
                                    ])->get();

        if($datosPaciente->estudiostemps_status == 0){
            $doctoresInt = DB::table('intestudios')
                            ->join('estudios','estudios.id','=','intestudios.id_estudio_fk')
                            ->join('doctors','doctors.id','=','intestudios.id_doctor_fk')
                            ->join('estudiostemps','estudiostemps.folio','=','intestudios.id_cobranza_fk')
                            ->select('intestudios.id','estudios.dscrpMedicosPro',DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop,' ',doctors.doctor_apellidom) AS doctor"))
                            ->where('estudiostemps.folio',$folioEst->id_cobranza_fk)
                            ->get();
        }else{
            $doctoresInt = DB::table('intestudios')
                            ->join('estudios','estudios.id','=','intestudios.id_estudio_fk')
                            ->join('doctors','doctors.id','=','intestudios.id_doctor_fk')
                            ->join('cobranza','cobranza.folio','=','intestudios.id_cobranza_fk')
                            ->select('intestudios.id','estudios.dscrpMedicosPro',DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop,' ',doctors.doctor_apellidom) AS doctor"))
                            ->where('cobranza.folio',$folioEst->id_cobranza_fk)
                            ->get();
        }

        return view('estudios.cobranza-paciente',compact('datosPaciente','doctores','tipoPac','empTrans','doctorInter','descripcionEstudios','empRealiza','empEnt','doctoresInt'));
    }

    public function statusInterpretacion(Request $request){
        if( $request->registroC == 'S'){
                foreach ($request->all()["info"] as $valor) {
                //echo $valor["clave"];
                    DB::table('intestudios')->where('id',$valor["clave"])
                            ->update(['intEstudios_status' => 1]);
                }
        }elseif ($request->registroC == 'N' || $request->statusPaciente == 0 || $request->statusPaciente == 3)
                foreach ($request->all()["info"] as $valor) {
                //echo $valor["clave"];
                    DB::table('intestudios')->where('id',$valor["clave"])
                            ->update(['intEstudios_status' => 0]);
                }
    }
}