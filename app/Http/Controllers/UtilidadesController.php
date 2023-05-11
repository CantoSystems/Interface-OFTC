<?php

namespace App\Http\Controllers;

use DB;

use App\Models\utilidades;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

class UtilidadesController extends Controller
{
    public function showUtilidades(){
     
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

        $fechaCorte = $this->fechaVigente();


        return view('utilidades.showUtilidades',compact('estudios','actividades','drUtilidadInterpreta','fechaCorte'));
    }

    public function fechaVigente(){
        $vigente =  DB::table('fechaCorte')
                        ->select('fechaCorte','id')
                        ->where('status_fechacorte',1)
                        ->latest('id')->first();
            return $vigente;
    }
   
    public function calcularUtilidades(Request $request){

        /* La tabla temporal ComisionesTemps se eliminará para insertar los nuevos datos calculos*/
        DB::table('comisiones_temps')->truncate();

        /*Variable para mantener las fechas actualizadas */
        $fechaInsert = now()->toDateString();

        /*Comprobación de las reglas de validación*/
         $validator = Validator::make($request->all(),[
            'slctEmpleado' => 'required',
            'slctEstudio'  => 'required',
        ],[
            'slctEmpleado.required' => 'Selecciona el empleado',
            'slctEstudio.required'  => 'Selecciona el estudio',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        //Colleción para capturar los fallos
        $fallo =  Collect();
        $resultPendientes = Collect();


        /*Comprobación de una fecha de corte activa*/
        $corteVigente =  $this->fechaVigente();

        
            
            if(!is_null($corteVigente)){

                
                foreach($request->slctEstudio as $arrayEstudios){

                    /*Consultamos los % para el pago de utilidades*/
                    $comisionUtilidad = DB::table('comisiones')
                                ->select('porcentajeUtilidad')
                                ->where([
                                    ['id_estudio_fk',$arrayEstudios],
                                    ['id_empleado_fk','=',$request->slctEmpleado]
                                ])->first();

                    /*Se obtienen las utilidades hasta la fecha de corte*/
                    $infoUtilidad = DB::table('status_cob_com')
                                            ->join('actividades','actividades.id','status_cob_com.id_actividad_fk')
                                            ->join('empleados','empleados.id_emp','status_cob_com.id_empleado_fk')
                                            ->join('puestos','puestos.id','empleados.puesto_id')
                                            ->join('estudios','estudios.id','status_cob_com.id_estudio_fk')
                                            ->join('estudiostemps','estudiostemps.id','status_cob_com.id_estudiostemps_fk',)
                                            ->where([
                                                ['status_cob_com.id_empleado_fk', $request->slctEmpleado],
                                                ['estudiostemps.fecha','<=', $corteVigente->fechaCorte],
                                                ['actividades.nombreActividad',"Utilidad"],
                                                ['status_cob_com.id_estudio_fk',$arrayEstudios],
                                                ['status_cob_com.statusComisiones','!=',"PAGADO"]
                                            ])
                                            ->select('actividades.nombreActividad',
                                                        'estudiostemps.paciente',
                                                        'estudiostemps.fecha',
                                                        'status_cob_com.id as identificadorEstatus',
                                                        'estudiostemps.folio as cobranzaFolio',
                                                        'status_cob_com.id_estudiostemps_fk as identificadorEstudio'
                                                    )
                                            ->get();
                        /*Se recorren los registros resultados de infoUtilidad */
                        foreach($infoUtilidad as $inf){
                            if(!is_null($inf) && !is_null($comisionUtilidad)){
                                /*Verificación sí todas las actividades de status_cob_com están pagadas*/
                                    $conteoPendientes = DB::table('status_cob_com')
                                                                    ->select('folio','id_estudio_fk')
                                                                    ->where([
                                                                            ['id_estudiostemps_fk',$inf->identificadorEstudio],
                                                                            ['id_actividad_fk','!=',10],
                                                                            ['statusComisiones',"P"]
                                                                    ])
                                                                    ->orderBy('folio','desc')
                                                                    ->get(); 

                                        if($conteoPendientes->count() === 0){
                                            /*solo en caso de que no existan actividades pendientes
                                                    Se hace la sumatoria excluyendo las actividad de entrega
                                                    */
                                                    $sumaActividades = DB::table('status_cob_com')
                                                                        ->where([
                                                                            ['id_actividad_fk','!=',3],
                                                                            ['id_actividad_fk','!=',10],
                                                                            ['id_estudiostemps_fk',$inf->identificadorEstudio]
                                                                        ])
                                                                        ->sum('cobranza_total');

                                                    $totalEstudiosTemps = DB::table('estudiostemps')
                                                                        ->select('total')
                                                                        ->where('id',$inf->identificadorEstudio)
                                                                        ->first();


                                                    /*Verificamos que el registro tenga entrega*/
                                                    $actividadEscaneo = DB::table('status_cob_com')
                                                                        ->select('cobranza_total')
                                                                        ->where([
                                                                            ['id_actividad_fk',3],
                                                                            ['id_estudiostemps_fk',$inf->identificadorEstudio]
                                                                        ])
                                                                        ->first();

                                                        if(!is_null($actividadEscaneo)){
                                                            /*Consulta para el conteo de enfermeria*/
                                                            $conteoEnfermeras = DB::table('empleados')
                                                                                ->where('puesto_id',8)
                                                                                ->count();

                                                            if(!is_null($conteoEnfermeras)){
                                                                $totalActividades = $sumaActividades+($actividadEscaneo->cobranza_total*$conteoEnfermeras);


                                                            $restante = ($totalEstudiosTemps->total - $totalActividades);

                                                           $pagoUtilidad = $restante* $comisionUtilidad->porcentajeUtilidad/100;

                                                            DB::table('status_cob_com')->where('id',$inf->identificadorEstatus)
                                                                ->update([
                                                                    'cobranza_porcentaje' => $comisionUtilidad->porcentajeUtilidad,
                                                                    'cobranza_total' => $pagoUtilidad,
                                                                    'updated_at' => $fechaInsert,
                                                            ]);

                                                            DB::table('comisiones_temps')->insert([
                                                                'id_emp_fk' => $request->slctEmpleado,
                                                                'id_estudio_fk' => $arrayEstudios,
                                                                'paciente' => $inf->paciente,
                                                                'fechaEstudio' => $inf->fecha,
                                                                'cantidad' => $totalEstudiosTemps->total,
                                                                'porcentaje' => $comisionUtilidad->porcentajeUtilidad,
                                                                'total' => $pagoUtilidad,
                                                                'created_at' => $fechaInsert,
                                                                'updated_at' => $fechaInsert,
                                                                'id_status_fk' => $inf->identificadorEstatus,
                                                                'cobranza_folio' => $inf->cobranzaFolio,
                                                                'totalsum_actividades' => $totalActividades,
                                                                'restanteUtilidad' => $restante,
                                                            ]);

                                                            }
                                                        }else if(is_null($actividadEscaneo)){

                                                            $restante = ($totalEstudiosTemps->total - $sumaActividades);
                                                            $pagoUtilidad = $restante  * $comisionUtilidad->porcentajeUtilidad/100;


                                                            DB::table('status_cob_com')->where('id',$inf->identificadorEstatus)
                                                                ->update([
                                                                    'cobranza_porcentaje' => $comisionUtilidad->porcentajeUtilidad,
                                                                    'cobranza_total' => $pagoUtilidad,
                                                                    'updated_at' => $fechaInsert,
                                                            ]);

                                                            DB::table('comisiones_temps')->insert([
                                                                'id_emp_fk' => $request->slctEmpleado,
                                                                'id_estudio_fk' => $arrayEstudios,
                                                                'paciente' => $inf->paciente,
                                                                'fechaEstudio' => $inf->fecha,
                                                                'cantidad' => $totalEstudiosTemps->total,
                                                                'porcentaje' => $comisionUtilidad->porcentajeUtilidad,
                                                                'total' => $pagoUtilidad,
                                                                'created_at' => $fechaInsert,
                                                                'updated_at' => $fechaInsert,
                                                                'id_status_fk' => $inf->identificadorEstatus,
                                                                'cobranza_folio' => $inf->cobranzaFolio,
                                                                'totalsum_actividades' => $sumaActividades,
                                                                'restanteUtilidad' => $restante,
                                                            ]);
                                                        }




                                        }else if(!is_null($conteoPendientes)){
                                            foreach($conteoPendientes as $pendientes){
                                                
                                                $coincidenciaEstudio = DB::table('estudios')
                                                                        ->select('dscrpMedicosPro')
                                                                        ->where('estudios.id', $pendientes->id_estudio_fk)
                                                                        ->first(); 

                                                $resultPendientes->push(["folio" => $pendientes->folio,"estudios" => $coincidenciaEstudio->dscrpMedicosPro]);

                                            }
                                        }  
                            }else if(is_null($comisionUtilidad)){
                                $coincidenciaEstudio = DB::table('estudios')
                                                    ->select('dscrpMedicosPro')
                                                    ->where('estudios.id', $arrayEstudios)
                                                    ->first();

                                $coincidenciaEmpleado = DB::table('empleados')
                                                    ->select( DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as emp"))
                                                    ->where('id_emp',$request->slctEmpleado)
                                                    ->first();

                                DB::table('status_cob_com')->where('id',$inf->identificadorEstatus)
                                             ->update([ 'statusComisiones' => "INFORMATIVO",
                                                'cobranza_porcentaje' =>  0,
                                                'cobranza_total' => 0,
                                                'created_at' => $fechaInsert,
                                                'updated_at' => $fechaInsert,
                                        ]);

                            }

                        }/*Cierre foreach infoUtilidad*/
                }/*Fin del cierre foreach del estudio*/

            }

            /*COLLECION PARA EMITIR FALLO*/

            if(isset($coincidenciaEstudio->dscrpMedicosPro) && isset($coincidenciaEmpleado->emp)){
                        $fallo->push(["descripcion" => $coincidenciaEstudio->dscrpMedicosPro,"empleado" => $coincidenciaEmpleado->emp]);
            }


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

            $fechaCorte = $this->fechaVigente();

            //Consultas para el llenado de las comisiones en el datatable
            $utilidadesDoctores = DB::table('comisiones_temps')
                            ->join('empleados','empleados.id_emp','=','comisiones_temps.id_emp_fk')
                            ->join('estudios','estudios.id','=','comisiones_temps.id_estudio_fk')
                            ->select('estudios.dscrpMedicosPro','fechaEstudio','total','paciente','cantidad','porcentaje','cobranza_folio','id_status_fk','totalsum_actividades','restanteUtilidad')
                            ->where([
                                ['comisiones_temps.id_emp_fk','=',$request->slctEmpleado]
                            ])
                            ->orderBy('fechaEstudio')
                            ->get();

            $totalComisionesUtilidades = DB::table('comisiones_temps')
                                ->where([
                                    ['comisiones_temps.id_emp_fk','=',$request->slctEmpleado]
                                ])->sum('total');

                      // return 
                       $result = json_decode($resultPendientes->unique());
                        
        return view('utilidades.showUtilidades',compact('estudios','actividades','drUtilidadInterpreta','fechaCorte','utilidadesDoctores','fallo','result','totalComisionesUtilidades'));


    }


    public function autorizarUtilidad(Request $request){
        if (!$request->all()) {
            return response()->json(["error" => "Sin data"]);
        }

        foreach ($request->only('info') as $value) {
            $data = json_decode($value);
        }

         /*Comprobación de una fecha de corte activa*/
        $corteVigente =  $this->fechaVigente();

        if(!is_null($corteVigente)){
            
            foreach ($data as $autorizar) {

               DB::table('status_cob_com')->where('id',$autorizar->status)
                        ->update([                                               
                            'statusComisiones' => "PAGADO",
                            'id_fcorte_fk' => $corteVigente->id,
                    ]);
            }
        }

    }
}