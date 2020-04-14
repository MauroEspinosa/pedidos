<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Products extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req){
      $product=\App\productos::Create([
                  "nombre"=>$req["nombre"],
                  "precio"=>$req["precio"],
                  "descripcion"=>$req["descripcion"],
                  "categoria"=>$req["categoria"],
                ]);
      $data=DB::table("productos")->where("id",$product->id)->first();
      return json_encode($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
      $data=DB::table("productos")->where("id",$id)->first();
      return json_encode($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id){
      $newInfo=["nombre"=>$req["nombre"],
                "precio"=>$req["precio"],
                "descripcion"=>$req["descripcion"],
                "categoria"=>$req["categoria"],];

      DB::table("productos")->where("id",$id)->update($newInfo);
      $data=DB::table("productos")->where("id",$id)->first();
      return json_encode($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
      DB::table("productos")->where("id",$id)->delete();
    }
}
