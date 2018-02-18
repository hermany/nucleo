<?php
$img = $_POST["inputImg"];
$img_mini = $_POST["inputImgMini"];
$item = $_POST["inputItem"];
$tipo_item = $_POST["inputTipoItem"];
$id_mod = $_POST["inputIdMod"];
$id_mul = $_POST["inputMul"];


$consulta = "SELECT mod_pro_mul_prod_id FROM mod_productos_mul WHERE mod_pro_mul_prod_id='$item' and mod_pro_mul_mul_id='$id_mul'";
$rs =$fmt->query->consulta($consulta,__METHOD__);
$num=$fmt->query->num_registros($rs);
//echo "num".$num;
if($num==0){
  $ingresar ="mod_pro_mul_prod_id,mod_pro_mul_mul_id,mod_pro_mul_orden";
  $valores  ="'".$item."','".$id_mul."','0'";
  $sql="insert into mod_productos_mul (".$ingresar.") values (".$valores.")";
  $fmt->query->consulta($sql,__METHOD__);
}


$consulta = "SELECT DISTINCT mod_prod_mul_prod_id,mul_url_archivo FROM mod_productos_mul,multimedia WHERE mod_prod_mul_prod_id='$id_item' and mod_prod_mul_mul_id=mul_id  ORDER BY mod_prod_mul_orden asc";
$rs =$fmt->query->consulta($consulta,__METHOD__);
$num=$fmt->query->num_registros($rs);
$aux="";
if($num>0){
  for($i=0;$i<$num;$i++){
    list($id_prod,$ruta)=$fmt->query->obt_fila($rs);
    $url = $this->fmt->archivos->convertir_url_mini($ruta);
    $extension = $this->fmt->archivos->saber_extension_archivo($ruta);
    $nombre_archivo=$this->fmt->archivos->saber_nombre_archivo($ruta);
    if($extension=="mp4"){
      //$link = _RUTA_WEB_NUCLEO."images/video-icon.png";
      $link =_RUTA_IMAGES."archivos/multimedia/".$nombre_archivo.".jpg";
      $link = $this->fmt->archivos->convertir_nombre_thumb($link);
    }else{
      $link=_RUTA_IMAGES.$url;
    }
    $aux .= "<li prod='$id_item' id='mul-$i' class='box-image box-image-block ui-state-default'>";
    $aux .= "<i class='icn icn-sorteable icn-reorder'></i>";
    $aux .= "<div class='box-acciones-img'>";
    $aux .= "<a class='btn btn-eliminar-file' prod='$id_prod' mul='$ruta' idm='mul-$i'><i class='icn icn-close' /></a>";
    $aux .= "<a class='btn btn-editar-file'><i class='icn icn-pencil' /></a>";
    $aux .= "</div>";
    $aux .= "<img class='img-catalogo img-file img-responsive' id='img-".$i."' src='".$link."' />";
    $aux .= "</li>";
  }

$aux .= "<li><a item='$id_item' class='ui-state-disabled btn btn-adicionar-imagen btn-up-finder'><i class='icn icn-upload' /></a></li>";

echo $aux;
}
//echo $_POST["inputImg"].":".$_POST["inputItem"].":".$_POST["inputTipoItem"].":".$_POST["inputIdMod"];

?>
