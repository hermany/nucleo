<?php 
$vars = explode(",",$_POST["inputVars"]);
$item= $vars[0];
$estado = $vars[1];

$sql="UPDATE mod_proyecto SET
						mod_proy_estado='".$estado."' 
						WHERE mod_proy_id='".$item."'";
$fmt->query->consulta($sql);

echo $item.":".$estado;

?>