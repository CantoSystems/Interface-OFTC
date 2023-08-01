<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CobranzaExport;

use App\Models\Doctor;
use App\Models\Empleado;
use App\Models\Estudios;
use App\Models\Cobranza;
use App\Models\Estudiostemp;
use App\Models\TipoPaciente;

class CobranzaController extends Controller{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $fechaInsert = now();
        
        if($request['transRd'] == 'N'){
            $doctorTrans = '1';
        }else{
            $doctorTrans = $request["drTransc"];
        }

        if($request["intRd"] == 'N'){
            $doctorInter = '1';
        }else{
            $doctorInter = $request["drInt"];
        }

        if($request["entRd"] == 'N' || $request["entRd"] == 'P'){
            $empEntrega = '1'; 
        }else{
            $empEntrega = $request["empEnt"];
        }

        //Verifiquemos que el estudio exista
        if($request['status'] == 3){
            $checkEst = DB::table('estudios')
                        ->where('dscrpMedicosPro','=',$request['estudioCorregido'])
                        ->count();

            $request['estudioCbr'] = $request['estudioCorregido'];
        }else{
            $checkEst = DB::table('estudios')
                        ->where('dscrpMedicosPro','=',$request['estudioCbr'])
                        ->count();
        }

        if($checkEst > 0){
            if($request['registroC']=='S'){
                $validator = Validator::make($request->all(),[
                    'registroC'    => 'required',
                    'drRequiere'   => 'required',
                    'tipoPaciente' => 'required',
                    'transRd'      => 'required',
                    'intRd'        => 'required',
                    'escRd'        => 'required',
                    'entRd'        => 'required',
                    'empRealiza'   => 'required'
                ],[
                    'registroC.required'    => 'Selecciona si el registro ya está completo.',
                    'drRequiere.required'   => 'Selecciona el doctor al que requiere el estudio.',
                    'tipoPaciente.required' => 'Selecciona si el paciente es interno o externo.',
                    'escRd.required'        => 'Selecciona el status de escaneado del estudio.',
                    'entRd.required'        => 'Selecciona el status de entregado del estudio.',
                    'transRd.required'      => 'Selecciona el status de transcripción del estudio.',
                    'intRd.required'        => 'Selecciona el status de interpretación del estudio.',
                    'empRealiza.required'   => 'Selecciona el empleado que realizó el estudio.'
                ]);

                if($validator->fails()){
                    return back()->withErrors($validator)->withInput();
                }else{
                    //Primera condicional encontrar la coincidencia de la descripción del estudio
                    $estUpd = Estudios::where('dscrpMedicosPro',$request['estudioCbr'])->first();

                    if(!is_null($estUpd)){
                        //Actualizamos el registro
                        Estudiostemp::where('id',$request['identificador'])
                                        ->update([
                                            'id_empTrans_fk' => $doctorTrans,                                         
                                            'id_doctor_fk' => $request["drRequiere"],
                                            'id_empEnt_fk' => $request['empEnt'],
                                            'id_empRea_fk' => $request['empRealiza'],
                                            'id_empInt_fk' => $doctorInter,
                                            'tipoPaciente' => $request['tipoPaciente'],
                                            'servicio' => $estUpd->dscrpMedicosPro,
                                            'transcripcion' => $request['transRd'],
                                            'interpretacion' => $request['intRd'],
                                            'escaneado' => $request['escRd'],
                                            'entregado' => $request['entRd'],
                                            'observaciones' => $request['obsCobranza'],
                                            'estudiostemps_status' => 1,
                                            'registroC' => $request['registroC'],
                                            'updated_at' => $fechaInsert
                        ]);
                    }else{
                        //No coincide el  estudio
                        if($request->status == 3 && $request->estudioCorregido != null){
                            Estudiostemp::where('id',$request['identificador'])
                                            ->update([
                                                'id_empTrans_fk' => $doctorTrans,                                         
                                                'id_doctor_fk' => $request["drRequiere"],
                                                'id_empEnt_fk' => $request['empEnt'],
                                                'id_empRea_fk' => $request['empRealiza'],
                                                'id_empInt_fk' => $doctorInter,
                                                'tipoPaciente' => $request['tipoPaciente'],
                                                'servicio' => $request['estudioCorregido'],
                                                'transcripcion' => $request['transRd'],
                                                'interpretacion' => $request['intRd'],
                                                'escaneado' => $request['escRd'],
                                                'entregado' => $request['entRd'],
                                                'observaciones' => $request['obsCobranza'],
                                                'estudiostemps_status' => 1,
                                                'registroC' => $request['registroC'],
                                                'updated_at' => $fechaInsert
                                ]);
                        }else{
                            $updateStatusC = Estudiostemp::where('id',$request['identificador'])
                                                ->update([
                                                    'id_empTrans_fk' => $doctorTrans,                                                
                                                    'id_doctor_fk' => $request["drRequiere"],
                                                    'id_empEnt_fk' => $empEntrega,
                                                    'id_empRea_fk' => $request['empRealiza'],
                                                    'id_empInt_fk' => $doctorInter,
                                                    'tipoPaciente' => $request['tipoPaciente'],
                                                    'transcripcion' => $request['transRd'],
                                                    'interpretacion' => $request['intRd'],
                                                    'escaneado' => $request['escRd'],
                                                    'entregado' => $request['entRd'],
                                                    'observaciones' => $request['obsCobranza'],
                                                    'estudiostemps_status' => 3,
                                                    'registroC' => $request['registroC'],
                                                    'updated_at' => $fechaInsert
                            ]);
                        }
                    }
                }

                //registro si se realiza
                $datosStatus5 = DB::table('status_cob_com')
                                ->where([
                                    ['status_cob_com.folio', $request->folioCbr],
                                    ['status_cob_com.id_estudio_fk',$estUpd->id],
                                    ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                    ['status_cob_com.id_actividad_fk',5]
                                ])->first();

                if(!is_null($datosStatus5)){
                    if($datosStatus5->statusComisiones == "P"){
                        if(Arr::has($request,'empRealiza')){
                            DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '5',
                                'id_empleado_fk'        => $request['empRealiza'],
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"],
                                'cobranza_porcentaje'   => $datosStatus5->cobranza_porcentaje,
                                'cobranza_total'        => $datosStatus5->cobranza_total
                            ]);
                        }
                    }
                }else{
                    DB::table('status_cob_com')->insert([
                        'id_estudio_fk'         => $estUpd->id,
                        'id_estudiostemps_fk'   => $request['identificador'],
                        'folio'                 => $request['folioCbr'],
                        'id_actividad_fk'       => '5',
                        'id_empleado_fk'        => $request['empRealiza'],
                        'paciente'              => $request['pacienteCbr'],
                        'statusComisiones'      => 'P',
                        'cobranza_fecha'        => $request["fchCbr"],
                        'cobranza_cantidad'     => $request["cantidadCbr"]
                    ]);
                }

                //registro si se transcribe
                $datosStatus = DB::table('status_cob_com')
                                    ->where([
                                        ['status_cob_com.folio', $request->folioCbr],
                                        ['status_cob_com.id_estudio_fk',$estUpd->id],
                                        ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                        ['status_cob_com.id_actividad_fk',1]
                                    ])->first();

                if($request['transRd'] == "S"){
                    if(is_null($datosStatus)){
                        DB::table('status_cob_com')->insert([
                            'id_estudio_fk'         => $estUpd->id,
                            'id_estudiostemps_fk'   => $request['identificador'],
                            'folio'                 => $request['folioCbr'],
                            'id_actividad_fk'       => '1',
                            'id_empleado_fk'        => $doctorTrans,
                            'paciente'              => $request['pacienteCbr'],
                            'statusComisiones'      => 'P',
                            'cobranza_fecha'        => $request["fchCbr"],
                            'cobranza_cantidad'     => $request["cantidadCbr"]
                        ]);
                    }else{
                        if($datosStatus->statusComisiones == "P"){
                            DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '1',
                                'id_empleado_fk'        => $doctorTrans,
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"],
                                'cobranza_porcentaje'   => $datosStatus->cobranza_porcentaje,
                                'cobranza_total'        => $datosStatus->cobranza_total
                            ]);
                        }
                    }
                }else{
                    if(!is_null($datosStatus) && $datosStatus->statusComisiones == "P"){
                        $matchEstudiosTemps2 = DB::table('status_cob_com')
                                                        ->where([
                                                            ['status_cob_com.folio',$request->folioCbr],
                                                            ['status_cob_com.id_estudio_fk',$estUpd->id],
                                                            ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                                            ['status_cob_com.id_actividad_fk',1]
                                                        ])->delete();
                    }
                }

                //registro si se interpretó
                $datosStatus2 = DB::table('status_cob_com')
                                    ->where([
                                        ['status_cob_com.folio', $request->folioCbr],
                                        ['status_cob_com.id_estudio_fk',$estUpd->id],
                                        ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                        ['status_cob_com.id_actividad_fk',2]
                                    ])->first();

                if($request['intRd'] == "S"){
                    if(is_null($datosStatus2)){
                        DB::table('status_cob_com')->insert([
                            'id_estudio_fk'         => $estUpd->id,
                            'id_estudiostemps_fk'   => $request['identificador'],
                            'folio'                 => $request['folioCbr'],
                            'id_actividad_fk'       => '2',
                            'id_empleado_fk'        => $request['drInt'],
                            'paciente'              => $request['pacienteCbr'],
                            'statusComisiones'      => 'P',
                            'cobranza_fecha'        => $request["fchCbr"],
                            'cobranza_cantidad'     => $request["cantidadCbr"]
                        ]);
                    }else{
                        if($datosStatus2->statusComisiones == "P"){
                            DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '2',
                                'id_empleado_fk'        => $request['drInt'],
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"],
                                'cobranza_porcentaje'   => $datosStatus2->cobranza_porcentaje,
                                'cobranza_total'        => $datosStatus2->cobranza_total
                            ]);
                        }
                    }
                }else{
                    if(!is_null($datosStatus2) && $datosStatus2->statusComisiones == "P"){
                        $matchEstudiosTemps2 = DB::table('status_cob_com')
                                                        ->where([
                                                            ['status_cob_com.folio',$request->folioCbr],
                                                            ['status_cob_com.id_estudio_fk',$estUpd->id],
                                                            ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                                            ['status_cob_com.id_actividad_fk',2]
                                                        ])->delete();
                    }
                }
                
                //registro si se escaneó
                $datosStatus3 = DB::table('status_cob_com')
                                    ->where([
                                        ['status_cob_com.folio', $request->folioCbr],
                                        ['status_cob_com.id_estudio_fk',$estUpd->id],
                                        ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                        ['status_cob_com.id_actividad_fk',3]
                                    ])->first();
                                    
                if($request['escRd'] == "S"){
                    if(is_null($datosStatus3)){
                        DB::table('status_cob_com')->insert([
                            'id_estudio_fk'         => $estUpd->id,
                            'id_estudiostemps_fk'   => $request['identificador'],
                            'folio'                 => $request['folioCbr'],
                            'id_actividad_fk'       => '3',
                            'id_empleado_fk'        => '1',
                            'paciente'              => $request['pacienteCbr'],
                            'statusComisiones'      => 'P',
                            'cobranza_fecha'        => $request["fchCbr"],
                            'cobranza_cantidad'     => $request["cantidadCbr"]
                        ]);
                    }else{
                        if($datosStatus3->statusComisiones == "P"){
                            DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '3',
                                'id_empleado_fk'        => '1',
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"],
                                'cobranza_porcentaje'   => $datosStatus3->cobranza_porcentaje,
                                'cobranza_total'        => $datosStatus3->cobranza_total
                            ]);
                        }
                    }
                }else{
                    if(!is_null($datosStatus3) && $datosStatus3->statusComisiones == "P"){
                        $matchEstudiosTemps2 = DB::table('status_cob_com')
                                                        ->where([
                                                            ['status_cob_com.folio',$request->folioCbr],
                                                            ['status_cob_com.id_estudio_fk',$estUpd->id],
                                                            ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                                            ['status_cob_com.id_actividad_fk',3]
                                                        ])->delete();
                    }
                }

                //registro si se entregó
                $datosStatus4 = DB::table('status_cob_com')
                                ->where([
                                    ['status_cob_com.folio', $request->folioCbr],
                                    ['status_cob_com.id_estudio_fk',$estUpd->id],
                                    ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                    ['status_cob_com.id_actividad_fk',4]
                                ])->first();

                if($request['entRd'] == "S"){
                    if(is_null($datosStatus4)){
                        DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '4',
                                'id_empleado_fk'        => $request['empEnt'],
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"]
                        ]);
                    }else{
                        if($datosStatus4->statusComisiones == 'P'){
                            DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '4',
                                'id_empleado_fk'        => $request['empEnt'],
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"],
                                'cobranza_porcentaje'   => $datosStatus4->cobranza_porcentaje,
                                'cobranza_total'        => $datosStatus4->cobranza_total
                            ]);
                        }else if($datosStatus4->statusComisiones == 'RESERVADO'){
                            DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '4',
                                'id_empleado_fk'        => $request['empEnt'],
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'RESERVADO',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"]
                            ]);
                        }
                    }
                }else if($request['entRd'] == "P"){
                    if(is_null($datosStatus4)){
                        DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '4',
                                'id_empleado_fk'        => '1',
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"]
                        ]);
                    }else{
                        if(($datosStatus4->statusComisiones == 'P') || ($datosStatus4->statusComisiones == 'S')){
                            DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '4',
                                'id_empleado_fk'        => '1',
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"],
                                'cobranza_porcentaje'   => $datosStatus4->cobranza_porcentaje,
                                'cobranza_total'        => $datosStatus4->cobranza_total
                            ]);
                        }
                    }
                }else{
                    if(!is_null($datosStatus4) && $datosStatus4->statusComisiones == "P"){
                        $matchEstudiosTemps2 = DB::table('status_cob_com')
                                            ->where([
                                                ['status_cob_com.folio',$request->folioCbr],
                                                ['status_cob_com.id_estudio_fk',$estUpd->id],
                                                ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                                ['status_cob_com.id_actividad_fk',4]
                                            ])->delete();
                    }
                }

                //registro "Adicional Administrativo"
                $datosStatus7 = DB::table('status_cob_com')
                                ->where([
                                    ['status_cob_com.folio', $request->folioCbr],
                                    ['status_cob_com.id_estudio_fk',$estUpd->id],
                                    ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                    ['status_cob_com.id_actividad_fk',7]
                                ])->first();

                if(!is_null($datosStatus7)){
                    if($datosStatus7->statusComisiones == "P"){
                        DB::table('status_cob_com')->insert([
                            'id_estudio_fk'         => $estUpd->id,
                            'id_estudiostemps_fk'   => $request['identificador'],
                            'folio'                 => $request['folioCbr'],
                            'id_actividad_fk'       => '7',
                            'id_empleado_fk'        => '12',
                            'paciente'              => $request['pacienteCbr'],
                            'statusComisiones'      => 'P',
                            'cobranza_fecha'        => $request["fchCbr"],
                            'cobranza_cantidad'     => $request["cantidadCbr"],
                            'cobranza_porcentaje'   => $datosStatus7->cobranza_porcentaje,
                            'cobranza_total'        => $datosStatus7->cobranza_total
                        ]);
                    }
                }else{
                    DB::table('status_cob_com')->insert([
                        'id_estudio_fk'         => $estUpd->id,
                        'id_estudiostemps_fk'   => $request['identificador'],
                        'folio'                 => $request['folioCbr'],
                        'id_actividad_fk'       => '7',
                        'id_empleado_fk'        => '12',
                        'paciente'              => $request['pacienteCbr'],
                        'statusComisiones'      => 'P',
                        'cobranza_fecha'        => $request["fchCbr"],
                        'cobranza_cantidad'     => $request["cantidadCbr"]
                    ]);
                }

                //registro "Adicional Egresos"
                $datosStatus8 = DB::table('status_cob_com')
                                ->where([
                                    ['status_cob_com.folio', $request->folioCbr],
                                    ['status_cob_com.id_estudio_fk',$estUpd->id],
                                    ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                    ['status_cob_com.id_actividad_fk',8]
                                ])->first();

                if(!is_null($datosStatus8)){
                    if($datosStatus8->statusComisiones == "P"){
                        DB::table('status_cob_com')->insert([
                            'id_estudio_fk'         => $estUpd->id,
                            'id_estudiostemps_fk'   => $request['identificador'],
                            'folio'                 => $request['folioCbr'],
                            'id_actividad_fk'       => '8',
                            'id_empleado_fk'        => '30',
                            'paciente'              => $request['pacienteCbr'],
                            'statusComisiones'      => 'P',
                            'cobranza_fecha'        => $request["fchCbr"],
                            'cobranza_cantidad'     => $request["cantidadCbr"],
                            'cobranza_porcentaje'   => $datosStatus8->cobranza_porcentaje,
                            'cobranza_total'        => $datosStatus8->cobranza_total
                        ]);
                    }
                }else{
                    DB::table('status_cob_com')->insert([
                        'id_estudio_fk'         => $estUpd->id,
                        'id_estudiostemps_fk'   => $request['identificador'],
                        'folio'                 => $request['folioCbr'],
                        'id_actividad_fk'       => '8',
                        'id_empleado_fk'        => '30',
                        'paciente'              => $request['pacienteCbr'],
                        'statusComisiones'      => 'P',
                        'cobranza_fecha'        => $request["fchCbr"],
                        'cobranza_cantidad'     => $request["cantidadCbr"]
                    ]);
                }

                //registro "Adicional Gestion"
                $datosStatus9 = DB::table('status_cob_com')
                                ->where([
                                    ['status_cob_com.folio', $request->folioCbr],
                                    ['status_cob_com.id_estudio_fk',$estUpd->id],
                                    ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                    ['status_cob_com.id_actividad_fk',9]
                                ])->first();

                if(!is_null($datosStatus9)){
                    if($datosStatus9->statusComisiones == "P"){
                        DB::table('status_cob_com')->insert([
                            'id_estudio_fk'         => $estUpd->id,
                            'id_estudiostemps_fk'   => $request['identificador'],
                            'folio'                 => $request['folioCbr'],
                            'id_actividad_fk'       => '9',
                            'id_empleado_fk'        => '31',
                            'paciente'              => $request['pacienteCbr'],
                            'statusComisiones'      => 'P',
                            'cobranza_fecha'        => $request["fchCbr"],
                            'cobranza_cantidad'     => $request["cantidadCbr"],
                            'cobranza_porcentaje'   => $datosStatus9->cobranza_porcentaje,
                            'cobranza_total'        => $datosStatus9->cobranza_total
                        ]);
                    }
                }else{
                    DB::table('status_cob_com')->insert([
                        'id_estudio_fk'         => $estUpd->id,
                        'id_estudiostemps_fk'   => $request['identificador'],
                        'folio'                 => $request['folioCbr'],
                        'id_actividad_fk'       => '9',
                        'id_empleado_fk'        => '31',
                        'paciente'              => $request['pacienteCbr'],
                        'statusComisiones'      => 'P',
                        'cobranza_fecha'        => $request["fchCbr"],
                        'cobranza_cantidad'     => $request["cantidadCbr"]
                    ]);
                }

                //registro(s) de utilidad
                $checkutilidades = DB::table('status_cob_com')
                                    ->where([
                                        ['status_cob_com.id_estudio_fk',$estUpd->id],
                                        ['status_cob_com.id_actividad_fk',10],
                                        ['status_cob_com.paciente',$request['pacienteCbr']],
                                        ['status_cob_com.folio',$request['folioCbr']],
                                        ['status_cob_com.id_estudiostemps_fk',$request['identificador']],
                                    ])->count();

                if($checkutilidades <= 0){
                    $datosUtilidades = DB::table('comisiones')
                                    ->where([
                                        ['comisiones.id_estudio_fk',$estUpd->id],
                                        ['comisiones.porcentajeUtilidad','<>',0],
                                    ])->get();

                    foreach ($datosUtilidades as $dUt){
                        DB::table('status_cob_com')->insert([
                            'id_estudio_fk'         => $estUpd->id,
                            'id_estudiostemps_fk'   => $request['identificador'],
                            'folio'                 => $request['folioCbr'],
                            'id_actividad_fk'       => '10',
                            'id_empleado_fk'        => $dUt->id_empleado_fk,
                            'paciente'              => $request['pacienteCbr'],
                            'statusComisiones'      => 'P',
                            'cobranza_fecha'        => $request["fchCbr"],
                            'cobranza_cantidad'     => $request["cantidadCbr"]
                        ]);
                    }
                }

                DB::delete("DELETE duplicados from status_cob_com as duplicados
                            INNER JOIN status_cob_com as temporales
                            WHERE duplicados.id < temporales.id
                            AND duplicados.folio = temporales.folio
                            AND duplicados.id_estudio_fk = temporales.id_estudio_fk
                            AND duplicados.paciente = temporales.paciente
                            AND duplicados.id_actividad_fk = temporales.id_actividad_fk
                            AND duplicados.id_actividad_fk <> 10");

                $matchEstudiosTemps = DB::table('status_cob_com')
                                            ->select('status_cob_com.id_empleado_fk','actividades.aliasEstudiosTemps')
                                            ->join('empleados','empleados.id_emp','status_cob_com.id_empleado_fk')
                                            ->join('actividades','actividades.id','status_cob_com.id_actividad_fk')
                                            ->where([
                                                ['status_cob_com.folio', $request->folioCbr],
                                                ['status_cob_com.paciente', $request->pacienteCbr],
                                                ['status_cob_com.id_estudio_fk',$estUpd->id]
                                            ])->get();

                foreach($matchEstudiosTemps as $match){
                    if($match->aliasEstudiosTemps == 'drTransc'){
                        Estudiostemp::where('id',$request['identificador'])
                                        ->update([
                                            'transcripcion'         => $request['transRd'],
                                            'id_empTrans_fk'        => $request['drTransc']
                                        ]);
                    }else if($match->aliasEstudiosTemps == 'drInt'){
                        Estudiostemp::where('id',$request['identificador'])
                                        ->update([
                                            'interpretacion'        => $request['intRd'],
                                            'id_empInt_fk'          => $request['drInt']
                                        ]);
                    }else if($match->aliasEstudiosTemps == 'escRd'){
                        Estudiostemp::where('id',$request['identificador'])
                                        ->update([
                                            'escaneado'             => $request['escRd'],
                                        ]);
                    }else if($match->aliasEstudiosTemps == 'empEnt'){
                        Estudiostemp::where('id',$request['identificador'])
                                        ->update([
                                            'entregado'             => $request['entRd'],
                                            'id_empEnt_fk'          => $request['empEnt']
                                        ]);
                    }else if($match->aliasEstudiosTemps == 'empRealiza'){
                        Estudiostemp::where('id',$request['identificador'])
                                        ->update([
                                            'id_empRea_fk'          => $request['empRealiza']
                                        ]);
                    }
                }
            //Registro no completado
            }else{
                $estUpd = Estudios::where('dscrpMedicosPro',$request['estudioCbr'])->first();
                
                //Registro faltante de datos
                if($request['status'] == 3){
                    $updateStatusC = Estudiostemp::where('id',$request['identificador'])
                                                ->update([
                                                    'id_empTrans_fk' => $doctorTrans,                                                
                                                    'id_doctor_fk' => $request["drRequiere"],
                                                    'id_empEnt_fk' => $request['empEnt'],
                                                    'id_empRea_fk' => $request['empRealiza'],
                                                    'id_empInt_fk' => $doctorInter,
                                                    'tipoPaciente' => $request['tipoPaciente'],
                                                    'transcripcion' => $request['transRd'],
                                                    'servicio' => $request['estudioCorregido'],
                                                    'interpretacion' => $request['intRd'],
                                                    'escaneado' => $request['escRd'],
                                                    'entregado' => $request['entRd'],
                                                    'observaciones' => $request['obsCobranza'],
                                                    'estudiostemps_status' => 2,
                                                    'registroC' => $request['registroC'],
                                                    'updated_at' => $fechaInsert
                                                ]);
                }else{
                    $updateStatusC = Estudiostemp::where('id',$request['identificador'])
                                                ->update([
                                                    'id_empTrans_fk' => $doctorTrans,                                                
                                                    'id_doctor_fk' => $request["drRequiere"],
                                                    'id_empEnt_fk' => $request['empEnt'],
                                                    'id_empRea_fk' => $request['empRealiza'],
                                                    'id_empInt_fk' => $doctorInter,
                                                    'tipoPaciente' => $request['tipoPaciente'],
                                                    'transcripcion' => $request['transRd'],
                                                    'interpretacion' => $request['intRd'],
                                                    'escaneado' => $request['escRd'],
                                                    'entregado' => $request['entRd'],
                                                    'observaciones' => $request['obsCobranza'],
                                                    'estudiostemps_status' => 2,
                                                    'registroC' => $request['registroC'],
                                                    'updated_at' => $fechaInsert
                                                ]);
                }
                
                //registro si se realiza
                $datosStatus5 = DB::table('status_cob_com')
                                ->where([
                                    ['status_cob_com.folio', $request->folioCbr],
                                    ['status_cob_com.id_estudio_fk',$estUpd->id],
                                    ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                    ['status_cob_com.id_actividad_fk',5]
                                ])->first();

                if(!is_null($datosStatus5)){
                    if($datosStatus5->statusComisiones == "P"){
                        if(Arr::has($request,'empRealiza')){
                            DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '5',
                                'id_empleado_fk'        => $request['empRealiza'],
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"],
                                'cobranza_porcentaje'   => $datosStatus5->cobranza_porcentaje,
                                'cobranza_total'        => $datosStatus5->cobranza_total
                            ]);
                        }
                    }
                }else{
                    DB::table('status_cob_com')->insert([
                        'id_estudio_fk'         => $estUpd->id,
                        'id_estudiostemps_fk'   => $request['identificador'],
                        'folio'                 => $request['folioCbr'],
                        'id_actividad_fk'       => '5',
                        'id_empleado_fk'        => $request['empRealiza'],
                        'paciente'              => $request['pacienteCbr'],
                        'statusComisiones'      => 'P',
                        'cobranza_fecha'        => $request["fchCbr"],
                        'cobranza_cantidad'     => $request["cantidadCbr"]
                    ]);
                }

                //registro si se transcribe
                $datosStatus = DB::table('status_cob_com')
                                    ->where([
                                        ['status_cob_com.folio', $request->folioCbr],
                                        ['status_cob_com.id_estudio_fk',$estUpd->id],
                                        ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                        ['status_cob_com.id_actividad_fk',1]
                                    ])->first();

                if($request['transRd'] == "S"){
                    if(is_null($datosStatus)){
                        DB::table('status_cob_com')->insert([
                            'id_estudio_fk'         => $estUpd->id,
                            'id_estudiostemps_fk'   => $request['identificador'],
                            'folio'                 => $request['folioCbr'],
                            'id_actividad_fk'       => '1',
                            'id_empleado_fk'        => $doctorTrans,
                            'paciente'              => $request['pacienteCbr'],
                            'statusComisiones'      => 'P',
                            'cobranza_fecha'        => $request["fchCbr"],
                            'cobranza_cantidad'     => $request["cantidadCbr"]
                        ]);
                    }else{
                        if($datosStatus->statusComisiones == "P"){
                            DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '1',
                                'id_empleado_fk'        => $doctorTrans,
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"],
                                'cobranza_porcentaje'   => $datosStatus->cobranza_porcentaje,
                                'cobranza_total'        => $datosStatus->cobranza_total
                            ]);
                        }
                    }
                }else{
                    if(!is_null($datosStatus) && $datosStatus->statusComisiones == "P"){
                        $matchEstudiosTemps2 = DB::table('status_cob_com')
                                                        ->where([
                                                            ['status_cob_com.folio',$request->folioCbr],
                                                            ['status_cob_com.id_estudio_fk',$estUpd->id],
                                                            ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                                            ['status_cob_com.id_actividad_fk',1]
                                                        ])->delete();
                    }
                }

                //registro si se interpretó
                $datosStatus2 = DB::table('status_cob_com')
                                    ->where([
                                        ['status_cob_com.folio', $request->folioCbr],
                                        ['status_cob_com.id_estudio_fk',$estUpd->id],
                                        ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                        ['status_cob_com.id_actividad_fk',2]
                                    ])->first();

                if($request['intRd'] == "S"){
                    if(is_null($datosStatus2)){
                        DB::table('status_cob_com')->insert([
                            'id_estudio_fk'         => $estUpd->id,
                            'id_estudiostemps_fk'   => $request['identificador'],
                            'folio'                 => $request['folioCbr'],
                            'id_actividad_fk'       => '2',
                            'id_empleado_fk'        => $request['drInt'],
                            'paciente'              => $request['pacienteCbr'],
                            'statusComisiones'      => 'P',
                            'cobranza_fecha'        => $request["fchCbr"],
                            'cobranza_cantidad'     => $request["cantidadCbr"]
                        ]);
                    }else{
                        if($datosStatus2->statusComisiones == "P"){
                            DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '2',
                                'id_empleado_fk'        => $request['drInt'],
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"],
                                'cobranza_porcentaje'   => $datosStatus2->cobranza_porcentaje,
                                'cobranza_total'        => $datosStatus2->cobranza_total
                            ]);
                        }
                    }
                }else{
                    if(!is_null($datosStatus2) && $datosStatus2->statusComisiones == "P"){
                        $matchEstudiosTemps2 = DB::table('status_cob_com')
                                                        ->where([
                                                            ['status_cob_com.folio',$request->folioCbr],
                                                            ['status_cob_com.id_estudio_fk',$estUpd->id],
                                                            ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                                            ['status_cob_com.id_actividad_fk',2]
                                                        ])->delete();
                    }
                }
                
                //registro si se escaneó
                $datosStatus3 = DB::table('status_cob_com')
                                    ->where([
                                        ['status_cob_com.folio', $request->folioCbr],
                                        ['status_cob_com.id_estudio_fk',$estUpd->id],
                                        ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                        ['status_cob_com.id_actividad_fk',3]
                                    ])->first();
                                    
                if($request['escRd'] == "S"){
                    if(is_null($datosStatus3)){
                        DB::table('status_cob_com')->insert([
                            'id_estudio_fk'         => $estUpd->id,
                            'id_estudiostemps_fk'   => $request['identificador'],
                            'folio'                 => $request['folioCbr'],
                            'id_actividad_fk'       => '3',
                            'id_empleado_fk'        => '1',
                            'paciente'              => $request['pacienteCbr'],
                            'statusComisiones'      => 'P',
                            'cobranza_fecha'        => $request["fchCbr"],
                            'cobranza_cantidad'     => $request["cantidadCbr"]
                        ]);
                    }else{
                        if($datosStatus3->statusComisiones == "P"){
                            DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '3',
                                'id_empleado_fk'        => '1',
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"],
                                'cobranza_porcentaje'   => $datosStatus3->cobranza_porcentaje,
                                'cobranza_total'        => $datosStatus3->cobranza_total
                            ]);
                        }
                    }
                }else{
                    if(!is_null($datosStatus3) && $datosStatus3->statusComisiones == "P"){
                        $matchEstudiosTemps2 = DB::table('status_cob_com')
                                                        ->where([
                                                            ['status_cob_com.folio',$request->folioCbr],
                                                            ['status_cob_com.id_estudio_fk',$estUpd->id],
                                                            ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                                            ['status_cob_com.id_actividad_fk',3]
                                                        ])->delete();
                    }
                }

                //registro si se entregó
                $datosStatus4 = DB::table('status_cob_com')
                                ->where([
                                    ['status_cob_com.folio', $request->folioCbr],
                                    ['status_cob_com.id_estudio_fk',$estUpd->id],
                                    ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                    ['status_cob_com.id_actividad_fk',4]
                                ])->first();

                if($request['entRd'] == "S"){
                    if(is_null($datosStatus4)){
                        DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '4',
                                'id_empleado_fk'        => $request['empEnt'],
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"]
                        ]);
                    }else{
                        if($datosStatus4->statusComisiones == 'P'){
                            DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '4',
                                'id_empleado_fk'        => $request['empEnt'],
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"],
                                'cobranza_porcentaje'   => $datosStatus4->cobranza_porcentaje,
                                'cobranza_total'        => $datosStatus4->cobranza_total
                            ]);
                        }else if($datosStatus4->statusComisiones == 'RESERVADO'){
                            DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '4',
                                'id_empleado_fk'        => $request['empEnt'],
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'RESERVADO',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"]
                            ]);
                        }
                    }
                }else if($request['entRd'] == "P"){
                    if(is_null($datosStatus4)){
                        DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '4',
                                'id_empleado_fk'        => '1',
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"]
                        ]);
                    }else{
                        if(($datosStatus4->statusComisiones == 'P') || ($datosStatus4->statusComisiones == 'S')){
                            DB::table('status_cob_com')->insert([
                                'id_estudio_fk'         => $estUpd->id,
                                'id_estudiostemps_fk'   => $request['identificador'],
                                'folio'                 => $request['folioCbr'],
                                'id_actividad_fk'       => '4',
                                'id_empleado_fk'        => '1',
                                'paciente'              => $request['pacienteCbr'],
                                'statusComisiones'      => 'P',
                                'cobranza_fecha'        => $request["fchCbr"],
                                'cobranza_cantidad'     => $request["cantidadCbr"],
                                'cobranza_porcentaje'   => $datosStatus4->cobranza_porcentaje,
                                'cobranza_total'        => $datosStatus4->cobranza_total
                            ]);
                        }
                    }
                }else{
                    if(!is_null($datosStatus4) && $datosStatus4->statusComisiones == "P"){
                        $matchEstudiosTemps2 = DB::table('status_cob_com')
                                            ->where([
                                                ['status_cob_com.folio',$request->folioCbr],
                                                ['status_cob_com.id_estudio_fk',$estUpd->id],
                                                ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                                ['status_cob_com.id_actividad_fk',4]
                                            ])->delete();
                    }
                }

                //registro "Adicional Administrativo"
                $datosStatus7 = DB::table('status_cob_com')
                                ->where([
                                    ['status_cob_com.folio', $request->folioCbr],
                                    ['status_cob_com.id_estudio_fk',$estUpd->id],
                                    ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                    ['status_cob_com.id_actividad_fk',7]
                                ])->first();

                if(!is_null($datosStatus7)){
                    if($datosStatus7->statusComisiones == "P"){
                        DB::table('status_cob_com')->insert([
                            'id_estudio_fk'         => $estUpd->id,
                            'id_estudiostemps_fk'   => $request['identificador'],
                            'folio'                 => $request['folioCbr'],
                            'id_actividad_fk'       => '7',
                            'id_empleado_fk'        => '12',
                            'paciente'              => $request['pacienteCbr'],
                            'statusComisiones'      => 'P',
                            'cobranza_fecha'        => $request["fchCbr"],
                            'cobranza_cantidad'     => $request["cantidadCbr"],
                            'cobranza_porcentaje'   => $datosStatus7->cobranza_porcentaje,
                            'cobranza_total'        => $datosStatus7->cobranza_total
                        ]);
                    }
                }else{
                    DB::table('status_cob_com')->insert([
                        'id_estudio_fk'         => $estUpd->id,
                        'id_estudiostemps_fk'   => $request['identificador'],
                        'folio'                 => $request['folioCbr'],
                        'id_actividad_fk'       => '7',
                        'id_empleado_fk'        => '12',
                        'paciente'              => $request['pacienteCbr'],
                        'statusComisiones'      => 'P',
                        'cobranza_fecha'        => $request["fchCbr"],
                        'cobranza_cantidad'     => $request["cantidadCbr"]
                    ]);
                }

                //registro "Adicional Egresos"
                $datosStatus8 = DB::table('status_cob_com')
                                ->where([
                                    ['status_cob_com.folio', $request->folioCbr],
                                    ['status_cob_com.id_estudio_fk',$estUpd->id],
                                    ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                    ['status_cob_com.id_actividad_fk',8]
                                ])->first();

                if(!is_null($datosStatus8)){
                    if($datosStatus8->statusComisiones == "P"){
                        DB::table('status_cob_com')->insert([
                            'id_estudio_fk'         => $estUpd->id,
                            'id_estudiostemps_fk'   => $request['identificador'],
                            'folio'                 => $request['folioCbr'],
                            'id_actividad_fk'       => '8',
                            'id_empleado_fk'        => '30',
                            'paciente'              => $request['pacienteCbr'],
                            'statusComisiones'      => 'P',
                            'cobranza_fecha'        => $request["fchCbr"],
                            'cobranza_cantidad'     => $request["cantidadCbr"],
                            'cobranza_porcentaje'   => $datosStatus8->cobranza_porcentaje,
                            'cobranza_total'        => $datosStatus8->cobranza_total
                        ]);
                    }
                }else{
                    DB::table('status_cob_com')->insert([
                        'id_estudio_fk'         => $estUpd->id,
                        'id_estudiostemps_fk'   => $request['identificador'],
                        'folio'                 => $request['folioCbr'],
                        'id_actividad_fk'       => '8',
                        'id_empleado_fk'        => '30',
                        'paciente'              => $request['pacienteCbr'],
                        'statusComisiones'      => 'P',
                        'cobranza_fecha'        => $request["fchCbr"],
                        'cobranza_cantidad'     => $request["cantidadCbr"]
                    ]);
                }

                //registro "Adicional Gestion"
                $datosStatus9 = DB::table('status_cob_com')
                                ->where([
                                    ['status_cob_com.folio', $request->folioCbr],
                                    ['status_cob_com.id_estudio_fk',$estUpd->id],
                                    ['status_cob_com.id_estudiostemps_fk',$request->identificador],
                                    ['status_cob_com.id_actividad_fk',9]
                                ])->first();

                if(!is_null($datosStatus9)){
                    if($datosStatus9->statusComisiones == "P"){
                        DB::table('status_cob_com')->insert([
                            'id_estudio_fk'         => $estUpd->id,
                            'id_estudiostemps_fk'   => $request['identificador'],
                            'folio'                 => $request['folioCbr'],
                            'id_actividad_fk'       => '9',
                            'id_empleado_fk'        => '31',
                            'paciente'              => $request['pacienteCbr'],
                            'statusComisiones'      => 'P',
                            'cobranza_fecha'        => $request["fchCbr"],
                            'cobranza_cantidad'     => $request["cantidadCbr"],
                            'cobranza_porcentaje'   => $datosStatus9->cobranza_porcentaje,
                            'cobranza_total'        => $datosStatus9->cobranza_total
                        ]);
                    }
                }else{
                    DB::table('status_cob_com')->insert([
                        'id_estudio_fk'         => $estUpd->id,
                        'id_estudiostemps_fk'   => $request['identificador'],
                        'folio'                 => $request['folioCbr'],
                        'id_actividad_fk'       => '9',
                        'id_empleado_fk'        => '31',
                        'paciente'              => $request['pacienteCbr'],
                        'statusComisiones'      => 'P',
                        'cobranza_fecha'        => $request["fchCbr"],
                        'cobranza_cantidad'     => $request["cantidadCbr"]
                    ]);
                }

                //registro(s) de utilidad
                $checkutilidades = DB::table('status_cob_com')
                                    ->where([
                                        ['status_cob_com.id_estudio_fk',$estUpd->id],
                                        ['status_cob_com.id_actividad_fk',10],
                                        ['status_cob_com.paciente',$request['pacienteCbr']],
                                        ['status_cob_com.folio',$request['folioCbr']],
                                        ['status_cob_com.id_estudiostemps_fk',$request['identificador']],
                                    ])->count();

                if($checkutilidades <= 0){
                    $datosUtilidades = DB::table('comisiones')
                                    ->where([
                                        ['comisiones.id_estudio_fk',$estUpd->id],
                                        ['comisiones.porcentajeUtilidad','<>',0],
                                    ])->get();

                    foreach ($datosUtilidades as $dUt){
                        DB::table('status_cob_com')->insert([
                            'id_estudio_fk'         => $estUpd->id,
                            'id_estudiostemps_fk'   => $request['identificador'],
                            'folio'                 => $request['folioCbr'],
                            'id_actividad_fk'       => '10',
                            'id_empleado_fk'        => $dUt->id_empleado_fk,
                            'paciente'              => $request['pacienteCbr'],
                            'statusComisiones'      => 'P',
                            'cobranza_fecha'        => $request["fchCbr"],
                            'cobranza_cantidad'     => $request["cantidadCbr"]
                        ]);
                    }
                }

                DB::delete("DELETE duplicados from status_cob_com as duplicados
                            INNER JOIN status_cob_com as temporales
                            WHERE duplicados.id < temporales.id
                            AND duplicados.folio = temporales.folio
                            AND duplicados.id_estudio_fk = temporales.id_estudio_fk
                            AND duplicados.paciente = temporales.paciente
                            AND duplicados.id_actividad_fk = temporales.id_actividad_fk
                            AND duplicados.id_actividaD_fk <> 10");

                $matchEstudiosTemps = DB::table('status_cob_com')
                                            ->select('status_cob_com.id_empleado_fk','actividades.aliasEstudiosTemps')
                                            ->join('empleados','empleados.id_emp','status_cob_com.id_empleado_fk')
                                            ->join('actividades','actividades.id','status_cob_com.id_actividad_fk')
                                            ->where([
                                                ['status_cob_com.folio', $request->folioCbr],
                                                ['status_cob_com.paciente', $request->pacienteCbr],
                                                ['status_cob_com.id_estudio_fk',$estUpd->id]
                                            ])->get();

                foreach($matchEstudiosTemps as $match){
                    if($match->aliasEstudiosTemps == 'drTransc'){
                        Estudiostemp::where('id',$request['identificador'])
                                        ->update([
                                            'transcripcion'         => $request['transRd'],
                                            'id_empTrans_fk'        => $request['drTransc']
                                        ]);
                    }else if($match->aliasEstudiosTemps == 'drInt'){
                        Estudiostemp::where('id',$request['identificador'])
                                        ->update([
                                            'interpretacion'        => $request['intRd'],
                                            'id_empInt_fk'          => $request['drInt']
                                        ]);
                    }else if($match->aliasEstudiosTemps == 'escRd'){
                        Estudiostemp::where('id',$request['identificador'])
                                        ->update([
                                            'escaneado'             => $request['escRd'],
                                        ]);
                    }else if($match->aliasEstudiosTemps == 'empEnt'){
                        Estudiostemp::where('id',$request['identificador'])
                                        ->update([
                                            'entregado'             => $request['entRd'],
                                            'id_empEnt_fk'          => $request['empEnt']
                                        ]);
                    }else if($match->aliasEstudiosTemps == 'empRealiza'){
                        Estudiostemp::where('id',$request['identificador'])
                                        ->update([
                                            'id_empRea_fk'          => $request['empRealiza']
                                        ]);
                    }
                }
            }
        }else{
            Estudiostemp::where('id',$request['identificador'])->update(['estudiostemps_status' => 3]);

            return back()->withErrors('El estudio no se encuentra registrado en el sistema.')->withInput();
        }//Se verifica si el estudio está completo
        
        return redirect()->route('importarCobranza.index');
    }

    /**
     * Display the specified resource.
     *update
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(){
        $estudios = Estudios::join('cat_estudios','cat_estudios.id','=','id_estudio_fk')
                            ->join('tipo_ojos','tipo_ojos.id','=','id_ojo_fk')
                            ->select('estudios.id','estudios.dscrpMedicosPro as descripcion','nombretipo_ojo')
                            ->orderBy('estudios.id','ASC')
                            ->get();
        
        return view('estudios.cobranzaTbl',compact('estudios'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCobranza(Request $request){
        if ($request->estudioSelect === null) {
            $busquedaEstudios = [];
        }
        
        $busquedaEstudios = $request->estudioSelect;
        $inicio = $request->historialInicio;
        $fin    = $request->historialFinal;

        $cobranza = DB::table('cobranza')
                    ->join('estudios','estudios.id','=','cobranza.id_estudio_fk')
                    ->join('cat_estudios','cat_estudios.id','=','estudios.id_estudio_fk')
                    ->join('tipo_ojos','tipo_ojos.id','=','estudios.id_ojo_fk')
                    ->join('doctors','doctors.id','=','cobranza.id_doctor_fk')
                    ->join('empleados','empleados.id_emp','=','cobranza.id_empRea_fk')
                    ->select('cobranza.folio'
                            ,'cobranza.fecha'
                            ,'cobranza.paciente'
                            ,'cat_estudios.descripcion'
                            ,'tipo_ojos.nombretipo_ojo'
                            ,DB::raw("UPPER(CONCAT(empleados.empleado_nombre,' ',empleados.empleado_apellidop,' ',empleados.empleado_apellidom)) AS EmpleadoRealiza")
                            ,DB::raw("UPPER(CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop)) AS Doctor")
                            ,DB::raw('(CASE WHEN transcripcion = "S" THEN "SI" ELSE "NO" END) AS Transcripcion')
                            ,DB::raw('(CASE WHEN interpretacion = "S" THEN "SI" ELSE "NO" END) AS Interpretacion')
                            ,DB::raw('(CASE WHEN escaneado = "S" THEN "SI" ELSE "NO" END) AS Escaneado')
                            ,'cobranza.cantidadCbr')
                    ->whereIn('cobranza.id_estudio_fk', $busquedaEstudios)
                    ->whereBetween('cobranza.fecha',[$inicio,$fin])
                    ->orderBy('cobranza.fecha','ASC')
                    ->get();

        $estudios = Estudios::join('cat_estudios','cat_estudios.id','=','id_estudio_fk')
                            ->join('tipo_ojos','tipo_ojos.id','=','id_ojo_fk')
                            ->select('estudios.id','descripcion','nombretipo_ojo')
                            ->orderBy('estudios.id','ASC')
                            ->get();

        return view('estudios.cobranzaTbl', compact('cobranza','estudios','busquedaEstudios','inicio','fin'));
    }

    public function exportExcel(Request $request){
        $busqueda = json_decode($request->clvEstudios);
        $incio = $request->inicio;
        $fin = $request->fin;
    
        return Excel::download(new CobranzaExport($busqueda,$incio,$fin), 'ReporteCobranza.xlsx');
    }
}