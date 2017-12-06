function seleccionar(campo){
   if(campo.length < 1){
   campo.LabelUsuario.display=none;
   }
}

function deseleccionarBuscar(campo){
  if(campo.length < 1){
    campo.LabelUsuario.display=block;
  }
}

function toggleId(id){
  $( "#" + id ).fadeToggle( 800 );void(0);
  //$( "#page-content-wrapper" ).toggleClass( "on" );
}

function toggleIdCerrar(id,tiempo){
  setTimeout(function() {
      $("#" + id ).fadeOut(800);void(0);
    }, tiempo );
}

function redireccionar_tiempo(ruta,tiempo,target='_self'){
  setTimeout(function() {
      //$("#" + id ).fadeOut(800);void(0);
      if (target=='_self') {
      	document.location.href=ruta;
      }else{
        window.open(ruta, '_blank');
      }
      
  }, tiempo );
}

$( document ).ready(function() {

	$('.preloader-modulo').fadeOut('slow');

	$("#bs-menu .dropdown-toggle").click( function(e){
		//alert ("aqui");
	  var i = $(this).parent().index();
	  $(this).parent().attr('id','dropdown-'+i);
	  $activo = $("#dropdown-"+i).attr('toogle');
	  //alert ($activo);
	  if ($activo=="true"){
	    $(".dropdown-menu").removeClass("on");
	    $(".dropdown-toggle").removeClass("on");
	    $(".dropdown").attr('toogle','false');
	  }else{
	    $(".dropdown[toogle=true] .dropdown-toggle").removeClass("on");
	    $(".dropdown[toogle=true] .dropdown-menu").removeClass("on");
	    $(".dropdown[toogle=true]").attr('toogle','false');
	    $("#dropdown-"+i).attr('toogle','true');
	    $("#dropdown-"+i+" .dropdown-toggle").addClass("on");
	    $("#dropdown-"+i+" .dropdown-menu").addClass("on");
	    $(".box-nav-modulos").removeClass("on");
	  }
	  resize();
	  e.stopPropagation();
	});



$('#content-page').on('click touchend', function(e) {
	//alert ("aqui");
  $(".dropdown-menu").removeClass("on");
  $(".dropdown-toggle").removeClass("on");
  $(".dropdown[toogle=true]").attr('toogle','false');
  $(".dropdown").attr('toogle','false');
  $(".box-nav-modulos").removeClass("on");
});

	function resize(){
	  if ( w < 470 ){
	    $(".collapse").addClass("on");
	    $(".collapse-in").addClass("on");
	  }
	  if ( w > 470 ){
	    $(".collapse").removeClass("on");
	    $(".collapse-in").removeClass("on");
	  }

	  var hmr = $(".nav-menu .dropdown-menu").innerHeight();
	  $(".box-nav-modulos").css("height", hm - 2 + "px");

	  //console.log( w + " : " + h + " : hm "+ hm + " : Hmr " + hmr);

	  if ( h > hm  ){
	    $(".nav-menu .dropdown-menu").css("height", hm + "px");
	    $(".nav-menu .dropdown-menu").css("overflow-y","hidden");
	  }else{
	    $(".nav-menu .dropdown-menu").css("height", h - 32 + "px");
	    $(".nav-menu .dropdown-menu").css("overflow-y","auto");
	  }
	}

	var idr;
	w = $(window).width();
	h = $(window).height();
	hp = 32; //padding caja
	hm = $(".nav-menu .dropdown-menu").innerHeight() + hp ; // menu + espacio de barra

	//console.log(w + " : " + h + " : hm " + hm  );
	resize();

	$(window).resize(function() {
	  clearTimeout(idr);
	  idr = setTimeout(doneResizing, 100 );
	});

	$('.disabled').attr("disabled", true);

	function doneResizing(){
	  w = $(window).width();
	  h = $(window).height();
	  resize();
	}

});
