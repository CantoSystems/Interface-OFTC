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

class ComisionesController extends Controller{
    public function showComisiones(){
        $empleados = DB::table('empleados')
                        ->join('puestos','puestos.id','=','puesto_id')
                        ->select('id_emp',
                                 DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as empleado"),
                                 'puestos.puestos_nombre')
                        ->where('id_emp','!=',1)
                        ->get();

        $estudios = DB::table('estudios')
                        ->select('id','dscrpMedicosPro')
                        ->get();

        return view('comisiones.showComisiones',compact('empleados','estudios'));
    }

    public function calcularComision(Request $request){
        DB::table('comisiones_Temps')->truncate();

        $puestoEmp = DB::table('empleados')
                        ->join('puestos','puestos.id','=','empleados.puesto_id')
                        ->select('puesto_id')
                        ->where('empleados.id_emp','=',$request->slctEmpleado)
                        ->first();

        if(($puestoEmp->puesto_id == 2) || ($puestoEmp->puesto_id == 3)){
            switch($puestoEmp->puesto_id){
                case 2:
                    $selectEstudios = DB::table('cobranza')
                                    ->select('fecha','paciente')
                                    ->where([
                                        ['id_estudio_fk','=',$request->slctEstudio],
                                        ['transcripcion','=','S'],
                                        ['id_empTrans_fk','=',$request->slctEmpleado]
                                    ])
                                    ->whereBetween('fecha',[$request->fechaInicio,$request->fechaFin])
                                    ->get();
                    break;
                case 3:
                    $selectEstudios = DB::table('cobranza')
                                    ->select('fecha','paciente')
                                    ->where([
                                        ['id_estudio_fk','=',$request->slctEstudio],
                                        ['interpretacion','=','S'],
                                        ['id_empTrans_fk','=',$request->slctEmpleado]
                                    ])
                                    ->whereBetween('fecha',[$request->fechaInicio,$request->fechaFin])
                                    ->get();
                    break;             
            }
        }else{
            $selectEstudios = DB::table('cobranza')
                                ->select('fecha','paciente')
                                ->where('id_estudio_fk','=',$request->slctEstudio)
                                ->whereBetween('fecha',[$request->fechaInicio,$request->fechaFin])
                                ->get();
        }

        $comisionEmp = Comisiones::select('cantidadComision','porcentaje')
                        ->where([
                            ['id_estudio_fk','=',$request->slctEstudio],
                            ['id_empleado_fk','=',$request->slctEmpleado]
                        ])->first();

        $fechaInsert = now()->toDateString();
        foreach($selectEstudios as $estudios){
            if($comisionEmp->cantidad != 0){        
                DB::table('comisiones_temps')->insert([
                    'id_emp_fk' => $request->slctEmpleado,
                    'paciente' => $estudios->paciente,
                    'id_estudio_fk' => $request->slctEstudio,
                    'fechaEstudio' => $estudios->fecha,
                    'cantidad' => $comisionEmp->cantidad,
                    'created_at' => $fechaInsert,
                    'updated_at' => $fechaInsert
                ]);
            }else{
                $precioEstudio = DB::table('estudios')
                                    ->select('precioEstudio')
                                    ->where('id','=',$request->slctEstudio)
                                    ->first();

                $totalCantidad = ($precioEstudio->precioEstudio*$comisionEmp->porcentaje)/100;

                DB::table('comisiones_temps')->insert([
                    'id_emp_fk' => $request->slctEmpleado,
                    'id_estudio_fk' => $request->slctEstudio,
                    'paciente' => $estudios->paciente,
                    'fechaEstudio' => $estudios->fecha,
                    'cantidad' => $totalCantidad,
                    'created_at' => $fechaInsert,
                    'updated_at' => $fechaInsert
                ]);
            }
        }

        $comisiones = DB::table('comisiones_temps')
                        ->join('empleados','empleados.id_emp','=','comisiones_temps.id_emp_fk')
                        ->join('estudios','estudios.id','=','comisiones_temps.id_estudio_fk')
                        ->select('estudios.dscrpMedicosPro','fechaEstudio','cantidad','paciente')
                        ->where([
                            ['comisiones_temps.id_estudio_fk','=',$request->slctEstudio],
                            ['comisiones_temps.id_emp_fk','=',$request->slctEmpleado]
                        ])->get();

        $totalComisiones = DB::table('comisiones_temps')
                                ->where([
                                    ['comisiones_temps.id_estudio_fk','=',$request->slctEstudio],
                                    ['comisiones_temps.id_emp_fk','=',$request->slctEmpleado]
                                ])->sum('cantidad');

        $empleados = DB::table('empleados')
                        ->join('puestos','puestos.id','=','puesto_id')
                        ->select('id_emp',
                                 DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as empleado"),
                                 'puestos.puestos_nombre')
                        ->where('id_emp','!=',1)
                        ->get();

        $estudios = DB::table('estudios')
                        ->select('id','dscrpMedicosPro')
                        ->get();

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
                                                    ,'comisiones.id')
                                    ->get();

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

        return view('catalogos.comisiones.catcomisiones',compact('lisComisiones','listEstudios','listEmpleados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
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

        $lisComisiones = Comisiones::join('estudios','estudios.id','=','comisiones.id_estudio_fk')
                                    ->join('empleados','empleados.id_emp','=','comisiones.id_empleado_fk')
                                    ->join('tipo_ojos','tipo_ojos.id','=','estudios.id_ojo_fk')
                                    ->join('cat_estudios','cat_estudios.id','=','estudios.id_estudio_fk')
                                    ->select(DB::raw("CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom) AS Empleado"),
                                             DB::raw("CONCAT(cat_estudios.descripcion,' ',tipo_ojos.nombretipo_ojo) AS Estudio")
                                                    ,'comisiones.cantidadComision'
                                                    ,'comisiones.porcentaje'
                                                    ,'comisiones.id')             
                                    ->get();

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

        return view('catalogos.comisiones.catcomisiones',compact('lisComisiones','listEstudios','listEmpleados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comisiones  $comisiones
     * @return \Illuminate\Http\Response
     */
    public function edit(Comisiones $comisiones)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comisiones  $comisiones
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){
        if($request->cantidadUtilidad != null){
            $editComision = Comisiones::find($request->idComision);
            $editComision->id_estudio_fk = $request->estudioGral;
            $editComision->cantidadComision = $request->cantidadComision;
            $editComision->cantidadUtilidad = $request->cantidadUtilidad;
            $editComision->porcentaje = $request->porcentajeComision;
            $editComision->save();
        }else{
            $editComision = Comisiones::find($request->idComision);
            $editComision->id_estudio_fk = $request->estudioGral;
            $editComision->cantidadComision = $request->cantidadComision;
            $editComision->cantidadUtilidad = 0;
            $editComision->porcentaje = $request->porcentajeComision;
            $editComision->save();
        }
        
        $lisComisiones = Comisiones::join('estudios','estudios.id','=','comisiones.id_estudio_fk')
                                    ->join('empleados','empleados.id_emp','=','comisiones.id_empleado_fk')
                                    ->join('tipo_ojos','tipo_ojos.id','=','estudios.id_ojo_fk')
                                    ->join('cat_estudios','cat_estudios.id','=','estudios.id_estudio_fk')
                                    ->select(DB::raw("CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom) AS Empleado"),
                                             DB::raw("CONCAT(cat_estudios.descripcion,' ',tipo_ojos.nombretipo_ojo) AS Estudio")
                                                    ,'comisiones.cantidadComision'
                                                    ,'comisiones.porcentaje'
                                                    ,'comisiones.id')
                                    ->get();

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

        return view('catalogos.comisiones.catcomisiones',compact('lisComisiones','listEstudios','listEmpleados'));
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