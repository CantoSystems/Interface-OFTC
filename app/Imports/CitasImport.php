<?php

namespace App\Imports;

use DB;
use App\Models\Cobranza;
use Maatwebsite\Excel\Concerns\ToModel;

class CitasImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row){
        foreach($row as $rw){
            dd($rw);
            /*if(DB::table('cobranza')->select('cobranza.paciente')->where('cobranza.paciente','=',$rw)->count() == '1'){
                echo "Si";
            }else{
                echo "No";
            }*/
        }
    }
}