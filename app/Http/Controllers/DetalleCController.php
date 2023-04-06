<?php

namespace App\Http\Controllers;

use DataTables;
use DB;
use Mail;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DetalleCImport;

use App\Models\DetalleTemp;
use App\Models\CatMetodoPago;
use App\Models\Doctor;
use App\Models\TipoPaciente;
use App\Mail\MessageReceived;

session_start();

class DetalleCController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $doctores = Doctor::whereNotIn('id',[1,2])->get();
        $metodoPago = DB::table('cat_metodo_pago')->where('statusMetodoPago','=','A')->get();
        $tipoPaciente = TipoPaciente::all();

        return view('detalleC.subirarchivoD', compact('doctores','metodoPago','tipoPaciente'));
    }

    public function importExcel(Request $request){
        if($request->hasFile('file')){
            $contarDatos = DB::table('detalletemps')->count();
            if($contarDatos != 0){
                DB::table('detalletemps')->truncate();
            }
            $file = $request->file('file');
            Excel::import(new DetalleCImport, $file);

            return redirect()->route('subirarchivoD.index');
        }

        return back()->withErrors('No se ha adjuntado ningún archivo.');
    }   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        $checkAdicional = DB::table('detalletemps')->count();
        if($checkAdicional != 0){
            $validator = Validator::make($request->all(),[
                'fechaHoja' => 'required',
                'doctorHoja' => 'required',
                'cirugia' => 'required',
                'pacienteHoja' => 'required',
                'tipoPacienteHoja' => 'required',
                'registroC' => 'required'
            ],[
                'fechaHoja.required' => 'Selecciona la fecha de la cirugía.',
                'doctorHoja.required' => 'Selecciona el doctor que requiere el detalle de consumo.',
                'cirugia.required' => 'Ingresa el tipo de cirugía.',
                'pacienteHoja.required' => 'Ingresa el nombre del paciente.',
                'tipoPacienteHoja.required' => 'Selecciona el tipo de paciente (Interno o Externo).',
                'registroC.required' => 'Selecciona si la cirugía es especial o no.'
            ]);

            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }else{
                $doctores = Doctor::where('id','<>','1')->get();
                $tipoPaciente = TipoPaciente::all();

                $contarFolio = DB::table('detalle_consumos')->where('id','=',$request->folioHoja)->count();

                if($contarFolio != 0){
                    return back()->withErrors('El folio ya se encuentra registrado.');
                }else{
                    if($request->registroC == "S"){
                        $porcentajeComision = DB::table('comisiones_doctores')
                                                ->where([
                                                    ['id_doctor_fk','=',$request->doctorHoja],
                                                    ['tipoPorcentaje','=','S'],
                                                    ['id_tipoPaciente_fk','=',$request->tipoPacienteHoja]
                                                ])
                                                ->select('porcentaje','id_metodoPago_fk')
                                                ->get();
                    }else{
                        $porcentajeComision = DB::table('comisiones_doctores')
                                                ->where([
                                                    ['id_doctor_fk','=',$request->doctorHoja],
                                                    ['id_tipoPaciente_fk','=',$request->tipoPacienteHoja],
                                                    ['tipoPorcentaje','=','N']
                                                ])
                                                ->select('porcentaje','id_metodoPago_fk')
                                                ->get();
                    }

                    $sumImporte = DB::table('detalletemps')->sum('importe');

                    foreach($porcentajeComision as $porcentajeComision){
                        if($porcentajeComision->id_metodoPago_fk == 1){
                            $totalEfectivo = (($sumImporte * $porcentajeComision->porcentaje)/100) + $sumImporte;
                            if($totalEfectivo%100 > 50){
                                $totalEfectivo = round($totalEfectivo,-2);
                            }else{
                                $totalEfectivo = floor($totalEfectivo - ($totalEfectivo%100));
                            }
                        }else if($porcentajeComision->id_metodoPago_fk == 2){
                            $totalTrans = (($sumImporte * $porcentajeComision->porcentaje)/100) + $sumImporte;
                            if($totalTrans%100 > 50){
                                $totalTrans = round($totalTrans,-2);
                            }else{
                                $totalTrans = floor($totalTrans - ($totalTrans%100));
                            }
                        }else{
                            $totalTPV = (($sumImporte * $porcentajeComision->porcentaje)/100) + $sumImporte;
                            if($totalTPV%100 > 50){
                                $totalTPV = round($totalTPV,-2);
                            }else{
                                $totalTPV = floor($totalTPV - ($totalTPV%100));
                            }
                        }
                    }

                    if(!isset($totalEfectivo) || !isset($totalTPV) || !isset($totalTrans)){
                        return back()->withErrors('El doctor no cuenta con un porcentaje configurado con las especificaciones ingresadas. Favor de verificar la información.');
                    }

                    $fechaInsert = now()->toDateString();
                    DB::table('detalle_consumos')->insert([
                        'id_doctor_fk' => $request->doctorHoja,
                        'fechaElaboracion' => $request->fechaHoja,
                        'paciente' => $request->pacienteHoja,
                        'tipoPaciente' => $request->tipoPacienteHoja,
                        'cantidadEfe' => $totalEfectivo,
                        'cantidadTrans' => $totalTrans,
                        'TPV' => $totalTPV,
                        'cirugia' => $request->cirugia,
                        'tipoCirugia' => $request->registroC,
                        'statusHoja' => 'Pendiente',
                        'created_at' => $fechaInsert,
                        'updated_at' => $fechaInsert
                    ]);
        
                    //Seleccionar datos de temporal
                    $datosDC = DB::table('detalletemps')->select('descripcion','um','cantidad','precio_unitario','importe')->get();
                    
                    //Seleccionar ID de la principal
                    $select2 = DB::table('detalle_consumos')->select('id')->orderBy('id','desc')->first();
        
                    //Insertar datos de la temporal a la principal
                    foreach($datosDC as $datos){
                        DB::table('detalle_adicional')->insert([
                            'id_detalleConsumo_FK' => $select2->id,
                            'descripcion' => $datos->descripcion,
                            'um' => $datos->um,
                            'cantidad' => $datos->cantidad,
                            'precio_unitario' => $datos->precio_unitario,
                            'importe' => $datos->importe,
                            'created_at' => $fechaInsert,
                            'updated_at' => $fechaInsert
                        ]);
                    }
                    DB::table('detalletemps')->truncate();
        
                    $data = DB::table('detalle_consumos')
                                ->join('doctors','doctors.id','=','id_doctor_fk')
                                ->join('tipo_pacientes','tipo_pacientes.id','=','tipoPaciente')
                                ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                        ,'detalle_consumos.fechaElaboracion'
                                        ,'detalle_consumos.id'
                                        ,'detalle_consumos.paciente'
                                        ,'tipo_pacientes.nombretipo_paciente'
                                        ,'doctors.doctor_email'
                                        ,'detalle_consumos.cantidadEfe'
                                        ,'detalle_consumos.cantidadTrans'
                                        ,'detalle_consumos.TPV'
                                        ,'detalle_consumos.cirugia')
                                ->where('detalle_consumos.id','=',$select2->id)
                                ->first();
        
                    $data2 = DB::table('detalle_adicional')
                                ->where('id_detalleConsumo_FK','=',$select2->id)
                                ->get();
        
                    $pdf = \PDF::loadView('pdf.vista-pdf', compact('data','data2'));
                }
                
                return view('detalleC.subirarchivoD', compact('doctores','tipoPaciente'));
            }
        }else{
            return back()->withErrors('No se ha importado ninguna hoja de consumo.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request){
        return datatables()->eloquent(DetalleTemp::where('id','!=','null'))->toJson();
    }

    public function viewHojas(Request $request){
        DB::table('historico_detalle_consumo')->truncate();
        $dateInicio = Carbon::now()->format('Y-m-01');
        $dateFin = Carbon::now()->format('Y-m-t');
        $doctores = Doctor::where('id','!=',1)->get();
        $tipoPaciente = TipoPaciente::all();
        $hojasConsumo = DB::table('detalle_consumos')
                        ->join('doctors','doctors.id','=','id_doctor_fk')
                        ->join('tipo_pacientes','tipo_pacientes.id','=','tipoPaciente')
                        ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                    ,'detalle_consumos.id as id_detalle'
                                    ,'detalle_consumos.id_doctor_fk'
                                    ,'detalle_consumos.fechaElaboracion'
                                    ,'detalle_consumos.paciente'
                                    ,'detalle_consumos.cirugia'
                                    ,'detalle_consumos.tipoCirugia'
                                    ,'detalle_consumos.tipoPaciente'
                                    ,'detalle_consumos.cantidadEfe'
                                    ,'detalle_consumos.cantidadTrans'
                                    ,'detalle_consumos.TPV'
                                    ,'detalle_consumos.statusHoja'
                                    ,'tipo_pacientes.nombretipo_paciente')
                        ->whereBetween('fechaElaboracion',[$dateInicio,$dateFin])
                        ->get();
        
        foreach($hojasConsumo as $hojas){
            DB::table('historico_detalle_consumo')->insert([
                'id_hoja' => $hojas->id_detalle,
                'id_doctor_fk' => $hojas->id_doctor_fk,
                'fechaElaboracion' => $hojas->fechaElaboracion,
                'paciente' => $hojas->paciente,
                'cirugia' => $hojas->cirugia,
                'tipoCirugia' => $hojas->tipoCirugia,
                'tipoPaciente' => $hojas->tipoPaciente,
                'cantidadEfe' => $hojas->cantidadEfe,
                'cantidadTrans' => $hojas->cantidadTrans,
                'TPV' => $hojas->TPV,
                'statusHoja' => $hojas->statusHoja
            ]);
        }
                        
        return view('detalleC.mostrarHojasConsumo', compact('doctores','tipoPaciente','hojasConsumo'));
    }

    public function mostrarHojas(Request $request){
        $_SESSION["slctDoctor"] = $request->slctDoctor;
        $_SESSION["fechaInicio"] = $request->fechaInicio;
        $_SESSION["fechaFin"] = $request->fechaFin;
        
        DB::table('historico_detalle_consumo')->truncate();

        $validator = Validator::make($request->all(),[
            'slctDoctor' => 'required',
            'fechaInicio' => 'required',
            'fechaFin' => 'required',
        ],[
            'slctDoctor.required' => 'Selecciona Dr.',
            'fechaInicio.required' => 'Selecciona Fecha de Inicio',
            'fechaFin.required' => 'Selecciona Fecha Fin',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $doctores = Doctor::where('id','!=',1)->get();
        $tipoPaciente = TipoPaciente::all();

        if($request->slctDoctor != 'TODOS'){
            $hojasConsumo = DB::table('detalle_consumos')
            ->join('doctors','doctors.id','=','id_doctor_fk')
            ->join('tipo_pacientes','tipo_pacientes.id','=','tipoPaciente')
            ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                        ,'detalle_consumos.id as id_detalle'
                        ,'detalle_consumos.id_doctor_fk'
                        ,'detalle_consumos.fechaElaboracion'
                        ,'detalle_consumos.paciente'
                        ,'detalle_consumos.cirugia'
                        ,'detalle_consumos.tipoCirugia'
                        ,'detalle_consumos.tipoPaciente'
                        ,'detalle_consumos.cantidadEfe'
                        ,'detalle_consumos.cantidadTrans'
                        ,'detalle_consumos.TPV'
                        ,'detalle_consumos.statusHoja'
                        ,'tipo_pacientes.nombretipo_paciente')
            ->where('doctors.id','=',$request->slctDoctor)
            ->whereBetween('fechaElaboracion',[$request->fechaInicio,$request->fechaFin])
            ->get();
        }else{
            $hojasConsumo = DB::table('detalle_consumos')
            ->join('doctors','doctors.id','=','id_doctor_fk')
            ->join('tipo_pacientes','tipo_pacientes.id','=','tipoPaciente')
            ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                        ,'detalle_consumos.id as id_detalle'
                        ,'detalle_consumos.id_doctor_fk'
                        ,'detalle_consumos.fechaElaboracion'
                        ,'detalle_consumos.paciente'
                        ,'detalle_consumos.cirugia'
                        ,'detalle_consumos.tipoCirugia'
                        ,'detalle_consumos.tipoPaciente'
                        ,'detalle_consumos.cantidadEfe'
                        ,'detalle_consumos.cantidadTrans'
                        ,'detalle_consumos.TPV'
                        ,'detalle_consumos.statusHoja'
                        ,'tipo_pacientes.nombretipo_paciente')
            ->whereBetween('fechaElaboracion',[$request->fechaInicio,$request->fechaFin])
            ->get();
        }

        foreach($hojasConsumo as $hojas){
            DB::table('historico_detalle_consumo')->insert([
                'id_hoja' => $hojas->id_detalle,
                'id_doctor_fk' => $hojas->id_doctor_fk,
                'fechaElaboracion' => $hojas->fechaElaboracion,
                'paciente' => $hojas->paciente,
                'cirugia' => $hojas->cirugia,
                'tipoCirugia' => $hojas->tipoCirugia,
                'tipoPaciente' => $hojas->tipoPaciente,
                'cantidadEfe' => $hojas->cantidadEfe,
                'cantidadTrans' => $hojas->cantidadTrans,
                'TPV' => $hojas->TPV,
                'statusHoja' => $hojas->statusHoja
            ]);
        }
        
        return view('detalleC.mostrarHojasConsumo', compact('doctores','hojasConsumo','tipoPaciente'));
    }

    public function editHojaConsumo($id){
        $doctores = Doctor::whereNotIn('id',[1,2])->get();
        $tipoPaciente = TipoPaciente::all();
        $data = DB::table('detalle_consumos')->where('detalle_consumos.id','=',$id)->first();

        return view('detalleC.edithojaconsumo', compact('data','doctores','tipoPaciente'));
    }

    public function updtHoja(Request $request){
        $arregloSesion = new Request(array(
            "slctDoctor" => $_SESSION["slctDoctor"],
            "fechaInicio" => $_SESSION["fechaInicio"],
            "fechaFin" => $_SESSION["fechaFin"],
        ));
        
        $doctores = Doctor::where('id','!=',1)->get();
        $tipoPaciente = TipoPaciente::all();

        if($request->registroC == "S"){
            $porcentajeComision = DB::table('comisiones_doctores')
                                    ->where([
                                        ['id_doctor_fk','=',$request->doctorHoja],
                                        ['tipoPorcentaje','=','S'],
                                        ['id_tipoPaciente_fk','=',$request->tipoPacienteHoja]])
                                    ->select('porcentaje','id_metodoPago_fk')
                                    ->get();
        }else{
            $porcentajeComision = DB::table('comisiones_doctores')
                                    ->where([
                                        ['id_doctor_fk','=',$request->doctorHoja],
                                        ['id_tipoPaciente_fk','=',$request->tipoPacienteHoja]])
                                    ->select('porcentaje','id_metodoPago_fk')
                                    ->get();
        }

        $sumImporte = DB::table('detalle_adicional')
                            ->where('id_detalleConsumo_FK','=',$request->idHoja)
                            ->sum('importe');

        foreach($porcentajeComision as $porcentajeComision){
            if($porcentajeComision->id_metodoPago_fk == 1){
                $totalEfectivo = (($sumImporte * $porcentajeComision->porcentaje)/100) + $sumImporte;
                if($totalEfectivo%100 > 50){
                    $totalEfectivo = round($totalEfectivo,-2);
                }else{
                    $totalEfectivo = floor($totalEfectivo - ($totalEfectivo%100));
                }
            }else if($porcentajeComision->id_metodoPago_fk == 2){
                $totalTrans = (($sumImporte * $porcentajeComision->porcentaje)/100) + $sumImporte;
                if($totalTrans%100 > 50){
                    $totalTrans = round($totalTrans,-2);
                }else{
                    $totalTrans = floor($totalTrans - ($totalTrans%100));
                }
            }else{
                $totalTPV = (($sumImporte * $porcentajeComision->porcentaje)/100) + $sumImporte;
                if($totalTPV%100 > 50){
                    $totalTPV = round($totalTPV,-2);
                }else{
                    $totalTPV = floor($totalTPV - ($totalTPV%100));
                }
            }
        }

        if(!isset($totalEfectivo) || !isset($totalTPV) || !isset($totalTrans)){
            return back()->withErrors('El doctor no cuenta con un porcentaje configurado con las especificaciones ingresadas. Favor de verificar la información.');
        }

        $nvoEmpleado = DB::table('detalle_consumos')
                            ->where('id','=',$request->idHoja)
                            ->update(['id_doctor_fk' => $request->doctorHoja,
                                    'fechaElaboracion' => $request->fechaHoja,
                                    'paciente' => $request->pacienteHoja,
                                    'tipoPaciente' => $request->tipoPacienteHoja,
                                    'cirugia' => $request->cirugia,
                                    'statusHoja' => $request->statusHoja,
                                    'cantidadEfe' => $totalEfectivo,
                                    'cantidadTrans' => $totalTrans,
                                    'TPV' => $totalTPV,
                                    'tipoCirugia' => $request->registroC
                            ]);

        $hojasConsumo = DB::table('detalle_consumos')
                            ->join('doctors','doctors.id','=','id_doctor_fk')
                            ->join('tipo_pacientes','tipo_pacientes.id','=','tipoPaciente')
                            ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                        ,'detalle_consumos.id as id_detalle'
                                        ,'detalle_consumos.id_doctor_fk'
                                        ,'detalle_consumos.fechaElaboracion'
                                        ,'detalle_consumos.paciente'
                                        ,'detalle_consumos.cirugia'
                                        ,'detalle_consumos.tipoCirugia'
                                        ,'detalle_consumos.tipoPaciente'
                                        ,'detalle_consumos.cantidadEfe'
                                        ,'detalle_consumos.cantidadTrans'
                                        ,'detalle_consumos.TPV'
                                        ,'detalle_consumos.statusHoja'
                                        ,'tipo_pacientes.nombretipo_paciente')
                            ->get();

        $updtHistorico = DB::table('historico_detalle_consumo')
                            ->where('id_hoja','=',$request->idHoja)
                            ->update(['id_doctor_fk' => $request->doctorHoja,
                                'fechaElaboracion' => $request->fechaHoja,
                                'paciente' => $request->pacienteHoja,
                                'tipoPaciente' => $request->tipoPacienteHoja,
                                'cirugia' => $request->cirugia,
                                'statusHoja' => $request->statusHoja,
                                'cantidadEfe' => $totalEfectivo,
                                'cantidadTrans' => $totalTrans,
                                'TPV' => $totalTPV,
                                'tipoCirugia' => $request->registroC
                        ]);

        return $this->mostrarHojas($arregloSesion);
    }

    public function exportarPDF($id){
        $data = DB::table('detalle_consumos')
                            ->join('doctors','doctors.id','=','id_doctor_fk')
                            ->join('tipo_pacientes','tipo_pacientes.id','=','tipoPaciente')
                            ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                    ,'detalle_consumos.id'
                                    ,'detalle_consumos.fechaElaboracion'
                                    ,'detalle_consumos.paciente'
                                    ,'tipo_pacientes.nombretipo_paciente'
                                    ,'doctors.doctor_email'
                                    ,'detalle_consumos.cantidadEfe'
                                    ,'detalle_consumos.cantidadTrans'
                                    ,'detalle_consumos.TPV'
                                    ,'detalle_consumos.cirugia')
                            ->where('detalle_consumos.id','=',$id)
                            ->first();
    
        $data2 = DB::table('detalle_adicional')->where('id_detalleConsumo_FK','=',$id)->get();
        $pdf = \PDF::loadView('pdf.vista-pdf', compact('data','data2'));
        
        return $pdf->download('Hoja de Consumo.pdf');
    }

    public function showPorcentajes(){
        $porcentajeDoctores = DB::table('comisiones_doctores')
                                ->join('doctors','doctors.id','=','comisiones_doctores.id_doctor_fk')
                                ->join('tipo_pacientes','tipo_pacientes.id','=','comisiones_doctores.id_tipoPaciente_fk')
                                ->join('cat_metodo_pago','cat_metodo_pago.id','=','comisiones_doctores.id_metodoPago_fk')
                                ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                        ,'tipo_pacientes.nombretipo_paciente'
                                        ,'cat_metodo_pago.descripcion'
                                        ,'comisiones_doctores.porcentaje'
                                        ,'comisiones_doctores.id'
                                        ,'comisiones_doctores.tipoPorcentaje')
                                ->get();
        
        $catMetodoPago = DB::table('cat_metodo_pago')->get();
        $catTipoPaciente = DB::table('tipo_pacientes')->get();

        $catDoctores = DB::table('doctors')
                            ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                    ,'id')
                            ->where([
                                ['doctor_status','=','A'],
                                ['id','!=',1]
                            ])->get();
        
        return view('catalogos.porcentajes.catporcentajes', compact('porcentajeDoctores','catMetodoPago','catTipoPaciente','catDoctores'));
    }

    public function createPorcentaje(Request $request){
        $validator = Validator::make($request->all(),[
            'doctorId' => 'required',
            'metodoPago' => 'required',
            'tipoPaciente' => 'required',
            'porcentajeDoctor' => 'required',
            'registroC' => 'required'
        ],[
            'doctorId.required' => 'Seleccciona un Doctor.',
            'metodoPago.required' => 'Selecciona un Método de Pago.',
            'tipoPaciente.required' => 'Selecciona el Tipo de Paciente.',
            'porcentajeDoctor.required' => 'Ingresa el Porcentaje.',
            'registroC' => 'Selecciona si el Porcentaje es especial o no.'
        ]);
        
        $fechaInsert = now()->toDateString();
        DB::table('comisiones_doctores')->insert([
            'id_doctor_fk' => $request->doctorId,
            'id_tipoPaciente_fk' => $request->tipoPaciente,
            'id_metodoPago_fk' => $request->metodoPago,
            'porcentaje' => $request->porcentajeDoctor,
            'tipoPorcentaje' => $request->registroC,
            'created_at' => $fechaInsert,
            'updated_at' => $fechaInsert
        ]);

        $porcentajeDoctores = DB::table('comisiones_doctores')
                                ->join('doctors','doctors.id','=','comisiones_doctores.id_doctor_fk')
                                ->join('tipo_pacientes','tipo_pacientes.id','=','comisiones_doctores.id_tipoPaciente_fk')
                                ->join('cat_metodo_pago','cat_metodo_pago.id','=','comisiones_doctores.id_metodoPago_fk')
                                ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                        ,'tipo_pacientes.nombretipo_paciente'
                                        ,'cat_metodo_pago.descripcion'
                                        ,'comisiones_doctores.porcentaje'
                                        ,'comisiones_doctores.id'
                                        ,'comisiones_doctores.tipoPorcentaje')
                                ->get();

        $catMetodoPago = DB::table('cat_metodo_pago')->get();
        $catTipoPaciente = DB::table('tipo_pacientes')->get();

        $catDoctores = DB::table('doctors')
                            ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                    ,'id')
                            ->where([
                                ['doctor_status','=','A'],
                                ['id','!=',1]
                            ])->get();
        
        return view('catalogos.porcentajes.catporcentajes', compact('porcentajeDoctores','catMetodoPago','catTipoPaciente','catDoctores'));
    }

    public function showPorcentaje($id){
        $catMetodoPago = DB::table('cat_metodo_pago')->get();
        $catTipoPaciente = DB::table('tipo_pacientes')->get();

        $catDoctores = DB::table('doctors')
                            ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                    ,'id')
                            ->where([
                                ['doctor_status','=','A'],
                                ['id','!=',1]
                            ])
                            ->get();

        $porcentajeInfo = DB::table('comisiones_doctores')
                                ->join('doctors','doctors.id','=','comisiones_doctores.id_doctor_fk')
                                ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                        ,'comisiones_doctores.id_doctor_fk'
                                        ,'comisiones_doctores.id_tipoPaciente_fk'
                                        ,'comisiones_doctores.id_metodoPago_fk'
                                        ,'comisiones_doctores.porcentaje'
                                        ,'comisiones_doctores.id'
                                        ,'comisiones_doctores.tipoPorcentaje')
                                ->where('comisiones_doctores.id','=',$id)
                                ->first();

        return view('catalogos.porcentajes.editporcentaje', compact('catMetodoPago','catTipoPaciente','catDoctores','porcentajeInfo'));
    }

    public function updtPorcentaje(Request $request){
        $nvoPorcentaje = DB::table('comisiones_doctores')
                            ->where('id','=',$request->idComision)
                            ->update(['id_doctor_fk' => $request->doctorId,
                                    'id_tipoPaciente_fk' => $request->tipoPaciente,
                                    'id_metodoPago_fk' => $request->metodoPago,
                                    'porcentaje' => $request->porcentajeDoctor,
                                    'tipoPorcentaje' => $request->registroC
                            ]);

        $porcentajeDoctores = DB::table('comisiones_doctores')
                                ->join('doctors','doctors.id','=','comisiones_doctores.id_doctor_fk')
                                ->join('tipo_pacientes','tipo_pacientes.id','=','comisiones_doctores.id_tipoPaciente_fk')
                                ->join('cat_metodo_pago','cat_metodo_pago.id','=','comisiones_doctores.id_metodoPago_fk')
                                ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                        ,'tipo_pacientes.nombretipo_paciente'
                                        ,'cat_metodo_pago.descripcion'
                                        ,'comisiones_doctores.porcentaje'
                                        ,'comisiones_doctores.id'
                                        ,'comisiones_doctores.tipoPorcentaje')
                                ->get();
        
        $catMetodoPago = DB::table('cat_metodo_pago')->get();
        $catTipoPaciente = DB::table('tipo_pacientes')->get();

        $catDoctores = DB::table('doctors')
                            ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                    ,'id')
                            ->where([
                                ['doctor_status','=','A'],
                                ['id','!=',1]
                            ])->get();

        return view('catalogos.porcentajes.catporcentajes', compact('porcentajeDoctores','catMetodoPago','catTipoPaciente','catDoctores'));
    }

    public function deletePorcentaje(Request $request){
        $delEComision = DB::table('comisiones_doctores')->where('id','=',$request->idComision)->delete();
        return redirect()->route('mostrarPorcentajes.show');
    }

    public function deleteHoja(Request $request){
        $deleteHoja = DB::table('detalle_adicional')->where('id_detalleConsumo_FK','=',$request->idHojaConsumo)->delete();
        $deleteHoja2 = DB::table('detalle_consumos')->where('id','=',$request->idHojaConsumo)->delete();
        
        $doctores = Doctor::where('id','!=',1)->get();
        $tipoPaciente = TipoPaciente::all();
        $hojasConsumo = DB::table('detalle_consumos')
                        ->join('doctors','doctors.id','=','id_doctor_fk')
                        ->join('tipo_pacientes','tipo_pacientes.id','=','tipoPaciente')
                        ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                    ,'detalle_consumos.id as id_detalle'
                                    ,'detalle_consumos.id_doctor_fk'
                                    ,'detalle_consumos.fechaElaboracion'
                                    ,'detalle_consumos.paciente'
                                    ,'detalle_consumos.cirugia'
                                    ,'detalle_consumos.tipoCirugia'
                                    ,'detalle_consumos.tipoPaciente'
                                    ,'detalle_consumos.cantidadEfe'
                                    ,'detalle_consumos.cantidadTrans'
                                    ,'detalle_consumos.TPV'
                                    ,'detalle_consumos.statusHoja'
                                    ,'tipo_pacientes.nombretipo_paciente')
                        ->where('doctors.id','=',$request->slctDoctor)
                        ->whereBetween('fechaElaboracion',[$request->fechaInicio,$request->fechaFin])
                        ->get();
        
        return redirect()->route('viewHojas.show', compact('doctores','tipoPaciente','hojasConsumo'));
    }

    public function exportPDFGral(){
        $hojasConsumo = DB::table('historico_detalle_consumo')
                        ->join('doctors','doctors.id','=','id_doctor_fk')
                        ->join('tipo_pacientes','tipo_pacientes.id','=','tipoPaciente')
                        ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                    ,'historico_detalle_consumo.id_hoja'
                                    ,'historico_detalle_consumo.fechaElaboracion'
                                    ,'historico_detalle_consumo.paciente'
                                    ,'historico_detalle_consumo.cirugia'
                                    ,'historico_detalle_consumo.cantidadEfe'
                                    ,'historico_detalle_consumo.cantidadTrans'
                                    ,'historico_detalle_consumo.statusHoja'
                                    ,'tipo_pacientes.nombretipo_paciente')->get();

        $sumPendiente = DB::table('historico_detalle_consumo')
                            ->where('statusHoja','=','Pendiente')
                            ->sum('cantidadEfe');

        $sumPagado = DB::table('historico_detalle_consumo')
                        ->where('statusHoja','=','Pagado')
                        ->sum('cantidadEfe');

        $sumPendienteT = DB::table('historico_detalle_consumo')
                        ->where('statusHoja','=','Pendiente')
                        ->sum('cantidadTrans');

        $sumPagadoT = DB::table('historico_detalle_consumo')
                    ->where('statusHoja','=','Pagado')
                    ->sum('cantidadTrans');

        $pdf = \PDF::loadView('pdf.vista-gral-pdf', compact('hojasConsumo','sumPendiente','sumPagado','sumPendienteT','sumPagadoT'));

        return $pdf->download('Historico Hoja de Consumo.pdf');
    }
}