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
            ["nombreActividad" => "Transcrito",   "statusActividad" => 'A'],
            ["nombreActividad" => "Interpretado", "statusActividad" => 'A'],
            ["nombreActividad" => "Escaneado",    "statusActividad" => 'A'],
            ["nombreActividad" => "Entregado",    "statusActividad" => 'A'],
        ];

        $fechaInsert = now()->toDateString();
        foreach($actividades as $act){
            DB::table('actividades')->insert([
                'nombreActividad' => $act["nombreActividad"],
                'statusActividad' => $act["statusActividad"],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert
            ]);
        }
    }
}