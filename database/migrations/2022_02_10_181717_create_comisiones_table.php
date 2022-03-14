<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComisionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comisiones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_estudio_fk');
            $table->unsignedBigInteger('id_empleado_fk');
            $table->double('cantidad',10,2)->nullable();
            $table->double('porcentaje',10,2)->nullable();
            $table->foreign('id_estudio_fk')->references('id')->on('estudios');
            $table->foreign('id_empleado_fk')->references('id_emp')->on('empleados');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comisiones');
    }
}
