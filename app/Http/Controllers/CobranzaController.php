<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Doctor;
use App\Models\Empleado;
use App\Models\Estudios;
use App\Models\Cobranza;
use App\Models\Estudiostemp;
use App\Models\TipoPaciente;


class CobranzaController extends Controller
{
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
        //dd($request);
        $validator = Validator::make($request->all(),[
            'registroC'  => 'required',
            'drRequiere' => 'required',
            'tipoPaciente' => 'required',
            'escRd' => 'required',
            'entRd' => 'required',
            'transRd' => 'required',
            'intRd' => 'required',
        ],[
            'registroC.required' => 'Selecciona si el registro ya está completo.',
            'drRequiere.required' => 'Selecciona el doctor al que requiere el estudio.',
            'tipoPaciente.required' => 'Selecciona si el paciente es interno o externo.',
            'escRd.required' => 'Selecciona el status de escaneado del estudio.',
            'entRd.required' => 'Selecciona el status de entregado del estudio.',
            'transRd.required' => 'Selecciona el status de transcripción del estudio.',
            'intRd.required' => 'Selecciona el status de interpretación del estudio.',
        ]);

        if($request['transRd'] == 'N'){
            $doctorTrans = '1';
        }else{
            $doctorTrans = $request["drTransc"];
        }

        if($request["intRd"] == 'N'){
            $doctorInter = '1';
        }else{
            $doctorInter = $request["drInterpreta"];
        }

        $fechaInsert = now();
        //->toDateString();

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }else{
            if($request['registroC']=='S'){
                //Primera condicional encontrar la coincidencia de la descripción del estudio
                $estUpd = Estudios::where('dscrpMedicosPro',$request['estudioCbr'])->first();

                if(!is_null($estUpd)){
                    if($request->status != 1){
                        DB::table('cobranza')->insert([
                            'id_estudio_fk' => $estUpd->id,
                            'id_doctor_fk' => $request["drRequiere"],
                            'id_empTrans_fk' => $doctorTrans,
                            'id_empInt_fk' => $doctorInter,
                            'folio' => $request['folioCbr'],
                            'fecha' => $request['fchCbr'],
                            'paciente' => $request['pacienteCbr'],
                            'tipoPaciente' => $request['tipoPaciente'],
                            'formaPago' => $request['formaPago'],
                            'transcripcion' => $request['transRd'],
                            'interpretacion' => $request['intRd'],
                            'escaneado' => $request['escRd'],
                            'cantidadCbr' => $request['cantidadCbr'],
                            'observaciones' => $request['obsCobranza'],
                            'entregado' => $request['entRd'],
                            'created_at' => $fechaInsert,
                            'updated_at' => $fechaInsert
                        ]);
                    
                        $updateStatusC = Estudiostemp::where('folio',$request['folioCbr'])
                                            ->update([
                                                'id_empTrans_fk' => $doctorTrans,                                                
                                                'id_doctor_fk' => $request["drRequiere"],
                                                'id_empInt_fk' => $doctorInter,
                                                'tipoPaciente' => $request['tipoPaciente'],
                                                'transcripcion' => $request['transRd'],
                                                'interpretacion' => $request['intRd'],
                                                'escaneado' => $request['escRd'],
                                                'entregado' => $request['entRd'],
                                                'observaciones' => $request['obsCobranza'],
                                                'estudiostemps_status' => 1,
                                                'updated_at' => $fechaInsert
                                       ]);
                     //Insertar cobranza status completado
                    }elseif ($request->status == 1){
                        $updateCobranza = DB::table('cobranza')->where('folio',$request->folioCbr)
                                        ->update([
                                                'id_doctor_fk' => $request["drRequiere"],
                                                'id_empTrans_fk' => $doctorTrans,
                                                'id_empInt_fk' => $doctorInter,
                                                'tipoPaciente' => $request['tipoPaciente'],
                                                'transcripcion' => $request['transRd'],
                                                'interpretacion' => $request['intRd'],
                                                'escaneado' => $request['escRd'],
                                                'observaciones' => $request['obsCobranza'],
                                                'entregado' => $request['entRd'],
                                                'created_at' => $fechaInsert,
                                                'updated_at' => $fechaInsert
                                        ]);
    
                        $updateStatusC = Estudiostemp::where('folio',$request['folioCbr'])
                                        ->update([
                                            'id_empTrans_fk' => $doctorTrans,                                                
                                            'id_doctor_fk' => $request["drRequiere"],
                                            'id_empInt_fk' => $doctorInter,
                                            'tipoPaciente' => $request['tipoPaciente'],
                                            'transcripcion' => $request['transRd'],
                                            'interpretacion' => $request['intRd'],
                                            'escaneado' => $request['escRd'],
                                            'entregado' => $request['entRd'],
                                            'observaciones' => $request['obsCobranza'],
                                            'updated_at' => $fechaInsert
                                        ]);
                    //El registro se actualiza en estudiostemps y en cobranza 
                    }//Fin para insertar o actualizar datos
                //Fin del registro cuando se encuentra el estudio
                }else{
                    if($request->status == 3 && $request->estudioCorregido != null){
                        DB::table('cobranza')->insert([
                            'id_estudio_fk' => $request->estudioCorregido,
                            'id_doctor_fk' => $request["drRequiere"],
                            'id_empTrans_fk' => $doctorTrans,
                            'id_empInt_fk' => $doctorInter,
                            'folio' => $request['folioCbr'],
                            'fecha' => $request['fchCbr'],
                            'paciente' => $request['pacienteCbr'],
                            'tipoPaciente' => $request['tipoPaciente'],
                            'formaPago' => $request['formaPago'],
                            'transcripcion' => $request['transRd'],
                            'interpretacion' => $request['intRd'],
                            'escaneado' => $request['escRd'],
                            'cantidadCbr' => $request['cantidadCbr'],
                            'observaciones' => $request['obsCobranza'],
                            'entregado' => $request['entRd'],
                            'created_at' => $fechaInsert,
                            'updated_at' => $fechaInsert
                        ]);
                        $descripcion = Estudios::select('dscrpMedicosPro')
                                            ->where('id',$request->estudioCorregido)
                                            ->first();
                        
                        Estudiostemp::where('folio',$request['folioCbr'])
                                            ->update([
                                                'id_empTrans_fk' => $doctorTrans,                                                
                                                'id_doctor_fk' => $request["drRequiere"],
                                                'id_empInt_fk' => $doctorInter,
                                                'tipoPaciente' => $request['tipoPaciente'],
                                                'servicio' => $descripcion->dscrpMedicosPro,
                                                'transcripcion' => $request['transRd'],
                                                'interpretacion' => $request['intRd'],
                                                'escaneado' => $request['escRd'],
                                                'entregado' => $request['entRd'],
                                                'observaciones' => $request['obsCobranza'],
                                                'estudiostemps_status' => 1,
                                                'updated_at' => $fechaInsert
                                            ]);
                    }else{
                        $updateStatusC = Estudiostemp::where('folio',$request['folioCbr'])
                                        ->update([
                                                'id_empTrans_fk' => $doctorTrans,                                                
                                                'id_doctor_fk' => $request["drRequiere"],
                                                'id_empInt_fk' => $doctorInter,
                                                'tipoPaciente' => $request['tipoPaciente'],
                                                'transcripcion' => $request['transRd'],
                                                'interpretacion' => $request['intRd'],
                                                'escaneado' => $request['escRd'],
                                                'entregado' => $request['entRd'],
                                                'observaciones' => $request['obsCobranza'],
                                                'estudiostemps_status' => 3,
                                                'updated_at' => $fechaInsert
                                        ]);
                    }
                //REgistro no se encuentran coincidencias
                }
            //Fin registro contiene todos los datos          
            }else{
                //Registro faltante de datos 
                $updateStatusC = Estudiostemp::where('folio',$request['folioCbr'])
                ->update([
                    'id_empTrans_fk' => $doctorTrans,                                                
                    'id_doctor_fk' => $request["drRequiere"],
                    'id_empInt_fk' => $doctorInter,
                    'tipoPaciente' => $request['tipoPaciente'],
                    'transcripcion' => $request['transRd'],
                    'interpretacion' => $request['intRd'],
                    'escaneado' => $request['escRd'],
                    'entregado' => $request['entRd'],
                    'observaciones' => $request['obsCobranza'],
                    'estudiostemps_status' => 2,
                    'updated_at' => $fechaInsert
                ]);
            }//Fin contiene todos los datos
        }//Fin validación request
        return redirect()->route('importarCobranza.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
