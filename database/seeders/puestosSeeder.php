<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class puestosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $puestosTab = Collect([
            ["puestos_nombre" => "OPTOMETRÍA", "actividad" => "TRANSCRIBE"],
            ["puestos_nombre" => "CAJA", "actividad" => "ESCANEA"],
            ["puestos_nombre" => "DOCTOR", "actividad" => "INTERPRETA"],
            ["puestos_nombre" => "ADMINISTRATIVO", "actividad" => ""],
            ["puestos_nombre" => "RECEPCIÓN", "actividad" => ""],
            ["puestos_nombre" => "COORDINACIÓN", "actividad" => ""],
            ["puestos_nombre" => "ENFERMERÍA", "actividad" => ""],
        ]);

        $fechaInsert = now()->toDateString();
        foreach($puestosTab as $puestos){
            DB::table('puestos')->insert([
                'puestos_nombre' => $puestos['puestos_nombre'],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert,
            ]);
        }
    }
}
