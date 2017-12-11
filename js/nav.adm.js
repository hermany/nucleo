$(document).ready(function(){

  var cant_sitios = $("#navbar-sites").attr("sitios");
  //console.log(cant_sitios);
  if ( cant_sitios == 0){
    $("#btn-m7").remove();
  }else{
    $("#btn-m6").remove();
  }

  $(".btn-salto").click(function(event) {
    /* Act on the event */
    $(".box-sites").toggleClass("on");
    $(".btn-salto").toggleClass("on");
    $(".profile").removeClass("on");
    $(".dropdown-menu").removeClass("on");
  });

  /*------   acciones de bones nav -----*/
  $(".btn-nav-sis").click( function(e){
    var id = $(this).attr("collapse");
    $(".box-nav-modulos").removeClass("on");
    $(".box-sites").removeClass("on");
    $(".btn-salto").removeClass("on");
    $(".box-nav-"+id).addClass("on");
    $(".nav-menu .dropdown-menu").addClass("leftIn");
    // var hm = $(".nav-menu .dropdown-menu").height();
    // alert(hm);
    // $(".box-nav-modulos").css("height",hm + 50 + "px"); //alto más padding + border
    $(".nav-menu .dropdown a").removeClass("active-left");
    $(this).toggleClass("active-left");
  });

  $(".dropdown-toggle").click( function(e){
      $(".box-sites").removeClass("on");
      $(".btn-salto").removeClass("on");
  });

  /*------   acciones de bones nav -----*/
  $(".btn-nav-back").click( function(e){
    var id = $(this).attr("collapse");
    $(".box-nav-"+id).removeClass("on");
    $(".nav-menu .dropdown-menu").removeClass("leftIn");
    $(".nav-menu .dropdown a").removeClass("active-left");
    $(".box-nav-modulos").removeClass("on");
    $(".box-sites").removeClass("on");
    $(".btn-salto").removeClass("on");
  });


}); // fin document ready
