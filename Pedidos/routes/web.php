<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post("/iniciar","LoginController@login");
Route::post("/logAdmin","logAdmin@login");
Route::get("/logout","LoginController@logout");
Route::resource("/user", "users");
Route::resource("/admin_categories","Categories");
Route::resource("/admin_product","Products");

Route::get("/",function(){
  if(Auth::check()){
    return view("indexMenu");}
  else{
    return view("indexLogin");}
});

Route::get("/admin",function(){
  return view("indexAdmin");
});

Route::get("/logAdmin",function(){
  return redirect("/admin");
});

Route::get('/menu/{category}', function($category, Request $req){
  if($category=="ordenes"){
    if(sizeOf(DB::table('pedidos')->where('id_client', Auth::id())->get())>0){
      if(sizeOf(DB::table("pedidos")->where(["id_client"=>Auth::id(),"confirmado"=>true])->get())>0){
        $data=DB::table("pedidos")->where(["id_client"=>Auth::id(),"confirmado"=>true])->get();}
      else{
        $data="none";}
    }
    else{
      $data="none";}
    return $data;}

  else{
    $data=DB::table("productos")->where("categoria",$category)->get();
    return $data->toJson();}
});

Route::get("/admin/{category}",function($category){
  if($category=="Pendientes"){
    $data=DB::table("pedidos")->where("confirmado",true)->whereIn("estatus",["Recibido","Preparando"])->orderBy("updated_at")->get();
    if(sizeOf($data)>0){
      $data=$data;}
    else{
      $data="none";}
    return $data;}

  else if($category=="Preparadas"){
    $data=DB::table("pedidos")->where("estatus","Lista")->orderBy("created_at")->get();
    if(sizeOf($data)>0){
      $data=$data;}
    else{
      $data="none";}
    return $data;}

  else if($category=="Entregadas"){
    $data=DB::table("ventas")->orderBy("created_at")->get();
    if(sizeOf($data)>0){
      $data=$data;}
    else{
      $data="none";}
    return $data;}

  else{
    $data=DB::table("productos")->where("categoria",$category)->get();
    return $data;}
});

Route::get("/carrito",function(){
  $data=DB::table("pedidos")->where([['id_client','=',Auth::id()],['confirmado','=',false]])->pluck("pedido");
  return $data;
});

Route::post("/nuevo_pedido", function(Request $req){
  $producto=DB::table("productos")->where("id",$req["product_id"])->first();
  $agregar='{"nombre":"'.$producto->nombre.'","precio":'.$producto->precio.'}';
  $pedido=DB::table("pedidos")->where("id_client",Auth::id())->latest()->first();

  if(!$pedido){
    \App\pedidos::Create([
      "id_client"=>Auth::id(),
      "pedido"=>$agregar,
      "total"=>$producto->precio,
      "confirmado"=>false,
    ]);
  }

  if($pedido && $pedido->confirmado==false){
    $add=$pedido->pedido.','.$agregar;
    $total=$pedido->total+$producto->precio;
    DB::table("pedidos")->where(["id_client"=>Auth::id(),"id"=>$pedido->id])->update([
      "pedido"=>$add,
      "total"=>$total,
    ]);
  }

  if($pedido && $pedido->confirmado==true){
    \App\pedidos::Create([
      "id_client"=>Auth::id(),
      "pedido"=>$agregar,
      "total"=>$producto->precio,
      "confirmado"=>false,
    ]);
  }

  return $agregar;
});

Route::put("/editar_pedido",function(Request $req){
  $pedido=DB::table("pedidos")->where(["id_client"=>Auth::id(),"confirmado"=>false])->latest()->first();

  if($req["carro"]!=NULL){
    DB::table("pedidos")->where(["id_client"=>Auth::id(),"id"=>$pedido->id])->update([
      "pedido"=>$req["carro"],
      "total"=>$req["total"],
    ]);
    /*$pedido = DB::update('update pedidos set pedido='.$req["carro"].',total='.$req["total"].
                      ' where id_client='.Auth::id().' and id='.$pedido->id);*/
  }
  else{
    DB::table("pedidos")->where(["id_client"=>Auth::id(),"id"=>$pedido->id])->delete();
  }
});

Route::post("confirmar_pedido", function(){
  DB::table("pedidos")->where("id_client",Auth::id())->latest()->limit(1)->update([
    "confirmado"=>true,
    "estatus"=>"Recibido",
  ]);
});

Route::put("/orden/{estatus}", function($status, Request $req){
  if($status=="preparando"){
    DB::table("pedidos")->where("id",$req["id"])->update([
      "estatus"=>"Preparando",
    ]);}

  if($status=="terminada"){
    DB::table("pedidos")->where("id",$req["id"])->update([
      "estatus"=>"Lista",
    ]);}

    if($status=="entregada"){
      $row=DB::table("pedidos")->where("id",$req['id'])->first();
      \App\ventas::Create([
        "id_client"=>$row->id_client,
        "pedido"=>$row->pedido,
        "total"=>$row->total,
        "estatus"=>"listo",
      ]);
      DB::table("pedidos")->where("id",$req['id'])->delete();
    }
});

Route::post("/terminada", function(Request $req){
  $row=DB::table("pedidos")->where("id",$req['id'])->first();
  \App\ventas::Create([
    "id_client"=>$row->id_client,
    "pedido"=>$row->pedido,
    "total"=>$row->total,
    "estatus"=>"listo",
  ]);
  DB::table("pedidos")->where("id",$req['id'])->delete();
});

Route::get("/sql", function(){
  $libros = DB::select('select * from productos');
});
