<?php
$consulta = "SELECT mul_id,mul_nombre,mul_tags,mul_url_archivo,mul_tipo_archivo,mul_id_dominio,mul_usuario,mul_fecha, mul_embed,mul_activar FROM multimedia WHERE mul_tipo_archivo='jpeg' or mul_tipo_archivo='jpg' or mul_tipo_archivo='png' or mul_tipo_archivo='gif' and mul_activar=1 ORDER BY mul_id desc";
$rs =$fmt->query->consulta($consulta);
$num=$fmt->query->num_registros($rs);
if($num>0){
  for($i=0;$i<$num;$i++){
    $row=$fmt->query->obt_fila($rs);
    $fila_id = $row["mul_id"];
    $fila_tipo = $row["mul_tipo_archivo"];
    $fila_nombre = $row["mul_nombre"]."(.".$fila_tipo.")";
    $etiquetas = $row["mul_tags"];
    $fila_url = $row["mul_url_archivo"];
    $fila_dominio = $row["mul_id_dominio"];
    $fila_usuario = $row["mul_usuario"];
    $fila_fecha = $row["mul_fecha"];
    $fila_embed = $row["mul_embed"];
    $fila_activar = $row["mul_activar"];

    $num_palabras = strlen($fila_nombre);
    if ($num_palabras>21){
         $inicio =   substr($fila_nombre,0,10);
         $fin  = substr($fila_nombre, -10);
         $nombre = $inicio."...".$fin;
    }else{
        $nombre= $fila_nombre;
    }

    $url = $fmt->archivos->convertir_url_mini($fila_url);
    echo "<li class='finder-item' item='".$fila_id."'  seleccionado='off' title='$fila_nombre' tipo_item='".$fila_tipo."'  id='item-i-$i' url='".$fila_url."' url_mini='".$url."' style='background:url("._RUTA_WEB.$url.") no-repeat; background-size: 100% auto;' ><span class='nombre'>".$nombre."</span><span class='etiquetas'>".$etiquetas."</span></li>";
  }
}
?>
