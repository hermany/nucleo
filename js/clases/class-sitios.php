<?php
header('Content-Type: text/html; charset=utf-8');

class CLASSSITIOS{

  var $fmt;

  function __construct($fmt){
    $this->fmt = $fmt;
  }

  function traer_carpeta_sitios(){
    $consulta = "SELECT sitio_carpeta FROM sitio WHERE sitio_activar='1'";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    $j=0;
    if ($num>0){
      for ($i=0;$i<$num;$i++){
        $file=$this->fmt->query->obt_fila($rs);
        if (!empty($file["sitio_carpeta"])){
            $varx[$j] = $file["sitio_carpeta"];
            $j++;
        }
      }
      return $varx;
    }else{
      return 0;
    }
  }
}
