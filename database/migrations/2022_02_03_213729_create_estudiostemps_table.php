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
            $table->char('id_doctor_fk',10)->nullable();
            $table->char('id_empTrans_fk',10)->nullable();
            $table->char('id_empInt_fk',10)->nullable();
            $table->char('id_empRea_fk',10)->nullable();
            $table->date('fecha');
            $table->char('folio',10)->unique();
            $table->text('paciente')->nullable();
            $table->char('tipoPaciente',1)->nullable();
            $table->text('servicio')->nullable();
            $table->text('met_pago')->nullable();
            $table->char('cfdi',10)->nullable();
            $table->double('subtotal',10,2)->nullable();
            $table->double('descuento',10,2)->nullable();
            $table->double('iva',10,2)->nullable();
            $table->double('total',10,2)->nullable();
            $table->char('transcripcion',1)->nullable();
            $table->char('interpretacion',1)->nullable();
            $table->char('escaneado',1)->nullable();
            $table->char('entregado',1)->nullable();
            $table->char('statusCita',15)->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('estudiostemps_status')->default(0)->nullable();
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
        Schema::dropIfExists('estudiostemps');
    }
}