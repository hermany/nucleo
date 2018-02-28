<?php
header("Content-Type: text/html;charset=utf-8");

class MENSAJES{

	var $fmt;
	var $id_app;
	var $id_item;
	var $id_estado;
 

	function MENSAJES($fmt,$id_app=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
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
				var wm = w - 300;
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
				  $btn_activar = "<a class='btn-activar-chat'><i class='icn icn-checkmark-circle'></i></a>";
					$tabsx = array ("");
					$iconosx = array ("icn icn-bubble-w");
					$this->fmt->class_pagina->tabs_mod("","charlas",$tabsx,$iconosx,0,"tabs-config","h4",$btn_activar);
				?>
			</div>
			<div class="box-chat"></div>
		</div>
		<?php
	}

}