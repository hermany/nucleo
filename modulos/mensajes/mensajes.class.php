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

		// $sql="DELETE FROM mod_cliente_mensajes";
		// $this->fmt->query->consulta($sql,__METHOD__);
		// $sql="DELETE FROM mod_cliente_atencion";
		// $this->fmt->query->consulta($sql,__METHOD__);
		// $sql="DELETE FROM mod_cliente_atencion_con";
		// $this->fmt->query->consulta($sql,__METHOD__);
		// $up_sqr6 = "ALTER TABLE mod_cliente_mensajes AUTO_INCREMENT=1";
		// $this->fmt->query->consulta($up_sqr6,__METHOD__);		
		// $up_sqr6 = "ALTER TABLE mod_cliente_atencion AUTO_INCREMENT=1";
		// $this->fmt->query->consulta($up_sqr6,__METHOD__);		
		// $up_sqr6 = "ALTER TABLE mod_cliente_atencion_con AUTO_INCREMENT=1";
		// $this->fmt->query->consulta($up_sqr6,__METHOD__);

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
				//var w = $(window).outerWidth(); 
				var h = $(window).outerHeight();
				var hm = h - 102;
				// var wm = w - 400;
				$(".tabs-body").height(hm);
				// $(".box-chat").outerWidth(wm);
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
				  //$btn_activar = "<a class='btn-activar-chat' title='Activar Atención' activar='0'><i class='icn icn-minus-circle'></i></a>";
					$tabsx = array ("");
					$iconosx = array ("icn icn-bubble-w");
					$this->fmt->class_pagina->tabs_mod("","charlas",$tabsx,$iconosx,0,"tabs-config","h4",$btn_activar);
				?>
				<div class="bloque-lista-chats">
					<?php 
						$consulta = "SELECT * FROM mod_cliente_atencion WHERE  mod_cli_ate_canal=2 ORDER BY mod_cli_ate_estado_chat ASC Limit 0,280";
						$rs =$this->fmt->query->consulta($consulta);
						$num=$this->fmt->query->num_registros($rs);
						if($num>0){
							for ($i=0; $i < $num ; $i++) { 
								$row=$this->fmt->query->obt_fila($rs);
								$emisor = $row["mod_cli_ate_id"];
								$estado = $row["mod_cli_ate_estado_chat"];
								// $estado = $row["mod_cli_ate_estado"];
								$nom = $row["mod_cli_ate_nombre"]." - ".$row["mod_cli_ate_ci"].$row["mod_cli_ate_ext"];
								// $estado = $row["men_estado"];
								// $emisor_array = $mensajes->datos_cliente_atencion($emisor);
								$sigla = $this->fmt->usuario->siglas_nombre($nom);
								//$nom = $emisor_array['mod_cli_ate_nombre']." - ".$emisor_array['mod_cli_ate_ci'].$emisor_array['mod_cli_ate_ext'];

								// $estado = $this->estado_ultimo_mensaje($emisor);

								echo '<a class="bloque-mensaje bloque-mensaje-emisor bloque-mensaje-emisor-'.$emisor.' btn-activar-chat-emisor" estado="'.$estado.'" emisor="'.$emisor.'">';
								echo '	<div class="info info-emisor">';
								echo '		<div class="siglas siglas-emisor">'.$sigla.'</div>';
								echo '		<div class="nombre">'.$nom.'</div>';
								echo '		<div class="estado estado-'.$estado.'"></div>';
								echo '	</div>';
								echo '</a>';
							}
						}
					?>
				</div>
			</div>
			<div class="box-chat">
			</div>
			<div class="box-cerrar-chat">
				<div class="box-inner">
					<label for="">Estas seguro de cerrar está conversación?</label>
					<div class="botones">
						<a class="btn btn-link btn-cancelar-cerrar-chat"> Cancelar</a>
						<a class="btn btn-info btn-cerrar-chat-ok" usu='' conv=''> Si, Cerrar Conversación</a>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				var timerId = false;
				var timerIdConversacion = false;
				var id_emisor;
				var num_mensaje=1;
				 


				ejecutarTimerConversacion();
				resizeMensaje();


				function resizeMensaje(){
					var w = $(window).outerWidth();
					var h = $(window).outerHeight();
					var wp = w - 369;
					var hpc = h - 203;
					$('.tab-content .box-chat').outerWidth(wp);
					$('.box-conversacion').outerHeight(hpc);
				}

				$(window).resize(function(event) {
					/* Act on the event */
					resizeMensaje();
				});

 
				function enviar_mensaje(cliente,id_usu,valor,id_mensaje,last,conv){ //cliente,usuario,mensaje,num_mensaje,last,conv
		    	// console.log(usu+":"+id_emisor+":"+valor);
		    	var ruta_ajax="ajax-enviar-mensaje";
          var variables = cliente+","+id_usu+","+valor+","+conv;
          var datos = {ajax:ruta_ajax, inputIdMod:<?php echo $this->id_app; ?> , inputVars : variables };
          var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
          $.ajax({ 
            url:ruta,
            type:"post",  
            async: true,   
            data:datos,  
            beforeSend: function () {
            	$('#inputMensajeReceptor').addClass('disabled');
  					},     
            success:function(msgx){ 
            	console.log(msgx);
            	if (msgx=='send'){
					    	 if(last=='emisor'){
					    	 	num_mensaje ++; 
					    	 	$(".box-conversacion").append("<div class='bloque-mensaje bloque-mensaje-receptor' id='mensaje-1'><div class='info info-receptor'><div class='siglas siglas-receptor'><?php echo $siglas_receptor; ?></div></div><div class='mensaje mensaje-receptor' id='mensaje-"+num_mensaje+"' tipo='emisor'>"+valor+"</div></div>");
					    	 	$('#inputMensajeReceptor').val("");

					    	 	$('.bloque-mensaje-emisor-'+cliente).attr('estado', '2');
					    	 	$('.bloque-mensaje-emisor-'+cliente+' .estado').addClass('estado-2');
		          	  $('.bloque-mensaje-emisor-'+cliente+' .estado').removeClass('estado-1');

					    	 	
					    	 	$('#inputMensajeReceptor').attr('mensaje', num_mensaje ); 
					    	 	$('#inputMensajeReceptor').attr('last', 'receptor' ); 
					    	 	$(".box-conversacion").animate({ scrollTop: $('.box-conversacion').prop("scrollHeight")}, 1000);
					    	 }else{
					    	 	$("#mensaje-"+num_mensaje).append("</br>"+valor);
			    				$("#inputMensajeReceptor").val("");
			    				$(".box-conversacion").animate({ scrollTop: $('.box-conversacion').prop("scrollHeight")}, 1000);
					    	 }
					    }

					    $('#inputMensajeReceptor').removeClass('disabled');
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



				function ejecutarTimer(emisor){
					// console.log("usu"+emisor);
					if (!timerId) {
						timerId = setInterval(function(){
								revisar_chat(emisor);
						}, 3000);
					}
				}

				function detenerTimer() {
				    clearInterval(timerId);
				    timerId = false;
				}

				function numOrdDesc(a, b) {
				  return ($(b).attr('estado')) < ($(a).attr('estado')) ? 1 : -1;
				}

				function revisar_conversacion(){
					console.log("revisando conversacion");
					var ruta_ajax="ajax-chat-buscar";
					var variables = "<?php echo _USU_ID; ?>";
					var datos = {ajax:ruta_ajax, inputIdMod:<?php echo $this->id_app; ?> , inputVars : variables };
					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php"; 
					$.ajax({ 
	          url:ruta,
	          type:"post",  
	          async: true,   
	          data:datos,       
	          success:function(msg){ 
	          	console.log(msg);
	          	var dat = msg.split('~');
	          	// var item = msg['1'];

	          	if (dat[0]=='ok'){
	          		$('.bloque-lista-chats').prepend(dat[2]);
	          	}
	          	
	          	if (dat[0]=='mensaje'){
	          		var min = dat[1].split(",");
	          		var nu = min.length;
	          		if (nu > 0){
		          		for (var i = 0; i < nu; i++) {
		          			// console.log(min[i]);
		          			$('.bloque-mensaje-emisor[emisor='+min[i]+']').attr('estado', '1');
		          			$('.bloque-mensaje-emisor-'+min[i]).prependTo('.bloque-lista-chats');
		          			$('.bloque-mensaje-emisor[emisor='+min[i]+'] .estado').removeClass('estado-2');
		          			$('.bloque-mensaje-emisor[emisor='+min[i]+'] .estado').addClass('estado-1');

		          		}
		          		 
	          		}
	          		
	          	}

	          }
	         });
				}

				$('body').on('click','.btn-cerrar-chat-ok', function(event) {
 			 			var conv = $(this).attr("conv");
 			 			var usu = $(this).attr("usu");
 			 			var emisor = $(this).attr("emisor");

 			 			var ruta_ajax="ajax-chat-cerrar";
	          var variables = conv+","+usu+","+emisor;
	          var datos = {ajax:ruta_ajax, inputIdMod:<?php echo $this->id_app; ?> , inputVars : variables };
	          var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";   
	          $.ajax({ 
	            url:ruta,
	            type:"post",  
	            async: true,   
	            data:datos,       
	            success:function(msgp){  
	            	console.log('.box-conversacion-'+emisor);
	            	 
	            		$('.box-cerrar-chat').removeClass('on');
	            		$('.btn-cerrar-atencion').addClass('disabled');
	            	 	$('.box-conversacion-'+emisor).append('<div class="mensaje-cerrar">Conversación Cerrada '+msgp+'</div>');
	            	 		num_mensaje ++; 
	            	 	$('#inputMensajeReceptor').attr('mensaje', num_mensaje ); 
	            	 	$('#inputMensajeReceptor').attr('last', 'emisor' ); 
	            	 	$(".box-conversacion").animate({ scrollTop: $('.box-conversacion').prop("scrollHeight")}, 100);

	            }
	          });
				});	

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

			 			 		var dat = msg.split("~");
			 			 		num_mensaje = dat[0];

				 			 		$(".box-chat").html(dat[1]);
				 			 		$('#inputMensajeReceptor').attr('mensaje', num_mensaje ); 

				 			 		$('.btn-cerrar-atencion').attr('conv',dat[2] ); 
				 			 		$('.btn-cerrar-atencion').attr('usu',<?php echo _USU_ID; ?> ); 				 			 		
				 			 		$('.btn-cerrar-atencion').attr('emisor',emisor); 				 			 		
				 			 		$('.btn-cerrar-chat-ok').attr('conv',dat[2] ); 
				 			 		$('.btn-cerrar-chat-ok').attr('usu',<?php echo _USU_ID; ?> ); 
				 			 		$('.btn-cerrar-chat-ok').attr('emisor',emisor); 

				 			 		$('.btn-cerrar-atencion').click(function(event) {
				 			 			/* Act on the event */
				 			 			$('.box-cerrar-chat').addClass('on');
				 			 		});

				 			 		$('.btn-cancelar-cerrar-chat').click(function(event) {
				 			 			/* Act on the event */
				 			 			$('.box-cerrar-chat').removeClass('on');
				 			 		});

				 			 		

				 			 		$("#inputMensajeReceptor").on('keydown', function(ev) {
										// console.log($(this).val());
									    if(ev.which === 13) {
									    	 var mensaje = $(this).val();
									    	 var num_mensaje = $(this).attr('mensaje');
									    	 var usuario = <?php echo _USU_ID; ?>;
									    	 var cliente = $(this).attr("cliente");
									    	 var last = $(this).attr("last");
									    	 var conv = $(this).attr("conv");
									    	 enviar_mensaje(cliente,usuario,mensaje,num_mensaje,last,conv);
									    }
									});



									//$('.topico').html("Tópico: <b>"+dat[2]+"</b>");

									$(".box-conversacion").animate({ scrollTop: $('.box-conversacion').prop("scrollHeight")}, 1000);

				 			 		// ejecutarTimerConversacion();

				 			 		$(".bloque-mensaje").removeClass('active');
				 			 		$(".bloque-mensaje[emisor="+emisor+"]").addClass('active');
				 			 		$(".bloque-mensaje[emisor="+emisor+"] .estado").removeClass('estado-1');
				 			 		$(".bloque-mensaje[emisor="+emisor+"] .estado").addClass('estado-2');
				 			 		resizeMensaje();
				 			 		detenerTimer();
				 			 		ejecutarTimer(emisor);
			 			 	}
			 			});	
				});

				function revisar_chat(emisor){
					console.log("revisando chat cliente " + emisor);
					var ruta_ajax="ajax-revisar-chat";
          var variables = "<?php echo _USU_ID; ?>,"+ emisor+','+num_mensaje;
          var datos = {ajax:ruta_ajax, inputIdMod:<?php echo $this->id_app; ?> , inputVars : variables };
          var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
          $.ajax({ 
            url:ruta,
            type:"post",  
            async: true,   
            data:datos,       
            success:function(msg){ 
            	console.log(msg);
            	var datm = msg.split("~");
            	if (datm[0]=='ok'){
	          		$('.box-conversacion').append(datm[1]);
	          		$(".box-conversacion").animate({ scrollTop: $('.box-conversacion').prop("scrollHeight")}, 1000);
	          		num_mensaje = datm[2];
	          		$('#inputMensajeReceptor').attr('mensaje', num_mensaje ); 
	          		$('#inputMensajeReceptor').attr('last', 'emisor'); 
	          		$('.bloque-mensaje-emisor[emisor='+datm[3]+']').attr('estado', '1');
	          		$(".bloque-mensaje[emisor="+datm[3]+"] .estado").removeClass('estado-2');
				 			 		$(".bloque-mensaje[emisor="+datm[3]+"] .estado").addClass('estado-1');
	          	}

            }
          });
				} // revisar_chat

			});
		</script>
		<?php
	}

	public function datos_cliente_atencion($id){
			$consulta = "SELECT *	FROM mod_cliente_atencion WHERE mod_cli_ate_id='$id'";
			$rs =$this->fmt->query->consulta($consulta);
			$num=$this->fmt->query->num_registros($rs);
		 	if($num>0){
		 		$row=$this->fmt->query->obt_fila($rs);
		 		return $row;
		 	}else{
		 		return 0;
		 	}

			$this->fmt->query->liberar_consulta();
	}

	public function datos_conversacion($id_con){
			$consulta = "SELECT *	FROM mod_cliente_atencion_con WHERE mod_cli_ate_con_id='$id_con'";
			$rs =$this->fmt->query->consulta($consulta);
			$num=$this->fmt->query->num_registros($rs);
		 	if($num>0){
		 		$row=$this->fmt->query->obt_fila($rs);
		 		return $row;
		 	}else{
		 		return 0;
		 	}

			$this->fmt->query->liberar_consulta();
	}

	public function primer_mensaje_cliente($id_cliente,$id_conv){
		$consulta = "SELECT *	FROM mod_cliente_mensajes WHERE mod_cli_men_con_id='$id_con' and mod_cli_men_cli_id='$id_cliente' and mod_cli_men_usu_id='0'";
		$rs =$this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);
	 	if($num>0){
	 		for ($i=0; $i < $num ; $i++) { 
	 			$row=$this->fmt->query->obt_fila($rs);
	 			if($i==0){
					return true;
	 			}else{
	 				return false;
	 			}
	 		}
	 		
	 	}
		$this->fmt->query->liberar_consulta();
	}

	public function estado_ultimo_mensaje($id_usu){

		$consulta = "SELECT mod_cli_men_estado FROM mod_cliente_mensajes WHERE mod_cli_men_cli_id='$id_usu' ORDER BY mod_cli_men_id DESC";
		$rs =$this->fmt->query->consulta($consulta);
		$row=$this->fmt->query->obt_fila($rs);
		return $row['mod_cli_men_estado'];
		$this->fmt->query->liberar_consulta();

	}
}