<?php
$consulta = "SELECT alb_id,alb_nombre,alb_tags,alb_fecha FROM album WHERE alb_activar=1 ORDER BY alb_id asc";
$rs =$fmt->query->consulta($consulta);
$num=$fmt->query->num_registros($rs);
if($num>0){
  for($i=0;$i<$num;$i++){
    list($fila_id,$fila_nombre,$etiquetas,$fila_fecha)=$fmt->query->obt_fila($rs);
    $nom= $fmt->class_modulo->recortar_texto($fila_nombre,"8")."...";
    $cant= $fmt->class_multimedia->cantidad_mul_albums($fila_id);
    $img = $fmt->class_multimedia->imagen_album($fila_id);
    $ruta_img=_RUTA_IMAGES;
    if ($img=="images/image-icon.png"){
      $ruta_img=_RUTA_WEB_NUCLEO;
    }
    echo "<li class='finder-item' item='".$fila_id."' tipo_item='album'  seleccionado='off'  id='item-al-$i' url='' url_mini='' style='background:url(".$ruta_img.$img.") no-repeat; background-size: 100% auto;' ><span class='nombre' title='".$fila_nombre."'>".$nom."</span><span class='etiquetas'>".$etiquetas." ".$fila_fecha."</span><span class='cantidades'>$cant</span></li>";
  }
}
?>
