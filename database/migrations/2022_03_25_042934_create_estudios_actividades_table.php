<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstudiosActividadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudios_actividades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cobranza_fk');
            $table->unsignedBigInteger('id_actividad_fk');
            $table->foreign('id_cobranza_fk')->references('id')->on('cobranza');
            $table->foreign('id_actividad_fk')->references('id')->on('actividades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estudios_actividades');
    }
}