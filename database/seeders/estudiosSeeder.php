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
            ["id_estudio_fk" => "1", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR FAG AO", "precioEstudio" => 1400],
            ["id_estudio_fk" => "1", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR FAG OD", "precioEstudio" => 900],
            ["id_estudio_fk" => "1", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR FAG OI", "precioEstudio" => 900],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS FAG OCT AO", "precioEstudio" => 2700],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS FAG OCT OD", "precioEstudio" => 2000],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS FAG OCT OI", "precioEstudio" => 2000],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE 4", "precioEstudio" => 3200],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE 5", "precioEstudio" => 4000],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE 6", "precioEstudio" => 4800],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE DOS", "precioEstudio" => 1600],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE PERSONALIZADO", "precioEstudio" => 0],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE TRES", "precioEstudio" => 2400],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE UNO", "precioEstudio" => 800],
            ["id_estudio_fk" => "4", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS FAGOCT N.O.OCT MACULA AO", "precioEstudio" => 3400],
            ["id_estudio_fk" => "4", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS FAGOCT N.O.OCT MACULA OI", "precioEstudio" => 2200],
            ["id_estudio_fk" => "4", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS FAGOCT N.O.OCT MACULA OD", "precioEstudio" => 2200],
            ["id_estudio_fk" => "5", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE CORNEA AO", "precioEstudio" => 2000],
            ["id_estudio_fk" => "5", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE CORNEA OD", "precioEstudio" => 1400],
            ["id_estudio_fk" => "5", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE CORNEA OI", "precioEstudio" => 1400],
            ["id_estudio_fk" => "6", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE MACULA AO", "precioEstudio" => 2000],
            ["id_estudio_fk" => "6", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE MACULA OD", "precioEstudio" => 1400],
            ["id_estudio_fk" => "6", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE MACULA OI", "precioEstudio" => 1400],
            ["id_estudio_fk" => "7", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE NERVIO OPTICO AO", "precioEstudio" => 2000],
            ["id_estudio_fk" => "7", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT NERVIO OPTICO OI", "precioEstudio" => 1400],
            ["id_estudio_fk" => "7", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE NERVIO OPTICO OD", "precioEstudio" => 1400],
            ["id_estudio_fk" => "8", "id_ojo_fk" => "1", "dscrpMedicosPro" => "OCT METRIX AMBOS OJOS", "precioEstudio" => 2000],
            ["id_estudio_fk" => "8", "id_ojo_fk" => "4", "dscrpMedicosPro" => "OCT METRIX UN OJO", "precioEstudio" => 900],
            ["id_estudio_fk" => "9", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS OCT N.O. CAMPOS VISUALES AO", "precioEstudio" => 2600],
            ["id_estudio_fk" => "9", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS OCT N.O. CAMPOS VISUALES OD", "precioEstudio" => 1700],
            ["id_estudio_fk" => "9", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS OCT N.O. CAMPOS VISUALES OI", "precioEstudio" => 1700],
            ["id_estudio_fk" => "10", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS OCT NERVIO OPTICO OCT MACULA AO", "precioEstudio" => 3000],
            ["id_estudio_fk" => "10", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS OCT NERVIO OPTICO OCT MACULA OI", "precioEstudio" => 1800],
            ["id_estudio_fk" => "10", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS OCT NERVIO OPTICO OCT MACULA OD", "precioEstudio" => 1800],
            ["id_estudio_fk" => "11", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR TOPOGRFIA CORNEAL AO", "precioEstudio" => 1100],
            ["id_estudio_fk" => "11", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR TOPOGRFIA CORNEAL OD", "precioEstudio" => 900],
            ["id_estudio_fk" => "11", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR TOPOGRFIA CORNEAL OI", "precioEstudio" => 900],
            ["id_estudio_fk" => "12", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR CAMPIMETRIA AO", "precioEstudio" => 950],
            ["id_estudio_fk" => "12", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR CAMPIMETRIA OD", "precioEstudio" => 750],
            ["id_estudio_fk" => "12", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS PORCAMPIMETRIA OI", "precioEstudio" => 750],
            ["id_estudio_fk" => "13", "id_ojo_fk" => "1", "dscrpMedicosPro" => "TOPOGRAFIA ANTERION MAS CALCULO ANTERION AO", "precioEstudio" => 2500],
            ["id_estudio_fk" => "13", "id_ojo_fk" => "4", "dscrpMedicosPro" => "TOPOGRAFIA ANTERION MÁS CALCULO ANTERION UN OJO", "precioEstudio" => 1500],
            ["id_estudio_fk" => "13", "id_ojo_fk" => "1", "dscrpMedicosPro" => "TOPOGRAFIA POR OCT ANTERION AO", "precioEstudio" => 1500],
            ["id_estudio_fk" => "13", "id_ojo_fk" => "4", "dscrpMedicosPro" => "TOPOGRAFIA POR OCT ANTERION UN OJO", "precioEstudio" => 900],
            ["id_estudio_fk" => "14", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIIOS MEDICOS POR USB  B AO", "precioEstudio" => 1600],
            ["id_estudio_fk" => "14", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USBB OI", "precioEstudio" => 1000],
            ["id_estudio_fk" => "14", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USBB OD", "precioEstudio" => 1000],
            ["id_estudio_fk" => "15", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USB A AO", "precioEstudio" => 1200],
            ["id_estudio_fk" => "15", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USB A OD", "precioEstudio" => 700],
            ["id_estudio_fk" => "15", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USB A OI", "precioEstudio" => 700],
            ["id_estudio_fk" => "18", "id_ojo_fk" => "1", "dscrpMedicosPro" => "TOPOGRAFIA POR OCT ANTERION AO", "precioEstudio" => 1500],
            ["id_estudio_fk" => "18", "id_ojo_fk" => "4", "dscrpMedicosPro" => "TOPOGRAFIA POR OCT ANTERION UN OJO", "precioEstudio" => 900],
            ["id_estudio_fk" => "17", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS FOTO DE NERVIO OPTICO AO", "precioEstudio" => 950],
            ["id_estudio_fk" => "17", "id_ojo_fk" => "4", "dscrpMedicosPro" => "HONORARIOS FOTO DE NERVIO OPTICO UO", "precioEstudio" => 750],
            ["id_estudio_fk" => "17", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS FOTOS CLINICAS AO", "precioEstudio" => 950],
            ["id_estudio_fk" => "17", "id_ojo_fk" => "4", "dscrpMedicosPro" => "HONORARIOS FOTOS CLINICAS UO", "precioEstudio" => 750]
        ]);

        $fechaInsert = now()->toDateString();
        foreach($estudiosInfo as $estudios){
            DB::table('estudios')->insert([
                'id_estudio_fk' => $estudios['id_estudio_fk'],
                'id_ojo_fk' => $estudios['id_ojo_fk'],
                'dscrpMedicosPro' => $estudios['dscrpMedicosPro'],
                'precioEstudio' => $estudios['precioEstudio'],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert,
            ]);
        }
    }
}