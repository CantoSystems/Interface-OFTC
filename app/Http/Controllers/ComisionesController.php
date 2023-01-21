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

        //Se obtiene el puesto del empleado
        $puestoEmp = DB::table('empleados')
                    ->join('puestos','puestos.id','=','empleados.puesto_id')
                    ->join('doctors'
                            ,DB::raw("CONCAT(doctors.doctor_nombre,' ',doctors.doctor_apellidop,' ',doctors.doctor_apellidom)"),'=',DB::raw("CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom)"))
                    ->select('empleados.puesto_id','doctors.id')
                    ->where('empleados.id_emp','=',$request->slctEmpleado)
                    ->first();

        dd($puestoEmp);
        /*switch ($puestoEmp->puesto_id) {
            //Si es doctor
            case '4':
                //Se obtiene la comisión de ese estudio del catálogo
                $comisionEmp = DB::table('comisiones')
                                ->select('porcentajeComision','porcentajeUtilidad')
                                ->where([
                                    ['id_estudio_fk','=',$request->slctEstudio],
                                    ['id_empleado_fk','=',$request->slctEmpleado]
                                ])->first();

                //Se traen los registros de cobranza del estudio seleccionado (Actividad: Interpretación)
                $comisionEmp = DB::table('status_cob_com')
                                ->join('estudiostemps','estudiostemps.folio','=','status_cob_com.folio')
                                ->select('status_cob_com.paciente','estudiostemps.total')
                                ->where([
                                    ['statusComisiones','=',null],
                                    ['id_estudio_fk','=',$request->slctEstudio],
                                    ['id_empleado_fk','=',$request->slctEmpleado],
                                    ['id_actividad_fk','=',2]
                                ])->get();

                dd($request);
                /*foreach($comisionEmp as $comisionesD){
                    DB::table('comisiones_temps')->insert([
                            'id_emp_fk' => $request->slctEmpleado,
                            'id_estudio_fk' => $request->slctEstudio,
                            'paciente' => $coms->paciente,
                            'fechaEstudio' => $coms->fecha,
                            'cantidad' => $comision,
                            'porcentaje' => $comisionEmp->porcentajeComision,
                            'total' => $comision,
                            'created_at' => $fechaInsert,
                            'updated_at' => $fechaInsert
                        ]);
                }

                //Se verifica si el doctor tiene utilidad. Caso "SI"
                if($comisionEmp->porcentajeUtilidad != "0"){
                    
                }else{
                    
                }
                break;
            case '5':
                break;
            case '6':
                break;
            default:
                break;
        }*/

        $comisiones = DB::table('comisiones_temps')
                            ->join('empleados','empleados.id_emp','=','comisiones_temps.id_emp_fk')
                            ->join('estudios','estudios.id','=','comisiones_temps.id_estudio_fk')
                            ->select('estudios.dscrpMedicosPro','fechaEstudio','total','paciente')
                            ->where([
                                ['comisiones_temps.id_emp_fk','=',$request->slctEmpleado],
                                ['total','!=',0]
                            ])->get();

        $totalComisiones = DB::table('comisiones_temps')
                                ->where([
                                    ['comisiones_temps.id_emp_fk','=',$request->slctEmpleado]
                                ])->sum('total');

        $empleados = DB::table('empleados')
                            ->join('puestos','puestos.id','=','puesto_id')
                            ->select('id_emp',
                                    DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as empleado"),
                                    'puestos.puestos_nombre')
                            ->where('id_emp','!=',1)
                            ->get();

        $estudios = DB::table('estudios')->select('id','dscrpMedicosPro')->get();

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