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
            ["id_estudio_fk" => "1", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "2", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "3", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "1", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "2", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "3", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "1", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "2", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "3", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "1", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "100"],
            ["id_estudio_fk" => "2", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "100"],
            ["id_estudio_fk" => "3", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "100"],
            //FAG + OCT
            ["id_estudio_fk" => "4", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "5", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "6", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "4", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "5", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "6", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "4", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "5", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "6", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "4", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "100"],
            ["id_estudio_fk" => "5", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "100"],
            ["id_estudio_fk" => "6", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "100"],
            //OCT DE MACULA
            ["id_estudio_fk" => "20", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "21", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "22", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "20", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "21", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "22", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "20", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "21", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "22", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "20", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "21", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "22", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "20", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "21", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "22", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            //OCT DE NERVIO OPTICO
            ["id_estudio_fk" => "23", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "24", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "25", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "23", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "24", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "25", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "23", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "24", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "25", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "23", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "24", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "25", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "23", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "24", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "25", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            //OCT DE CORNEA
            ["id_estudio_fk" => "17", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "18", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "19", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "17", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "18", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "19", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "17", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "18", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "19", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "17", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "18", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "19", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "17", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "18", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "19", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            //OCT DE NERVIO ÓPTICO + CAMPOS VISUALES
            ["id_estudio_fk" => "28", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "29", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "30", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "28", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "29", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "30", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "28", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "29", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "30", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "28", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "29", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "30", "id_empleado_fk" => "14", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "28", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "29", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            ["id_estudio_fk" => "30", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "33.33"],
            //CAMPIMETRÍA
            ["id_estudio_fk" => "37", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "38", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "39", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "37", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "70"],
            ["id_estudio_fk" => "38", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "70"],
            ["id_estudio_fk" => "39", "id_empleado_fk" => "13", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "70"],
            ["id_estudio_fk" => "37", "id_empleado_fk" => "4", "porcentajeComision" => "8", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "38", "id_empleado_fk" => "4", "porcentajeComision" => "8", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "39", "id_empleado_fk" => "4", "porcentajeComision" => "8", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "37", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "30"],
            ["id_estudio_fk" => "38", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "30"],
            ["id_estudio_fk" => "39", "id_empleado_fk" => "15", "porcentajeComision" => "0", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "30"],
            //FOTO DE NERVIO OPTICO
            ["id_estudio_fk" => "52", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "53", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "52", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "53", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            //FOTOS CLÍNICAS DE RETINA
            ["id_estudio_fk" => "54", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "55", "id_empleado_fk" => "12", "porcentajeComision" => "2", "porcentajeAdicional" => "0", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "54", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
            ["id_estudio_fk" => "55", "id_empleado_fk" => "4", "porcentajeComision" => "10", "porcentajeAdicional" => "4", "porcentajeUtilidad" => "0"],
        ]);

        $fechaInsert = now()->toDateString();
        foreach($comisionesEmps as $comisiones){
            DB::table('comisiones')->insert([
                'id_estudio_fk' => $comisiones['id_estudio_fk'],
                'id_empleado_fk' => $comisiones['id_empleado_fk'],
                'porcentajeComision' => $comisiones['porcentajeComision'],
                'porcentajeAdicional' => $comisiones['porcentajeAdicional'],
                'porcentajeUtilidad' => $comisiones['porcentajeUtilidad'],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert,
            ]);
        }
    }
}