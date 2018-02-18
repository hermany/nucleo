<?php

if(!empty($_POST["inputVars"])){
	$lista = $_POST["inputVars"];//tarea,id,estado
  // echo " ";
  $co = explode(":",$lista);
	$cant = count($co) ;
}

for ($i=0; $i < $cant ; $i++) {
  $v = $i + 1;
  $sql_upd="UPDATE sistema SET sis_orden=$v WHERE sis_id='$co[$i]'";
  $fmt->query->consulta($sql_upd,__METHOD__);
}

?>
