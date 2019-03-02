<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasas', function (Blueprint $table) {
            $table->increments('id');
            $table->double('tasa_porciento');
            $table->double('monto_inicio');
            $table->double('monto_final');
            $table->enum('tipo', ['interes', 'mora']);
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
        Schema::dropIfExists('tasas');
    }
}
