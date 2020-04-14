<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class categorias extends Model{
  protected $table="categorias";
  protected $fillable=["id","categoria"];
}
