<?php

namespace App\Exports;

use App\Models\Cobranza;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class CobranzaExport implements FromView, ShouldAutoSize{
    use exportable;

    public function __construct(array $arrayEstudios, $inicio, $fin){
        $this->arrayEstudios = $arrayEstudios;
        $this->inicio = $inicio;
        $this->fin = $fin;
    }
    
    public function view(): View{
        $dataCobranza = DB::table('cobranza')
                                ->join('estudios','estudios.id','=','cobranza.id_estudio_fk')
                                ->join('cat_estudios','cat_estudios.id','=','estudios.id_estudio_fk')
                                ->join('tipo_ojos','tipo_ojos.id','=','estudios.id_ojo_fk')
                                ->join('doctors','doctors.id','=','cobranza.id_doctor_fk')
                                ->join('doctors as d','d.id','=','cobranza.id_empInt_fk')
                                ->join('empleados as e','e.id_emp','=','cobranza.id_empTrans_fk')
                                ->join('empleados as e1','e1.id_emp','=','cobranza.id_empRea_fk')
                                ->select('cobranza.folio'
                                        ,'cobranza.fecha'
                                        ,'cobranza.paciente'
                                        ,'estudios.dscrpMedicosPro as descripcion'
                                        ,'tipo_ojos.nombretipo_ojo'
                                        ,DB::raw("UPPER(CONCAT(doctors.doctor_titulo,' ',doctors.doctor_nombre,' ',doctors.doctor_apellidop)) AS Doctor")
                                        ,DB::raw('(CASE WHEN transcripcion = "S" THEN "SI" ELSE "NO" END) AS Transcripcion')
                                        ,DB::raw('(CASE WHEN interpretacion = "S" THEN "SI" ELSE "NO" END) AS Interpretacion')
                                        ,DB::raw("UPPER(CONCAT(d.doctor_titulo,' ',d.doctor_nombre,' ',d.doctor_apellidop)) AS empleadoInter")
                                        ,DB::raw("UPPER(CONCAT(e.empleado_nombre,' ',e.empleado_apellidop,' ',e.empleado_apellidom)) AS empleadoTrans")
                                        ,DB::raw("UPPER(CONCAT(e1.empleado_nombre,' ',e1.empleado_apellidop,' ',e1.empleado_apellidom)) AS EmpleadoRealiza")
                                        ,DB::raw('(CASE WHEN escaneado = "S" THEN "SI" ELSE "NO" END) AS Escaneado')
                                        ,'cobranza.cantidadCbr')
                                ->whereIn('cobranza.id_estudio_fk',$this->arrayEstudios)
                                ->whereBetween('cobranza.fecha',[$this->inicio, $this->fin])
                                ->orderBy('cobranza.fecha','ASC')
                                ->get();
        
                    return view('export-excel.cobranza-exports', [
                        'data' => $dataCobranza
                    ]);
    }
}