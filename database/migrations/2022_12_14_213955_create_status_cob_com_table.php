<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusCobComTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_cob_com', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cobranza_fk');
            $table->unsignedBigInteger('id_actividad_fk');
            $table->date('fechaActualización');
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
        Schema::dropIfExists('status_cob_com');
    }
}