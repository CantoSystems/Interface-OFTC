<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OjosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $tipoOjo = Collect([
            ["nombretipo_ojo" => "AO"],
            ["nombretipo_ojo" => "OI"],
            ["nombretipo_ojo" => "OD"],
            ["nombretipo_ojo" => "UO"]
        ]);

        $fechaInsert = now()->toDateString();
        foreach($tipoOjo as $ojo){
            DB::table('tipo_ojos')->insert([
                'nombretipo_ojo' => $ojo['nombretipo_ojo'],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert,
            ]);
        }
    }
}