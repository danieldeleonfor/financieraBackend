<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrestamosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('solicitud_id')->unsigned()->index();
            $table->integer('prestamista_id')->unsigned()->index();
            $table->integer('cliente_id')->unsigned()->index();
            $table->double('monto_total');
            $table->integer('meses');
            $table->double('mora_porciento');
            $table->double('interes_porciento');
            $table->enum('estado', ['activo', 'inactivo', 'cancelado']);
            $table->timestamps();

            $table->foreign('solicitud_id')->references('id')->on('solicitudes')->onDelete('cascade');
            $table->foreign('prestamista_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prestamos');
    }
}
