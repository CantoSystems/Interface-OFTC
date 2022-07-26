<?php

namespace App\Http\Controllers;

use DB;

use App\Models\Empleado;
use App\Models\Puesto;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpleadoController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $listPuestos = Puesto::where('id','!=',1)->get();
        $empleados = Empleado::join('puestos','puestos.id','=','puesto_id')
                            ->select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado")
                                    ,'puestos.puestos_nombre'
                                    ,'empleados.id_emp')
                            ->where([
                                ['empleados.empleado_status','=','A'],
                                ['empleados.id_emp','!=',1]
                            ])
                            ->get();

        return view('catalogos.empleados.catempleados',compact('empleados','listPuestos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'nombreEmpleado' => 'required',
            'appEmpleado'  => 'required',
            'apmEmpleado' => 'required',
            'puestoEmp' => 'required',
        ],[
            'nombreEmpleado.required' => 'Ingrese el nombre del empleado',
            'appEmpleado.required'  => 'Ingrese el apellido paterno del empleado',
            'apmEmpleado.required' => 'Ingrese el apellido materno del empleado',
            'puestoEmp.required' => 'Seleccione el puesto del empleado',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $duplicados = Empleado::where([
            ['empleado_nombre',$request->nombreEmpleado],
            ['empleado_apellidop',$request->appEmpleado],
            ['empleado_apellidom',$request->apmEmpleado],
        ])->get();
        
        if($duplicados->count() >= 1){
            return back()->with('duplicados','El registro ingresado ya existe');
        }

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

        if($request->puestoEmp == 4){
            DB::table('doctors')->insert([
                'doctor_titulo' => "Dr.",
                'doctor_nombre' => $request->nombreEmpleado,
                'doctor_apellidop' => $request->appEmpleado,
                'doctor_apellidom' => $request->apmEmpleado,
                'doctor_status' => 'A',
                'categoria_id' => 1,
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert
            ]);
        }

        return $this->index();
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
        $listPuestos = Puesto::where('id','!=',1)->get();
        $empleado = Empleado::select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado")
                                    ,'empleado_nombre'
                                    ,'empleado_apellidop'
                                    ,'empleado_apellidom'
                                    ,'id_emp'
                                    ,'puesto_id')
                                    ->where('id_emp','=',$id)->first();

        return view('catalogos.empleados.editempleado',compact('empleado','listPuestos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit(Empleado $empleado){
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
        $listPuestos = Puesto::where('id','!=',1)->get();
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

        return view('catalogos.empleados.catempleados',compact('empleados','listPuestos'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request){
        $delEmpleado = Empleado::where('id_emp','=',$request->idEmpleadoDel)->update(['empleado_status' => 'N']);
        $listPuestos = Puesto::where('id','!=',1)->get();
        $empleados = Empleado::join('puestos','puestos.id','=','puesto_id')
                                ->select(DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) AS empleado")
                                        ,'puestos.puestos_nombre'
                                        ,'empleados.id_emp')
                                ->where([
                                    ['empleados.empleado_status','=','A'],
                                    ['empleados.id_emp','!=',1]
                                ])
                                ->get();

        return view('catalogos.empleados.catempleados',compact('empleados','listPuestos'));
    }
}