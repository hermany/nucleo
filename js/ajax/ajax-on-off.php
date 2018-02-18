<?php
$fmt->get->validar_get($_POST['inputVars']);

if(!empty($_POST["inputVars"])){
	$vars = $_POST["inputVars"];//tarea,id,estado
	$data = explode(",", $vars);
	$tarea = $data[0];
	$id = $data[1];
	$bd = $data[2];
	$bd_campo = $data[3];
	$bd_valor = $data[4];
}
$sql="update $bd set ".$bd_campo."='".$bd_valor."'";
$fmt->query->consulta($sql,__METHOD__);

echo $_POST["inputVars"];
?>
