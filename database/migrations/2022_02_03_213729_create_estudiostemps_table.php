<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstudiostempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('estudiostemps', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->char('serie',2)->nullable();
            $table->char('folio',10)->nullable();
            $table->text('doctor')->nullable();
            $table->text('paciente')->nullable();
            $table->text('servicio')->nullable();
            $table->text('met_pago')->nullable();
            $table->char('cfdi',10)->nullable();
            $table->double('subtotal',10,2)->nullable();
            $table->double('descuento',10,2)->nullable();
            $table->double('iva',10,2)->nullable();
            $table->double('total',10,2)->nullable();
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
        Schema::dropIfExists('estudiostemps');
    }
}
