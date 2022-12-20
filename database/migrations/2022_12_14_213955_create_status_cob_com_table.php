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
            $table->unsignedBigInteger('id_estudio_fk');
            $table->unsignedBigInteger('id_actividad_fk');
            $table->unsignedBigInteger('id_empleado_fk');
            $table->text('paciente');
            $table->text('folio');
            $table->text('statusComisiones');
            $table->date('fechaCorte');
            $table->foreign('id_estudio_fk')->references('id')->on('estudios');
            $table->foreign('id_actividad_fk')->references('id')->on('actividades');
            $table->foreign('id_empleado_fk')->references('id_emp')->on('empleados');
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