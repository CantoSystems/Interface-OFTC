<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HistoricoDetalleConsumo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('historico_detalle_consumo', function (Blueprint $table) {
            $table->text('id_doctor_fk');
            $table->date('fechaElaboracion');
            $table->text('paciente');
            $table->text('tipoPaciente');
            $table->text('cirugia');
            $table->char('tipoCirugia',2);
            $table->double('cantidadEfe',10,2);
            $table->double('cantidadTrans',10,2);
            $table->double('TPV',10,2);
            $table->text('statusHoja');
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
        Schema::dropIfExists('historico_detalle_consumo');
    }
}
