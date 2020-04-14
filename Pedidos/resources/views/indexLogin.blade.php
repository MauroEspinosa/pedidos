<!DOCTYPE html>
<html ng-app="login">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300i,400i" rel="stylesheet">
  <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>-->
  <script src="JS/angular.min.js"></script>
  <!--<script src="http://code.angularjs.org/1.3.14/angular-route.min.js"></script>-->
  <script src="JS/angular-route.min.js"></script>
  <script src="JS/login.js"></script>
  <link rel="stylesheet" href="CSS/Master-User.css">
  <title></title>
</head>
<body>
  <div id="fondo"><video autoplay loop><source src="Images/fondo.mp4" type="video/mp4"></video><div id="filtro"></div></div>
  <input type="hidden" name="_token" value="{{csrf_token()}}" id="csrf">
  <div ng-controller="forms">
    <div id="options"><ul>
      <li class="select" ng-click="view('login'); focus('login')" id="login">Login</li>
      <li class="select" ng-click="view('signup'); focus('signup')" id="signup">Signup</li>
    </ul></div>

    <div id="brand">
      <h1 id="name">LOREM</h1>
      <h3>Lorem ipsum dolor sit amet</h3>
    </div>

    <div id="login" ng-include=form></div>
  </div>
  <?php
    $mensaje=Session::get("mensaje");
    if($mensaje=="failAttempt"){echo "<script type=\"text/javascript\">setTimeout(function(){ alert(\"Datos incorrectos\"); }, 1000);</script>";}
    if($mensaje=="emailUsed"){echo "<script type=\"text/javascript\">setTimeout(function(){ alert(\"El email que intentas usar ya ha sido registrado\"); }, 1000);</script>";}
  ?>
</body>
</html>
