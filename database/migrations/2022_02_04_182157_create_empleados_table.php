<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('empleados', function (Blueprint $table) {
            $table->id('id_emp');
            $table->string('empleado_nombre',20);
            $table->string('empleado_apellidop',20);
            $table->string('empleado_apellidom',20);
            $table->char('empleado_status',1);
            $table->unsignedBigInteger('puesto_id');
            $table->unsignedBigInteger('actividades_fk')->nullable();
            $table->foreign('puesto_id')->references('id')->on('puestos');
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
        Schema::dropIfExists('empleados');
    }
}