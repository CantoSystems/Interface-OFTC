<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class comisionesEmpSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $comisionesEmps = Collect([
            //FAG
            ["id_estudio_fk" => "1", "id_empleado_fk" => "14", "cantidad" => "250", "porcentaje" => "0"],
            ["id_estudio_fk" => "2", "id_empleado_fk" => "14", "cantidad" => "125", "porcentaje" => "0"],
            ["id_estudio_fk" => "3", "id_empleado_fk" => "14", "cantidad" => "125", "porcentaje" => "0"],
            ["id_estudio_fk" => "1", "id_empleado_fk" => "4", "cantidad" => "50", "porcentaje" => "0"],
            ["id_estudio_fk" => "2", "id_empleado_fk" => "4", "cantidad" => "50", "porcentaje" => "0"],
            ["id_estudio_fk" => "3", "id_empleado_fk" => "4", "cantidad" => "50", "porcentaje" => "0"],
            //FAG + OCT
            ["id_estudio_fk" => "4", "id_empleado_fk" => "14", "cantidad" => "200", "porcentaje" => "0"],
            ["id_estudio_fk" => "5", "id_empleado_fk" => "14", "cantidad" => "125", "porcentaje" => "0"],
            ["id_estudio_fk" => "6", "id_empleado_fk" => "14", "cantidad" => "125", "porcentaje" => "0"],
            //OCT DE MACULA
            ["id_estudio_fk" => "20", "id_empleado_fk" => "14", "cantidad" => "250", "porcentaje" => "0"],
            ["id_estudio_fk" => "21", "id_empleado_fk" => "14", "cantidad" => "125", "porcentaje" => "0"],
            ["id_estudio_fk" => "22", "id_empleado_fk" => "14", "cantidad" => "125", "porcentaje" => "0"],
            ["id_estudio_fk" => "20", "id_empleado_fk" => "4", "cantidad" => "50", "porcentaje" => "0"],
            ["id_estudio_fk" => "21", "id_empleado_fk" => "4", "cantidad" => "50", "porcentaje" => "0"],
            ["id_estudio_fk" => "22", "id_empleado_fk" => "4", "cantidad" => "50", "porcentaje" => "0"],
            //OCT DE MACULA MÁS FAG
            //OCT DE NERVIO OPTICO
        ]);

        $fechaInsert = now()->toDateString();
        foreach($comisionesEmps as $comisiones){
            DB::table('comisiones')->insert([
                'id_estudio_fk' => $comisiones['id_estudio_fk'],
                'id_empleado_fk' => $comisiones['id_empleado_fk'],
                'cantidad' => $comisiones['cantidad'],
                'porcentaje' => $comisiones['porcentaje'],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert,
            ]);
        }
    }
}
