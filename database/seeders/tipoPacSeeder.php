<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class tipoPacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $categoriaPac = [
            ["nombretipo_paciente" => "Interno"],
            ["nombretipo_paciente" => "Externo"],
        ];

        $fechaInsert = now()->toDateString();
        foreach($categoriaPac as $cat){
            DB::table('tipo_pacientes')->insert([
                'nombretipo_paciente' => $cat["nombretipo_paciente"],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert
            ]);
        }
    }
}