<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleAdicionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_adicional', function (Blueprint $table) {
            $table->unsignedBigInteger('id_detalleConsumo_FK');
            $table->text('codigo',20)->nullable();
            $table->string('descripcion',60)->nullable();
            $table->char('um',5)->nullable();
            $table->double('cantidad',10,2)->nullable();
            $table->double('precio_unitario',10,2)->nullable();
            $table->double('importe',10,2)->nullable();
            $table->foreign('id_detalleConsumo_FK')->references('id')->on('detalle_consumos');
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
        Schema::dropIfExists('detalle_adicional');
    }
}
