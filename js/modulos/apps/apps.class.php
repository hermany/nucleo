<?php
header("Content-Type: text/html;charset=utf-8");

class APP{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo ="modulos/apps/apps.adm.php";
	var $nombre_modulo ="apps.adm.php";
	var $nombre_tabla ="aplicaciones";
	var $prefijo_tabla ="app_";

	function APP($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
	}

	function dashboard_modulo(){
		if (isset($_GET["p"])){
      $clase_activa = $_GET["p"];
    }else{
      $clase_activa = "activar";
    }
		$this->fmt->class_pagina->crear_head( $this->id_mod,"");
		$fmt=$this->fmt;
		$id_mod=$this->id_mod;
		?>
		<ul class="nav nav-tabs" role="tablist">

      <li role="presentation" class="<?php if ($clase_activa=="activar"){ echo "active"; }?>"><a href="#activar" aria-controls="activar" role="tab" data-toggle="tab"><i class="icn-conector color-text-rojo"></i> Activar</a></li>

      <li role="presentation" class="<?php if ($clase_activa=="configurar"){ echo "active"; }?>"><a href="#configurar" aria-controls="configurar" role="tab" data-toggle="tab"><i class="icn-conf color-text-azul"></i> Configurar</a></li>

      <!-- <li role="presentation" class="<?php if ($clase_activa=="divisiones"){ echo "active"; }?>"><a href="#divisiones" aria-controls="divisiones" role="tab" data-toggle="tab"><i class="icn-division color-text-verde"></i> Divisiones</a></li> -->

    </ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane <?php if ($clase_activa=="activar"){ echo "active"; }?>" id="activar">
				<?php require_once("apps-activar.php"); ?>
			</div>
			<div role="tabpanel" class="tab-pane <?php if ($clase_activa=="configurar"){ echo "active"; }?>" id="configurar">
				<?php require_once("apps-config.adm.php"); ?>
			</div>
		</div><!-- fin tab-content -->
		<?php
			$this->fmt->class_modulo->script_form($this->ruta_modulo,$this->id_mod,'');
  }
}
