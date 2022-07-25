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
        $validator = Validator::make($request->all(),[
            'folioHoja'  => 'required',
            'fechaHoja' => 'required',
            'doctorHoja' => 'required',
            'cirugia' => 'required',
            'pacienteHoja' => 'required',
            'tipoPacienteHoja' => 'required'
        ],[
            'folioHoja.required' => 'Ingresa el folio del detalle de consumo.',
            'fechaHoja.required' => 'Selecciona la fecha de la cirugía.',
            'doctorHoja.required' => 'Selecciona el doctor que requiere el detalle de consumo.',
            'cirugia.required' => 'Selecciona el tipo de cirugía.',
            'pacienteHoja.required' => 'Ingresa el nombre del paciente.',
            'tipoPacienteHoja.required' => 'Selecciona el tipo de paciente (Interno o Externo).' 
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }else{
            $doctores = Doctor::where('id','<>','1')->get();
            $tipoPaciente = TipoPaciente::all();
            
            $contarFolio = DB::table('detalle_consumos')
                            ->where('folio','=',$request->folioHoja)
                            ->count();
    
            if($contarFolio != 0){
                return back()->withErrors('El folio ya se encuentra registrado.');
            }else{
                $sumImporte = DB::table('detalletemps')->sum('importe');

                $porcentajeComision = DB::table('comisiones_doctores')
                                    ->where([
                                        ['id_doctor_fk','=',$request->doctorHoja],
                                        ['id_tipoPaciente_fk','=',$request->tipoPacienteHoja]
                                    ])
                                    ->select('porcentaje','id_metodoPago_fk')
                                    ->get();

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
                
                $fechaInsert = now()->toDateString();
                DB::table('detalle_consumos')->insert([
                    'id_doctor_fk' => $request->doctorHoja,
                    'folio' => $request->folioHoja,
                    'fechaElaboracion' => $request->fechaHoja,
                    'paciente' => $request->pacienteHoja,
                    'tipoPaciente' => $request->tipoPacienteHoja,
                    'cantidadEfe' => $totalEfectivo,
                    'cantidadTrans' => $totalTrans,
                    'TPV' => $totalTPV,
                    'cirugia' => $request->cirugia,
                    'statusHoja' => 'Pendiente',
                    'created_at' => $fechaInsert,
                    'updated_at' => $fechaInsert
                ]);
    
                //Seleccionar datos de temporal
                $datosDC = DB::table('detalletemps')->select('codigo','descripcion','um','cantidad','precio_unitario','importe')->get();
                
                //Seleccionar ID de la principal
                $select2 = DB::table('detalle_consumos')->select('id')->orderBy('id','desc')->first();
    
                //Insertar datos de la temporal a la principal
                foreach($datosDC as $datos){
                    DB::table('detalle_adicional')->insert([
                        'id_detalleConsumo_FK' => $select2->id,
                        'codigo' => $datos->codigo,
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
                                    ,'detalle_consumos.folio'
                                    ,'detalle_consumos.fechaElaboracion'
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
        
                /*Mail::send('emails.messageReceived', compact('data'), function ($mail) use ($pdf) {
                    $mail->to('jpom_prime@hotmail.com');
                    //$mail->to($data->doctor_email);
                    $mail->subject('Detalle de Consumo');
                    $mail->attachData($pdf->output(), 'detalleConsumo.pdf');
                });*/
            }
    
            return view('detalleC.subirarchivoD', compact('doctores','tipoPaciente'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request){
        return datatables()->eloquent(DetalleTemp::where('codigo','!=','null'))->toJson();
    }

    public function viewHojas(Request $request){
        $doctores = Doctor::where('id','!=',1)->get();
        $tipoPaciente = TipoPaciente::all();
        $hojasConsumo = DB::table('detalle_consumos')
                        ->join('doctors','doctors.id','=','id_doctor_fk')
                        ->join('tipo_pacientes','tipo_pacientes.id','=','tipoPaciente')
                        ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                    ,'detalle_consumos.folio'
                                    ,'detalle_consumos.fechaElaboracion'
                                    ,'detalle_consumos.paciente'
                                    ,'tipo_pacientes.nombretipo_paciente'
                                    ,'detalle_consumos.cantidadEfe'
                                    ,'detalle_consumos.id as id_detalle'
                                    ,'detalle_consumos.cirugia')->get();
                        
        return view('detalleC.mostrarHojasConsumo', compact('doctores','tipoPaciente','hojasConsumo'));
    }

    public function mostrarHojas(Request $request){
        $doctores = Doctor::where('id','!=',1)->get();
        $tipoPaciente = TipoPaciente::all();
        $hojasConsumo = DB::table('detalle_consumos')
                        ->join('doctors','doctors.id','=','id_doctor_fk')
                        ->join('tipo_pacientes','tipo_pacientes.id','=','tipoPaciente')
                        ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                    ,'detalle_consumos.folio'
                                    ,'detalle_consumos.fechaElaboracion'
                                    ,'detalle_consumos.paciente'
                                    ,'tipo_pacientes.nombretipo_paciente'
                                    ,'detalle_consumos.cantidadEfe'
                                    ,'detalle_consumos.id as id_detalle'
                                    ,'detalle_consumos.cirugia')
                        ->where('doctors.id','=',$request->slctDoctor)
                        ->whereBetween('fechaElaboracion',[$request->fechaInicio,$request->fechaFin])
                        ->get();
        
        return view('detalleC.mostrarHojasConsumo', compact('doctores','hojasConsumo','tipoPaciente'));
    }

    public function editHojaConsumo($id){
        $doctores = Doctor::where('id','!=',1)->get();
        $tipoPaciente = TipoPaciente::all();
        $data = DB::table('detalle_consumos')->where('detalle_consumos.id','=',$id)->first();

        return view('detalleC.edithojaconsumo', compact('data','doctores','tipoPaciente'));
    }

    public function updtHoja(Request $request){
        $doctores = Doctor::where('id','!=',1)->get();
        $tipoPaciente = TipoPaciente::all();
        $nvoEmpleado = DB::table('detalle_consumos')
                            ->where('id','=',$request->idHoja)
                            ->update(['id_doctor_fk' => $request->doctorHoja,
                                    'fechaElaboracion' => $request->fechaHoja,
                                    'folio' => $request->folioHoja,
                                    'paciente' => $request->pacienteHoja,
                                    'tipoPaciente' => $request->tipoPacienteHoja,
                                    'cirugia' => $request->cirugia,
                                    'statusHoja' => $request->statusHoja
                            ]);

        return view('detalleC.mostrarHojasConsumo', compact('doctores','tipoPaciente'));
    }

    public function exportarPDF($id){
        $data = DB::table('detalle_consumos')
                            ->join('doctors','doctors.id','=','id_doctor_fk')
                            ->join('tipo_pacientes','tipo_pacientes.id','=','tipoPaciente')
                            ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                    ,'detalle_consumos.folio'
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
                                        ,'comisiones_doctores.id')
                                ->get();
        
        $catMetodoPago = DB::table('cat_metodo_pago')
                            ->get();
                            
        $catTipoPaciente = DB::table('tipo_pacientes')
                                ->get();

        $catDoctores = DB::table('doctors')
                            ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                    ,'id')
                            ->where([
                                ['doctor_status','=','A'],
                                ['id','!=',1]
                            ])
                            ->get();

        return view('catalogos.porcentajes.catporcentajes', compact('porcentajeDoctores','catMetodoPago','catTipoPaciente','catDoctores'));
    }

    public function createPorcentaje(Request $request){
        $validator = Validator::make($request->all(),[
            'doctorId' => 'required',
            'metodoPago' => 'required',
            'tipoPaciente' => 'required',
            'porcentajeDoctor' => 'required',
        ],[
            'doctorId.required' => 'Seleccciona un Doctor',
            'metodoPago.required' => 'Selecciona un Método de Pago',
            'tipoPaciente.required' => 'Selecciona el Tipo de Paciente',
            'porcentajeDoctor.required' => 'Ingresa el Porcentaje',
        ]);
        
        $fechaInsert = now()->toDateString();
        DB::table('comisiones_doctores')->insert([
            'id_doctor_fk' => $request->doctorId,
            'id_tipoPaciente_fk' => $request->tipoPaciente,
            'id_metodoPago_fk' => $request->metodoPago,
            'porcentaje' => $request->porcentajeDoctor,
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
                                        ,'comisiones_doctores.id')
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
        $porcentajeDoctores = DB::table('comisiones_doctores')
                                ->join('doctors','doctors.id','=','comisiones_doctores.id_doctor_fk')
                                ->join('tipo_pacientes','tipo_pacientes.id','=','comisiones_doctores.id_tipoPaciente_fk')
                                ->join('cat_metodo_pago','cat_metodo_pago.id','=','comisiones_doctores.id_metodoPago_fk')
                                ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                        ,'tipo_pacientes.nombretipo_paciente'
                                        ,'cat_metodo_pago.descripcion'
                                        ,'comisiones_doctores.porcentaje')
                                ->get();
        
        $catMetodoPago = DB::table('cat_metodo_pago')
                            ->get();
                            
        $catTipoPaciente = DB::table('tipo_pacientes')
                                ->get();

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
                                        ,'comisiones_doctores.id')
                                ->where('comisiones_doctores.id','=',$id)
                                ->first();

        return view('catalogos.porcentajes.editporcentaje', compact('porcentajeDoctores','catMetodoPago','catTipoPaciente','catDoctores','porcentajeInfo'));
    }

    public function updtPorcentaje(Request $request){
        $nvoEmpleado = DB::table('comisiones_doctores')
                            ->where('id','=',$request->idComision)
                            ->update(['id_doctor_fk' => $request->doctorId,
                                    'id_tipoPaciente_fk' => $request->tipoPaciente,
                                    'id_metodoPago_fk' => $request->metodoPago,
                                    'porcentaje' => $request->porcentajeDoctor
                            ]);

        $porcentajeDoctores = DB::table('comisiones_doctores')
                                ->join('doctors','doctors.id','=','comisiones_doctores.id_doctor_fk')
                                ->join('tipo_pacientes','tipo_pacientes.id','=','comisiones_doctores.id_tipoPaciente_fk')
                                ->join('cat_metodo_pago','cat_metodo_pago.id','=','comisiones_doctores.id_metodoPago_fk')
                                ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                        ,'tipo_pacientes.nombretipo_paciente'
                                        ,'cat_metodo_pago.descripcion'
                                        ,'comisiones_doctores.porcentaje'
                                        ,'comisiones_doctores.id')
                                ->get();
        
        $catMetodoPago = DB::table('cat_metodo_pago')
                            ->get();
                            
        $catTipoPaciente = DB::table('tipo_pacientes')
                                ->get();

        $catDoctores = DB::table('doctors')
                            ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                    ,'id')
                            ->where([
                                ['doctor_status','=','A'],
                                ['id','!=',1]
                            ])
                            ->get();

        return view('catalogos.porcentajes.catporcentajes', compact('porcentajeDoctores','catMetodoPago','catTipoPaciente','catDoctores'));
    }

    public function deletePorcentaje(Request $request){
        $delEComision = DB::table('comisiones_doctores')->where('id','=',$request->idComision)->delete();
        
        return redirect()->route('mostrarPorcentajes.show');
    }
}