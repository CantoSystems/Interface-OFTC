<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class metodoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $metPago = Collect([
            ["descripcion" => "Efectivo",      "statusMetodoPago" => "A"],
            ["descripcion" => "Transferencia", "statusMetodoPago" => "A"],
            ["descripcion" => "TPV",           "statusMetodoPago" => "A"],
        ]);

        $fechaInsert = now()->toDateString();
        foreach($metPago as $metP){
            DB::table('cat_metodo_pago')->insert([
                'descripcion' => $metP['descripcion'],
                'statusMetodoPago' => $metP['statusMetodoPago'],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert,
            ]);
        }
    }
}
