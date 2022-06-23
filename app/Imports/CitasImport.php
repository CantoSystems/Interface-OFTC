<?php

namespace App\Imports;

use DB;
use App\Models\citasTemp;
use Maatwebsite\Excel\Concerns\ToModel;

class CitasImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row){
        return new citasTemp([
            'fechaCita'         => $row[1],
            'paciente'          => $row[6],
            'statusCita'        => $row[7],
        ]);
    }
}