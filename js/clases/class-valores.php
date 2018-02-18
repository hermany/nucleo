<?php
header('Content-Type: text/html; charset=utf-8');

class VALORES{

  var $fmt;

  function __construct($fmt){
    $this->fmt = $fmt;
  }

  function convertir_cantidad_rs($cantidad){
  	if(	$cantidad > 999){
  		if ($cantidad > 999999)
  			return round(($cantidad / 1000000), 1)."m";
  		else{
  			return round(($cantidad / 1000), 1)."k";
  		}
  	}else{
  		return $cantidad;
  	}
  }

  function recortar_texto($texto,$cantidad="0",$extra){
    $cn=$this->cantidad_caracteres($texto);
    if($cn>$cantidad){
      $aux="... ".$extra;
    }else{
      $aux="";
    }
    return substr($texto, 0, $cantidad).$aux;
  }


  function cantidad_caracteres($texto){
    return strlen($texto);
  }

}