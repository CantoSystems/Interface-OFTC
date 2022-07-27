<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ComisionesDoctoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $comisionesDres = Collect([
            ["id_doctor_fk" => "3", "id_tipoPaciente_fk" => "1", "id_metodoPago_fk" => "1", "tipoPorcentaje" => "N", "porcentaje" => "15"],
            ["id_doctor_fk" => "3", "id_tipoPaciente_fk" => "1", "id_metodoPago_fk" => "2", "tipoPorcentaje" => "N", "porcentaje" => "15"],
            ["id_doctor_fk" => "3", "id_tipoPaciente_fk" => "1", "id_metodoPago_fk" => "3", "tipoPorcentaje" => "N", "porcentaje" => "15"],
            ["id_doctor_fk" => "3", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "1", "tipoPorcentaje" => "N", "porcentaje" => "20"],
            ["id_doctor_fk" => "3", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "2", "tipoPorcentaje" => "N", "porcentaje" => "25"],
            ["id_doctor_fk" => "3", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "3", "tipoPorcentaje" => "N", "porcentaje" => "25"],
            ["id_doctor_fk" => "4", "id_tipoPaciente_fk" => "1", "id_metodoPago_fk" => "1", "tipoPorcentaje" => "N", "porcentaje" => "15"],
            ["id_doctor_fk" => "4", "id_tipoPaciente_fk" => "1", "id_metodoPago_fk" => "2", "tipoPorcentaje" => "N", "porcentaje" => "15"],
            ["id_doctor_fk" => "4", "id_tipoPaciente_fk" => "1", "id_metodoPago_fk" => "3", "tipoPorcentaje" => "N", "porcentaje" => "15"],
            ["id_doctor_fk" => "4", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "1", "tipoPorcentaje" => "N", "porcentaje" => "20"],
            ["id_doctor_fk" => "4", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "2", "tipoPorcentaje" => "N", "porcentaje" => "25"],
            ["id_doctor_fk" => "4", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "3", "tipoPorcentaje" => "N", "porcentaje" => "25"],
            ["id_doctor_fk" => "5", "id_tipoPaciente_fk" => "1", "id_metodoPago_fk" => "1", "tipoPorcentaje" => "N", "porcentaje" => "15"],
            ["id_doctor_fk" => "5", "id_tipoPaciente_fk" => "1", "id_metodoPago_fk" => "2", "tipoPorcentaje" => "N", "porcentaje" => "15"],
            ["id_doctor_fk" => "5", "id_tipoPaciente_fk" => "1", "id_metodoPago_fk" => "3", "tipoPorcentaje" => "N", "porcentaje" => "15"],
            ["id_doctor_fk" => "5", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "1", "tipoPorcentaje" => "N", "porcentaje" => "20"],
            ["id_doctor_fk" => "5", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "2", "tipoPorcentaje" => "N", "porcentaje" => "25"],
            ["id_doctor_fk" => "5", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "3", "tipoPorcentaje" => "N", "porcentaje" => "25"],
            ["id_doctor_fk" => "6", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "1", "tipoPorcentaje" => "N", "porcentaje" => "25"],
            ["id_doctor_fk" => "6", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "2", "tipoPorcentaje" => "N", "porcentaje" => "30"],
            ["id_doctor_fk" => "6", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "3", "tipoPorcentaje" => "N", "porcentaje" => "30"],
            ["id_doctor_fk" => "7", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "1", "tipoPorcentaje" => "N", "porcentaje" => "25"],
            ["id_doctor_fk" => "7", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "2", "tipoPorcentaje" => "N", "porcentaje" => "30"],
            ["id_doctor_fk" => "7", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "3", "tipoPorcentaje" => "N", "porcentaje" => "30"],
            ["id_doctor_fk" => "8", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "1", "tipoPorcentaje" => "N", "porcentaje" => "25"],
            ["id_doctor_fk" => "8", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "2", "tipoPorcentaje" => "N", "porcentaje" => "30"],
            ["id_doctor_fk" => "8", "id_tipoPaciente_fk" => "2", "id_metodoPago_fk" => "3", "tipoPorcentaje" => "N", "porcentaje" => "30"],
            ["id_doctor_fk" => "4", "id_tipoPaciente_fk" => "1", "id_metodoPago_fk" => "1", "tipoPorcentaje" => "S", "porcentaje" => "10"],
            ["id_doctor_fk" => "4", "id_tipoPaciente_fk" => "1", "id_metodoPago_fk" => "2", "tipoPorcentaje" => "S", "porcentaje" => "10"],
            ["id_doctor_fk" => "4", "id_tipoPaciente_fk" => "1", "id_metodoPago_fk" => "3", "tipoPorcentaje" => "S", "porcentaje" => "10"],
        ]);

        $fechaInsert = now()->toDateString();
        foreach($comisionesDres as $comisiones){
            DB::table('comisiones_doctores')->insert([
                'id_doctor_fk' => $comisiones['id_doctor_fk'],
                'id_tipoPaciente_fk' => $comisiones['id_tipoPaciente_fk'],
                'id_metodoPago_fk' => $comisiones['id_metodoPago_fk'],
                'porcentaje' => $comisiones['porcentaje'],
                'tipoPorcentaje' => $comisiones['tipoPorcentaje'],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert,
            ]);
        }
    }
}