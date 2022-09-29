<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class estudiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $estudiosInfo = Collect([
            ["id_estudio_fk" => "1", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR FAG AO", "paquete" => 'N'],
            ["id_estudio_fk" => "1", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR FAG OD", "paquete" => 'N'],
            ["id_estudio_fk" => "1", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR FAG OI", "paquete" => 'N'],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS FAG OCT AO", "paquete" => 'S'],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS FAG OCT OD", "paquete" => 'S'],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS FAG OCT OI", "paquete" => 'S'],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE 4", "paquete" => 'S'],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE 5", "paquete" => 'S'],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE 6", "paquete" => 'S'],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE DOS", "paquete" => 'S'],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE PERSONALIZADO", "paquete" => 'S'],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE TRES", "paquete" => 'S'],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE UNO", "paquete" => 'S'],
            ["id_estudio_fk" => "4", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS FAGOCT N.O.OCT MACULA AO", "paquete" => 'S'],
            ["id_estudio_fk" => "4", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS FAGOCT N.O.OCT MACULA OI", "paquete" => 'S'],
            ["id_estudio_fk" => "4", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS FAGOCT N.O.OCT MACULA OD", "paquete" => 'S'],
            ["id_estudio_fk" => "5", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE CORNEA AO", "paquete" => 'N'],
            ["id_estudio_fk" => "5", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE CORNEA OD", "paquete" => 'N'],
            ["id_estudio_fk" => "5", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE CORNEA OI", "paquete" => 'N'],
            ["id_estudio_fk" => "6", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE MACULA AO", "paquete" => 'N'],
            ["id_estudio_fk" => "6", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE MACULA OD", "paquete" => 'N'],
            ["id_estudio_fk" => "6", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE MACULA OI", "paquete" => 'N'],
            ["id_estudio_fk" => "7", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE NERVIO OPTICO AO", "paquete" => 'N'],
            ["id_estudio_fk" => "7", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT NERVIO OPTICO OI", "paquete" => 'N'],
            ["id_estudio_fk" => "7", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE NERVIO OPTICO OD", "paquete" => 'N'],
            ["id_estudio_fk" => "8", "id_ojo_fk" => "1", "dscrpMedicosPro" => "OCT METRIX AMBOS OJOS", "paquete" => 'N'],
            ["id_estudio_fk" => "8", "id_ojo_fk" => "4", "dscrpMedicosPro" => "OCT METRIX UN OJO", "paquete" => 'N'],
            ["id_estudio_fk" => "9", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS OCT N.O. CAMPOS VISUALES AO", "paquete" => 'S'],
            ["id_estudio_fk" => "9", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS OCT N.O. CAMPOS VISUALES OD", "paquete" => 'S'],
            ["id_estudio_fk" => "9", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS OCT N.O. CAMPOS VISUALES OI", "paquete" => 'S'],
            ["id_estudio_fk" => "10", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS OCT NERVIO OPTICO OCT MACULA AO", "paquete" => 'S'],
            ["id_estudio_fk" => "10", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS OCT NERVIO OPTICO OCT MACULA OI", "paquete" => 'S'],
            ["id_estudio_fk" => "10", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS OCT NERVIO OPTICO OCT MACULA OD", "paquete" => 'S'],
            ["id_estudio_fk" => "11", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR TOPOGRFIA CORNEAL AO", "paquete" => 'N'],
            ["id_estudio_fk" => "11", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR TOPOGRFIA CORNEAL OD", "paquete" => 'N'],
            ["id_estudio_fk" => "11", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR TOPOGRFIA CORNEAL OI", "paquete" => 'N'],
            ["id_estudio_fk" => "12", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR CAMPIMETRIA AO", "paquete" => 'N'],
            ["id_estudio_fk" => "12", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR CAMPIMETRIA OD", "paquete" => 'N'],
            ["id_estudio_fk" => "12", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS PORCAMPIMETRIA OI", "paquete" => 'N'],
            ["id_estudio_fk" => "13", "id_ojo_fk" => "1", "dscrpMedicosPro" => "TOPOGRAFIA ANTERION MAS CALCULO ANTERION AO", "paquete" => 'N'],
            ["id_estudio_fk" => "13", "id_ojo_fk" => "4", "dscrpMedicosPro" => "TOPOGRAFIA ANTERION MÁS CALCULO ANTERION UN OJO", "paquete" => 'N'],
            ["id_estudio_fk" => "13", "id_ojo_fk" => "1", "dscrpMedicosPro" => "TOPOGRAFIA POR OCT ANTERION AO", "paquete" => 'N'],
            ["id_estudio_fk" => "13", "id_ojo_fk" => "4", "dscrpMedicosPro" => "TOPOGRAFIA POR OCT ANTERION UN OJO", "paquete" => 'N'],
            ["id_estudio_fk" => "14", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIIOS MEDICOS POR USB  B AO", "paquete" => 'N'],
            ["id_estudio_fk" => "14", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USBB OI", "paquete" => 'N'],
            ["id_estudio_fk" => "14", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USBB OD", "paquete" => 'N'],
            ["id_estudio_fk" => "15", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USB A AO", "paquete" => 'N'],
            ["id_estudio_fk" => "15", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USB A OD", "paquete" => 'N'],
            ["id_estudio_fk" => "15", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USB A OI", "paquete" => 'N'],
            ["id_estudio_fk" => "18", "id_ojo_fk" => "1", "dscrpMedicosPro" => "TOPOGRAFIA POR OCT ANTERION AO", "paquete" => 'N'],
            ["id_estudio_fk" => "18", "id_ojo_fk" => "4", "dscrpMedicosPro" => "TOPOGRAFIA POR OCT ANTERION UN OJO", "paquete" => 'N'],
            ["id_estudio_fk" => "17", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS FOTO DE NERVIO OPTICO AO", "paquete" => 'N'],
            ["id_estudio_fk" => "17", "id_ojo_fk" => "4", "dscrpMedicosPro" => "HONORARIOS FOTO DE NERVIO OPTICO UO", "paquete" => 'N'],
            ["id_estudio_fk" => "17", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS FOTOS CLINICAS AO", "paquete" => 'N'],
            ["id_estudio_fk" => "17", "id_ojo_fk" => "4", "dscrpMedicosPro" => "HONORARIOS FOTOS CLINICAS UO", "paquete" => 'N']
        ]);

        $fechaInsert = now()->toDateString();
        foreach($estudiosInfo as $estudios){
            DB::table('estudios')->insert([
                'id_estudio_fk' => $estudios['id_estudio_fk'],
                'id_ojo_fk' => $estudios['id_ojo_fk'],
                'dscrpMedicosPro' => $estudios['dscrpMedicosPro'],
                'paquete' => $estudios['paquete'],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert,
            ]);
        }
    }
}