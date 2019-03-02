<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cliente_id')->unsigned()->index();
            $table->integer('prestamo_id')->unsigned()->index();
            $table->double('monto');
            $table->integer('mes');
            $table->double('mora')->nullable();
            $table->double('monto_a_pagar')->nullable();
            $table->dateTime('fecha_pago')->nullable();
            $table->dateTime('fecha_limite_pago')->nullable();
            $table->enum('estado', ['pago', 'atrasado', 'pago_atrasado', 'pendiente']);
            $table->timestamps();

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
        Schema::dropIfExists('pagos');
    }
}
