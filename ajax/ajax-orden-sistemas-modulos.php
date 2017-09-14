<?php

if(!empty($_POST["inputVars"])){
	$lista = $_POST["inputVars"];//tarea,id,estado
  // echo " ";
  $co = explode(":",$lista);
	$bd = $_POST["inputFrom"];
	$cant = $_POST["inputCant"];
	$id_sis = $_POST["inputSis"];
}

for ($i=0; $i < $cant ; $i++) {
  $v = $i + 1;
  $sql_upd="UPDATE sistema_modulos SET sis_mod_orden=$v WHERE sis_mod_sis_id='$id_sis' and sis_mod_mod_id='$co[$i]'";
  $fmt->query->consulta($sql_upd,__METHOD__);
}

?>
