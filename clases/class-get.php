<?php
header('Content-Type: text/html; charset=utf-8');

class GET{

  var $fmt;

  function __construct($fmt){
    $this->fmt = $fmt;
  }

  function get_categoria_index(){
    //echo "Ingreso get categoria</br>";
    if ( (isset($_GET["cat"]) && !empty($_GET["cat"])) ) {
      //echo "id_pla:".$_GET["pla"];
	    $this->validar_get($_GET["cat"]);
      $id_categoria =   $_GET["cat"];
    }else{
      //echo "id=1";
      $id_categoria = 1;
    }
    return $id_categoria;
  }

  function get_plantilla_index($query,$id_categoria){
    //echo "Ingreso get plantilla $id_categoria</br>";
    if ( (isset($_GET["pla"]) && !empty($_GET["pla"])) ) {
      //echo "id_pla:".$_GET["pla"];
	    $this->validar_get($_GET["pla"]);
      $id_plantilla =  $_GET["pla"];
    }else{
      $id_plantilla = $this->obtener_plantilla($query,$id_categoria);
      //echo "id_pla:".$id_plantilla;
    }
    //echo "id_pla:".$id_plantilla;
    return $id_plantilla;
  }

  function obtener_plantilla($query,$cat){
    //echo "Entre a obtener_plantilla $cat</br>";
    $sql = "SELECT cat_id_plantilla
           FROM categoria
           WHERE cat_id='$cat'";
    $res  = $this->fmt->query->consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($res);
    // echo "fila:".	$fila["cat_id_plantilla"]."</br>";
    // exit(0);
    $p = $fila['cat_id_plantilla'];
    if ($p==0){
      $p = 1;
    }
    return $p;
  }

  function validar_get_num($get){
    if ($get!=""){
      if (!is_numeric($get)){
        $this->fmt->error->error_modulo_no_encontrado();
      }
    }
  }

  function validar_get($get){
    if ($get!=""){
      $get = strtolower($get);
      $getx = explode(" ",$get);
      $ng = count($getx);
      $pos= array("union","select","insert","query","cast","set","declare","drop","update","md5","benchmark","and "," and ","=","and 1=1",";","|","’");
      $nm = count($pos);
      for ($i=0; $i < $nm; $i++) {
          //echo $pos[$i].":".$get;
          $post= strpos($get,$pos[$i]);
          if ($post === false) {
          }else{
            return false;
          }
      }
      return true;
    }else{
      return false;
    }
  }

  function convertir_url_amigable($cadena){
  	$cadena= utf8_decode($cadena);
  	$cadena = strtolower($cadena);
    $cadena = str_replace(' ', '-', $cadena);
  	$cadena = str_replace('?', '', $cadena);
  	$cadena = str_replace('+', '', $cadena);
  	$cadena = str_replace(':', '', $cadena);
  	$cadena = str_replace('??', '', $cadena);
  	$cadena = str_replace('`', '', $cadena);
  	$cadena = str_replace('!', '', $cadena);
  	$cadena = str_replace('¿', '', $cadena);
    $cadena = str_replace(',', '-', $cadena);
    $cadena = str_replace('(', '', $cadena);
    $cadena = str_replace(')', '', $cadena);
  	$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ??';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);

    return $cadena;
  }


}

?>
