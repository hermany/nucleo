<?php
$consulta = "SELECT doc_id,doc_nombre,doc_tags,doc_url,doc_tipo_archivo,doc_activar FROM documento WHERE  doc_activar=1 ORDER BY doc_id desc";
$rs =$fmt->query->consulta($consulta);
$num=$fmt->query->num_registros($rs);
if($num>0){
  for($i=0;$i<$num;$i++){
    $row=$fmt->query->obt_fila($rs);
    $fila_id = $row["doc_id"];
    $fila_nombre = $row["doc_nombre"];
    $etiquetas = $row["doc_tags"];
    $fila_url = $row["doc_url"];
    $fila_tipo = $row["doc_tipo_archivo"];
    $fila_dominio = $row["doc_id_dominio"];
    $fila_activar = $row["doc_activar"];
    $ruta = _RUTA_WEB_NUCLEO;
    //$nom= $fmt->class_modulo->recortar_texto($fila_nombre,"8")."...";
    if ($fila_tipo=="xls"||$fila_tipo=="xlsx" ){
      $icon="excel";
    }

    if ($fila_tipo=="pdf"){
      $icon="pdf";
    }

    if ($fila_tipo=="doc"||$fila_tipo=="docx" ){
      $icon="word";
    }

    if ($fila_tipo=="ppt" ||$fila_tipo=="pptx"){
      $icon="powerpoint";
    }

    $nom= $fmt->class_modulo->recortar_texto($fila_nombre,"40")." <span class='tipo-archivo'>(".$fila_tipo.")</span>";
    $nomx= $fmt->class_modulo->recortar_texto($fila_nombre,"35")." (".$fila_tipo.")";
    echo "<li class='finder-item item-doc' seleccionado='off' nombre='".$nomx."' item='".$fila_id."' tipo_item='$icon' id='item-d-$i' url='".$fila_url."'  url_mini='".$url."' style='' ><i class='icn icn-$icon'></i><span class='nombre' title='$fila_nombre'>".$nom."</span><div class='etiquetas'>".$etiquetas."</div></li>";
  }
}
?>
