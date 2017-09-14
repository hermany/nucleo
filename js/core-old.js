function PaginaCargada() {
	$("body").removeClass("loading");
	$("#Diagrama_1").css("opacity", "1");
}

function AbrirPopup(Url,tipo){
	document.title=Url;
	var ancho1= $( window ).width();
	ancho=Math.round(ancho1*0.98);
	$("#PopupDiv").css("width",ancho1+"px");
	   var popupdiv = document.getElementById("PopupDiv");
	   var popupdivbg = document.getElementById("PopupDivBg");
	    var closeb = "<a  href='javascript:CerrarPopup(\""+tipo+"\");'><i class='fa fa-times'></i></a>";
	    popupdiv.style.display='block';
	    popupdivbg.style.display='block';
	    popupdiv.innerHTML='<div class="CerrarPopup">'
	                        + closeb
	                        + '</div><div class="BloquePopup">'
	                        + '<iframe id="IdFrame" src="' + Url
	                        + '" name="frame_content" scrolling=auto ></div>';
	   ancho = ancho -1;
	   $("#IdFrame").css("width",ancho+"px");
	  $("#wrapper").toggleClass("toggled");
}

function CerrarPopup(Accion){
      var popupdiv = document.getElementById("PopupDiv");
      var popupdivbg = document.getElementById("PopupDivBg");
	  popupdiv.style.display='none';
	  popupdivbg.style.display='none';
	  document.getElementById("PopupDiv").innerHTML="";
	  window.location.reload();
	  if (Accion=="RefrescarPagina"){ // Corregi por RefrecarPagina
	      window.location.reload();
      }
}

function AbrirPopupPagina(Url,tipo){
	document.title=Url;
   var popupdivpag = document.getElementById("PopupDivPagina");
   var bgdivpag = document.getElementById("BgDivPagina");
   var D1 = document.getElementById("ContCuerpo");
    var closebpag = "<a href='javascript:CerrarPopupPagina(\""+tipo+"\");'><img src='admin/img/cerrar_popup.png' border=0 /></a>";
    popupdivpag.style.display='block';
    bgdivpag.style.display='block';
    popupdivpag.style.height=10 + D1.offsetHeight+'px';
    popupdivpag.innerHTML='<div class="BloquePopupPagina" ><div class="CerrarPopupPagina">'
                        + closebpag
                        + '</div>'
                        + '<iframe src="' + Url
                        + '" name="frame_content" scrolling="no">'
                        + '</div>';
}

function CerrarPopupPagina(Accion){
      var popupdiv = document.getElementById("PopupDivPagina");
      var bgdivpag = document.getElementById("BgDivPagina");
	  popupdiv.style.display='none';
	  bgdivpag.style.display='none';
	  document.getElementById("PopupDivPagina").innerHTML="";
	  if (Accion=="RefrescarPagina"){ // Corregi por RefrecarPagina
	      window.location.reload();
      }
}

function MostrarOcultarElemento(Id){
							var Obj=document.getElementById(Id);

							if(Obj.style.display=='none')
							{
								Obj.style.display='block';
							}
							else
							{
								Obj.style.display='none';
							}
						}

						function Validar(Text)
						{

								patron = /([a-z]|[A-Z]|[0-9])+$/;//acepta valores alfanumericos

								//alert(patron.test(Text));

							return patron.test(Text);
						}

						function ValidarSesionAdministrador()
						{
							var Usuario=document.getElementById('UsuarioAdmin').value;
							var Codigo=document.getElementById('CodigoAdmin').value;

							if(Usuario.length>0)
							{
								if(Codigo.length>0)
								{
									if(Validar(Usuario))
									{
										if(Validar(Codigo))
										{
											return true;
										}
										else
										{
											alert('Ingrese el C�digo de Usuario Correctamente');
											return false;
										}
									}
									else
									{
										alert('Ingrese el Nombre de Usuario Correctamente');
										return false;
									}
								}
								else
								{
									alert('Ingrese el C�digo de Acceso');
									return false;
								}
							}
							else
							{
								alert('Ingrese el Nombre de Usuario');
								return false;
							}
}

$(document).ready(function() {

    $('#B1').click(function(){

      var dataString = $('#form_account').serialize();

      $.ajax({
          type: "POST",
          url: "modulos/formularios/registro.php",
          data: dataString,
          success: function(data) {
			$( "#MsgForm" ).html(data);
			$( "#B2" ).click();
			var url = "#paso1";
			$(location).attr('href',url);

          }
      });

    });


});

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

function redireccionar_tiempo(ruta,tiempo){
	setTimeout(function() {
      //$("#" + id ).fadeOut(800);void(0);
			document.location.href=ruta;
  }, tiempo );
}

$("#menu-toggle").click(function(e) {
	e.preventDefault();
	$("#wrapper").toggleClass("toggled");
});


$(document).ready(function(){


});

/*function AbrirPopup(Url){
		 document.getElementById('PopupDiv').style.display='block';
         document.getElementById('PopupDiv').innerHTML='<div class="CerrarPopup">';
         var closeb = "<a href='javascript:CerrarPopup("+tipo+");'><img src='admin/images/cerrar_popup.png' border=0 /></a>";
         document.getElementById('PopupDiv').innerHTML=closeb;
         document.getElementById('PopupDiv').innerHTML='</div><div class="BloquePopup"><iframe src="'+Url+'" name="frame_content" scrolling=auto ></div>';

		var CapaPopup = document.getElementById("PopupDiv");
		var h1 = document.createElement("div");
        h1.innerHTML = "<div class='CerrarPopup'><a href='javascript:CerrarPopup();'><img src='admin/images/cerrar_popup.png' border='0' /></a><div class='Bloque'><iframe src='"+Url+"?modo=popup' name='frame_content' scrolling='auto' ></div>";
        CapaPopup.appendChild(h1)

		};



function CerrarPopup(){
		  document.getElementById('PopupDiv').style.display='none';
		  document.getElementById("PopupDiv").innerHTML="";
		  if (tipo=="RefrecarPagina"){
		  window.location.reload();
		  }
}*/
