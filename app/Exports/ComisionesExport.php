<?php

namespace App\Exports;

use App\Models\comisionesTemps;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use DB;

class ComisionesExport implements FromView{
    use exportable;

    public function view(): View{
        $dataComisiones = comisionesTemps::join('empleados','empleados.id_emp','=','comisiones_temps.id_emp_fk')
                                        ->join('estudios','estudios.id','=','comisiones_temps.id_estudio_fk')
                                        ->select('estudios.dscrpMedicosPro'
                                                ,'comisiones_temps.paciente'
                                                ,'comisiones_temps.fechaEstudio'
                                                ,'comisiones_temps.cantidad')
                                        ->get();

        $totalComisiones = comisionesTemps::sum('cantidad');
        
        return view('export-excel.comisiones-export', [
            'data' => $dataComisiones,
            'total' => $totalComisiones
        ]);
    }
}