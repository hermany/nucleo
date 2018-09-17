<?php
header("Content-Type: text/html;charset=utf-8");
 
class REGISTRADOS{
	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;
	var $curso;

	public function REGISTRADOS($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
		require_once(_RUTA_NUCLEO."modulos/e-learning/cursos.class.php");
		$this->curso = new CURSO($this->fmt);
	}

	
}