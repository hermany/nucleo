<?php
header("Content-Type: text/html;charset=utf-8");

class APP_CONFIG{

	var $fmt_c;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo ="modulos/apps/apps.adm.php";
	var $nombre_modulo ="apps.adm.php";
	var $nombre_tabla ="aplicaciones";
	var $prefijo_tabla ="app_";

	function APP_CONFIG($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt_c = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
	}

  function busqueda(){
    $this->fmt->class_pagina->crear_head_mod("icn-credential color-text-naranja-a","Roles"); // bd, id modulo, botones
  }
}
