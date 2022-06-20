<?php

namespace App\Http\Controllers;

use DB;

use App\Models\Empleado;
use App\Models\Puesto;

use Illuminate\Http\Request;

class EmpleadoController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $empleados = Empleado::join('puestos','puestos.id','=','puesto_id')
                            ->select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado")
                                    ,'puestos.puestos_nombre'
                                    ,'empleados.id_emp')
                            ->where([
                                ['empleados.empleado_status','=','A'],
                                ['empleados.id_emp','!=',1]
                            ])
                            ->get();

        $listPuestos = Puesto::where('id','!=',1)->get();

        return view('catalogos.empleados.catempleados',compact('empleados','listPuestos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        $fechaInsert = now()->toDateString();
        DB::table('empleados')->insert([
            'empleado_nombre' => $request->nombreEmpleado,
            'empleado_apellidop' => $request->appEmpleado,
            'empleado_apellidom' => $request->apmEmpleado,
            'empleado_status' => 'A',
            'puesto_id' => $request->puestoEmp,
            'created_at' => $fechaInsert,
            'updated_at' => $fechaInsert
        ]);

        $empleados = Empleado::join('puestos','puestos.id','=','puesto_id')
                            ->select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado")
                                    ,'puestos.puestos_nombre'
                                    ,'empleados.id_emp')
                            ->where([
                                ['empleados.empleado_status','=','A'],
                                ['empleados.id_emp','!=',1]
                            ])
                            ->get();

        $listPuestos = Puesto::where('id','!=',1)->get();

        return view('catalogos.empleados.catempleados',compact('empleados','listPuestos'));
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
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $empleado = Empleado::select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado")
                                    ,'empleado_nombre'
                                    ,'empleado_apellidop'
                                    ,'empleado_apellidom'
                                    ,'id_emp'
                                    ,'puesto_id')
                                    ->where('id_emp','=',$id)->first();

        $listPuestos = Puesto::where('id','!=',1)->get();

        return view('catalogos.empleados.editempleado',compact('empleado','listPuestos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit(Empleado $empleado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){
        $nvoEmpleado = Empleado::where('id_emp','=',$request->idEmpleado)
                                ->update(['empleado_nombre' => $request->nombreEmpleado,
                                         'empleado_apellidop' => $request->appEmpleado,
                                         'empleado_apellidom' => $request->apmEmpleado,
                                         'puesto_id' => $request->puestoEmp
                                ]);

        $empleados = Empleado::join('puestos','puestos.id','=','puesto_id')
                                ->select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado")
                                        ,'puestos.puestos_nombre'
                                        ,'empleados.id_emp')
                                ->where([
                                    ['empleados.empleado_status','=','A'],
                                    ['empleados.id_emp','!=',1]
                                ])
                                ->get();
    
        $listPuestos = Puesto::where('id','!=',1)->get();

        return view('catalogos.empleados.catempleados',compact('empleados','listPuestos'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request){
        $delEmpleado = Empleado::where('id_emp','=',$request->idEmpleadoDel)
                                ->update(['empleado_status' => 'N']);

        $empleados = Empleado::join('puestos','puestos.id','=','puesto_id')
                                ->select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado")
                                        ,'puestos.puestos_nombre'
                                        ,'empleados.id_emp')
                                ->where([
                                    ['empleados.empleado_status','=','A'],
                                    ['empleados.id_emp','!=',1]
                                ])
                                ->get();
    
        $listPuestos = Puesto::where('id','!=',1)->get();

        return view('catalogos.empleados.catempleados',compact('empleados','listPuestos'));
    }
}