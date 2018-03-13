<?php
$id_doc = $_POST["id_doc"];

$consulta = "SELECT doc_url FROM documento WHERE doc_id='$id_doc'";
$rs =$fmt->query->consulta($consulta);
$row=$fmt->query->obt_fila($rs);
$archivo = _RUTA_HOST.$row["doc_url"];
$fmt->archivos->eliminar_archivo($archivo);
$fmt->query->liberar_consulta($rs); 

$sql1="DELETE FROM documento WHERE doc_id='$id_doc'";
$fmt->query->consulta($sql1,__METHOD__);

echo $id_doc;

?>
