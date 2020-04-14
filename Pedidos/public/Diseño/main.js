$(document).ready(function(){
  $("div#options ul li#signup").click(function(){
    $(this).css("color","#e1b6b6");
    $("div#options ul li#login").css("color","white");
  });

  $("div#options ul li#login").click(function(){
    $(this).css("color","#e1b6b6");
    $("div#options ul li#signup").css("color","white");
  });
});
