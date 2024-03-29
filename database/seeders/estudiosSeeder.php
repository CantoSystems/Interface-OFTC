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
            ["id_estudio_fk" => "2", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE NERVIO OPTICO AO"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE NERVIO OPTICO OD"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT NERVIO OPTICO OI"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE MACULA AO"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE MACULA OD"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE MACULA OI"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE CORNEA AO"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE CORNEA OD"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT DE CORNEA OI"],
            ["id_estudio_fk" => "1", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR FAG AO"],
            ["id_estudio_fk" => "1", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR FAG OD"],
            ["id_estudio_fk" => "1", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR FAG OI"],
            ["id_estudio_fk" => "4", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR CAMPIMETRIA AO"],
            ["id_estudio_fk" => "4", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR CAMPIMETRIA OD"],
            ["id_estudio_fk" => "4", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS PORCAMPIMETRIA OI"],
            ["id_estudio_fk" => "6", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USB- A AO"],
            ["id_estudio_fk" => "6", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USB- A OD"],
            ["id_estudio_fk" => "6", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USB-A OI"],
            ["id_estudio_fk" => "7", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIIOS MEDICOS POR USB - B AO"],
            ["id_estudio_fk" => "7", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USB-B OD"],
            ["id_estudio_fk" => "7", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR USB-B OI"],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR TOPOGRFIA  CORNEAL AO"],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR TOPOGRFIA CORNEAL OD"],
            ["id_estudio_fk" => "3", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR TOPOGRFIA CORNEAL OI"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE UNO"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE DOS"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE TRES"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE 4"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE 5"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT PAQUETE 6"],
            ["id_estudio_fk" => "10", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR TOPOGRAFIA POR OCT ANTERION OD"],
            ["id_estudio_fk" => "10", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR TOPOGRAFIA POR OCT ANTERION OI"],
            ["id_estudio_fk" => "10", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR TOPOGRAFIA POR OCT ANTERION AO"],
            ["id_estudio_fk" => "10", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR CÁLCULO DE LENTE ANTERION OD"],
            ["id_estudio_fk" => "10", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR CÁLCULO DE LENTE ANTERION OI"],
            ["id_estudio_fk" => "10", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR TOPOGRFIA CORNEAL OI"],
            ["id_estudio_fk" => "10", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR CÁLCULO DE LENTE ANTERION AO"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT METRICS OD"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT METRICS OI"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT METRICS AMBOS OJOS"],
            ["id_estudio_fk" => "8", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR PAQUIMETRIA AO"],
            ["id_estudio_fk" => "8", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR PAQUIMETRIA OD"],
            ["id_estudio_fk" => "8", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARISO MEDICOS POR PAQUIMETRIA OI"],
            ["id_estudio_fk" => "12", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR AUTOFLUORESCENCIA AO"],
            ["id_estudio_fk" => "12", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR AUTOFLUORESCENCIA OI"],
            ["id_estudio_fk" => "12", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR AUTOFLUORESCENCIA OD"],
            ["id_estudio_fk" => "2", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR OCT CELULAS GANGLIONALES AO"],
            ["id_estudio_fk" => "11", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR ANALISIS DE CELULAS GANGLIONALES OI"],
            ["id_estudio_fk" => "11", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR ANALISIS DE CELULAS GANGLIONALES OD"],
            ["id_estudio_fk" => "1", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR SEGUIMIENTO DE FAG AO"],
            ["id_estudio_fk" => "1", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR SEGUIMIENTO DE FAG OD"],
            ["id_estudio_fk" => "1", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR SEGUIMIENTO DE FAG OI"],
            ["id_estudio_fk" => "9", "id_ojo_fk" => "1", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR FOTOS CLINICAS AO"],
            ["id_estudio_fk" => "9", "id_ojo_fk" => "2", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR FOTOS CLINICAS OI"],
            ["id_estudio_fk" => "9", "id_ojo_fk" => "3", "dscrpMedicosPro" => "HONORARIOS MEDICOS POR FOTOS CLINICAS OD"]
        ]);

        $fechaInsert = now()->toDateString();
        foreach($estudiosInfo as $estudios){
            DB::table('estudios')->insert([
                'id_estudio_fk' => $estudios['id_estudio_fk'],
                'id_ojo_fk' => $estudios['id_ojo_fk'],
                'dscrpMedicosPro' => $estudios['dscrpMedicosPro'],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert,
            ]);
        }
    }
}