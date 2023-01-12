<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ActividadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $actividades = [
            ["nombreActividad" => "Transcrito",   "statusActividad" => 'A', "alias" => "drTransc"],
            ["nombreActividad" => "Interpretado", "statusActividad" => 'A', "alias" => "drInt" ],
            ["nombreActividad" => "Escaneado",    "statusActividad" => 'A', "alias" => "escRd" ],
            ["nombreActividad" => "Entregado",    "statusActividad" => 'A', "alias" => "empEnt"],
            ["nombreActividad" => "Realizado",    "statusActividad" => 'A', "alias" => "empRealiza"],
        ];

        $fechaInsert = now()->toDateString();
        foreach($actividades as $act){
            DB::table('actividades')->insert([
                'nombreActividad' => $act["nombreActividad"],
                'statusActividad' => $act["statusActividad"],
                'aliasEstudiosTemps' => $act["alias"],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert
            ]);
        }
    }
}