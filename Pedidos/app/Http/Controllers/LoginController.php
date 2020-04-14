<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller{

public function login(Request $req){
  if(Auth::attempt(["email"=>$req["email"],"password"=>$req["password"]])){
    return redirect("/");}
  else{
    return redirect("/")->with("mensaje","failAttempt");}
}

public function logout(Request $req){
  Auth::logout();
  return redirect("/");
}

}
