<?php
// require_once(_RUTA_HOST."/nucleo/config.php");
// require_once(_RUTA_HOST."/nucleo/clases/class-mysql.php");
// //echo "Class BRAND";
header('Content-Type: text/html; charset=utf-8');

class BRAND{

  var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

  function brand_login($cat,$tipo){

    $sql ="SELECT cat_imagen FROM categoria WHERE cat_id='$cat' and cat_tipo='2'";
    $rs = $this->fmt->query -> consulta($sql,__METHOD__);
    $fila =  $this->fmt->query -> obt_fila($rs);
    $img_cat = $fila["cat_imagen"];

    if ($img_cat){
      return "<img class=' $tipo' src='"._RUTA_WEB.$img."' />";
    }else{
      $sql ="SELECT conf_imagen FROM configuracion";
      $rs = $this->fmt->query -> consulta($sql,__METHOD__);
      $fila = $this->fmt->query -> obt_fila($rs);
      $img_conf = $fila["conf_imagen"];
      if ($img_conf){
        return "<img class='".$tipo."' src='"._RUTA_WEB.$img_conf."' />";
      }else{
        return '<div class="logo-zundi"><i class="icn-zundi color-text-rojo-a"></i><span class="color-text-gris">zundi</span></div>';
      }
    }
  } // fin function brand_login

  function brand_favicon($cat,$clase){

    $sql ="SELECT cat_favicon FROM categoria WHERE cat_id='$cat' and cat_tipo='2'";
    $rs = $this->fmt->query -> consulta($sql,__METHOD__);
    $fila =  $this->fmt->query -> obt_fila($rs);
    $img_cat = $fila["cat_favicon"];

    if ($img_cat){
      return "<img class=' $clase' src='"._RUTA_WEB.$img."' />";
    }else{
      $sql ="SELECT conf_favicon FROM configuracion";
      $rs = $this->fmt->query -> consulta($sql,__METHOD__);
      $fila = $this->fmt->query -> obt_fila($rs);
      $img_conf = $fila["conf_favicon"];
      if ($img_conf){
        return "<img class='".$clase."' src='"._RUTA_WEB.$img_conf."' />";
      }else{
        return '<div class="logo-zundi"><i class="icn-zundi"></i><span>zundi</span></div>';
      }
    }
  } // fin function brand_login

} // fin class brand

?>
