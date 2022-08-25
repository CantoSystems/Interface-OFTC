<?php

namespace App\Imports;

use App\Models\DetalleTemp;
use Maatwebsite\Excel\Concerns\ToModel;

class DetalleCImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row){
        return new DetalleTemp([
            'descripcion'       => $row[0],
            'um'                => $row[1],
            'cantidad'          => $row[2],
            'precio_unitario'   => $row[3],
            'importe'           => $row[4],
        ]);
    }
}