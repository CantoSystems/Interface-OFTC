<?php

namespace App\Http\Controllers;

use DB;

use App\Models\utilidades;
use Illuminate\Http\Request;

class UtilidadesController extends Controller
{
    public function showUtilidades(){
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

        $estudios = DB::table('estudios')->select('id','dscrpMedicosPro')->get();

        $actividades = DB::table('actividades')->select('id','nombreActividad')->get();

        $fechaCorte = DB::table('fechaCorte')
                        ->select('fechaCorte')
                        ->where('status_fechacorte',1)
                        ->latest('id')->first();

        return view('utilidades.showUtilidades',compact('empleados','estudios','actividades','drUtilidadInterpreta','fechaCorte'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\utilidades  $utilidades
     * @return \Illuminate\Http\Response
     */
    public function show(utilidades $utilidades)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\utilidades  $utilidades
     * @return \Illuminate\Http\Response
     */
    public function edit(utilidades $utilidades)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\utilidades  $utilidades
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, utilidades $utilidades)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\utilidades  $utilidades
     * @return \Illuminate\Http\Response
     */
    public function destroy(utilidades $utilidades)
    {
        //
    }
}