<?php
header("Content-Type: text/html;charset=utf-8");

class PUBLICACION{

	var $fmt;
	var $pub_id;
	var $pub_nombre;
	var $pub_descripcion;
	var $pub_imagen;
	var $pub_titulo;
	var $pub_tipo;
	var $pub_archivo;
	var $pub_css;
	var $pub_clase;
	var $pub_id_item;
	var $pub_numero;
	var $pub_id_cat;
	var $pub_activar;

  function  __construct($fmt) {
    $this->fmt = $fmt;
  }

  public function  cargar_publicacion($id){
		//echo "id:".$id;
  	$consulta = "SELECT	* from publicacion WHERE pub_id = '".$id."'";

	$rs = $this->fmt->query->consulta($consulta,__METHOD__);
	if ($rs){
		$cant = $this->fmt->query->num_registros($rs);
		if ($cant > 0){
			$fila = $this->fmt->query->obt_fila($rs);
			$this->pub_id				= $id;
			$this->pub_nombre     		= $fila["pub_nombre"];
			$this->pub_descripcion 		= $fila["pub_descripcion"];
			$this->pub_imagen 			= $fila["pub_imagen"];
			$this->pub_titulo 			= $fila["pub_titulo"];
			$this->pub_tipo 			= $fila["pub_tipo"];
			$this->pub_archivo			= $fila["pub_archivo"];
			$this->pub_css				= $fila["pub_css"];
			$this->pub_clase 			= $fila["pub_clase"];
			$this->pub_id_item			= $fila["pub_id_item"];
			$this->pub_numero 			= $fila["pub_numero"];
			$this->pub_id_cat			= $fila["pub_id_cat"];
			$this->pub_activar   		= $fila["pub_activar"];
		}else{
			return false;
		}
	}else{
	   return false;
	}
	  $this->fmt->query->liberar_consulta($rs);
  }
  
  public function  publicacion($fmt,$id){
		$this->fmt = $fmt;
		//echo "id:".$id;
  	$consulta = "SELECT	* from publicacion WHERE pub_id = '".$id."'";

		$rs = $this->fmt->query->consulta($consulta,__METHOD__);
		if ($rs){
			$cant = $this->fmt->query->num_registros($rs);
			if ($cant > 0){
				$fila = $this->fmt->query->obt_fila($rs);
				$this->pub_id				= $id;
				$this->pub_nombre     		= $fila["pub_nombre"];
				$this->pub_descripcion 		= $fila["pub_descripcion"];
				$this->pub_imagen 			= $fila["pub_imagen"];
				$this->pub_titulo 			= $fila["pub_titulo"];
				$this->pub_tipo 			= $fila["pub_tipo"];
				$this->pub_archivo			= $fila["pub_archivo"];
				$this->pub_css				= $fila["pub_css"];
				$this->pub_clase 			= $fila["pub_clase"];
				$this->pub_id_item			= $fila["pub_id_item"];
				$this->pub_numero 			= $fila["pub_numero"];
				$this->pub_id_cat			= $fila["pub_id_cat"];
				$this->pub_activar   		= $fila["pub_activar"];
			}else{
				return false;
			}
		}else{
		   return false;
		}
		  $this->fmt->query->liberar_consulta($rs);
  }

  public function  get_pub_id(){
	  return $this->pub_id;
  }
  public function  get_pub_nombre(){
	  return $this->pub_nombre;
  }
  public function  get_pub_descripcion(){
	  return $this->pub_descripcion;
  }
  public function  get_pub_imagen(){
	  return $this->pub_imagen;
  }
  public function  get_pub_titulo(){
	  return $this->pub_titulo;
  }
  public function  get_pub_tipo(){
	  return $this->pub_tipo;
  }
  public function  get_pub_archivo(){
	  return $this->pub_archivo;
  }
  public function  get_pub_css(){
	  return $this->pub_css;
  }
  public function  get_pub_clase(){
	  return $this->pub_clase;
  }
  public function  get_pub_id_item(){
	  return $this->pub_id_item;
  }
  public function  get_pub_numero_items(){
	  return $this->pub_numero;
  }
  public function  get_pub_id_cat(){
	  return $this->pub_id_cat;
  }
  public function  get_pub_activar(){
	  return $this->pub_activar;
  }

  public function nombre_publicacion($id){
  	$this->cargar_publicacion($id);
  	return $this->pub_nombre;
  }

}
?>
