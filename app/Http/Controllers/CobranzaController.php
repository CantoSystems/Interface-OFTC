<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CobranzaExport;

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
    public function store(Request $request){
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
        //Se verifica si el estudio está completo
        if($request['registroC']=='S'){
            $validator = Validator::make($request->all(),[
                'registroC'  => 'required',
                'drRequiere' => 'required',
                'tipoPaciente' => 'required',
                'transRd' => 'required',
                'intRd' => 'required',
                'escRd' => 'required',
                'entRd' => 'required',
                'empRealiza' => 'required'
            ],[
                'registroC.required' => 'Selecciona si el registro ya está completo.',
                'drRequiere.required' => 'Selecciona el doctor al que requiere el estudio.',
                'tipoPaciente.required' => 'Selecciona si el paciente es interno o externo.',
                'escRd.required' => 'Selecciona el status de escaneado del estudio.',
                'entRd.required' => 'Selecciona el status de entregado del estudio.',
                'transRd.required' => 'Selecciona el status de transcripción del estudio.',
                'intRd.required' => 'Selecciona el status de interpretación del estudio.',
                'empRealiza.required' => 'Selecciona el empleado que realizó el estudio.'
            ]);

            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }else{
                //Primera condicional encontrar la coincidencia de la descripción del estudio
                $estUpd = Estudios::where('dscrpMedicosPro',$request['estudioCbr'])->first();

                if(!is_null($estUpd)){
                    if($request->status != 1){
                        DB::table('cobranza')->insert([
                            'id_estudio_fk' => $estUpd->id,
                            'id_doctor_fk' => $request["drRequiere"],
                            'id_empTrans_fk' => $doctorTrans,
                            'id_empInt_fk' => $doctorInter,
                            'id_empRea_fk' => $request['empRealiza'],
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
                                                'id_empRea_fk' => $request['empRealiza'],
                                                'tipoPaciente' => $request['tipoPaciente'],
                                                'transcripcion' => $request['transRd'],
                                                'interpretacion' => $request['intRd'],
                                                'escaneado' => $request['escRd'],
                                                'entregado' => $request['entRd'],
                                                'observaciones' => $request['obsCobranza'],
                                                'estudiostemps_status' => 1,
                                                'updated_at' => $fechaInsert
                                            ]);

                        $updateStatusC = Estudiostemp::where('folio',$request['folioCbr'])
                                            ->update([
                                                'id_empTrans_fk' => $doctorTrans,                                                
                                                'id_doctor_fk' => $request["drRequiere"],
                                                'id_empInt_fk' => $doctorInter,
                                                'id_empRea_fk' => $request['empRealiza'],
                                                'tipoPaciente' => $request['tipoPaciente'],
                                                'transcripcion' => $request['transRd'],
                                                'interpretacion' => $request['intRd'],
                                                'escaneado' => $request['escRd'],
                                                'entregado' => $request['entRd'],
                                                'observaciones' => $request['obsCobranza'],
                                                'updated_at' => $fechaInsert
                                            ]);
                     //Insertar cobranza status completado
                    }elseif ($request->status == 1){
                        $updateCobranza = DB::table('cobranza')->where('folio',$request->folioCbr)
                                        ->update([
                                                'id_doctor_fk' => $request["drRequiere"],
                                                'id_empTrans_fk' => $doctorTrans,
                                                'id_empInt_fk' => $doctorInter,
                                                'id_empRea_fk' => $request['empRealiza'],
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
                                            'id_empRea_fk' => $request['empRealiza'],
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
                            'id_empRealiza_fk' => $request['empRealiza'],
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
                                                'id_empRea_fk' => $request['empRealiza'],
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
                                                'id_empRea_fk' => $request['empRealiza'],
                                                'tipoPaciente' => $request['tipoPaciente'],
                                                'transcripcion' => $request['transRd'],
                                                'interpretacion' => $request['intRd'],
                                                'escaneado' => $request['escRd'],
                                                'entregado' => $request['entRd'],
                                                'observaciones' => $request['obsCobranza'],
                                                'estudiostemps_status' => 3,
                                                'updated_at' => $fechaInsert
                                        ]);
                    }//Registro no se encuentran coincidencias
                }
            }//Fin registro contiene todos los datos          
        }else{
            //Registro faltante de datos 
            $updateStatusC = Estudiostemp::where('folio',$request['folioCbr'])
                                ->update([
                                    'id_empTrans_fk' => $doctorTrans,                                                
                                    'id_doctor_fk' => $request["drRequiere"],
                                    'id_empInt_fk' => $doctorInter,
                                    'id_empRea_fk' => $request['empRealiza'],
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

        return redirect()->route('importarCobranza.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $estudios = Estudios::join('cat_estudios','cat_estudios.id','=','id_estudio_fk')
                            ->join('tipo_ojos','tipo_ojos.id','=','id_ojo_fk')
                            ->select('estudios.id','descripcion','nombretipo_ojo')
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
        if ( $request->estudioSelect === null) {
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
                        ->select('cobranza.folio'
                                ,'cobranza.fecha'
                                ,'cobranza.paciente'
                                ,'cat_estudios.descripcion'
                                ,'tipo_ojos.nombretipo_ojo'
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