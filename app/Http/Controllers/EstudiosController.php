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
use App\Models\CatEstudios;
use App\Models\Doctor;
use App\Models\TipoPaciente;
use App\Models\Empleado;
use App\Models\Estudios;
use App\Models\Cobranza;
use App\Models\TipoOjo;

use App\Http\Requests\ImportCobranzaRequest;

class EstudiosController extends Controller{
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
            $this->eliminarDuplicados();
            
            return redirect()->route('importarCobranza.index');
        }
        
    }

    public function eliminarDuplicados(){
        DB::delete("DELETE duplicados from estudiostemps as duplicados
                    INNER JOIN estudiostemps as temporales
                    WHERE duplicados.id > temporales.id and duplicados.folio = temporales.folio
                    and duplicados.paciente = temporales.paciente and duplicados.servicio = temporales.servicio");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){

        $consulta = Estudiostemp::where('estudiostemps_status','!=',5)->get();

        //->eloquent(Estudiostemp::query())       

        return datatables::of($consulta)
                ->addColumn('date','estudios.columnaFecha')
                ->addColumn('btn','estudios.btnCobranza-ver')
                ->addColumn('on-off','estudios.btnCobranza-status')
                ->rawColumns(['date','btn','on-off'])
                ->toJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $datosPaciente = Estudiostemp::find($id);
        $descripcionEstudios = Estudios::all();  
        $doctores = Doctor::where('id','<>','1')->get();
        $tipoPac = TipoPaciente::all();
        $empTrans = Empleado::join('puestos','puestos.id','=','puesto_id')
                                ->select('empleados.id_emp','empleado_nombre','empleado_apellidop','empleado_apellidom')
                                ->where([
                                    ['puestos.actividad','=','TRANSCRIBE'],
                                    ['empleados.id_emp','<>','1']
                                ])->get();

        $empRealiza = Empleado::select(DB::raw("CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom,' (',puestos.puestos_nombre,')') AS empleado"),'empleados.id_emp')
                                    ->join('puestos','puestos.id','=','puesto_id')
                                    ->whereIn('id_emp',[14,15,13,16,4,20,8,10,2])
                                    ->orderBy('empleado','asc')
                                    ->get();
                                    
        $empEntrega = Empleado::select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado"),'id_emp')
                                    ->where([
                                        ['id_emp','!=',1],
                                        ['puesto_id','=',6]
                                    ])->get();
                                    
        $doctorInter = Empleado::join('puestos','puestos.id','puesto_id')
                                ->select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado"),'id_emp')
                                ->whereIn('id_emp',[13,14,15])->get();

        $statusCobCom = DB::table('status_cob_com')
                            ->join('empleados','empleados.id_emp','=','status_cob_com.id_empleado_fk')
                            ->join('actividades','actividades.id','=','status_cob_com.id_actividad_fk')
                            ->join('estudios','estudios.id','=','status_cob_com.id_estudio_fk')
                            ->select('status_cob_com.id'
                                    ,'cobranza_total'
                                    ,'estudios.dscrpMedicosPro'
                                    ,'status_cob_com.folio'
                                    ,'actividades.nombreActividad'
                                    ,'status_cob_com.statusComisiones'
                                    ,'empleados.id_emp'
                                    ,DB::raw("CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom) AS empleado"))
                            ->where('id_estudiostemps_fk',$id)
                            ->get();

        $totalStatusPagado = DB::table('status_cob_com')
                                ->where('id_estudiostemps_fk',$id)
                                ->whereIn('statusComisiones',["PAGADO","RESERVADO"])
                                ->count('statusComisiones');

        $totalStatusUtilidades = DB::table('status_cob_com')
                                    ->where([
                                        ['id_estudiostemps_fk',$id],
                                        ['id_actividad_fk',10],
                                        ['statusComisiones','PAGADO']
                                    ])
                                    ->count();

        return view('estudios.cobranza-paciente',compact('datosPaciente','doctores','tipoPac','empTrans','doctorInter','descripcionEstudios','empRealiza','empEntrega','statusCobCom','totalStatusPagado','totalStatusUtilidades'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request){

        if($request->palabra_clave === "OFTALMOCENTER"){
            DB::table('estudiostemps')->where('estudiostemps_status',1)
                ->update([                                               
                    'estudiostemps_status' => 5
        ]);
        }

        //$dataCobranza = Estudiostemp::where('',1)->delete();
        return redirect()->route('importarCobranza.index');
    }

    //Funciones de Catálogos (Estudios Invididuales)
    public function showCatalogo(){
        $listEstudios = Estudios::join('cat_estudios','cat_estudios.id','=','id_estudio_fk')
                                ->join('tipo_ojos','tipo_ojos.id','=','id_ojo_fk')
                                ->select('estudios.id','cat_estudios.descripcion','tipo_ojos.nombretipo_ojo','estudios.dscrpMedicosPro')
                                ->orderBy('estudios.id','asc')
                                ->get();
                                
        $catEstudios = CatEstudios::all();
        $catOjos = TipoOjo::all();

        return view('catalogos.estudios.catestudios',compact('listEstudios','catEstudios','catOjos'));
    }

    public function mostrarEstudio($id){
        $estudio = Estudios::orderBy('estudios.id','asc')
                            ->where('estudios.id','=',$id)
                            ->first();

        $catEstudios = CatEstudios::all();
        $catOjos = TipoOjo::all();

        return view('catalogos.estudios.editestudios',compact('estudio','catEstudios','catOjos'));
    }

    public function updateEstudio(Request $request){
        $nvoEstudio = Estudios::find($request->idEstudio);
        $nvoEstudio->id_estudio_fk = $request->estudioGral;
        $nvoEstudio->id_ojo_fk = $request->tipoOjo;
        $nvoEstudio->dscrpmedicosPro = $request->dscrpMedicosPro;
        $nvoEstudio->save();

        $listEstudios = Estudios::join('cat_estudios','cat_estudios.id','=','id_estudio_fk')
                                ->join('tipo_ojos','tipo_ojos.id','=','id_ojo_fk')
                                ->select('estudios.id','cat_estudios.descripcion','tipo_ojos.nombretipo_ojo','estudios.dscrpMedicosPro')
                                ->orderBy('estudios.id','asc')
                                ->get();

        $catEstudios = CatEstudios::all();
        $catOjos = TipoOjo::all();

        return view('catalogos.estudios.catestudios',compact('listEstudios','catEstudios','catOjos'));
    }

    public function nvoEstudio(Request $request){
        $validator = Validator::make($request->all(),[
            'estudioGral' => 'required',
            'tipoOjo' => 'required',
            'dscrpMedicosPro' => 'required',
        ],[
            'estudioGral.required' => 'Selecccione el estudio.',
            'tipoOjo.required' => 'Seleccione el tipo de ojo.',
            'dscrpMedicosPro.required' => 'Ingrese la descripción del estudio.',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $duplicados = Estudios::where([
                            ['dscrpMedicosPro',$request->dscrpMedicosPro],
                            ['id_estudio_fk',$request->estudioGral],
                            ['id_ojo_fk',$request->tipoOjo]
                        ])->get();

        if($duplicados->count() >= 1){
            return back()->with('duplicados','El registro ingresado ya existe');
        }
        
        $fechaInsert = now()->toDateString();
        DB::table('estudios')->insert([
            'id_estudio_fk' => $request->estudioGral,
            'id_ojo_fk' => $request->tipoOjo,
            'dscrpMedicosPro' => $request->dscrpMedicosPro,
            'created_at' => $fechaInsert,
            'updated_at' => $fechaInsert
        ]);

        $listEstudios = Estudios::join('cat_estudios','cat_estudios.id','=','id_estudio_fk')
                                ->join('tipo_ojos','tipo_ojos.id','=','id_ojo_fk')
                                ->select('estudios.id','cat_estudios.descripcion','tipo_ojos.nombretipo_ojo','estudios.dscrpMedicosPro')
                                ->orderBy('estudios.id','asc')
                                ->get();
                                
        $catEstudios = CatEstudios::all();
        $catOjos = TipoOjo::all();
        
        return view('catalogos.estudios.catestudios',compact('listEstudios','catEstudios','catOjos'));
    }

    public function deleteEstudio(Request $request){
        $delEstudios = Estudios::find($request->idEstudioDel)->delete();
        
        return redirect()->route('mostrarCatalogo.show');
    }

    //Funciones de catálogos generales
    public function showEstudiosGrales(){
        $listEstudiosGrales = CatEstudios::orderBy('id','asc')->get();
        
        return view('catalogos.estudiosgenerales.catestudiosgrales',compact('listEstudiosGrales'));
    }

    public function nvoEstudioGral(Request $request){
        $validator = Validator::make($request->all(),[
            'descripcionGral' => 'Required',
        ],[
            'descripcionGral.required' => 'Ingrese la descripción del estudio'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $duplicados = CatEstudios::where('descripcion',$request->descripcionGral)->get();
        if($duplicados->count() >= 1){
            return back()->with('duplicados','El registro ingresado ya existe');
        }
        
        $fechaInsert = now()->toDateString();
        DB::table('cat_estudios')->insert([
            'descripcion' => $request->descripcionGral,
            'created_at' => $fechaInsert,
            'updated_at' => $fechaInsert
        ]);

        $listEstudiosGrales = CatEstudios::orderBy('id','asc')->get();
        return view('catalogos.estudiosgenerales.catestudiosgrales',compact('listEstudiosGrales'));
    }

    public function mostrarEstudioGral($id){
        $estudio = CatEstudios::find($id);
        return view('catalogos.estudiosgenerales.editestudiosgrales',compact('estudio'));
    }

    public function updateEstudioGral(Request $request){
        $nvoEstudioGral = CatEstudios::find($request->idEstudio);
        $nvoEstudioGral->descripcion = $request->descripcionGral;
        $nvoEstudioGral->save();

        $listEstudiosGrales = CatEstudios::orderBy('id','asc')->get();

        return view('catalogos.estudiosgenerales.catestudiosgrales',compact('listEstudiosGrales'));
    }

    public function deleteEstudioGral(Request $request){
        $delEstudios = CatEstudios::find($request->idEstudioDel)->delete();
        return redirect()->route('mostrarCatalogoGral.show');
    }

    public function showActividad($id){
        $empleados = Empleado::select(DB::raw("CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom,' (',puestos.puestos_nombre,')') AS empleado"),'empleados.id_emp')
                                    ->join('puestos','puestos.id','=','puesto_id')
                                    ->orderBy('empleado','asc')
                                    ->get();

        $nombreActividad = DB::table('status_cob_com')
                            ->join('actividades','actividades.id','=','status_cob_com.id_actividad_fk')
                            ->select('actividades.nombreActividad')
                            ->where('status_cob_com.id',$id)
                            ->first();

        if($nombreActividad->nombreActividad == "Entregado"){
            $statusCobCom = DB::table('status_cob_com')
                            ->join('empleados','empleados.id_emp','=','status_cob_com.id_empleado_fk')
                            ->join('actividades','actividades.id','=','status_cob_com.id_actividad_fk')
                            ->join('estudiostemps','estudiostemps.id','=','status_cob_com.id_estudiostemps_fk')
                            ->select('status_cob_com.id'
                                    ,'status_cob_com.id_estudiostemps_fk AS idEstudios'
                                    ,'actividades.nombreActividad'
                                    ,'status_cob_com.statusComisiones'
                                    ,'empleados.id_emp'
                                    ,'estudiostemps.entregado'
                                    ,DB::raw("CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom) AS empleado"))
                            ->where('status_cob_com.id',$id)
                            ->first();
        }else{
            $statusCobCom = DB::table('status_cob_com')
                            ->join('empleados','empleados.id_emp','=','status_cob_com.id_empleado_fk')
                            ->join('actividades','actividades.id','=','status_cob_com.id_actividad_fk')
                            ->join('estudiostemps','estudiostemps.id','=','status_cob_com.id_estudiostemps_fk')
                            ->select('status_cob_com.id'
                                    ,'status_cob_com.id_estudiostemps_fk AS idEstudios'
                                    ,'actividades.nombreActividad'
                                    ,'status_cob_com.statusComisiones'
                                    ,'empleados.id_emp'
                                    ,DB::raw("CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom) AS empleado"))
                            ->where('status_cob_com.id',$id)
                            ->first();
        }

        return view('estudios.editactividad',compact('statusCobCom','empleados'));
    }

    public function updateActividad(Request $request){
        if($request->nombreActividad != 'Entregado'){
            $validator = Validator::make($request->all(),[
                'empNuevo' => 'Required',
            ],[
                'empNuevo.required' => 'Seleccione el nuevo empleado.'
            ]);

            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }

            DB::table('status_cob_com')->where('id',$request['idActividad'])
                ->update([                                               
                    'id_empleado_fk' => $request["empNuevo"]
            ]);

            $tipoAct = DB::table('status_cob_com')
                            ->select('id_actividad_fk')
                            ->where('id',$request['idActividad'])
                            ->first();

            switch($tipoAct->id_actividad_fk){
                //Transcrito
                case '1':
                    DB::table('estudiostemps')->where('id',$request['idEstudios'])
                        ->update([                                               
                            'id_empTrans_fk' => $request["empNuevo"]
                    ]);
                break;
                //Interpretado
                case '2':
                    DB::table('estudiostemps')->where('id',$request['idEstudios'])
                        ->update([                                               
                            'id_empInt_fk' => $request["empNuevo"]
                    ]);
                break;
                //Realizado
                case '5':
                    DB::table('estudiostemps')->where('id',$request['idEstudios'])
                        ->update([                                               
                            'id_empRea_fk' => $request["empNuevo"]
                    ]);
                break;
            }
        }else{
            //Condiciones para la actividad entregado
           //dd($request);
            $datosEnt = DB::table('status_cob_com')
                            ->select('statusComisiones')
                            ->where('id',$request["idActividad"])
                            ->first();

            if($datosEnt->statusComisiones != "RESERVADO"){
                if($request->entRd == "S"){
                    $validator = Validator::make($request->all(),[
                        'empNuevo' => 'Required',
                    ],[
                        'empNuevo.required' => 'Seleccione el nuevo empleado.'
                    ]);

                    if($validator->fails()){
                        return back()->withErrors($validator)->withInput();
                    }

                    DB::table('status_cob_com')->where('id',$request['idActividad'])
                        ->update([                                               
                            'id_empleado_fk' => $request["empNuevo"],
                            'statusComisiones'  => 'P'
                    ]);

                    DB::table('estudiostemps')->where('id',$request['idEstudios'])
                        ->update([                                               
                            'id_empEnt_fk'  => $request["empNuevo"],
                            'entregado'     => 'S' 
                    ]);
                }else if($request->entRd == "P"){
                    DB::table('status_cob_com')->where('id',$request['idActividad'])
                        ->update([                                               
                            'id_empleado_fk'    => 1,
                            'statusComisiones'  => 'P'
                    ]);

                    DB::table('estudiostemps')->where('id',$request['idEstudios'])
                        ->update([                                               
                            'id_empEnt_fk'  => 1,
                            'entregado'     => 'P' 
                    ]);
                }else{
                    $delStatusCob = DB::table('status_cob_com')->where('id',$request['idActividad'])->delete();

                    DB::table('estudiostemps')->where('id',$request['idEstudios'])
                        ->update([                                               
                            'id_empEnt_fk'  => 1,
                            'entregado'     => 'N' 
                    ]);
                }
            }else{
                if($request->entRd == "S"){
                    $validator = Validator::make($request->all(),[
                        'empNuevo' => 'Required',
                    ],[
                        'empNuevo.required' => 'Seleccione el nuevo empleado.'
                    ]);

                    if($validator->fails()){
                        return back()->withErrors($validator)->withInput();
                    }

                    DB::table('status_cob_com')->where('id',$request['idActividad'])
                        ->update([                                               
                            'id_empleado_fk' => $request["empNuevo"],
                            'statusComisiones' => "ASIGNADO"
                    ]);

                    DB::table('estudiostemps')->where('id',$request['idEstudios'])
                        ->update([                                               
                            'id_empEnt_fk'  => $request["empNuevo"],
                            'entregado'     => 'S' 
                    ]);
                }else if($request->entRd == "P"){
                    DB::table('status_cob_com')->where('id',$request['idActividad'])
                        ->update([                                               
                            'id_empleado_fk'    => 1
                    ]);

                    DB::table('estudiostemps')->where('id',$request['idEstudios'])
                        ->update([                                               
                            'id_empEnt_fk'  => 1,
                            'entregado'     => 'P' 
                    ]);
                }else{
                    $delStatusCob = DB::table('status_cob_com')->where('id',$request['idActividad'])->delete();

                    DB::table('estudiostemps')->where('id',$request['idEstudios'])
                        ->update([                                               
                            'id_empEnt_fk'  => 1,
                            'entregado'     => 'N' 
                    ]);
                }
            }
        }
        
        return redirect()->route('importarCobranza.show',[$request->idEstudios]);
    }

    public function eliminar($id){
        $statusExistencias = DB::table('status_cob_com')
                            ->where('id_estudiostemps_fk',$id)
                            ->select('id')
                            ->get();
       

        if(!is_null($statusExistencias)){
            foreach($statusExistencias as $existencias){
                DB::table('status_cob_com')
                    ->where('id',$existencias->id)
                    ->delete();
            }
                DB::table('estudiostemps')
                    ->where('id',$id)
                    ->delete();
        }else if(is_null($statusExistencias)){
            DB::table('estudiostemps')
                    ->where('id',$id)
                    ->delete();
        }

        return redirect()->route('importarCobranza.index');
    }
}