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
            
            try {
                Excel::import(new ReportesImport, $file);
            } catch (\Illuminate\Database\QueryException $e) {
                return back()->with('duplicados','Los Folios ya existen');
            }
            
            return redirect()->route('importarCobranza.index');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        return datatables()
                ->eloquent(Estudiostemp::query())
                ->addColumn('date','estudios.columnaFecha')
                ->addColumn('btn','estudios.btnCobranza-ver')
                ->addColumn('on-off','estudios.btnCobranza-status')
                ->rawColumns(['date','btn','on-off'])
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

        if($datosPaciente->estudiostemps_status != 3){
            $doctoresInt = DB::table('intestudios')
                            ->join('estudios','estudios.id','=','intestudios.id_estudio_fk')
                            ->join('doctors','doctors.id','=','intestudios.id_doctor_fk')
                            ->join('estudiostemps','estudiostemps.folio','=','intestudios.id_cobranza_fk')
                            ->select('intestudios.id','estudios.dscrpMedicosPro',DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop,' ',doctors.doctor_apellidom) AS doctor"))
                            ->where('estudiostemps.id',$id)
                            ->get();

        }else{
            $doctoresInt = DB::table('intestudios')
                            ->join('estudios','estudios.id','=','intestudios.id_estudio_fk')
                            ->join('doctors','doctors.id','=','intestudios.id_doctor_fk')
                            ->join('cobranza','cobranza.folio','=','intestudios.id_cobranza_fk')
                            ->select('intestudios.id','estudios.dscrpMedicosPro',DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop,' ',doctors.doctor_apellidom) AS doctor"))
                            ->where('cobranza.id',$id)
                            ->get();
        }

        return view('estudios.cobranza-paciente',compact('datosPaciente','doctores','tipoPac','empTrans','doctorInter','descripcionEstudios','empRealiza','empEnt','doctoresInt'));
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

    //Funciones de Catálogos (Estudios Invididuales)
    public function showCatalogo(){
        $listEstudios = Estudios::join('cat_estudios','cat_estudios.id','=','id_estudio_fk')
                                ->join('tipo_ojos','tipo_ojos.id','=','id_ojo_fk')
                                ->select('estudios.id','cat_estudios.descripcion','tipo_ojos.nombretipo_ojo','estudios.dscrpMedicosPro','estudios.paquete')
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
        $nvoEstudio->paquete = $request->paqEst;
        $nvoEstudio->save();

        $listEstudios = Estudios::join('cat_estudios','cat_estudios.id','=','id_estudio_fk')
                                ->join('tipo_ojos','tipo_ojos.id','=','id_ojo_fk')
                                ->select('estudios.id','cat_estudios.descripcion','tipo_ojos.nombretipo_ojo','estudios.dscrpMedicosPro','estudios.paquete')
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
            'paqEst' => 'required',
        ],[
            'estudioGral.required' => 'Selecccione el estudio.',
            'tipoOjo.required' => 'Seleccione el tipo de ojo.',
            'dscrpMedicosPro.required' => 'Ingrese la descripción del estudio.',
            'paqEst.required' => 'Seleccione si el estudio es un paquete.',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $duplicados = Estudios::where([
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
            'paquete' => $request->paqEst,
            'created_at' => $fechaInsert,
            'updated_at' => $fechaInsert
        ]);

        $listEstudios = Estudios::join('cat_estudios','cat_estudios.id','=','id_estudio_fk')
                                ->join('tipo_ojos','tipo_ojos.id','=','id_ojo_fk')
                                ->select('estudios.id','cat_estudios.descripcion','tipo_ojos.nombretipo_ojo','estudios.dscrpMedicosPro','estudios.paquete')
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
}
