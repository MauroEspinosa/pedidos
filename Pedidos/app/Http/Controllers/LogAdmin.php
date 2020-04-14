<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class LogAdmin extends Controller{
  public function login(Request $req){
    if(sizeOf(DB::table('admins')->where(["usuario"=>$req['user'],"contraseña"=>$req["pass"]])->get())>0){
      return view("indexSystem");}
    else{
      return redirect("/admin")->with("mensaje","failAttempt");}
  }
}
