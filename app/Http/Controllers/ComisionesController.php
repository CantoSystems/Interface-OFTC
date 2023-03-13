<?php
 
namespace App\Http\Controllers;

use DB;

use App\Models\Comisiones;
use App\Models\Empleado;
use App\Models\Estudios;
use App\Models\Cobranza;
use App\Models\FechaCorte;

use App\Exports\ComisionesExport;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ComisionesController extends Controller{
    public function showComisiones(){
        $empleados = DB::table('empleados')
                        ->join('puestos','puestos.id','=','puesto_id')
                        ->select('id_emp',
                                    DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as empleado"),
                                        'puestos.puestos_nombre')
                        ->whereIn('puestos_nombre',["OPTOMETRÍA","ENFERMERÍA","RECEPCIÓN","EGRESOS","GESTIÓN","ADMINISTRATIVO","N/A"])
                        ->orderBy('empleado','asc')
                        ->get();

        $drUtilidadInterpreta = DB::table('empleados')
                                ->join('actividades','actividades.id','actividades_fk')
                                ->join('puestos','puestos.id','=','puesto_id')                                
                                ->select('id_emp',
                                    DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as empleado"),
                                        'puestos.puestos_nombre')
                                ->where('nombreActividad',"Interpretado")
                                ->orderBy('empleado','asc')
                                ->get();


        $estudios = DB::table('estudios')
                        ->select('id','dscrpMedicosPro')
                        ->get();

        $actividades = DB::table('actividades')
                        ->select('id','nombreActividad')
                        ->where('statusActividad',"A")
                        ->get();

        return view('comisiones.showComisiones',compact('empleados','estudios','actividades','drUtilidadInterpreta'));
    }

    public function calcularComision(Request $request){
        
        //Eliminar datos en la tabla temporal
        DB::table('comisiones_temps')->truncate();

        //Actualización de Fechas en las inserciones y actualizaciones 
        $fechaInsert = now()->toDateString();

        //Colleción para capturar los fallos
        $fallo =  Collect();


        //Validación de los campos requeridos 
        $validator = Validator::make($request->all(),[
            'slctEmpleado' => 'required',
            'slctEstudio'  => 'required',
            'fechaFin'     => 'required',
            'selectCalculo' => 'required',
        ],[
            'slctEmpleado.required' => 'Selecciona el empleado',
            'slctEstudio.required'  => 'Selecciona el estudio',
            'fechaFin.required'     => 'Selecciona la fecha fin',
            'selectCalculo.required' => 'Selecciona el tipo de cálculo'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        //Fin de la validación 

        //Se realiza la iteración del array de los estudios 


        foreach($request->slctEstudio as $estudiosArray){

            // Obtenemos el porcentaje de comision 
            $comisionEmp = DB::table('comisiones')
                                ->select('porcentajeComision','porcentajeUtilidad','porcentajeAdicional')
                                ->where([
                                    ['id_estudio_fk',$estudiosArray],
                                    ['id_empleado_fk','=',$request->slctEmpleado]
                                ])->first();

                    if($request->selectCalculo == "Transcrito"){


                        //Obtener las actividades transcrito por el estudio y empleado antes o igual de la fecha fin
                        $infoCalculoComision = DB::table('status_cob_com')
                                    ->join('actividades','actividades.id','status_cob_com.id_actividad_fk')
                                    ->join('empleados','empleados.id_emp','status_cob_com.id_empleado_fk')
                                    ->join('puestos','puestos.id','empleados.puesto_id')
                                    ->join('estudios','estudios.id','status_cob_com.id_estudio_fk')
                                    ->join('estudiostemps','estudiostemps.id','status_cob_com.id_estudiostemps_fk')
                                    ->where([
                                        ['status_cob_com.id_empleado_fk', $request->slctEmpleado],
                                        ['estudiostemps.fecha','<=', $request->fechaFin],
                                        ['actividades.nombreActividad',$request->selectCalculo],
                                        ['status_cob_com.id_estudio_fk',$estudiosArray],
                                        ['status_cob_com.statusComisiones','!=',"PAGADO"]
                                    ])
                                    ->select('actividades.nombreActividad','estudiostemps.total','estudiostemps.paciente',
                                            'estudiostemps.fecha','status_cob_com.id as identificadorEstatus','estudiostemps.folio as cobranzaFolio')
                                    ->get();
                            
                        foreach ($infoCalculoComision as $info) {
                            if(!is_null($info) && !is_null($comisionEmp)){
                            $this->calculoTranscrito($request->slctEmpleado,$estudiosArray,$info->identificadorEstatus,$info->total,$comisionEmp->porcentajeAdicional,$info->paciente,$info->fecha,$fechaInsert,$info->cobranzaFolio
                            );
                            }else if(is_null($comisionEmp)){
                            $coincidenciaEstudio = DB::table('estudios')
                                    ->select('dscrpMedicosPro')
                                    ->where('estudios.id', $estudiosArray)
                                    ->first();

                            
                            $coincidenciaEmpleado = DB::table('empleados')
                                                ->select( DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as emp"))
                                                ->where('id_emp',$request->slctEmpleado)
                                                ->first();

                                 DB::table('status_cob_com')->where('id',$info->identificadorEstatus)
                                        ->update([                                               
                                                'statusComisiones' => "Informativo",
                                                'cobranza_fecha' => $info->fecha,
                                                'cobranza_cantidad' => $info->total,
                                                'cobranza_porcentaje' =>  0,
                                                'cobranza_total' => 0,
                                                'created_at' => $fechaInsert,
                                                'updated_at' => $fechaInsert,
                                    ]);

                            }
                        }
                    }else if($request->selectCalculo == "Interpretado"){
                        //Obtener los registros por empleado de Entrega y los pendientes
                        $infoCalculoComisionInterpreta  = DB::table('status_cob_com')
                                ->join('actividades','actividades.id','status_cob_com.id_actividad_fk')
                                ->join('empleados','empleados.id_emp','status_cob_com.id_empleado_fk')
                                ->join('puestos','puestos.id','empleados.puesto_id')
                                ->join('estudios','estudios.id','status_cob_com.id_estudio_fk')
                                ->join('estudiostemps','estudiostemps.id','status_cob_com.id_estudiostemps_fk')
                                        ->where([
                                            ['estudiostemps.fecha','<=', $request->fechaFin],
                                            ['actividades.nombreActividad',$request->selectCalculo],
                                            ['status_cob_com.id_empleado_fk',$request->slctEmpleado],
                                            ['status_cob_com.id_estudio_fk',$estudiosArray],
                                            ['status_cob_com.statusComisiones','!=',"PAGADO"]
                                        ])
                                        ->select('actividades.nombreActividad','estudiostemps.total','estudiostemps.paciente',
                                            'estudiostemps.fecha',
                                            'status_cob_com.id as identificadorEstatus',
                                            'estudiostemps.interpretacion',
                                            'estudiostemps.folio as cobranzaFolio')
                                        ->get();

                                    foreach ($infoCalculoComisionInterpreta as $infoInterpreta) {
                                        if(!is_null($infoInterpreta) && !is_null($comisionEmp)){
                                            $this->calculoInterpreta($request->slctEmpleado,
                                                $infoInterpreta->identificadorEstatus,
                                                $infoInterpreta->interpretacion,
                                                $comisionEmp->porcentajeComision,$estudiosArray,
                                                $infoInterpreta->total,$infoInterpreta->paciente,
                                                $infoInterpreta->fecha,$fechaInsert,
                                                $infoInterpreta->cobranzaFolio);

                                        }else if(is_null($comisionEmp)){
                                            $coincidenciaEstudio = DB::table('estudios')
                                                ->select('dscrpMedicosPro')
                                                ->where('estudios.id', $estudiosArray)
                                                ->first();

                            
                                            $coincidenciaEmpleado = DB::table('empleados')
                                                    ->select( DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as emp"))
                                                    ->where('id_emp',$request->slctEmpleado)
                                                    ->first();

                                             DB::table('status_cob_com')->where('id',$info->identificadorEstatus)
                                                     ->update([                                                 'statusComisiones' => "Informativo",
                                                                'cobranza_fecha' => $infoInterpreta->fecha,
                                                                'cobranza_cantidad' => $infoInterpreta->total,
                                                                'cobranza_porcentaje' =>  0,
                                                                'cobranza_total' => 0,
                                                                'created_at' => $fechaInsert,
                                                                'updated_at' => $fechaInsert,
                                                ]);

                                        }
                                    }
                    }else if($request->selectCalculo == "Entregado"){
                        //Obtener los registros por empleado de Entrega y los pendientes
                        $infoCalculoComisionEntregado  = DB::table('status_cob_com')
                                ->join('actividades','actividades.id','status_cob_com.id_actividad_fk')
                                ->join('empleados','empleados.id_emp','status_cob_com.id_empleado_fk')
                                ->join('puestos','puestos.id','empleados.puesto_id')
                                ->join('estudios','estudios.id','status_cob_com.id_estudio_fk')
                                ->join('estudiostemps','estudiostemps.id','status_cob_com.id_estudiostemps_fk')
                                        ->where([
                                            ['estudiostemps.fecha','<=', $request->fechaFin],
                                            ['actividades.nombreActividad',$request->selectCalculo],
                                            ['status_cob_com.id_empleado_fk',$request->slctEmpleado],
                                            ['status_cob_com.id_estudio_fk',$estudiosArray],
                                            ['status_cob_com.statusComisiones','!=',"PAGADO"]
                                        ])
                                        ->select('actividades.nombreActividad','estudiostemps.total','estudiostemps.paciente',
                                            'estudiostemps.fecha','status_cob_com.id as identificadorEstatus','estudiostemps.entregado')
                                        ->get();
                        foreach($infoCalculoComisionEntregado as $infoEntrega){
                            if(!is_null($infoEntrega) && !is_null($comisionEmp)){
                                    $this->calculoEntrega($request->slctEmpleado,$infoEntrega->identificadorEstatus,$infoEntrega->entregado,$infoEntrega->total,$comisionEmp->porcentajeComision,$estudiosArray,$infoEntrega->paciente,$infoEntrega->fecha,$fechaInsert);
                            }else if(is_null($comisionEmp)){
                                $coincidenciaEstudio = DB::table('estudios')
                                    ->select('dscrpMedicosPro')
                                    ->where('estudios.id', $estudiosArray)
                                    ->first();

                            
                                $coincidenciaEmpleado = DB::table('empleados')
                                    ->select( DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as emp"))
                                    ->where('id_emp',$request->slctEmpleado)
                                    ->first();

                            }

                        }

                    }else if ($request->selectCalculo == "Escaneado") {
                        //Obtener los registros que tienen actividad de Escaneo
                        $infoCalculoComisionEscaneo  = DB::table('status_cob_com')
                                ->join('actividades','actividades.id','status_cob_com.id_actividad_fk')
                                ->join('empleados','empleados.id_emp','status_cob_com.id_empleado_fk')
                                ->join('puestos','puestos.id','empleados.puesto_id')
                                ->join('estudios','estudios.id','status_cob_com.id_estudio_fk')
                                ->join('estudiostemps','estudiostemps.id','status_cob_com.id_estudiostemps_fk')
                                        ->where([
                                            ['estudiostemps.fecha','<=', $request->fechaFin],
                                            ['estudiostemps.escaneado',"S"],
                                            ['actividades.nombreActividad',$request->selectCalculo],
                                            ['status_cob_com.id_estudio_fk',$estudiosArray],
                                            ['status_cob_com.statusComisiones','!=',"PAGADO"]
                                        ])
                                        ->select('actividades.nombreActividad','estudiostemps.total','estudiostemps.paciente',
                                            'estudiostemps.fecha','status_cob_com.id as identificadorEstatus',
                                            'estudiostemps.escaneado')
                                        ->get();


                        //Iteracion de los estudios con escaneos
                        foreach($infoCalculoComisionEscaneo as $infoEscaneo){
                            if(!is_null($infoEscaneo) && !is_null($comisionEmp)){

                                $this->calculoEscaneo($request->slctEmpleado,$estudiosArray,$infoEscaneo->total,$comisionEmp->porcentajeAdicional,
                                    $infoEscaneo->paciente,$infoEscaneo->fecha,
                                    $fechaInsert);

                            }else if(is_null($comisionEmp)){
                                $coincidenciaEstudio = DB::table('estudios')
                                    ->select('dscrpMedicosPro')
                                    ->where('estudios.id', $estudiosArray)
                                    ->first();

                            
                                $coincidenciaEmpleado = DB::table('empleados')
                                    ->select( DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as emp"))
                                    ->where('id_emp',$request->slctEmpleado)
                                    ->first();

                            }

                        }

                        
                    }else if ($request->selectCalculo == "Realizado") {
                        //Obtener las actividades que Realizo por el estudio y empleado antes o igual de la fecha fin
                        $infoCalculoComisionRealiza = DB::table('status_cob_com')
                                    ->join('actividades','actividades.id','status_cob_com.id_actividad_fk')
                                    ->join('empleados','empleados.id_emp','status_cob_com.id_empleado_fk')
                                    ->join('puestos','puestos.id','empleados.puesto_id')
                                    ->join('estudios','estudios.id','status_cob_com.id_estudio_fk')
                                    ->join('estudiostemps','estudiostemps.id','status_cob_com.id_estudiostemps_fk')
                                    ->where([
                                        ['status_cob_com.id_empleado_fk', $request->slctEmpleado],
                                        ['estudiostemps.fecha','<=', $request->fechaFin],
                                        ['actividades.nombreActividad',$request->selectCalculo],
                                        ['status_cob_com.id_estudio_fk',$estudiosArray],
                                        ['status_cob_com.statusComisiones','!=',"PAGADO"]
                                    ])
                                    ->select('actividades.nombreActividad','estudiostemps.total','estudiostemps.paciente',
                                            'estudiostemps.fecha','status_cob_com.id as identificadorEstatus')
                                    ->get();

                                 
                        //Iteramos los estudios que se han realizado
                        foreach($infoCalculoComisionRealiza as $infoRealiza ){
                            if(!is_null($infoRealiza) && !is_null($comisionEmp)){
                                    $this->calculoRealiza($request->slctEmpleado,$infoRealiza->identificadorEstatus,$estudiosArray,$infoRealiza->total,$infoRealiza->paciente,$infoRealiza->fecha,$comisionEmp->porcentajeComision,$fechaInsert);
                            }else if(is_null($comisionEmp)){
                                    $coincidenciaEstudio = DB::table('estudios')
                                    ->select('dscrpMedicosPro')
                                    ->where('estudios.id', $estudiosArray)
                                    ->first();

                            
                                    $coincidenciaEmpleado = DB::table('empleados')
                                                ->select( DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as emp"))
                                                ->where('id_emp',$request->slctEmpleado)
                                                ->first();

                            }


                        }
                    }else if($request->selectCalculo == "adicionales"){
                        $infoEstudiosUnicos = DB::table('status_cob_com')
                                                ->join('actividades','actividades.id','status_cob_com.id_actividad_fk')
                                                ->join('empleados','empleados.id_emp','status_cob_com.id_empleado_fk')
                                                ->join('puestos','puestos.id','empleados.puesto_id')
                                                ->join('estudios','estudios.id','status_cob_com.id_estudio_fk')
                                                ->join('estudiostemps','estudiostemps.id','status_cob_com.id_estudiostemps_fk')
                                                ->where([
                                                    ['estudiostemps.fecha','<=', $request->fechaFin],
                                                    ['status_cob_com.id_estudio_fk',$estudiosArray],
                                                    ['status_cob_com.statusComisiones','!=',"PAGADO"]
                                                ])
                                                ->select('estudiostemps.id as unico')
                                                ->distinct()->get();

                                
                            
                            //Iteramos los estudios de forma unica, para los gastos administrativos

                            foreach($infoEstudiosUnicos as $infoEstudios){

                                $infoAdicional = DB::table('estudiostemps')
                                                ->where('id',$infoEstudios->unico)
                                                ->select('total','paciente','fecha')
                                                ->first();

                                if(!is_null($infoAdicional) && !is_null($comisionEmp) ){
                                        $this->adicional($request->slctEmpleado,$infoAdicional->total,$comisionEmp->porcentajeComision,$estudiosArray,$infoAdicional->paciente,$infoAdicional->fecha,$fechaInsert);
                                }else if(is_null($comisionEmp)){
                                        $coincidenciaEstudio = DB::table('estudios')
                                                ->select('dscrpMedicosPro')
                                                ->where('estudios.id', $estudiosArray)
                                                ->first();

                            
                                        $coincidenciaEmpleado = DB::table('empleados')
                                                ->select( DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as emp"))
                                                ->where('id_emp',$request->slctEmpleado)
                                                ->first();
                                }
                            }

                        
                    }

                   
                        
            

              
        }
        if(isset($coincidenciaEstudio->dscrpMedicosPro) && isset($coincidenciaEmpleado->emp)){
            $fallo->push(["descripcion" => $coincidenciaEstudio->dscrpMedicosPro,"empleado" => $coincidenciaEmpleado->emp]);
        }
        

         
        //Fin del array del estudio


        // Llenado de tablas
        $empleados = DB::table('empleados')
                        ->join('puestos','puestos.id','=','puesto_id')
                        ->select('id_emp',
                                    DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as empleado"),
                                        'puestos.puestos_nombre')
                        ->whereIn('puestos_nombre',["OPTOMETRÍA","ENFERMERÍA","RECEPCIÓN","EGRESOS","GESTIÓN","ADMINISTRATIVO","N/A"])
                        ->orderBy('empleado','asc')
                        ->get();

        $drUtilidadInterpreta = DB::table('empleados')
                                ->join('actividades','actividades.id','actividades_fk')
                                ->join('puestos','puestos.id','=','puesto_id')                                
                                ->select('id_emp',
                                    DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as empleado"),
                                        'puestos.puestos_nombre')
                                ->where('nombreActividad',"Interpretado")
                                ->orderBy('empleado','asc')
                                ->get();


        $estudios = DB::table('estudios')
                        ->select('id','dscrpMedicosPro')
                        ->get();

        $actividades = DB::table('actividades')
                        ->select('id','nombreActividad')
                        ->where('statusActividad',"A")
                        ->get();

        //Consultas para el llenado de las comisiones en el datatable
        $comisiones = DB::table('comisiones_temps')
                            ->join('empleados','empleados.id_emp','=','comisiones_temps.id_emp_fk')
                            ->join('estudios','estudios.id','=','comisiones_temps.id_estudio_fk')
                            ->select('estudios.dscrpMedicosPro','fechaEstudio','total','paciente','cantidad','porcentaje','cobranza_folio')
                            ->where([
                                ['comisiones_temps.id_emp_fk','=',$request->slctEmpleado]
                            ])
                            ->orderBy('fechaEstudio')
                            ->get();

        $totalComisiones = DB::table('comisiones_temps')
                                ->where([
                                    ['comisiones_temps.id_emp_fk','=',$request->slctEmpleado]
                                ])->sum('total');

                return view('comisiones.showComisiones',compact('empleados','estudios','actividades','drUtilidadInterpreta','fallo','comisiones','totalComisiones'));

    
    }

    public function calculoTranscrito($slctEmpleado,$slctEstudio,$identificadorEstatus,$total,$porcentajeAdicional,$paciente,
                        $fecha,$fechaInsert,$cobranzaFolio){
                //Verificación del empleado, realiza transcripcion y pago de la comision
            $restriccionTrans =  DB::table('empleados')
                                            ->join('puestos','puestos.id','empleados.puesto_id')
                                            ->join('actividades','actividades.id','empleados.actividades_fk')
                                            ->where([
                                                ['puestos_nombre',"OPTOMETRÍA"],
                                                ['nombreActividad',"Transcrito"],
                                                ['empleados.id_emp',$slctEmpleado]
                                            ])->count();
                    

                    if($restriccionTrans == 1){

                        //Excluir Estudios Anterion 
                         $excluyeEstudioTrans = DB::table('estudios')
                                                        ->join('cat_estudios','cat_estudios.id','estudios.id_estudio_fk')
                                                        ->whereIn('cat_estudios.descripcion',["ANTERION"])
                                                        ->where('estudios.id', $slctEstudio)
                                                        ->count();


                                if($excluyeEstudioTrans == 0 ){
                                    //porcentajeAdicional es el porcentaje de Transcripcion

                                            $comisionTrans = ($total * $porcentajeAdicional) /100 ;
                                           
                                        //Insert en la tabla temporal
                                                DB::table('comisiones_temps')->insert([
                                                    'id_emp_fk' => $slctEmpleado,
                                                    'id_estudio_fk' => $slctEstudio,
                                                    'paciente' => $paciente,
                                                    'fechaEstudio' => $fecha,
                                                    'cantidad' => $total,
                                                    'porcentaje' => $porcentajeAdicional,
                                                    'total' => $comisionTrans,
                                                    'created_at' => $fechaInsert,
                                                    'updated_at' => $fechaInsert,
                                                    'cobranza_folio' => $cobranzaFolio
                                                ]);

                                                DB::table('status_cob_com')->where('id',$identificadorEstatus)
                                                ->update([
                                                    'cobranza_fecha' => $fecha,
                                                    'cobranza_cantidad' => $total,
                                                    'cobranza_porcentaje' =>  $porcentajeAdicional,
                                                    'cobranza_total' => $comisionTrans,
                                                     'created_at' => $fechaInsert,
                                                    'updated_at' => $fechaInsert,
                                                ]);
                                }else if($excluyeEstudioTrans == 1){
                                     DB::table('status_cob_com')->where('id',$identificadorEstatus)
                                        ->update([                                               
                                                'statusComisiones' => "Informativo",
                                                'cobranza_fecha' => $fecha,
                                                'cobranza_cantidad' => $total,
                                                'cobranza_porcentaje' =>  0,
                                                'cobranza_total' => 0,
                                                'created_at' => $fechaInsert,
                                                'updated_at' => $fechaInsert,
                                    ]);


                                }

                    }else if($restriccionTrans == 0){
                        DB::table('status_cob_com')->where('id',$identificadorEstatus)
                                ->update([                                               
                                    'statusComisiones' => "Informativo",
                                    'cobranza_fecha' => $fecha,
                                    'cobranza_cantidad' => $total,
                                    'cobranza_porcentaje' =>  0,
                                    'cobranza_total' => 0,
                                    'created_at' => $fechaInsert,
                                    'updated_at' => $fechaInsert,
                            ]);

                    }

                     
    }

    public function calculoRealiza($slctEmpleado,$identificadorEstatus,$slctEstudio,$total,$paciente,$fecha,$porcentajeComision,$fechaInsert){
            //Restriccion si se debe realizar el pago por la actividad realizada
             $restriccionRealiza =  DB::table('empleados')
                                            ->join('puestos','puestos.id','empleados.puesto_id')
                                            ->whereIn('puestos_nombre',["ENFERMERÍA","OPTOMETRÍA"])
                                            ->where('empleados.id_emp',$slctEmpleado)
                                            ->select('puestos_nombre')
                                            ->get();

                                          
                    if(!is_null($restriccionRealiza)){
                        if($restriccionRealiza->puestos_nombre = "OPTOMETRÍA"){
                            //Excluir Estudios Anterion y ultrasonido

                            $excluyeEstudioRealiza = DB::table('estudios')
                                                        ->join('cat_estudios','cat_estudios.id','estudios.id_estudio_fk')
                                                        ->whereIn('cat_estudios.descripcion',["ULTRASONIDO","   ANTERION"])
                                                        ->where('estudios.id', $slctEstudio)
                                                        ->count();

                                if($excluyeEstudioRealiza == 0){
                                    $comsionRealizado = ($total * $porcentajeComision) /100 ;

                                        //Insert en la tabla temporal
                                        DB::table('comisiones_temps')->insert([
                                            'id_emp_fk' => $slctEmpleado,
                                            'id_estudio_fk' => $slctEstudio,
                                            'paciente' => $paciente,
                                            'fechaEstudio' => $fecha,
                                            'cantidad' => $total,
                                            'porcentaje' => $porcentajeComision,
                                            'total' => $comsionRealizado,
                                            'created_at' => $fechaInsert,
                                            'updated_at' => $fechaInsert
                                        ]);

                                }else if($excluyeEstudioRealiza == 1){
                                     DB::table('status_cob_com')->where('id',$identificadorEstatus)
                                                        ->update([                                       
                                                            'statusComisiones' => "Informativo"
                                                        ]);

                                }

                        }else if($restriccionRealiza->puestos_nombre = "ENFERMERÍA"){
                             //Excluir estudios Generales FAG, OCT, CAMPIMETRIA, ULTRASONIDO

                            $excluyeEstudioRealizaEnfermeria = DB::table('estudios')
                                                        ->join('cat_estudios','cat_estudios.id','estudios.id_estudio_fk')
                                                        ->whereIn('cat_estudios.descripcion',["ULTRASONIDO","   FAG","OCT","CAMPIMETRIA"])
                                                        ->where('estudios.id', $slctEstudio)
                                                        ->count();
                                    if($excluyeEstudioRealizaEnfermeria == 0){
                                        $comsionRealizado = ($total * $porcentajeComision) /100 ;

                                        //Insert en la tabla temporal
                                        DB::table('comisiones_temps')->insert([
                                            'id_emp_fk' => $slctEmpleado,
                                            'id_estudio_fk' => $slctEstudio,
                                            'paciente' => $paciente,
                                            'fechaEstudio' => $fecha,
                                            'cantidad' => $total,
                                            'porcentaje' => $porcentajeComision,
                                            'total' => $comsionRealizado,
                                            'created_at' => $fechaInsert,
                                            'updated_at' => $fechaInsert
                                        ]);


                                    }else if($excluyeEstudioRealizaEnfermeria == 1){
                                        DB::table('status_cob_com')->where('id',$identificadorEstatus)
                                                        ->update([                                       
                                                            'statusComisiones' => "Informativo"
                                                        ]);
                                    }


                        }

                    }else if(is_null($restriccionRealiza)){
                        DB::table('status_cob_com')->where('id',$identificadorEstatus)
                                ->update([                                               
                                    'statusComisiones' => "Informativo"
                            ]);
                    }

    }


    public function calculoEscaneo($slctEmpleado,$slctEstudio,$total,$porcentajeAdicional,$paciente,$fecha,$fechaInsert){
        //Restriccion Escaneo para Enfermeras
         $restriccionEscaneo =  DB::table('empleados')
                                        ->join('puestos','puestos.id','empleados.puesto_id')
                                        ->where([
                                            ['puestos_nombre',"ENFERMERÍA"],
                                            ['empleados.id_emp', $slctEmpleado]
                                        ])
                                        ->select('puestos.puestos_nombre')
                                        ->first();

            if(!is_null($restriccionEscaneo)){
                $conteoEnfermeria = DB::table('empleados')
                                        ->join('puestos','puestos.id','empleados.puesto_id')
                                        ->where('puestos_nombre',"ENFERMERÍA")
                                        ->count();

                    //se toma el porcentaje Adicional, 
                    $comisionEscaneo = (($total * $porcentajeAdicional) /100)/$conteoEnfermeria;

                                //Insert en la tabla temporal
                                    DB::table('comisiones_temps')->insert([
                                            'id_emp_fk' => $slctEmpleado,
                                            'id_estudio_fk' => $slctEstudio,
                                            'paciente' => $paciente,
                                            'fechaEstudio' => $fecha,
                                            'cantidad' => $total,
                                            'porcentaje' => $porcentajeAdicional,
                                            'total' => $comisionEscaneo,
                                            'created_at' => $fechaInsert,
                                            'updated_at' => $fechaInsert
                                    ]);
            }

    }

    public function calculoEntrega($slctEmpleado,$identificadorEstatus,$entregado,$total,$porcentajeComision,$slctEstudio,$paciente,$fecha,$fechaInsert){
        //Comprobar si se debe pagar la comision por entregado

            $restriccionEntrega =  DB::table('empleados')
                        ->join('puestos','puestos.id','empleados.puesto_id')
                        ->where('empleados.id_emp',$slctEmpleado)
                        ->whereIn('puestos_nombre',["RECEPCIÓN", "N/A"])
                        ->count();

                if($restriccionEntrega == 1){

                    //Restricción estudio anterion 
                     $excluyeEstudioTransEntrega = DB::table('estudios')
                                ->join('cat_estudios','cat_estudios.id','estudios.id_estudio_fk')
                                ->whereIn('cat_estudios.descripcion',["ANTERION"])
                                ->where('estudios.id', $slctEstudio)
                                ->count();

                        if($excluyeEstudioTransEntrega == 0){

                                if($entregado == 'S'){
                                        $comisionEntrega = ($total * $porcentajeComision)/100;
                                            //Insert en la tabla temporal
                                            DB::table('comisiones_temps')->insert([
                                                'id_emp_fk' => $slctEmpleado,
                                                'id_estudio_fk' => $slctEstudio,
                                                'paciente' => $paciente,
                                                'fechaEstudio' => $fecha,
                                                'cantidad' => $total,
                                                'porcentaje' => $porcentajeComision,
                                                'total' => $comisionEntrega,
                                                'created_at' => $fechaInsert,
                                                'updated_at' => $fechaInsert
                                            ]);

                                }else if($entregado == 'P'){
                                        $comisionEntrega = ($total * $porcentajeComision)/100;
                                            //Insert en la tabla temporal
                                            DB::table('comisiones_temps')->insert([
                                                'id_emp_fk' => $slctEmpleado,
                                                'id_estudio_fk' => $slctEstudio,
                                                'paciente' => $paciente,
                                                'fechaEstudio' => $fecha,
                                                'cantidad' => $total,
                                                'porcentaje' => $porcentajeComision,
                                                'total' => $comisionEntrega,
                                                'created_at' => $fechaInsert,
                                                'updated_at' => $fechaInsert
                                            ]);

                                        DB::table('status_cob_com')->where('id',$identificadorEstatus)
                                            ->update([                                               
                                                    'statusComisiones' => "Reservado"
                                            ]);


                                }

                        }else if($excluyeEstudioTransEntrega == 1){
                            DB::table('status_cob_com')->where('id',$identificadorEstatus)
                                ->update([                                               
                                        'statusComisiones' => "Informativo"
                                ]);

                        }
                }else if($restriccionEntrega == 0){
                        DB::table('status_cob_com')->where('id',$identificadorEstatus)
                                ->update([                                               
                                        'statusComisiones' => "Informativo"
                                ]);
                }


    }

    public function calculoInterpreta($slctEmpleado,$identificadorEstatus,$interpretacion,$porcentajeComision,$slctEstudio,$total,$paciente,$fecha,$fechaInsert,$cobranzaFolio){

        //Restriccion Interpretación para Doctores con Actividad Interpretado
         $restriccionInterpreta =  DB::table('empleados')
                                            ->join('puestos','puestos.id','empleados.puesto_id')
                                            ->join('actividades','actividades.id','empleados.actividades_fk')
                                            ->where([
                                                ['puestos_nombre',"DOCTOR"],
                                                ['nombreActividad',"Interpretado"],
                                                ['empleados.id_emp',$slctEmpleado]
                                            ])->count();
                if($restriccionInterpreta == 1){
                    $excluyeEstudioInterpreta = DB::table('estudios')
                                ->join('cat_estudios','cat_estudios.id','estudios.id_estudio_fk')
                                ->whereIn('cat_estudios.descripcion',["ANTERION"])
                                ->where('estudios.id', $slctEstudio)
                                ->count();

                            if($excluyeEstudioInterpreta == 0){
                                if($interpretacion == 'S'){
                                    $comisionInterpreta = ($total * $porcentajeComision)/100;
                                            //Insert en la tabla temporal
                                            DB::table('comisiones_temps')->insert([
                                                'id_emp_fk' => $slctEmpleado,
                                                'id_estudio_fk' => $slctEstudio,
                                                'paciente' => $paciente,
                                                'fechaEstudio' => $fecha,
                                                'cantidad' => $total,
                                                'porcentaje' => $porcentajeComision,
                                                'total' => $comisionInterpreta,
                                                'created_at' => $fechaInsert,
                                                'updated_at' => $fechaInsert,
                                                'cobranza_folio' => $cobranzaFolio
                                            ]);

                                    DB::table('status_cob_com')->where('id',$identificadorEstatus)
                                            ->update([                                              
                                                'cobranza_fecha' => $fecha,
                                                'cobranza_cantidad' => $total,
                                                'cobranza_porcentaje' =>  $porcentajeComision,
                                                'cobranza_total' => 0,
                                                'created_at' => $fechaInsert,
                                                'updated_at' => $fechaInsert,
                                            ]);

                                }

                            }else if($excluyeEstudioInterpreta == 1){
                                 DB::table('status_cob_com')->where('id',$identificadorEstatus)
                                            ->update([                                              
                                                'statusComisiones' => "Informativo",
                                                'cobranza_fecha' => $fecha,
                                                'cobranza_cantidad' => $total,
                                                'cobranza_porcentaje' =>  $porcentajeComision,
                                                'cobranza_total' => 0,
                                                'created_at' => $fechaInsert,
                                                'updated_at' => $fechaInsert,
                                            ]);

                            }


                }else if($restriccionInterpreta == 0){
                     DB::table('status_cob_com')->where('id',$identificadorEstatus)
                                ->update([                                               
                                    'statusComisiones' => "Informativo",
                                    'cobranza_fecha' => $fecha,
                                                'cobranza_cantidad' => $total,
                                                'cobranza_porcentaje' =>  $porcentajeComision,
                                                'cobranza_total' => $comisionInterpreta,
                                                'created_at' => $fechaInsert,
                                                'updated_at' => $fechaInsert,
                            ]);
                }
        
    }

    public function adicional($slctEmpleado,$total,$porcentajeComision,$slctEstudio,$paciente,$fecha,$fechaInsert){
        //REstriccion empleados asignados para gastos administrativos
         $restriccionAdicionales =  DB::table('empleados')
                                        ->join('puestos','puestos.id','empleados.puesto_id')
                                        ->whereIn('puestos_nombre',["ADMINISTRATIVO","EGRESOS","GESTIÓN"])
                                        ->where('empleados.id_emp', $slctEmpleado)
                                        ->select('puestos.puestos_nombre')
                                        ->first();
                if(!is_null($restriccionAdicionales)){
                        if($restriccionAdicionales->puestos_nombre == "ADMINISTRATIVO"){
                            $comisionAdministrativo = ($total * $porcentajeComision) /100;

                                                DB::table('comisiones_temps')->insert([
                                                    'id_emp_fk' => $slctEmpleado,
                                                    'id_estudio_fk' => $slctEstudio,
                                                    'paciente' => $paciente,
                                                    'fechaEstudio' => $fecha,
                                                    'cantidad' => $total,
                                                    'porcentaje' => $porcentajeComision,
                                                    'total' => $comisionAdministrativo,
                                                    'created_at' => $fechaInsert,
                                                    'updated_at' => $fechaInsert
                                                ]);


                        }else if($restriccionAdicionales->puestos_nombre == "EGRESOS"){

                                    $comisionEgreso = ($total * $porcentajeComision) /100;

                                                 DB::table('comisiones_temps')->insert([
                                                    'id_emp_fk' => $slctEmpleado,
                                                    'id_estudio_fk' => $slctEstudio,
                                                    'paciente' => $paciente,
                                                    'fechaEstudio' => $fecha,
                                                    'cantidad' => $total,
                                                    'porcentaje' => $porcentajeComision,
                                                    'total' => $comisionEgreso,
                                                    'created_at' => $fechaInsert,
                                                    'updated_at' => $fechaInsert
                                                ]);


                        }else if($restriccionAdicionales->puestos_nombre == "GESTION"){

                             $comisionGestion = ($total * $porcentajeComision) /100;

                                                 DB::table('comisiones_temps')->insert([
                                                    'id_emp_fk' => $slctEmpleado,
                                                    'id_estudio_fk' => $slctEstudio,
                                                    'paciente' => $paciente,
                                                    'fechaEstudio' => $fecha,
                                                    'cantidad' => $total,
                                                    'porcentaje' => $porcentajeComision,
                                                    'total' => $comisionGestion,
                                                    'created_at' => $fechaInsert,
                                                    'updated_at' => $fechaInsert
                                                ]);

                        }
                }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $lisComisiones = Comisiones::join('estudios','estudios.id','=','comisiones.id_estudio_fk')
                                    ->join('empleados','empleados.id_emp','=','comisiones.id_empleado_fk')
                                    ->join('tipo_ojos','tipo_ojos.id','=','estudios.id_ojo_fk')
                                    ->join('cat_estudios','cat_estudios.id','=','estudios.id_estudio_fk')
                                    ->join('puestos','puestos.id','empleados.puesto_id')
                                    ->select(DB::raw("CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom) AS Empleado"),
                                             DB::raw("estudios.dscrpMedicosPro as Estudio")
                                                    ,'comisiones.porcentajeComision'
                                                    ,'comisiones.porcentajeUtilidad'
                                                    ,'comisiones.porcentajeAdicional'
                                                    ,'puestos.puestos_nombre'
                                                    ,'comisiones.id')
                                    ->orderBy('empleados.empleado_nombre','asc')
                                    ->get();

        $listEstudios = Estudios::select('id','dscrpMedicosPro')->orderBy('estudios.id','asc')->get();

        $listEmpleados = Empleado::join('puestos','puestos.id','=','puesto_id')
                                    ->select(DB::raw("CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom,' (',puestos.puestos_nombre,')') AS Empleado"),'id_emp')
                                    ->where([
                                        ['empleados.empleado_status','=','A']
                                    ])
                                    ->orderBy('puestos.id','asc')
                                    ->get();

        return view('catalogos.comisiones.catcomisiones',compact('lisComisiones','listEstudios','listEmpleados'));
    }

   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'estudioGral' => 'required',
            'empleadoComision' => 'required',
            'porcentajeComision' => 'required',
        ],[
            'estudioGral.required' => 'Seleccciona el Estudio',
            'empleadoComision.required' => 'Selecciona el Empleado',
            'porcentajeComision.required' => 'Ingresa el porcentaje',
        ]);
        
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $duplicados = Comisiones::where([
            ['id_estudio_fk',$request->estudioGral],
            ['id_empleado_fk',$request->empleadoComision]
        ])->get();

        if($duplicados->count() >= 1){
            return back()->with('duplicados','El registro ingresado ya existe');
        }

        $fechaInsert = now()->toDateString();
        DB::table('comisiones')->insert([
            'id_estudio_fk' => $request->estudioGral,
            'id_empleado_fk' => $request->empleadoComision,
            'porcentajeComision' => $request->porcentajeComision,
            'porcentajeAdicional' => $request->cantidadComision,
            'porcentajeUtilidad' => $request->utilidadComision,
            'created_at' => $fechaInsert,
            'updated_at' => $fechaInsert
        ]);
        return redirect()->route('mostrarComisiones.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comisiones  $comisiones
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $comision = Comisiones::join('estudios','estudios.id','=','comisiones.id_estudio_fk')
                              ->join('empleados','empleados.id_emp','=','comisiones.id_empleado_fk')
                              ->join('puestos','puestos.id','empleados.puesto_id')
                              ->select(DB::raw("CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom) AS empleado")
                                                ,'estudios.dscrpMedicosPro'
                                                ,'comisiones.id_estudio_fk'
                                                ,'comisiones.id_empleado_fk'
                                                ,'comisiones.porcentajeComision'
                                                ,'comisiones.porcentajeUtilidad'
                                                ,'comisiones.porcentajeAdicional'
                                                ,'empleados.puesto_id'
                                                ,'puestos.puestos_nombre'
                                                ,'comisiones.id')
                              ->where('comisiones.id','=',$id)
                              ->first();

        $listEstudios = Estudios::select('id','dscrpMedicosPro')
                                ->orderBy('estudios.id','asc')
                                ->get();

        $listEmpleados = Empleado::join('puestos','puestos.id','=','puesto_id')
                                    ->select(DB::raw("CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom,' (',puestos.puestos_nombre,')') AS Empleado"),'id_emp')
                                    ->where([
                                        ['empleados.empleado_status','=','A'],
                                        ['puestos.id','!=','1']
                                    ])
                                    ->orderBy('puestos.id','asc')
                                    ->get();

        return view('catalogos.comisiones.editcomision',compact('comision','listEstudios','listEmpleados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comisiones  $comisiones
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){
        $editComision = Comisiones::find($request->idComision);
        $editComision->id_estudio_fk = $request->estudioGral;
        $editComision->porcentajeComision = $request->porcentajeComision;
        $editComision->porcentajeAdicional = $request->cantidadComision;
        $editComision->porcentajeUtilidad = $request->cantidadUtilidad;
        $editComision->save();
        
        return redirect()->route('mostrarComisiones.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comisiones  $comisiones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request){
        $delEComision = Comisiones::find($request->idComision)->delete();
        return redirect()->route('mostrarComisiones.index');
    }

    public function exportExcel(){
        return Excel::download(new ComisionesExport, 'Comision.xlsx');
    }

    public function fechaCorte(Request $request){

        $fecha = new FechaCorte;
        $fecha->fechaCorte = $request->fechaCorte;
        $fecha->save();

    }
}