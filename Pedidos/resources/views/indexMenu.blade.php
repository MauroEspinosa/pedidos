<!DOCTYPE html>
<html ng-app="app">
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
  <script src="JS/App.js"></script>
  <link rel="stylesheet" href="CSS/Master-User.css">
  <title></title>
</head>
<body>
  <div id="fondo"><video autoplay loop><source src="Images/fondo.mp4" type="video/mp4"></video><div id="filtro"></div></div>
  <input type="hidden" name="_token" value="{{csrf_token()}}" id="csrf">
  <div ng-view ng-controller="menu"></div>
</body>
</html>
