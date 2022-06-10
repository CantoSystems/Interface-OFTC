<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_estudio_fk');
            $table->unsignedBigInteger('id_ojo_fk');
            $table->double('precioEstudio',10,2);
            $table->string('dscrpMedicosPro',60)->nullable();
            $table->foreign('id_ojo_fk')->references('id')->on('tipo_ojos');
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
        Schema::dropIfExists('estudios');
    }
}