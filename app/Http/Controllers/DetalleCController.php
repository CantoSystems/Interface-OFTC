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

class DetalleCController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $doctores = Doctor::where('id','<>','1')->get();
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
            'metodoPagoHoja' => 'required',
            'pacienteHoja' => 'required',
            'tipoPacienteHoja' => 'required'
        ],[
            'folioHoja.required' => 'Ingresa el folio del detalle de consumo.',
            'fechaHoja.required' => 'Selecciona la fecha de elaboración.',
            'doctorHoja.required' => 'Selecciona el doctor que requiere el detalle de consumo.',
            'metodoPagoHoja.required' => 'Selecciona el método de pago del doctor.',
            'pacienteHoja.required' => 'Ingresa el nombre del paciente.',
            'tipoPacienteHoja.required' => 'Selecciona el tipo de paciente (Interno o Externo).' 
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }else{
            $doctores = Doctor::where('id','<>','1')->get();
            $metodoPago = DB::table('cat_metodo_pago')->where('statusMetodoPago','=','A')->get();
            $tipoPaciente = TipoPaciente::all();
            
            $contarFolio = DB::table('detalle_consumos')
                           ->where('folio','=',$request->folioHoja)
                           ->count();
    
            if($contarFolio != 0){
                return back()->withErrors('El folio ya se encuentra registrado.');
            }else{
                $datosDC = DB::table('detalletemps')
                           ->sum('importe');
                
                $porcentajeComision = DB::table('comisiones_doctores')
                                    ->where([
                                        ['id_doctor_fk','=',$request->doctorHoja],
                                        ['id_tipoPaciente_fk','=',$request->tipoPacienteHoja],
                                        ['id_metodoPago_fk','=',$request->metodoPagoHoja]
                                    ])
                                    ->select('porcentaje')
                                    ->first();
                
                $finalPorcentaje = (($datosDC * $porcentajeComision->porcentaje)/100) + $datosDC;
    
                //Insertar en la tabla principal
                $fechaInsert = now()->toDateString();
                DB::table('detalle_consumos')->insert([
                    'id_doctor_fk' => $request->doctorHoja,
                    'folio' => $request->folioHoja,
                    'fechaElaboracion' => $request->fechaHoja,
                    'paciente' => $request->pacienteHoja,
                    'tipoPaciente' => $request->tipoPacienteHoja,
                    'cantidadTotal' => $finalPorcentaje,
                    'metodoPago' => $request->metodoPagoHoja,
                    'created_at' => $fechaInsert,
                    'updated_at' => $fechaInsert
                ]);
    
                //Seleccionar datos de temporal
                $datosDC = DB::table('detalletemps')
                        ->select('codigo','descripcion','um','cantidad','precio_unitario','importe')->get();
                
                //Seleccionar ID de la principal
                $select2 = DB::table('detalle_consumos')
                        ->select('id')
                        ->orderBy('id','desc')
                        ->first();
    
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
                            ->join('cat_metodo_pago','cat_metodo_pago.id','=','metodoPago')
                            ->select(DB::raw("CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop) AS Doctor")
                                    ,'detalle_consumos.folio'
                                    ,'detalle_consumos.fechaElaboracion'
                                    ,'detalle_consumos.paciente'
                                    ,'tipo_pacientes.nombretipo_paciente'
                                    ,'cat_metodo_pago.descripcion')
                            ->where('detalle_consumos.id','=',$select2->id)
                            ->first();
    
                $data2 = DB::table('detalle_adicional')
                             ->where('id_detalleConsumo_FK','=',$select2->id)
                             ->get();
    
                $pdf = \PDF::loadView('pdf.vista-pdf', compact('data','data2','datosDC','finalPorcentaje'));
    
                Mail::send('emails.messageReceived', compact('data'), function ($mail) use ($pdf) {
                    $mail->to('jpomprime@gmail.com');
                    $mail->subject('Detalle de Consumo');
                    $mail->attachData($pdf->output(), 'detalleConsumo.pdf');
                });
            }
    
            return view('detalleC.subirarchivoD', compact('doctores','metodoPago','tipoPaciente'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request){
        return datatables()
                ->eloquent(DetalleTemp::where('codigo','!=','null'))
                ->toJson();
    }
}