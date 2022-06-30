<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComisionesTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comisiones_temps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_emp_fk');
            $table->unsignedBigInteger('id_estudio_fk');
            $table->text('paciente');
            $table->date('fechaEstudio');
            $table->double('cantidadComision',10,2);
            $table->double('cantidadUtilidad',10,2)->nulleable();
            $table->foreign('id_emp_fk')->references('id_emp')->on('empleados');
            $table->foreign('id_estudio_fk')->references('id')->on('estudios');
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
        Schema::dropIfExists('comisiones_temps');
    }
}