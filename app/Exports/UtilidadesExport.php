<?php

namespace App\Exports;

use App\Models\comisionesTemps;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class UtilidadesExport implements FromView, ShouldAutoSize{
    use exportable;

    public function view(): View{
        $dataEmpleado = comisionesTemps::join('empleados as e','e.id_emp','=','comisiones_temps.id_emp_fk')
                            ->select(DB::raw("CONCAT(e.empleado_nombre,' ',e.empleado_apellidop,' ',e.empleado_apellidom) as empleado"))
                            ->groupBy('empleado')
                            ->first();

        $dataComisiones = comisionesTemps::join('estudios','estudios.id','=','comisiones_temps.id_estudio_fk')
                            ->select('estudios.dscrpMedicosPro'
                                    ,'comisiones_temps.paciente'
                                    ,'comisiones_temps.fechaEstudio'
                                    ,'comisiones_temps.cantidad'
                                    ,'comisiones_temps.porcentaje'
                                    ,'comisiones_temps.total'
                                    ,'comisiones_temps.totalsum_actividades'
                                    ,'comisiones_temps.restanteUtilidad')
                            ->where('comisiones_temps.total','!=',0)
                            ->get();

        $totalComisiones = comisionesTemps::sum('total');
        
        return view('export-excel.utilidades-export', [
            'emp' => $dataEmpleado->empleado,
            'data' => $dataComisiones,
            'total' => $totalComisiones
        ]);
    }
}