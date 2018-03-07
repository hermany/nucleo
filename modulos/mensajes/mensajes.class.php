<?php
header("Content-Type: text/html;charset=utf-8");

class MENSAJES{

	var $fmt;
	var $id_app;
	var $id_item;
	var $id_estado;
 

	function MENSAJES($fmt,$id_app=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_app = $id_app;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
	}

	function dashboard_mensajes(){
		$tabs = array ("Bandeja de Entrada","Estadisticas");
		$iconos = array ("icn icn-inbox","icn icn-stadistics");
		$this->fmt->class_pagina->tabs_mod("","dashboard",$tabs,$iconos,0,"tabs-config head-modulo-inner","h4");
		$rand = range(1, 100); shuffle($rand); foreach ($rand as $val) { $vrand = $val;}
		?>
		<link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/m-mensajes.css?reload=<?php echo $rand; ?>" rel="stylesheet" type="text/css">
		<div class="tabs-body">
			<div class="tbody tab-content on" id="content-dashboard-0">
				<?php $this->chat(); ?>
			</div>
			<div class="tbody tab-content" id="content-dashboard-1">
				
			</div>
		</div> <!-- fin   tabs-body -->
		<script type="text/javascript">
			$(document).ready(function() {
				var w = $(window).width(); 
				var h = $(window).outerHeight();
				var hm = h - 102;
				var wm = w - 350 -2;
				$(".tabs-body").height(hm);
				$(".tabs-body .box-chat").width(wm);
			});
		</script>
		<?php
	}

	function chat(){
		?>
		<div class="block-chat container-fluid">
			<div class="tablero">
				<div class="box-buscar">
					<form id="form-buscar-chat">
						<i class="icn icn-search"></i>
						<input type="text" id="inputBuscarChat" placeholder="Buscar"  />
					</form>
				</div>
				<?php 
				  // verificar estado de usuario
				  $btn_activar = "<a class='btn-activar-chat' title='Activar Atención' activar='0'><i class='icn icn-minus-circle'></i></a>";
					$tabsx = array ("");
					$iconosx = array ("icn icn-bubble-w");
					$this->fmt->class_pagina->tabs_mod("","charlas",$tabsx,$iconosx,0,"tabs-config","h4",$btn_activar);
				?>
				<div class="bloque-lista-chats"></div>
			</div>
			<div class="box-chat"></div>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {

				var timerId = false;
				var timerIdConversacion = false;
				
				$("body").on('click', '.btn-activar-chat', function() {
 					var activar = $(this).attr("activar");
			    var ruta_ajax="ajax-activar-canal";
          var variables = activar+",<?php echo _USU_ID; ?>,2,<?php echo _USU_ID_ROL; ?>";
          var datos = {ajax:ruta_ajax, inputIdMod:<?php echo $this->id_app; ?> , inputVars : variables };

         //console.log(datos);
          
         	var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";   
          $.ajax({ 
            url:ruta,
            type:"post",  
            async: true,   
            data:datos,       
            success:function(msg){  
		 			 		//console.log(msg);
		 			 		var dat = msg.split(":");
		 			 		if (dat[0]=="error"){
		 			 		  console.log("error: "+dat[1]);
		 			 		  clearInterval(myVar);
		 			 		}
		 			 		if (dat[0]=="estado"){
		 			 			if (dat[1]=="1"){
		 			 				ejecutarTimer();
		 			 				$(".btn-activar-chat").attr("activar","1");
		 			 				$(".btn-activar-chat i").removeClass();
		 			 				$(".btn-activar-chat i").addClass("icn icn-checkmark-circle");
		 			 			}

		 			 			if (dat[1]=="0"){
		 			 				detenerTimer();
		 			 				$(".btn-activar-chat").attr("activar","0");
		 			 				$(".btn-activar-chat i").removeClass();
		 			 				$(".btn-activar-chat i").addClass("icn icn-minus-circle");	
		 			 			}	
		 			 		}
		 				}
		 			});
				}); // click .btn-activar-chat;

				$("body").on('click', '.btn-activar-chat-emisor', function( ) {
					var id_emisor = $(this).attr("emisor");
					 //console.log(id_emisor);
					$(".charla").removeClass("on");
					$(".charla").addClass('off');
					$("#charla-"+id_emisor).addClass('on');
					$("#check-"+id_emisor).addClass('off');

					var ruta_ajax="ajax-cargar-chat";
          var variables = "<?php echo _USU_ID; ?>,"+id_emisor;
          var datos = {ajax:ruta_ajax, inputIdMod:<?php echo $this->id_app; ?>, inputVars : variables };
          var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
          $.ajax({ 
            url:ruta,
            type:"post",  
            async: true,   
            data:datos,       
            success:function(msg){ 
            	console.log(msg);
            	var m1 = msg.split(",");
		 			 		if (m1[0]=="nuevo"){
		 			 			$("#conversacion-"+m1[1]).append(m1[2]);
		 			 			var hc = $(".charla").outerHeight() - 56 - 61;
		 			 			$(".box-conversacion").height(hc);
		 			 			ejecutarTimerConversacion();
		 			 		}
            }
          });
					/* Act on the event */
				});

				$("body").on('click', '.btn-enviar-mensaje-emisor', function() {
					var id_emisor = $(this).attr("emisor");
					var valor = $(".input-"+ id_emisor).val();
					var usu =  <?php echo _USU_ID; ?>
					enviar_mensaje(usu,id_emisor,valor);
					//console.log(usu+":"+id_emisor+":"+valor);
					/* Act on the event */
				});

				$("body").on('keypress','#inputEnviarMensaje', function(e){
					var valor = $(this).val();
					var id_emisor = $(this).attr("emisor");
					var usu =  <?php echo _USU_ID; ?>;
					if (valor){
						if(e.which == 13) {
			        // console.log(usu+":"+id_emisor+":"+valor);
			        enviar_mensaje(usu,id_emisor,valor);
			    	}
					}
		    });

		    function enviar_mensaje(usu,id_emisor,valor){
		    	console.log(usu+":"+id_emisor+":"+valor);
		    	var ruta_ajax="ajax-enviar-mensaje";
          var variables = usu+","+id_emisor+","+valor;
          var datos = {ajax:ruta_ajax, inputIdMod:<?php echo $this->id_app; ?> , inputVars : variables };
          var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
          $.ajax({ 
            url:ruta,
            type:"post",  
            async: true,   
            data:datos,       
            success:function(msg){ 
            	console.log(msg);
		 			 		}
            }
          });
		    }

				function ejecutarTimerConversacion(){
					if (!timerIdConversacion) {
						timerIdConversacion = setInterval(function(){
								revisar_conversacion();
						}, 3000);
					}
				}

				function detenerTimerConversacion() {
				    clearInterval(timerIdConversacion);
				    timerIdConversacion = false;
				}



				function ejecutarTimer(){
					if (!timerId) {
						timerId = setInterval(function(){
								revisar_chat();
						}, 3000);
					}
				}

				function detenerTimer() {
				    clearInterval(timerId);
				    timerId = false;
				}

				function revisar_conversacion(){
					console.log("revisando conversacion");
					// var ruta_ajax="ajax-buscar-mensajes";
					// var variables = "<?php echo _USU_ID; ?>";
					// var datos = {ajax:ruta_ajax, inputIdMod:<?php echo $this->id_app; ?> , inputVars : variables };
					// $.ajax({ 
     //        url:ruta,
     //        type:"post",  
     //        async: true,   
     //        data:datos,       
     //        success:function(msg){ 
     //        	console.log(msg);
     //        }
     //       });
				}

				function revisar_chat(){
					console.log("revisando cliente");
					var ruta_ajax="ajax-revisar-chat";
          var variables = "<?php echo _USU_ID; ?>";
          var datos = {ajax:ruta_ajax, inputIdMod:<?php echo $this->id_app; ?> , inputVars : variables };
          var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
          $.ajax({ 
            url:ruta,
            type:"post",  
            async: true,   
            data:datos,       
            success:function(msg){ 
            	//console.log(msg);
            	var datm = msg.split(",");
            	if (datm[0]=="nuevo"){
            		$(".bloque-lista-chats").prepend(datm[1]);
            		$(".box-chat").prepend(datm[2]);
            	}

            }
          });
				} // revisar_chat

			});
		</script>
		<?php
	}

}