<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $doctoresTab = [    
            ["doctor_titulo" => "N/A",  "doctor_nombre" => "N/A",        "doctor_apellidop" => "N/A",         "doctor_apellidom" => "N/A",      "doctor_email" => "",                                       "doctor_status" => "A", "categoria_id" => "1"],
            ["doctor_titulo" => "Dr.",  "doctor_nombre" => "Carlos",     "doctor_apellidop" => "Carrillo",    "doctor_apellidom" => "Salazar",  "doctor_email" => "",                                       "doctor_status" => "A", "categoria_id" => "2"],
            ["doctor_titulo" => "Dr.",  "doctor_nombre" => "Alfredo",    "doctor_apellidop" => "Morales",     "doctor_apellidom" => "Delgado",  "doctor_email" => "moda720112@yahoo.com.mx",                "doctor_status" => "A", "categoria_id" => "2"],
            ["doctor_titulo" => "Dra.", "doctor_nombre" => "Rosa María", "doctor_apellidop" => "Romero",      "doctor_apellidom" => "Castro",   "doctor_email" => "rromerocastro@yahoo.com",                "doctor_status" => "A", "categoria_id" => "2"],
            ["doctor_titulo" => "Dr.",  "doctor_nombre" => "Gonzalo",    "doctor_apellidop" => "Padilla",     "doctor_apellidom" => "Aguilar",  "doctor_email" => "gonzalopadilla@me.com",                  "doctor_status" => "A", "categoria_id" => "2"],
            ["doctor_titulo" => "Dra.", "doctor_nombre" => "Alejandra",  "doctor_apellidop" => "Ocampo",      "doctor_apellidom" => "García",   "doctor_email" => "oftalmologaalejandraocampo@gmail.com",   "doctor_status" => "A", "categoria_id" => "3"],
            ["doctor_titulo" => "Dr.",  "doctor_nombre" => "Efren",      "doctor_apellidop" => "Muñoz",       "doctor_apellidom" => "Miranda",  "doctor_email" => "emz.oftalmo@gmail.com",                  "doctor_status" => "A", "categoria_id" => "3"],
            ["doctor_titulo" => "Dr.",  "doctor_nombre" => "Juan Carlos","doctor_apellidop" => "de la Luz",   "doctor_apellidom" => "Osnaya",   "doctor_email" => "jcdllo@yahoo.com.mx",                    "doctor_status" => "A", "categoria_id" => "3"],
        ];

        $fechaInsert = now()->toDateString();
        foreach($doctoresTab as $dres){
            DB::table('doctors')->insert([
                'doctor_titulo' => $dres["doctor_titulo"],
                'doctor_nombre' => $dres["doctor_nombre"],
                'doctor_apellidop' => $dres["doctor_apellidop"],
                'doctor_apellidom' => $dres["doctor_apellidom"],
                'doctor_email' => $dres["doctor_email"],
                'doctor_status' => $dres["doctor_status"], 
                'categoria_id' => $dres["categoria_id"],
                'created_at' => $fechaInsert,
                'updated_at' => $fechaInsert
            ]);
        }
    }
}