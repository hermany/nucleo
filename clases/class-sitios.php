<?php
header('Content-Type: text/html; charset=utf-8');

class SITIOS{

  var $fmt;

  function __construct($fmt){
    $this->fmt = $fmt;
  }

  function traer_carpeta_sitios(){
    $consulta = "SELECT sitio_carpeta FROM sitio WHERE sitio_activar='1'";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      for ($i=0;$i<$num;$i++){
        list($file)=$this->fmt->query->obt_fila($rs);
        $varx[$i] = $file;
      }
      return $varx;
    }else{
      return 0;
    }
  }
}
