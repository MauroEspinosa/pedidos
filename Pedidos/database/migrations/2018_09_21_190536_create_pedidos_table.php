<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
      Schema::create('pedidos', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('id_client')->unsigned();
          $table->foreign('id_client')->references('id')->on('users');
          $table->json('pedido');
          $table->integer("precio")->unsigned();
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        //
    }
}
