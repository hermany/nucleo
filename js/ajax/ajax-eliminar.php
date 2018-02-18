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

$aux="";
$bd = $fmt->class_modulo->bd_modulo($id_mod);
$bd_prefijo = $fmt->class_modulo->bd_prefijo_modulo($id_mod);
$bd_relaciones = $fmt->class_modulo->bd_relaciones_modulo($id_mod);

if (!empty($bd_relaciones)){
	$relaciones = explode(",",$bd_relaciones);
	$c_r = count($relaciones);
	for ($i=0; $i < $c_r; $i++) {
		$filax = explode(":", $relaciones[$i]);
		$sql1="DELETE FROM ".$filax[0]." WHERE ".$filax[1]."='".$id_item."'";
		$aux .= "[ ".$sql1." ]";
		$fmt->query->consulta($sql1,__METHOD__);
	}
}

$sqle="DELETE FROM ".$bd." WHERE ".$bd_prefijo."id='".$id_item."'";
$fmt->query->consulta($sqle,__METHOD__);
$aux .= "[ ".$sqle." ]";

$up_sqr6 = "ALTER TABLE ".$bd." AUTO_INCREMENT=1";
$fmt->query->consulta($up_sqr6,__METHOD__);
$aux .= "[ ".$up_sqr6." ]";

//echo $c_r;
//echo $sqle;
//echo "bd:".$bd.":".$bd_prefijo.":".$bd_relaciones;
echo "eliminar:".$id_item.":".$estado.":".$id_mod.":".$aux;

//echo $_POST["inputVars"];

?>
