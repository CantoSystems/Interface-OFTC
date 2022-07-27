<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComisionesDoctoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comisiones_doctores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_doctor_fk');
            $table->unsignedBigInteger('id_tipoPaciente_fk');
            $table->unsignedBigInteger('id_metodoPago_fk');
            $table->double('porcentaje',10,2);
            $table->char('tipoPorcentaje',2)->nullable();
            $table->foreign('id_doctor_fk')->references('id')->on('doctors');
            $table->foreign('id_tipoPaciente_fk')->references('id')->on('tipo_pacientes');
            $table->foreign('id_metodoPago_fk')->references('id')->on('cat_metodo_pago');
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
        Schema::dropIfExists('comisiones_doctores');
    }
}