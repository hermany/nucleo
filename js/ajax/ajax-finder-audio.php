<?php
$consulta = "SELECT mul_id,mul_nombre,mul_tags,mul_url_archivo,mul_tipo_archivo,mul_id_dominio,mul_usuario,mul_fecha, mul_embed,mul_activar FROM multimedia WHERE mul_tipo_archivo='mp3' and mul_activar=1 ORDER BY mul_id asc";
$rs =$fmt->query->consulta($consulta);
$num=$fmt->query->num_registros($rs);
if($num>0){
  for($i=0;$i<$num;$i++){
    list($fila_id,$fila_nombre,$etiquetas,$fila_url,$fila_tipo,$fila_dominio,$fila_usuario,$fila_fecha,$fila_embed,$fila_activar)=$fmt->query->obt_fila($rs);
    $url = "images/audio-icon.png";
    $ruta = _RUTA_WEB_NUCLEO;
    $nom= $fmt->class_modulo->recortar_texto($fila_nombre,"8")."...";
    echo "<li class='finder-item item-audio' item='".$fila_id."'  seleccionado='off' tipo_item='".$fila_tipo."' id='item-a-$i' url='".$fila_url."'  url_mini='".$url."' style='background:url(".$ruta.$url.") no-repeat; background-size: 100% auto;' ><audio controls><source src='"._RUTA_IMAGES.$fila_url."' type='audio/mpeg'></audio><span class='nombre' title='$fila_nombre'>".$nom."</span><span class='etiquetas'>".$etiquetas."</span></li>";
  }
}
?>
