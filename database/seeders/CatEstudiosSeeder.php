<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CatEstudiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $catEstudios = Collect([
            ["descripcion" => "FAG"],
            ["descripcion" => "FAG + OCT"],
            ["descripcion" => "OCT"],
            ["descripcion" => "FAG + OCT DE N.O. + OCT DE MACULA"],
            ["descripcion" => "OCT DE CORNEA"],
            ["descripcion" => "OCT DE MACULA"],
            ["descripcion" => "OCT DE NERVIO ÓPTICO"],
            ["descripcion" => "OCT METRIX"],
            ["descripcion" => "OCT DE N.O. + CAMPOS VISUALES"],
            ["descripcion" => "OCT DE N.O. + OCT DE MACULA"],
            ["descripcion" => "TOPOGRAFÍA CORNEAL"],
            ["descripcion" => "CAMPIMETRIA"],
            ["descripcion" => "TOPOGRAFIA"],
            ["descripcion" => "ULTRASONIDO MODO B"],
            ["descripcion" => "ULTRASONIDO MODO A"],
            ["descripcion" => "PAQUIMETRIA"],
            ["descripcion" => "FOTOS CLINICAS"],
            ["descripcion" => "ANTERION"]
        ]);

        $fechaInsert = now()->toDateString();
        foreach($catEstudios as $estudios){
            DB::table('cat_estudios')->insert([
                'descripcion' => $estudios['descripcion'],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert,
            ]);
        }
    }
}
