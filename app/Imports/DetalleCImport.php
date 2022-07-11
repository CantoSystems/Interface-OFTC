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
            'codigo'            => $row[0],
            'descripcion'       => $row[1],
            'um'                => $row[2],
            'cantidad'          => $row[3],
            'precio_unitario'   => $row[4],
            'importe'           => $row[5],
        ]);
    }
}