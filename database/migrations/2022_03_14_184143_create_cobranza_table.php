<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCobranzaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cobranza', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_estudio_fk');
            $table->unsignedBigInteger('id_doctor_fk');
            $table->unsignedBigInteger('id_empTrans_fk');
            $table->unsignedBigInteger('id_empInt_fk');
            $table->date('fecha');
            $table->char('folio',10);
            $table->text('paciente');
            $table->text('tipoPaciente');
            $table->text('formaPago');
            $table->char('transcripcion',1)->nullable();
            $table->char('interpretacion',1)->nullable();
            $table->char('escaneado',1)->nullable();
            $table->double('cantidadCbr',10,2)->nullable();
            $table->text('observaciones')->nullable();
            $table->foreign('id_estudio_fk')->references('id')->on('estudios');
            $table->foreign('id_doctor_fk')->references('id')->on('doctors');
            $table->foreign('id_empTrans_fk')->references('id_emp')->on('empleados');
            $table->foreign('id_empInt_fk')->references('id_emp')->on('empleados');
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
        Schema::dropIfExists('cobranza');
    }
}
