<?php
 $consulta = "SELECT mul_id,mul_nombre,mul_tags,mul_url_archivo,mul_tipo_archivo,mul_id_dominio,mul_usuario,mul_fecha, mul_embed,mul_activar FROM multimedia WHERE mul_tipo_archivo='mp4' or mul_tipo_archivo='embed' and mul_activar=1 ORDER BY mul_id asc";

$rs =$fmt->query->consulta($consulta);
$num=$fmt->query->num_registros($rs);
if($num>0){
  for($i=0;$i<$num;$i++){
    $row=$fmt->query->obt_fila($rs);
    $fila_id=$row["mul_id"];
    $fila_nombre=$row["mul_nombre"];
    $etiquetas=$row["mul_tags"];
    $fila_url=$row["mul_url_archivo"];
    $fila_tipo=$row["mul_tipo_archivo"];
    // $fila_dominio=$row["mul_id"];
    $fila_usuario=$row["mul_usuario"];
    $fila_fecha=$row["mul_fecha"];
    $fila_embed=$row["mul_embed"];
    $fila_activar=$row["mul_activar"];

    $img_url = $fmt->archivos->convertir_url_mini($fila_url);
    if ( $fmt->archivos->existe_archivo(_RUTA_HOST.$img_url)){
      $url = $img_url;
      $ruta = _RUTA_IMAGES;
    }else{
      $url = "images/video-icon.png";
      $ruta = _RUTA_WEB_NUCLEO;
    }

    $nom= $fmt->class_modulo->recortar_texto($fila_nombre,"8")."...";
    if ( $fila_tipo=="mp4" || $fila_tipo=="embed"){
      if ($fila_tipo=="embed"){
        $fila_url="images/video-icon.png";
        echo "<li class='finder-item item-video'  seleccionado='off'  embed='".$fila_embed."' item='".$fila_id."' tipo_item='".$fila_tipo."' id='item-v-$i' url='".$fila_url."' nombre='$fila_nombre'  url_mini='".$url."' style='background:url(".$ruta.$url.") no-repeat; background-size: 100% auto;' ><span class='video-embed'>".$fila_embed."</span><span class='nombre' title='$fila_nombre'>".$nom."</span><span class='etiquetas'>".$etiquetas."</span></li>";
      }else{
        echo "<li class='finder-item item-video'  seleccionado='off' nombre='$fila_nombre' item='".$fila_id."' tipo_item='".$fila_tipo."' id='item-v-$i' url='".$fila_url."' url_mini='".$url."' style='background:url(".$ruta.$url.") no-repeat; background-size: 100% auto;' ><span class='nombre' title='$fila_nombre'>".$nom.$mun."</span><span class='etiquetas'>".$etiquetas."</span></li>";
      }

    }
  }
}
?>
