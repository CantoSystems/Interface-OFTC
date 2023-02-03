<?php

namespace App\Http\Controllers;

use DB;

use App\Models\Comisiones;
use App\Models\Empleado;
use App\Models\Estudios;
use App\Models\Cobranza;

use App\Exports\ComisionesExport;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class ComisionesController extends Controller{
    public function showComisiones(){
        $empleados = DB::table('empleados')
                        ->join('puestos','puestos.id','=','puesto_id')
                        ->select('id_emp',
                                    DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as empleado"),
                                        'puestos.puestos_nombre')
                        ->where('id_emp','!=',1)
                        ->orderBy('empleado','asc')
                        ->get();

        $estudios = DB::table('estudios')
                        ->select('id','dscrpMedicosPro')
                        ->orderBy('dscrpMedicosPro','asc')
                        ->get();

        return view('comisiones.showComisiones',compact('empleados','estudios'));
    }

    public function calcularComision(Request $request){
        //Eliminar datos en la tabla temporal
        DB::table('comisiones_temps')->truncate();
        $fechaInsert = now()->toDateString();

        $validator = Validator::make($request->all(),[
            'slctEmpleado' => 'required',
            'slctEstudio'  => 'required',
            'fechaFin'     => 'required',
        ],[
            'slctEmpleado.required' => 'Selecciona el empleado',
            'slctEstudio.required'  => 'Selecciona el estudio',
            'fechaFin.required'     => 'Selecciona la fecha fin',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        //Obtener los porcentajes de comisión
            $comisionEmp = DB::table('comisiones')
                                ->select('porcentajeComision','porcentajeUtilidad','porcentajeAdicional')
                                ->where([
                                    ['id_estudio_fk','=',$request->slctEstudio],
                                    ['id_empleado_fk','=',$request->slctEmpleado]
                                ])->first();



        if($request->selectCalculo == "comisionesEmpleados"){
            //Obtener las actividades realizadas segun el estudio y empleado antes o igual de la fecha fin
            $infoCalculoComision = DB::table('status_cob_com')
                                    ->join('actividades','actividades.id','status_cob_com.id_actividad_fk')
                                    ->join('empleados','empleados.id_emp','status_cob_com.id_empleado_fk')
                                    ->join('puestos','puestos.id','empleados.puesto_id')
                                    ->join('estudios','estudios.id','status_cob_com.id_estudio_fk')
                                    ->join('estudiostemps','estudiostemps.id','status_cob_com.id_estudiostemps_fk')
                                    ->where([
                                        ['status_cob_com.id_empleado_fk', $request->slctEmpleado],
                                        ['status_cob_com.id_estudio_fk', $request->slctEstudio],
                                        ['estudiostemps.fecha','<=', $request->fechaFin],
                                    ])
                                    ->select('actividades.nombreActividad','estudiostemps.total','estudiostemps.paciente',
                                            'estudiostemps.fecha','status_cob_com.id as identificadorEstatus',
                                            'estudiostemps.escaneado')
                                    ->get();

           

                if(!is_null($infoCalculoComision) && !is_null($comisionEmp)){

                    foreach($infoCalculoComision as $info){

                    if($info->nombreActividad == "Transcrito"){

                        //Detectar si se realiza el pago de comisión por transcripción
                        $restriccionTrans =  DB::table('empleados')
                                            ->join('puestos','puestos.id','empleados.puesto_id')
                                            ->join('actividades','actividades.id','empleados.actividades_fk')
                                            ->where([
                                                ['puestos_nombre',"OPTOMETRÍA"],
                                                ['nombreActividad',"Transcrito"],
                                                ['empleados.id_emp',$request->slctEmpleado]
                                            ])->count();

                        if($restriccionTrans == 0){

                            DB::table('status_cob_com')->where('id',$info->identificadorEstatus)
                                ->update([                                               
                                    'statusComisiones' => "Informativo"
                            ]);


                        }else if($restriccionTrans == 1){

                            //Excluir Estudios Anterion 

                            $excluyeEstudioTrans = DB::table('estudios')
                                                        ->join('cat_estudios','cat_estudios.id','estudios.id_estudio_fk')
                                                        ->whereIn('cat_estudios.descripcion',["ANTERION"])
                                                        ->where('estudios.id', $request->slctEstudio)
                                                        ->count();
                                 

                                    if($excluyeEstudioTrans == 0){
                                        //porcentajeAdicional es el porcentaje de Transcripcion
                                            $comisionTrans = ($info->total * $comisionEmp->porcentajeAdicional) /100 ;
                                -                //Insert en la tabla temporal
                                                DB::table('comisiones_temps')->insert([
                                                    'id_emp_fk' => $request->slctEmpleado,
                                                    'id_estudio_fk' => $request->slctEstudio,
                                                    'paciente' => $info->paciente,
                                                    'fechaEstudio' => $info->fecha,
                                                    'cantidad' => $info->total,
                                                    'porcentaje' => $comisionEmp->porcentajeAdicional,
                                                    'total' => $comisionTrans,
                                                    'created_at' => $fechaInsert,
                                                    'updated_at' => $fechaInsert
                                                ]);
                                    }else if($excluyeEstudioTrans == 1){
                                        DB::table('status_cob_com')->where('id',$info->identificadorEstatus)
                                            ->update([                                               
                                                    'statusComisiones' => "Informativo"
                                        ]);
                                    }

                        }

                    }else if($info->nombreActividad == "Interpretado"){

                    }else if($info->nombreActividad == "Entregado"){
                        //Comprobar si se debe pagar la comision por entregado

                        $restriccionEntrega =  DB::table('empleados')
                                            ->join('puestos','puestos.id','empleados.puesto_id')
                                            ->where([
                                                ['puestos_nombre',"RECEPCIÓN"],
                                                ['empleados.id_emp',$request->slctEmpleado]
                                            ])
                                            ->count();
                                if($restriccionEntrega == 0){
                                        DB::table('status_cob_com')->where('id',$info->identificadorEstatus)
                                                        ->update([                                               
                                                                    'statusComisiones' => "Informativo"
                                                        ]);
                                }else if($restriccionEntrega == 1){
                                    $comisionEntrega = ($info->total * $comisionEmp->porcentajeComision)/100;
                                            //Insert en la tabla temporal
                                            DB::table('comisiones_temps')->insert([
                                                'id_emp_fk' => $request->slctEmpleado,
                                                'id_estudio_fk' => $request->slctEstudio,
                                                'paciente' => $info->paciente,
                                                'fechaEstudio' => $info->fecha,
                                                'cantidad' => $info->total,
                                                'porcentaje' => $comisionEmp->porcentajeComision,
                                                'total' => $comisionEntrega,
                                                'created_at' => $fechaInsert,
                                                'updated_at' => $fechaInsert
                                            ]);

                                } 

                    }else if($info->nombreActividad == "Realizado"){

                        //Detectar si se realiza el pago de comisión por Realizado
                        $restriccionRealiza =  DB::table('empleados')
                                            ->join('puestos','puestos.id','empleados.puesto_id')
                                            ->whereIn('puestos_nombre',["ENFERMERÍA","OPTOMETRÍA"])
                                            ->where('empleados.id_emp',$request->slctEmpleado)
                                            ->count();

                        if($restriccionRealiza == 0){
                            DB::table('status_cob_com')->where('id',$info->identificadorEstatus)
                                ->update([                                               
                                    'statusComisiones' => "Informativo"
                            ]);

                        }else if($restriccionRealiza == 1){


                            //Excluir Estudios Anterion 

                            $excluyeEstudioRealiza = DB::table('estudios')
                                                        ->join('cat_estudios','cat_estudios.id','estudios.id_estudio_fk')
                                                        ->whereIn('cat_estudios.descripcion',["ULTRASONIDO MODO B","ULTRASONIDO MODO B"])
                                                        ->where('estudios.id', $request->slctEstudio)
                                                        ->count();

                                    if($excluyeEstudioRealiza == 0){
                                        $comsionRealizado = ($info->total * $comisionEmp->porcentajeComision) /100 ;

                                        //Insert en la tabla temporal
                                        DB::table('comisiones_temps')->insert([
                                            'id_emp_fk' => $request->slctEmpleado,
                                            'id_estudio_fk' => $request->slctEstudio,
                                            'paciente' => $info->paciente,
                                            'fechaEstudio' => $info->fecha,
                                            'cantidad' => $info->total,
                                            'porcentaje' => $comisionEmp->porcentajeComision,
                                            'total' => $comsionRealizado,
                                            'created_at' => $fechaInsert,
                                            'updated_at' => $fechaInsert
                                        ]);
                                    }else if($excluyeEstudioRealiza == 1){
                                         DB::table('status_cob_com')->where('id',$info->identificadorEstatus)
                                                        ->update([                                               
                                                                    'statusComisiones' => "Informativo"
                                                        ]);

                                    }
                        }

                    }
                }
                }else{
                    if(is_null($infoCalculoComision)){
                        return back()->with('resultadosVacios','El empleado no cuenta con Actividades para este EStudio en el rango de fechas ingresado');
                    }else if(is_null($comisionEmp)){
                        return back()->with('resultadosVacios','El empleado no cuenta con porcentajes de comisión para este estudio');
                    }
            }

        }else if($request->selectCalculo == "procesoEscaneo"){
            //Restriccion para Escaneo
            $restriccionEscaneo =  DB::table('empleados')
                                        ->join('puestos','puestos.id','empleados.puesto_id')
                                        ->where([
                                            ['puestos_nombre',"ENFERMERÍA"],
                                            ['empleados.id_emp', $request->slctEmpleado]
                                        ])
                                        ->select('puestos.puestos_nombre')
                                        ->first();

                    if(!is_null($restriccionEscaneo)){

                        $infoEscaneo = DB::table('status_cob_com')
                                                ->join('actividades','actividades.id','status_cob_com.id_actividad_fk')
                                                ->join('empleados','empleados.id_emp','status_cob_com.id_empleado_fk')
                                                ->join('puestos','puestos.id','empleados.puesto_id')
                                                ->join('estudios','estudios.id','status_cob_com.id_estudio_fk')
                                                ->join('estudiostemps','estudiostemps.id','status_cob_com.id_estudiostemps_fk')
                                                ->where([
                                                    ['status_cob_com.id_estudio_fk', $request->slctEstudio],
                                                    ['estudiostemps.fecha','<=', $request->fechaFin],
                                                ])
                                                ->select('actividades.nombreActividad','estudiostemps.total','estudiostemps.paciente',
                                                        'estudiostemps.fecha','status_cob_com.id as identificadorEstatus',
                                                        'estudiostemps.escaneado')
                                                ->get();

                           



                        if(!is_null($infoEscaneo) and !is_null($comisionEmp) ){

                                foreach($infoEscaneo as $esc){
                                    if($restriccionEscaneo->puestos_nombre == "ENFERMERÍA"){
                                        if($esc->nombreActividad == "Escaneado" ){

                                            //se toma el porcentaje Adicional, para no confundir el porcentaje asignado a REalizado
                                            $comisionEscaneo = (($esc->total * $comisionEmp->porcentajeAdicional) /100)/3;


                                                //Insert en la tabla temporal
                                                DB::table('comisiones_temps')->insert([
                                                    'id_emp_fk' => $request->slctEmpleado,
                                                    'id_estudio_fk' => $request->slctEstudio,
                                                    'paciente' => $esc->paciente,
                                                    'fechaEstudio' => $esc->fecha,
                                                    'cantidad' => $esc->total,
                                                    'porcentaje' => $comisionEmp->porcentajeAdicional,
                                                    'total' => $comisionEscaneo,
                                                    'created_at' => $fechaInsert,
                                                    'updated_at' => $fechaInsert
                                                ]);

                                        }else if($esc->escaneado == 'N'){
                                             DB::table('status_cob_com')->where('id',$info->identificadorEstatus)
                                                        ->update([                                               
                                                                    'statusComisiones' => "No aplica"
                                                        ]);
                                        }


                                    }
                                }

                            }else{
                                if(is_null($infoEscaneo)){
                                    return back()->with('resultadosVacios','El empleado no cuenta con Actividades para este EStudio en el rango de fechas ingresado');
                                }else if(is_null($comisionEmp)){
                                    return back()->with('resultadosVacios','El empleado no cuenta con porcentajes de comisión para este estudio');
                                }
                            }


                    }else if($restriccionEscaneo == null){
                        return back()->with('resultadosVacios','El empleado no aplica para este rubro ');
                    }


                       

        }else if($request->selectCalculo == "adicionales"){
            //Restriccion de empleados para gastos
            $restriccionGastos =  DB::table('empleados')
                                        ->join('puestos','puestos.id','empleados.puesto_id')
                                        ->whereIn('puestos_nombre',["ADMINISTRATIVO","EGRESOS","GESTIÓN"])
                                        ->where('empleados.id_emp', $request->slctEmpleado)
                                        ->select('puestos.puestos_nombre')
                                        ->first();

                    if(!is_null($restriccionGastos)){
                        $otrosGastos = DB::table('status_cob_com')
                                                ->join('actividades','actividades.id','status_cob_com.id_actividad_fk')
                                                ->join('empleados','empleados.id_emp','status_cob_com.id_empleado_fk')
                                                ->join('puestos','puestos.id','empleados.puesto_id')
                                                ->join('estudios','estudios.id','status_cob_com.id_estudio_fk')
                                                ->join('estudiostemps','estudiostemps.id','status_cob_com.id_estudiostemps_fk')
                                                ->where([
                                                    ['status_cob_com.id_estudio_fk', $request->slctEstudio],
                                                    ['estudiostemps.fecha','<=', $request->fechaFin],
                                                ])
                                                ->select('estudiostemps.id as unico')
                                                ->distinct()->get();
                        if(!is_null($otrosGastos) and !is_null($comisionEmp)){
                            foreach($otrosGastos as $gastos){
                                $infoGastos = DB::table('estudiostemps')
                                                ->where('id',$gastos->unico)
                                                ->select('total','paciente','fecha','servicio')
                                                ->first();



                                    if(!is_null($infoGastos)){

                                            if($restriccionGastos->puestos_nombre = "ADMINISTRATIVO"){
                                                $comisionAdministrativo = ($infoGastos->total * $comisionEmp->porcentajeComision) /100;

                                                 DB::table('comisiones_temps')->insert([
                                                    'id_emp_fk' => $request->slctEmpleado,
                                                    'id_estudio_fk' => $request->slctEstudio,
                                                    'paciente' => $infoGastos->paciente,
                                                    'fechaEstudio' => $infoGastos->fecha,
                                                    'cantidad' => $infoGastos->total,
                                                    'porcentaje' => $comisionEmp->porcentajeComision,
                                                    'total' => $comisionAdministrativo,
                                                    'created_at' => $fechaInsert,
                                                    'updated_at' => $fechaInsert
                                                ]);

                                            }else if($restriccionGastos->puestos_nombre = "EGRESOS"){
                                                $comisionEgreso = ($infoGastos->total * $comisionEmp->porcentajeComision) /100;

                                                 DB::table('comisiones_temps')->insert([
                                                    'id_emp_fk' => $request->slctEmpleado,
                                                    'id_estudio_fk' => $request->slctEstudio,
                                                    'paciente' => $infoGastos->paciente,
                                                    'fechaEstudio' => $infoGastos->fecha,
                                                    'cantidad' => $infoGastos->total,
                                                    'porcentaje' => $comisionEmp->porcentajeComision,
                                                    'total' => $comisionEgreso,
                                                    'created_at' => $fechaInsert,
                                                    'updated_at' => $fechaInsert
                                                ]);

                                            }else if($restriccionGastos->puestos_nombre = "GESTIÓN"){
                                                $comisionGestion = ($infoGastos->total * $comisionEmp->porcentajeComision) /100;

                                                 DB::table('comisiones_temps')->insert([
                                                    'id_emp_fk' => $request->slctEmpleado,
                                                    'id_estudio_fk' => $request->slctEstudio,
                                                    'paciente' => $infoGastos->paciente,
                                                    'fechaEstudio' => $infoGastos->fecha,
                                                    'cantidad' => $infoGastos->total,
                                                    'porcentaje' => $comisionEmp->porcentajeComision,
                                                    'total' => $comisionGestion,
                                                    'created_at' => $fechaInsert,
                                                    'updated_at' => $fechaInsert
                                                ]);

                                            }
                                    }
                            }

                        }else{
                            if(is_null($otrosGastos)){
                                    return back()->with('resultadosVacios','El empleado no cuenta con Actividades para este EStudio en el rango de fechas ingresado');
                            }else if(is_null($comisionEmp)){
                                    return back()->with('resultadosVacios','El empleado no cuenta con porcentajes de comisión para este estudio');
                            }
                        }

                    }else if($restriccionGastos == null){
                         return back()->with('resultadosVacios','El empleado no aplica para este rubro ');
                    }

        }
        //Adicionales

       


        //Consultas para los valores de carga de empleado y estudios 
        $empleados = DB::table('empleados')
                        ->join('puestos','puestos.id','=','puesto_id')
                        ->select('id_emp',
                                    DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as empleado"),
                                        'puestos.puestos_nombre')
                        ->where('id_emp','!=',1)
                        ->orderBy('empleado','asc')
                        ->get();

        $estudios = DB::table('estudios')
                        ->select('id','dscrpMedicosPro')
                        ->orderBy('dscrpMedicosPro','asc')
                        ->get();

        //Fin de valores de los combos de busqueda 

        //Consultas para el llenado de las comisiones en el datatable
                        $comisiones = DB::table('comisiones_temps')
                            ->join('empleados','empleados.id_emp','=','comisiones_temps.id_emp_fk')
                            ->join('estudios','estudios.id','=','comisiones_temps.id_estudio_fk')
                            ->select('estudios.dscrpMedicosPro','fechaEstudio','total','paciente','cantidad','porcentaje')
                            ->where([
                                ['comisiones_temps.id_emp_fk','=',$request->slctEmpleado]
                            ])->get();

                        $totalComisiones = DB::table('comisiones_temps')
                                ->where([
                                    ['comisiones_temps.id_emp_fk','=',$request->slctEmpleado]
                                ])->sum('total');

        
       
        return view('comisiones.showComisiones',compact('empleados','estudios','comisiones','totalComisiones'));
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
                                        ['empleados.empleado_status','=','A'],
                                        ['puestos.id','!=','1']
                                    ])
                                    ->orderBy('puestos.id','asc')
                                    ->get();

        return view('catalogos.comisiones.catcomisiones',compact('lisComisiones','listEstudios','listEmpleados'));
    }

    public function calculosAdicionales(){
        
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
}