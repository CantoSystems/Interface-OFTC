<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalletempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalletemps', function (Blueprint $table) {
            $table->id();
            $table->text('codigo',20)->nullable();
            $table->string('descripcion',60)->nullable();
            $table->char('um',5)->nullable();
            $table->double('cantidad',10,2)->nullable();
            $table->double('precio_unitario',10,2)->nullable();
            $table->double('importe',10,2)->nullable();
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
        Schema::dropIfExists('detalletemps');
    }
}
