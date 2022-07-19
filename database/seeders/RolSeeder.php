<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ["descripcion" => "comisionesAdministrador"],
            ["descripcion" => "cobranzaReportes"],
            ["descripcion" => "detalleConsumo"],
        ];

        $fechaInsert = now()->toDateString();
        foreach($roles as $rol){
            DB::table('roles')->insert([
                'nombre_rol' => $rol["descripcion"],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert
            ]);
        }


    }
}
