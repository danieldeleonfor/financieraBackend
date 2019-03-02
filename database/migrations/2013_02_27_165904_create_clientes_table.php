<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique()->nullable();
            $table->string('cedula')->unique();
            $table->text('direccion');
            $table->enum('sexo', ['F', 'M']);
            $table->enum('estado_civil', ['S', 'C']);
            $table->string('telefono')->nullable();
            $table->string('empresa')->nullable();
            $table->string('cargo')->nullable();
            $table->double('sueldo')->nullable();
            $table->integer('valoracion_por_pagos')->nullable();
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
        Schema::dropIfExists('clientes');
    }
}
