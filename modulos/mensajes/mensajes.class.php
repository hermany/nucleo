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
		$nom = $this->fmt->usuario->nombre_apellidos(_USU_ID);
		$siglas_receptor = $this->fmt->usuario->siglas_nombre($nom);  
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
			<div class="box-chat">
				<div class="charla"></div>
				<div class='bloque-enviar-mensajes'><input class='' id='inputMensajeReceptor' receptor='' tipe='text' mensaje='1' placeholder='Escribe un mensaje...' ></div>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				var timerId = false;
				var timerIdConversacion = false;
				
				$('.btn-activar-chat').click(function(event) {
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
		 			 		console.log(msg);
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

				$("#inputMensajeReceptor").on('keydown', function(ev) {
				    if(ev.which === 13) {
				    	 var mensaje = $(this).val();
				    	 var num_mensaje = $(this).attr('mensaje');
				    	 var emisor = <?php echo _USU_ID; ?>;
				    	 var receptor = $(this).attr("receptor");
				    	 enviar_mensaje(receptor,emisor,mensaje,num_mensaje);
				    }
				});


				function enviar_mensaje(usu,id_emisor,valor,num_mensaje){
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
            success:function(msgx){ 
            	console.log(msgx);
            	if (msgx=='send'){
					    	 if(num_mensaje==1){
					    	 	$(".box-conversacion").append("<div class='bloque-mensaje bloque-mensaje-receptor' id='mensaje-1'><div class='info info-receptor'><div class='siglas siglas-receptor'><?php $siglas_receptor; ?></div></div><div class='mensaje mensaje-receptor' id='mensaje-1' tipo='emisor'>"+valor+"</div></div>");
					    	 	$('#inputMensajeReceptor').val("");
					    	 	num_mensaje = num_mensaje + 1;
					    	 	$('#inputMensajeReceptor').attr('mensaje', num_mensaje ); 
					    	 }else{
					    	 	$("#mensaje-"+num_mensaje).append("</br>"+mensaje);
			    				$("#inputMensajeEmisor").val("");
					    	 }
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

				$("body").on('click','.btn-activar-chat-emisor', function(event) {
						/* Act on the event */
						console.log("btn-activar-chat-emisor");
						

						var emisor = $(this).attr("emisor");
						$("#inputMensajeReceptor").attr("receptor",emisor)
						var ruta_ajax="ajax-activar-chat";
	          var variables = emisor+",<?php echo _USU_ID; ?>,2,<?php echo _USU_ID_ROL; ?>";
	          var datos = {ajax:ruta_ajax, inputIdMod:<?php echo $this->id_app; ?> , inputVars : variables };
	          var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";   
	          $.ajax({ 
	            url:ruta,
	            type:"post",  
	            async: true,   
	            data:datos,       
	            success:function(msg){  
			 			 		console.log(msg);
			 			 		$(".box-chat .charla").html(msg);
			 			 		ejecutarTimerConversacion();
			 			 	}
			 			});	
					});

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

	public function datos_cliente_atencion($id){
			$consulta = "SELECT mod_cli_ate_usu_id,mod_cli_ate_canal,mod_cli_ate_nombre,mod_cli_ate_ci,mod_cli_ate_topico,mod_cli_ate_fecha_registro,mod_cli_ate_estado	FROM mod_cliente_atencion WHERE mod_cli_ate_id='$id'";
			$rs =$this->fmt->query->consulta($consulta);
			$row=$this->fmt->query->obt_fila($rs);
			$cadena[0]= $row["mod_cli_ate_nombre"];
			$cadena[1]= $row["mod_cli_ate_ci"];
			$cadena[2]= $row["mod_cli_ate_estado"];
			$cadena[3]= $row["mod_cli_ate_topico"];

			return $cadena;

			$this->fmt->query->liberar_consulta();
	}
}