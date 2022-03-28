<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleConsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_consumos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_doctor_fk');
            $table->char('folio',10);
            $table->date('fechaElaboracion');
            $table->text('paciente');
            $table->foreign('id_doctor_fk')->references('id')->on('doctors');
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
        Schema::dropIfExists('detalle_consumos');
    }
}