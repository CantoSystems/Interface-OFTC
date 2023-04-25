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
                        ->select('fechaCorte')
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
                                                ['status_cob_com.id_estudio_fk',$arrayEstudios]
                                            ])
                                            ->select('actividades.nombreActividad',
                                                        'estudiostemps.paciente',
                                                        'estudiostemps.fecha',
                                                        'status_cob_com.id as identificadorEstatus',
                                                        'estudiostemps.folio as cobranzaFolio',
                                                        'status_cob_com.id_estudiostemps_fk as identificadorEstudio'
                                                    )
                                            ->get();
                            dd($infoUtilidad);

                        if(!is_null($infoUtilidad)){
                            foreach($infoUtilidad as $inf){
                                if(!is_null($comisionUtilidad)){
                                    $this->pagoUtilidad($inf->identificadorEstudio);
                                }else if(is_null($comisionUtilidaddad)){
                                    $coincidenciaEstudio = DB::table('estudios')
                                                    ->select('dscrpMedicosPro')
                                                    ->where('estudios.id', $arrayEstudios)
                                                    ->first();

                                    $coincidenciaEmpleado = DB::table('empleados')
                                                    ->select( DB::raw("CONCAT(empleado_nombre,' ',empleado_apellidop,' ',empleado_apellidom) as emp"))
                                                    ->where('id_emp',$request->slctEmpleado)
                                                    ->first();

                                        DB::table('status_cob_com')->where('id',$inf->identificadorEstatus)
                                             ->update([                                               
                                                'statusComisiones' => "INFORMATIVO",
                                                'cobranza_porcentaje' =>  0,
                                                'cobranza_total' => 0,
                                                'created_at' => $fechaInsert,
                                                'updated_at' => $fechaInsert,
                                        ]);
                                }

                            }
                        }
                }/*Fin del cierre foreach del estudio*/

            }else if(is_null($corteVigente)){

            }

            /*COLLECION PARA EMITIR FALLO*/

            if(isset($coincidenciaEstudio->dscrpMedicosPro) && isset($coincidenciaEmpleado->emp)){
                        $fallo->push(["descripcion" => $coincidenciaEstudio->dscrpMedicosPro,"empleado" => $coincidenciaEmpleado->emp]);
            }

    }

    public function pagoUtilidad($identificadorEstudio){
        /*Verificación sí todas las actividades de status_cob_com están pagadas*/

            $conteoPendientes = DB::table('status_cob_com')
                                            ->where([
                                                ['id_estudiostemps_fk',$identificadorEstudio],
                                                ['id_actividad_fk',]
                                            ])
                                            ->count('statusComisiones');
                return $identificadorEstudio;

    }
}