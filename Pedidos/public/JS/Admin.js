angular.module("admin",['ngRoute'])
.config(function($routeProvider){
  $routeProvider
  .when("/admin",{
    templateUrl: "Templates/options.html"
  })
  .when("/admin/pedidos",{
    templateUrl: "Templates/sistema.html"
  })
  .when("/admin/menu",{
    templateUrl: "Templates/men.html"
  })
})

.controller("admin",function($location){
  $location.path("/admin");
})

.factory("token", function(){
  var token=document.getElementById("csrf").value;
  return token;
})

.factory("categories", function($http){
  var categories=[]
  $http.get("/admin_categories").then(function(res){
    for(i=0;i<res.data.length;i++){
      categories.push(res.data[i]);
    }
  });
  return categories;
})

.factory("selected", function(){
  var selected="";
  return selected;
})

.value("globalVars",{
  selected:"empty",
  cart:false
})

.controller("system", function($scope, $http, $location, token, categories, globalVars){
  $scope.categories=categories;
  $scope.select=true;

  var elements = document.querySelectorAll('.category');

  var changeData=function(category){
    $http.get("/admin/"+category).then(function(res){
      console.log(res.data);
      if(res.data=="none"){
        $scope.empty=true;
        $scope.orders=""}
      else{
        $scope.empty="";
        var obj=[];
        for(i=0;i<res.data.length;i++){
          var temp=JSON.parse("["+res.data[i].pedido+"]");
          obj.push(temp);
          obj[i].id=res.data[i].id;
          obj[i].total=res.data[i].total;
          obj[i].estatus=res.data[i].estatus;
          if(category=="Preparadas"){$scope.prep=true; $scope.pend=false; $scope.entr=false}
          if(category=="Pendientes"){$scope.pend=true; $scope.prep=false; $scope.entr=false}
          if(category=="Entregadas"){$scope.entr=true; $scope.prep=false; $scope.pend=false;}}
        console.log(obj);
        $scope.orders=obj;}
    });

    var dynamic=angular.element(document.getElementById("dynamic"));
    dynamic[0].scrollTop=0;

    for(var i=0;i<elements.length;i++){
      var ide=elements[i].getAttribute('id');
      if(ide==category){elements[i].className="selected";}
      else{elements[i].className="unselected";}
    }
  }

  $scope.focus=function(category){
    changeData(category);
    $scope.select=false;
  }

  $scope.categoria=function(category){
    $scope.select=false;
    globalVars.selected=category;
    $http.get("/admin/"+category).then(function(res){
      console.log(res.data);
      $scope.items=res.data;
    });

    var dynamic=angular.element(document.getElementById("dynamic"));
    dynamic[0].scrollTop=0;

    var elements=document.querySelectorAll(".category, .selected, .unselected");
    for(var i=0;i<elements.length;i++){
      var ide=elements[i].getAttribute('id');
      if(ide==category){elements[i].className="selected";}
      else{elements[i].className="unselected";}}

  }

  $scope.preparando=function(event,id){
    $http({
        method : "put",
        url : "/orden/preparando",
        headers:{"X-CSRF-TOKEN":token},
        dataType: "text",
        data: {id:id}
    }).then(function(response){
         event.currentTarget.className="preparando";
       });
    }

  $scope.terminada=function(id){
    $http({
        method : "put",
        url : "/orden/terminada",
        headers:{"X-CSRF-TOKEN":token},
        dataType: "text",
        data: {id:id}
    }).then(function(response){
          changeData("Pendientes");
       });
  }

  $scope.entregada=function(id){
    $http({
        method : "put",
        url : "/orden/entregada",
        headers:{"X-CSRF-TOKEN":token},
        dataType: "text",
        data: {id:id}
    }).then(function(response){
          changeData("Preparadas");
       });
  }

  $scope.addCategory=function(){
    $http({
        method : "post",
        url : "/admin_categories",
        headers:{"X-CSRF-TOKEN":token},
        dataType: "text",
        data: {category:$scope.newCategory}
    }).then(function(response){
          $scope.categorias.push($scope.newCategory);
          $scope.newCategory="";
       });
  }

  $scope.deleteCategory=function(item,index){
    if(confirm("Si realiza esta accion, eliminara todos los productos de la categoria '" +item+"'. ¿Desea continuar?")){
      $http.delete('/admin_categories/'+item).then(function(){
        $scope.categorias.splice(index,1);})
    }
  }

  $scope.addProduct=function(){
   $.ajax({
     url: "/admin_product",
     type: "POST",
     headers:{"X-CSRF-TOKEN":token},
     data: {nombre:$scope.newName,
            precio:$scope.newPrice,
            categoria:$scope.newCategory,
            descripcion:$scope.newDesc},
     success: function(res){
       alert("Producto guardado correctamente");
       if(globalVars.selected==$scope.newCategory){
         $scope.items.unshift(JSON.parse(res));}
     }
   });
  }

  $scope.updateProduct=function(id,index){
    var info={nombre:$scope.editName,
              precio:$scope.editPrice,
              categoria:$scope.editCateg,
              descripcion:$scope.editDesc};

    console.log(info);

    $http({
        method : "put",
        url : "/admin_product/"+id,
        headers:{"X-CSRF-TOKEN":token},
        dataType: "text",
        data:info
    }).then(function(res){
        if(info.categoria!=globalVars.selected){
          $scope.items.splice(index,1);}
        else{
          $scope.items[index]=res.data;}
        alert("Producto actualizado correctamente");
       });
  }

  $scope.deleteProduct=function(id,index,producto,categoria){
    if(confirm("Esta apunto de eliminar '"+producto+"' de la categoria '"+categoria+"¿' ¿Desea continuar?")){
      $http.delete('/admin_product/'+id).then(function(){
        $scope.cart=false;
        $scope.img="Images/cart2.png";
        $scope.items.splice(index,1);
      });
    }
  }

  $scope.img="Images/cart2.png";

  $scope.prompt=function(element,nombre,precio,descripcion,categoria,id,index){
    $scope.element=element;
    $scope.section="Templates/"+element+".html";

    if($scope.img=="Images/cart2.png"){
      $scope.img="Images/close.png";}
    else {
      $scope.img="Images/cart2.png";}

    if(element=="Categorias"){
      $scope.categorias=categories;
    }

    if(element=="Editar"){
      $scope.categorias=categories;

      $scope.editName=nombre;
      $scope.editPrice=precio;
      $scope.editDesc=descripcion;
      $scope.editCateg=categoria;
      $scope.productId=id;
      $scope.index=index;
   }
  }
})
