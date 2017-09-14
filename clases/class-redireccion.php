<?php
header('Content-Type: text/html; charset=utf-8');

class REDIRECCION{

  var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

  function login($cat,$pla,$usu_id){
    //return "cat:".$cat."pla:".$pla."usu_id:".$usu_id;
    $id_rol=$this->fmt->usuario->id_rol_usuario($usu_id);
    $ruta = $this->fmt->usuario->traer_ruta_rol($id_rol);
    if($ruta==""){
    	$this->fmt->get->validar_get ( $_GET['cat'] );
		$this->fmt->get->validar_get ( $_GET['pla'] );
		$cat =  $_GET['cat'];
		$pla = $_GET['pla'];
       return $this->ruta_amigable($cat,$pla);
     }
     else
	 	return $ruta;

  } // fin login

  function ruta_amigable($cat,$pla){
    //$query = new MYSQL();
    //$query ->conectar(_BASE_DE_DATOS,_HOST,_USUARIO,_PASSWORD);

    $sql ="SELECT cat_ruta_amigable FROM categoria WHERE cat_id='$cat'";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query-> obt_fila($rs);
    $ruta_cat = $fila["cat_ruta_amigable"];

    return _RUTA_WEB.$ruta_cat;

  } // fin ruta amigable

} // fin clase
?>
