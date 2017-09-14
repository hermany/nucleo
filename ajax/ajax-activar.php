<?php
$fmt->get->validar_get($_POST['inputIdMod']);
$id_mod = $_POST['inputIdMod'];

if(!empty($_POST["inputVars"])){
	$vars = $_POST["inputVars"];//tarea,id,estado
	$data = explode(",", $vars);
	$tarea = $data[0];
	$id_item = $data[1];
	$id_estado = $data[2];
}

$bd = $fmt->class_modulo->bd_modulo($id_mod);
$bd_prefijo = $fmt->class_modulo->bd_prefijo_modulo($id_mod);

$sql="update $bd set
    ".$bd_prefijo."activar='".$id_estado."' where ".$bd_prefijo."id='".$id_item."'";
$fmt->query->consulta($sql,__METHOD__);
//header("location: sistemas.adm.php?id_mod=".$this->id_mod);
// $this->fmt->class_modulo->script_location($this->id_mod,"busqueda");

echo  $tarea.":".$id_item.":".$id_estado.":".$id_mod;

?>
