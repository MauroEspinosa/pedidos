<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pedidos extends Model{
  protected $table="pedidos";
  protected $fillable=["id","id_client","pedido","total","confirmado","estatus"];
}
