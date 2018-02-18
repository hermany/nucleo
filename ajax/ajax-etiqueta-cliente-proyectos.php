<?php 

$vars = explode(",",$_POST["inputVars"]);
$valor= $vars[0];
$item = $vars[1];

$sql="UPDATE mod_cliente_proyectos SET
						mod_cli_proy_etiqueta='".$valor."' 
						WHERE mod_cli_proy_id ='".$item."'";
$fmt->query->consulta($sql);

echo $item.":".$valor;

?>