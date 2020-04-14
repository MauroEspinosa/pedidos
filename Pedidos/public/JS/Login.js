angular.module("login",[])

.factory("token", function(){
  var token=document.getElementById("csrf").value;
  return token;
})

.controller("forms",function($scope,$location,$http, token){
  $scope.token=token;
  $scope.form="Templates/login.html";
  $scope.view=function(view){
    $scope.form="Templates/"+view+".html";
  }

  var elements = document.querySelectorAll('.select');
  $scope.focus=function(category){
    for(var i=0;i<elements.length;i++){
      var ide=elements[i].getAttribute('id');
      if(ide==category){elements[i].className="selected";}
      else{elements[i].className="unselected";}
    }
  }
})
