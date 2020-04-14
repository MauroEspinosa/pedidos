<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ventas extends Model{
  protected $table="ventas";
  protected $fillable=["id","id_client","pedido","total","estatus"];
}
