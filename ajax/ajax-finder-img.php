<?php
$consulta = "SELECT mul_id,mul_nombre,mul_tags,mul_url_archivo,mul_tipo_archivo,mul_id_dominio,mul_usuario,mul_fecha, mul_embed,mul_activar FROM multimedia WHERE mul_tipo_archivo='jpeg' or mul_tipo_archivo='jpg' or mul_tipo_archivo='png' or mul_tipo_archivo='gif' and mul_activar=1 ORDER BY mul_id desc";
$rs =$fmt->query->consulta($consulta);
$num=$fmt->query->num_registros($rs);
if($num>0){
  for($i=0;$i<$num;$i++){
    $row=$fmt->query->obt_fila($rs);
    $fila_id = $row["mul_id"];
    $fila_nombre = $row["mul_nombre"];
    $etiquetas = $row["mul_tags"];
    $fila_url = $row["mul_url_archivo"];
    $fila_tipo = $row["mul_tipo_archivo"];
    $fila_dominio = $row["mul_id_dominio"];
    $fila_usuario = $row["mul_usuario"];
    $fila_fecha = $row["mul_fecha"];
    $fila_embed = $row["mul_embed"];
    $fila_activar = $row["mul_activar"];
    $url = $fmt->archivos->convertir_url_mini($fila_url);
    echo "<li class='finder-item' item='".$fila_id."'  seleccionado='off' title='$fila_nombre' tipo_item='".$fila_tipo."'  id='item-i-$i' url='".$fila_url."' url_mini='".$url."' style='background:url("._RUTA_WEB.$url.") no-repeat; background-size: 100% auto;' ><span class='nombre'>".$fila_nombre."</span><span class='etiquetas'>".$etiquetas."</span></li>";
  }
}
?>
