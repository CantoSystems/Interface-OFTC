<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorCategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoria = [
            ["nombre" => "Interno"],
            ["nombre" => "Externo"],
        ];

        foreach($categoria as $cat){
            DB::table('categoria_doctors')->insert([
                'nombre_categoria' => $cat["nombre"],
            ]);
        }
    }
}
