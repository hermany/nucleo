<?php 
$id_doc = $_POST["inputItem"];
$inputNombre = $_POST["inputNombre"];
$inputTags = $_POST["inputTags"];
$inputRutaAmigable = $_POST["inputRutaAmigable"];
$inputDescripcion = $_POST["inputDescripcion"];
$inputTipo = $_POST["inputTipo"];
$inputUrl = $_POST["inputUrl"];

//17:inputId=17&inputNombre=aseguradossip&inputTags=&inputRutaAmigable=aseguradossip&inputDescripcion=&inputUrl=archivos%2Fdocs%2Faseguradossip.pdf&inputTipo=pdf

$sql="UPDATE documento SET
						doc_nombre='".$inputNombre."',
						doc_url ='".$inputUrl."',
						doc_tags ='".$inputTags."',
						doc_tipo_archivo='".$inputTipo."',
						doc_ruta_amigable='".$inputRutaAmigable."',
						doc_descripcion='".$inputDescripcion."'
						WHERE doc_id='".$id_doc."'";

//exit(0);

$fmt->query->consulta($sql);

echo $id_doc.",".$inputNombre;

?>