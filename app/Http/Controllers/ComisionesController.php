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
            'fechaInicio'  => 'required',
            'fechaFin'     => 'required',
        ],[
            'slctEmpleado.required' => 'Selecciona el empleado',
            'slctEstudio.required'  => 'Selecciona el estudio',
            'fechaInicio.required'  => 'Selecciona la fecha de inicio',
            'fechaFin.required'     => 'Selecciona la fecha fin',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $puestoEmp = DB::table('empleados')
                        ->join('puestos','puestos.id','=','empleados.puesto_id')
                        ->select('puesto_id')
                        ->where('empleados.id_emp','=',$request->slctEmpleado)
                        ->first();

        //Comisión individual
        if($request->slctEstudio != 'TODOS'){
            $selectEstudios = DB::table('cobranza')
                                ->select('fecha','paciente','id_empTrans_fk','id_empRea_fk')
                                ->where('id_estudio_fk','=',$request->slctEstudio)
                                ->whereBetween('fecha',[$request->fechaInicio,$request->fechaFin])
                                ->get();

            if($puestoEmp->puesto_id == 2){
                $comisionEmp = Comisiones::select('cantidadComision as cantidad'
                                                ,'porcentaje'
                                                ,'cantidadUtilidad as utilidad')
                                            ->where([
                                                ['id_estudio_fk','=',$request->slctEstudio],
                                                ['id_empleado_fk','=',$request->slctEmpleado]
                                            ])->first();

                $precioEstudio = DB::table('estudios')
                                        ->select('precioEstudio')
                                        ->where('id','=',$request->slctEstudio)
                                        ->first();
                
                $totalComisiones = 0;
                foreach($selectEstudios as $estudios){
                    if(($estudios->id_empTrans_fk == $request->slctEmpleado) && ($estudios->id_empRea_fk == $request->slctEmpleado)){
                        $totalComisiones = $comisionEmp->cantidad + (($precioEstudio->precioEstudio*$comisionEmp->porcentaje)/100);
                    }else if($estudios->id_empTrans_fk == $request->slctEmpleado){
                        if($comisionEmp->cantidad != 0){
                            $totalComisiones = $comisionEmp->cantidad;
                        }else{
                            $totalComisiones = ($precioEstudio->precioEstudio*$comisionEmp->porcentaje)/100;
                        }
                    }else if($estudios->id_empRea_fk == $request->slctEmpleado){
                        if($comisionEmp->porcentaje != 0){
                            $totalComisiones = ($precioEstudio->precioEstudio*$comisionEmp->porcentaje)/100;
                        }else{
                            $totalComisiones = $comisionEmp->cantidad;
                        }
                    }else{
                        $estudios++;
                    }

                    DB::table('comisiones_temps')->insert([
                        'id_emp_fk' => $request->slctEmpleado,
                        'id_estudio_fk' => $request->slctEstudio,
                        'paciente' => $estudios->paciente,
                        'fechaEstudio' => $estudios->fecha,
                        'cantidad' => $comisionEmp->cantidad,
                        'porcentaje' => $comisionEmp->porcentaje,
                        'total' => $totalComisiones,
                        'created_at' => $fechaInsert,
                        'updated_at' => $fechaInsert
                    ]);
                    
                    $totalComisiones = 0;
                }
            }else{
                $comisionEmp = Comisiones::select('cantidadComision as cantidad'
                                                ,'porcentaje'
                                                ,'cantidadUtilidad as utilidad')
                                            ->where([
                                                ['id_estudio_fk','=',$request->slctEstudio],
                                                ['id_empleado_fk','=',$request->slctEmpleado]
                                            ])->first();

                foreach($selectEstudios as $estudios){
                    if($comisionEmp->cantidad != 0){
                        $totalCantidad = $comisionEmp->cantidad;
                        $totalComisiones = $totalCantidad;
    
                        if($comisionEmp->porcentaje != 0){
                            $totalPorcentaje = ($precioEstudio->precioEstudio*$comisionEmp->porcentaje)/100;
                            $totalComisiones = $totalComisiones + $totalPorcentaje;
    
                            if($comisionEmp->utilidad != 0){
                                $totalUtilidad = ($precioEstudio->precioEstudio*$comisionEmp->utilidad)/100;
                                $totalComisiones = $totalComisiones + $totalUtilidad;
                            }
                        }else if($comisionEmp->utilidad != 0){
                            $totalUtilidad = ($precioEstudio->precioEstudio*$comisionEmp->utilidad)/100;
                            $totalComisiones = $totalComisiones + $totalUtilidad;
                        }
    
                        DB::table('comisiones_temps')->insert([
                            'id_emp_fk' => $request->slctEmpleado,
                            'id_estudio_fk' => $request->slctEstudio,
                            'paciente' => $estudios->paciente,
                            'fechaEstudio' => $estudios->fecha,
                            'cantidad' => $comisionEmp->cantidad,
                            'porcentaje' => $comisionEmp->porcentaje,
                            'total' => $totalComisiones,
                            'created_at' => $fechaInsert,
                            'updated_at' => $fechaInsert
                        ]);
                    }else if($comisionEmp->porcentaje != 0){
                        $totalPorcentaje = ($precioEstudio->precioEstudio*$comisionEmp->porcentaje)/100;
                        $totalComisiones = $totalPorcentaje;
    
                        if($comisionEmp->utilidad != 0){
                            $totalUtilidad = ($precioEstudio->precioEstudio*$comisionEmp->utilidad)/100;
                            $totalComisiones = $totalComisiones + $totalUtilidad;
                        }
    
                        DB::table('comisiones_temps')->insert([
                            'id_emp_fk' => $request->slctEmpleado,
                            'id_estudio_fk' => $request->slctEstudio,
                            'paciente' => $estudios->paciente,
                            'fechaEstudio' => $estudios->fecha,
                            'cantidad' => $comisionEmp->cantidad,
                            'porcentaje' => $comisionEmp->porcentaje,
                            'total' => $totalComisiones,
                            'created_at' => $fechaInsert,
                            'updated_at' => $fechaInsert
                        ]);
                    }else{
                        $totalUtilidad = ($precioEstudio->precioEstudio*$comisionEmp->utilidad)/100;
                        $totalComisiones = $totalComisiones + $totalUtilidad;
    
                        DB::table('comisiones_temps')->insert([
                            'id_emp_fk' => $request->slctEmpleado,
                            'id_estudio_fk' => $request->slctEstudio,
                            'paciente' => $estudios->paciente,
                            'fechaEstudio' => $estudios->fecha,
                            'cantidad' => $comisionEmp->cantidad,
                            'porcentaje' => $comisionEmp->porcentaje,
                            'total' => $totalComisiones,
                            'created_at' => $fechaInsert,
                            'updated_at' => $fechaInsert
                        ]);
                    }
                }
            }
        //Comisión general
        }else{
            $selectEstudios = DB::table('cobranza')
                                    ->select('fecha','paciente','id_empTrans_fk','id_empRea_fk','id_estudio_fk')
                                    ->whereBetween('fecha',[$request->fechaInicio,$request->fechaFin])
                                    ->get();  

            if($puestoEmp->puesto_id == 2){
                $totalComisiones = 0;
                foreach($selectEstudios as $estudios){
                    $comisionEmp = Comisiones::select('cantidadComision as cantidad'
                                                    ,'porcentaje'
                                                    ,'cantidadUtilidad as utilidad')
                                                ->where([
                                                    ['id_estudio_fk','=',$estudios->id_estudio_fk],
                                                    ['id_empleado_fk','=',$request->slctEmpleado]
                                                ])->first();

                    $precioEstudio = DB::table('estudios')
                                        ->select('precioEstudio')
                                        ->where('id','=',$estudios->id_estudio_fk)
                                        ->first();
                    
                    if(($estudios->id_empTrans_fk == $request->slctEmpleado) && ($estudios->id_empRea_fk == $request->slctEmpleado)){
                        $totalComisiones = $comisionEmp->cantidad + (($precioEstudio->precioEstudio*$comisionEmp->porcentaje)/100);
                    }else if($estudios->id_empTrans_fk == $request->slctEmpleado){
                        if($comisionEmp->cantidad != null){
                            $totalComisiones = $comisionEmp->cantidad;
                        }else{
                            $totalComisiones = ($precioEstudio->precioEstudio*$comisionEmp->porcentaje)/100;
                        }
                    }else if($estudios->id_empRea_fk == $request->slctEmpleado){
                        if($comisionEmp->cantidad != null){
                            $totalComisiones = $comisionEmp->cantidad;
                        }else{
                            $totalComisiones = ($precioEstudio->precioEstudio*$comisionEmp->porcentaje)/100;
                        }
                    }else{
                        $estudios++;
                    }

                    DB::table('comisiones_temps')->insert([
                        'id_emp_fk' => $request->slctEmpleado,
                        'id_estudio_fk' => $estudios->id_estudio_fk,
                        'paciente' => $estudios->paciente,
                        'fechaEstudio' => $estudios->fecha,
                        'cantidad' => $comisionEmp->cantidad,
                        'porcentaje' => $comisionEmp->porcentaje,
                        'total' => $totalComisiones,
                        'created_at' => $fechaInsert,
                        'updated_at' => $fechaInsert
                    ]);

                    $totalComisiones = 0;
                }
            }else{
                foreach($selectEstudios as $estudios){
                    $comisionEmp = Comisiones::select('cantidadComision as cantidad'
                                                    ,'porcentaje'
                                                    ,'cantidadUtilidad as utilidad')
                                                ->where([
                                                    ['id_estudio_fk','=',$estudios->id_estudio_fk],
                                                    ['id_empleado_fk','=',$request->slctEmpleado]
                                                ])->first();

                    $precioEstudio = DB::table('estudios')
                                        ->select('precioEstudio')
                                        ->where('id','=',$estudios->id_estudio_fk)
                                        ->first();

                    if($comisionEmp->cantidad != 0){
                        $totalCantidad = $comisionEmp->cantidad;
                        $totalComisiones = $totalCantidad;
    
                        if($comisionEmp->porcentaje != 0){
                            $totalPorcentaje = ($precioEstudio->precioEstudio*$comisionEmp->porcentaje)/100;
                            $totalComisiones = $totalComisiones + $totalPorcentaje;
    
                            if($comisionEmp->utilidad != 0){
                                $totalUtilidad = ($precioEstudio->precioEstudio*$comisionEmp->utilidad)/100;
                                $totalComisiones = $totalComisiones + $totalUtilidad;
                            }
                        }else if($comisionEmp->utilidad != 0){
                            $totalUtilidad = ($precioEstudio->precioEstudio*$comisionEmp->utilidad)/100;
                            $totalComisiones = $totalComisiones + $totalUtilidad;
                        }
    
                        DB::table('comisiones_temps')->insert([
                            'id_emp_fk' => $request->slctEmpleado,
                            'id_estudio_fk' => $estudios->id_estudio_fk,
                            'paciente' => $estudios->paciente,
                            'fechaEstudio' => $estudios->fecha,
                            'cantidad' => $comisionEmp->cantidad,
                            'porcentaje' => $comisionEmp->porcentaje,
                            'total' => $totalComisiones,
                            'created_at' => $fechaInsert,
                            'updated_at' => $fechaInsert
                        ]);
                    }else if($comisionEmp->porcentaje != 0){
                        $totalPorcentaje = ($precioEstudio->precioEstudio*$comisionEmp->porcentaje)/100;
                        $totalComisiones = $totalPorcentaje;
    
                        if($comisionEmp->utilidad != 0){
                            $totalUtilidad = ($precioEstudio->precioEstudio*$comisionEmp->utilidad)/100;
                            $totalComisiones = $totalComisiones + $totalUtilidad;
                        }
    
                        DB::table('comisiones_temps')->insert([
                            'id_emp_fk' => $request->slctEmpleado,
                            'id_estudio_fk' => $estudios->id_estudio_fk,
                            'paciente' => $estudios->paciente,
                            'fechaEstudio' => $estudios->fecha,
                            'cantidad' => $comisionEmp->cantidad,
                            'porcentaje' => $comisionEmp->porcentaje,
                            'total' => $totalComisiones,
                            'created_at' => $fechaInsert,
                            'updated_at' => $fechaInsert
                        ]);
                    }else{
                        $totalUtilidad = ($precioEstudio->precioEstudio*$comisionEmp->utilidad)/100;
                        $totalComisiones = $totalComisiones + $totalUtilidad;
    
                        DB::table('comisiones_temps')->insert([
                            'id_emp_fk' => $request->slctEmpleado,
                            'id_estudio_fk' => $estudios->id_estudio_fk,
                            'paciente' => $estudios->paciente,
                            'fechaEstudio' => $estudios->fecha,
                            'cantidad' => $comisionEmp->cantidad,
                            'porcentaje' => $comisionEmp->porcentaje,
                            'total' => $totalComisiones,
                            'created_at' => $fechaInsert,
                            'updated_at' => $fechaInsert
                        ]);
                    }
                }
            }
        }

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
                                    ->select(DB::raw("CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom) AS Empleado"),
                                             DB::raw("CONCAT(cat_estudios.descripcion,' ',tipo_ojos.nombretipo_ojo) AS Estudio")
                                                    ,'comisiones.cantidadComision'
                                                    ,'comisiones.porcentaje'
                                                    ,'comisiones.cantidadUtilidad'
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'estudioGral' => 'required',
            'empleadoComision' => 'required',
            'cantidadComision' => 'required',
            'porcentajeComision'      => 'required',
        ],[
            'estudioGral.required' => 'Seleccciona el Estudio',
            'empleadoComision.required' => 'Selecciona el Empleado',
            'cantidadComison.required' => 'Ingresa la cantidad de Comisión',
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
            'cantidadComision' => $request->cantidadComision,
            'cantidadUtilidad' => $request->utilidadComision,
            'porcentaje' => $request->porcentajeComision,
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
                              ->select(DB::raw("CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom) AS empleado")
                                                ,'estudios.dscrpMedicosPro'
                                                ,'comisiones.id_estudio_fk'
                                                ,'comisiones.id_empleado_fk'
                                                ,'comisiones.cantidadComision'
                                                ,'comisiones.cantidadUtilidad'
                                                ,'comisiones.porcentaje'
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
        $editComision->id_doctor_fk = $request->estudioGral;
        $editComision->cantidadComision = $request->cantidadComision;
        $editComision->cantidadUtilidad = $request->cantidadUtilidad;
        $editComision->porcentaje = $request->porcentajeComision;
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