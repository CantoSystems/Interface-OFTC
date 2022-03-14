<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class empleadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $empleadosTab = Collect([
            ["empleado_nombre" => "Leticia Rosas",   "empleado_apellidop" => "Perez",     "empleado_apellidom" => "Vargas",    "empleado_status" => "A", "puesto_id" => "7"],
            ["empleado_nombre" => "Karen Zulema",    "empleado_apellidop" => "Sotelo",    "empleado_apellidom" => "Delgado",   "empleado_status" => "A", "puesto_id" => "5"],
            ["empleado_nombre" => "Héctor Ulises",   "empleado_apellidop" => "Soto",      "empleado_apellidom" => "Peregrino", "empleado_status" => "A", "puesto_id" => "1"],
            ["empleado_nombre" => "Nancy Shaila",    "empleado_apellidop" => "Cipres",    "empleado_apellidom" => "Blanquel",  "empleado_status" => "A", "puesto_id" => "2"],
            ["empleado_nombre" => "Jeny Jazaret",    "empleado_apellidop" => "Jaimes",    "empleado_apellidom" => "Cuenca",    "empleado_status" => "A", "puesto_id" => "6"],
            ["empleado_nombre" => "Sara",            "empleado_apellidop" => "Montiel",   "empleado_apellidom" => "Polis",     "empleado_status" => "A", "puesto_id" => "6"],
            ["empleado_nombre" => "Nataly",          "empleado_apellidop" => "Carbajal",  "empleado_apellidom" => "Sampayo",   "empleado_status" => "A", "puesto_id" => "7"],
            ["empleado_nombre" => "Guadalupe Itzel", "empleado_apellidop" => "Chavez",    "empleado_apellidom" => "Díaz",      "empleado_status" => "A", "puesto_id" => "5"],
            ["empleado_nombre" => "Angélica",        "empleado_apellidop" => "Morales",   "empleado_apellidom" => "Torres",    "empleado_status" => "A", "puesto_id" => "7"],
            ["empleado_nombre" => "Maria del Pilar", "empleado_apellidop" => "Sánchez",   "empleado_apellidom" => "Vergara",   "empleado_status" => "A", "puesto_id" => "2"],
            ["empleado_nombre" => "Natalia Antonia", "empleado_apellidop" => "Velázquez", "empleado_apellidom" => "Valdéz",    "empleado_status" => "A", "puesto_id" => "4"],
            ["empleado_nombre" => "Carlos",          "empleado_apellidop" => "Carrillo",  "empleado_apellidom" => "Salazar",   "empleado_status" => "A", "puesto_id" => "3"],
            ["empleado_nombre" => "Rosa María",      "empleado_apellidop" => "Romero",    "empleado_apellidom" => "",          "empleado_status" => "A", "puesto_id" => "3"],
            ["empleado_nombre" => "Gonzalo",         "empleado_apellidop" => "Padilla",   "empleado_apellidom" => "Valdéz",    "empleado_status" => "A", "puesto_id" => "3"]

        ]);

        $fechaInsert = now()->toDateString();
        foreach($puestosTab as $puestos){
            DB::table('empleados')->insert([
                'empleado_nombre' => $puestos['empleado_nombre'],
                'empleado_apellidop' => $puestos['empleado_apellidop'],
                'empleado_apellidom' => $puestos['empleado_apellidom'],
                'empleado_status' => $puestos['empleado_status'],
                'puesto_id' => $puestos['puesto_id'],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert,
            ]);
        }
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> fade5fb3d6a8ef7a38ed2c2cfc8685a0e30c02ba
