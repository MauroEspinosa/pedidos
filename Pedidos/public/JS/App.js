angular.module("app",['ngRoute'])
.config(function($routeProvider){
  $routeProvider
  .when("/menu",{
    templateUrl: "Templates/Menu.html"
  })
  .otherwise({redirectTo: '/menu'})
})

.controller("menu",function($location){
  $location.path("/menu");
})

.factory("token", function(){
  var token=document.getElementById("csrf").value;
  return token;
})

.factory("carrito", function(){
  return carrito=[];
})

.controller("data",function($scope,$http,carrito,token){
  $scope.category="Templates/category.html";
  $scope.img="Images/cart2.png";

  $http.get("/carrito").then(function(res){
    carrito.push(JSON.parse("["+res.data+"]"));
    $scope.carrito=carrito[0];
  });

  $http.get("/admin_categories").then(function(res){
    $scope.categories=res.data;
  });


  $http.get("/menu/ordenes").then(function(res){
    if(res.data=="none"){
      $scope.none=true;}
    else{
      var obj=[];
      for(i=0;i<res.data.length;i++){
        var temp=JSON.parse("["+res.data[i].pedido+"]");
        obj.push(temp);
        obj[i].estatus=res.data[i].estatus;}
      console.log(obj);
      $scope.orders=obj;}
  });


  $scope.categoria=function(category){
    $http.get("/menu/"+category).then(function(res){
      if(category=="ordenes"){
        if(res.data=="none"){
          $scope.none=true;
          $scope.data="";
          $scope.orders="";
        }
        else{
          $scope.data="";
          var obj=[];
          for(i=0;i<res.data.length;i++){
            var temp=JSON.parse("["+res.data[i].pedido+"]");
            obj.push(temp);
            obj[i].estatus=res.data[i].estatus}
          console.log(obj);
          $scope.orders=obj;
          $scope.total=res.data[0].precio;
        }
      }
      else{
        $scope.none=false;
        $scope.orders="";
        $scope.data=res.data;
      }
    });
  }


  $scope.focus=function(category){
    var elements=document.querySelectorAll(".select, .selected, .unselected");
    for(var i=0;i<elements.length;i++){
      var ide=elements[i].getAttribute('id');
      if(ide==category){elements[i].className="selected";}
      else{elements[i].className="unselected";}
    }
  }


  $scope.add=function(id){
    $http({
        method : "post",
        url : "/nuevo_pedido",
        headers:{"X-CSRF-TOKEN":token},
        dataType: "text",
        data: {product_id:id}
    }).then(function(response){
        if(response.data.indexOf("error")!=-1){
          alert("Podras hacer otro pedido hasta que recojas el anterior");
        }
        else{
          var cart=document.getElementById("circle");
          cart.className = "changeColor";
          setTimeout(function(){
            cart.classList.remove("changeColor");
          },500);
          var res=response.data.split("/>");
          var res=JSON.parse(res[res.length-1]);
          carrito[0].push({nombre:res.nombre,precio:res.precio});
        }
      });
  }


  $scope.remove=function(index){
    var carritoTemp=[];
    for (i=0;i<carrito[0].length; i++){
      if(i!=index){
      carritoTemp.push(carrito[0][i]);}}

    var temp="";
    for(i=0;i<carritoTemp.length;i++){
      if(i<1){
        temp=angular.toJson(carritoTemp[i]);}
      else{
        temp=temp+","+angular.toJson(carritoTemp[i]);}}

    var total=0;
    for(i=0;i<carritoTemp.length;i++){
      total=total+(carritoTemp[i].precio);}

    $http({
        method : "put",
        url : "/editar_pedido",
        headers:{"X-CSRF-TOKEN":token},
        dataType: "text",
        data: {carro:temp,total:total}
    }).then(function(res){
        carrito[0].splice(index,1);
    });
  }


  $scope.confirm=function(){
    $http({
        method : "post",
        url : "/confirmar_pedido",
        headers:{"X-CSRF-TOKEN":token},
        dataType: "text"
    }).then(function(res){
        carrito[0].splice(0,carrito[0].length);
        $scope.cart=false;
        $scope.img="Images/cart2.png";
    });
  }

  $scope.close=function(){
    if($scope.img=="Images/cart2.png"){
      $scope.img="Images/close.png";}
    else {
      $scope.img="Images/cart2.png";}
  }


  $scope.getTotal=function(){
    var total=0;
    for(i=0;i<carrito[0].length;i++){
      total=total+(carrito[0][i].precio);
    }
    return total;
  }

})
