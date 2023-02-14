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
                        ->whereIn('puestos_nombre',["OPTOMETRÍA","ENFERMERÍA","RECEPCIÓN","EGRESOS","GESTIÓN","ADMINISTRATIVO"])
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
                        ->get();

        return view('comisiones.showComisiones',compact('empleados','estudios','actividades','drUtilidadInterpreta'));
    }

    public function calcularComision(Request $request){
        
        //Eliminar datos en la tabla temporal
        DB::table('comisiones_temps')->truncate();

        //Actualización de Fechas en las inserciones y actualizaciones 
        $fechaInsert = now()->toDateString();


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

            //Obtener las actividades realizadas segun el estudio y empleado antes o igual de la fecha fin
            $infoCalculoComision = DB::table('status_cob_com')
                                    ->join('actividades','actividades.id','status_cob_com.id_actividad_fk')
                                    ->join('empleados','empleados.id_emp','status_cob_com.id_empleado_fk')
                                    ->join('puestos','puestos.id','empleados.puesto_id')
                                    ->join('estudios','estudios.id','status_cob_com.id_estudio_fk')
                                    ->join('estudiostemps','estudiostemps.id','status_cob_com.id_estudiostemps_fk')
                                    ->where([
                                        ['status_cob_com.id_empleado_fk', $request->slctEmpleado],
                                        ['estudiostemps.fecha','<=', $request->fechaFin],
                                    ])
                                    ->whereIn('status_cob_com.id_estudio_fk',$request->slctEstudio)
                                    ->select('actividades.nombreActividad','estudiostemps.total','estudiostemps.paciente',
                                            'estudiostemps.fecha','status_cob_com.id as identificadorEstatus',
                                            'estudiostemps.escaneado')
                                    ->get();


                    if($request->selectCalculo == "Transcrito"){
                            $this->calculoTranscrito()
                    }else if($request->selectCalculo == "Interpretado"){

                    }else if($request->selectCalculo == "Transcrito"){

                    }else if ($request->selectCalculo == "Escaneado") {
                        
                    }else if ($request->selectCalculo == "Realizado") {
                       
                    }
            

              
        }

         
        //Fin del array del estudio


        // Llenado de tablas
        $empleados = DB::table('empleados')
                        ->join('puestos','puestos.id','=','puesto_id')
                        ->select('id_emp',
                                    DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as empleado"),
                                        'puestos.puestos_nombre')
                        ->whereIn('puestos_nombre',["OPTOMETRÍA","ENFERMERÍA","RECEPCIÓN","EGRESOS","GESTIÓN","ADMINISTRATIVO"])
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
                        ->get();

                        return view('comisiones.showComisiones',compact('empleados','estudios','actividades','drUtilidadInterpreta','fallo'));

    
    }

    public function calculoTranscrito(){

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