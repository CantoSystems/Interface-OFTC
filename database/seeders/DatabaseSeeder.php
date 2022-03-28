<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $this->call([
            DoctorCategoriaSeeder::class,
            tipoPacSeeder::class,
            CatEstudiosSeeder::class,
            OjosSeeder::class,
            estudiosSeeder::class,
            puestosSeeder::class,
            empleadosSeeder::class,
            DoctorSeeder::class,
            ActividadesSeeder::class,
       ]);
    }
}