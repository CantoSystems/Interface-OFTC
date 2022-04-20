<?php

namespace App\Imports;

use App\Models\Estudiostemp;
use Maatwebsite\Excel\Concerns\ToModel;




class ReportesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row){
        
        return new Estudiostemp([
            'fecha'     => $row[0],
            'folio'     => $row[2],
            'paciente'  => $row[4],
            'servicio'  => $row[5],
            'met_pago'  => $row[6],
            'CFDI'      => $row[7],
            'subtotal'  => $row[8],
            'descuento' => $row[9],
            'iva'       => $row[10],
            'total'     => $row[11],
        ]);
    }


}
