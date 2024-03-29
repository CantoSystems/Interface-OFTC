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
            ["clave" => "comisiones", "descripcion" => "COMISIONES"],
            ["clave" => "cobranzaReportes","descripcion" => "COBRANZA"],
            ["clave" => "detalleConsumo", "descripcion" => "DETALLE DE CONSUMO"],
            ["clave" => "auxiliarCobranzaReportes", "descripcion" => "AUXILIAR ESTUDIOS COBRANZA"],
            ["clave" => "auxiliardetalleConsumo", "descripcion" => "AUXILIAR DETALLE DE CONSUMO"],
            ["clave" => "invitado", "descripcion" => "INVITADO"],
            ["clave" => "administrador", "descripcion" => "ADMINISTRADOR USUARIOS"],
            ["clave" => "optometria", "descripcion" => "OPTOMETRIA"],
            
        ];

        $fechaInsert = now()->toDateString();
        foreach($roles as $rol){
            DB::table('roles')->insert([
                'clave_rol' => $rol["clave"],
                'descripcion_rol' => $rol["descripcion"],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert
            ]);
        }


    }
}
