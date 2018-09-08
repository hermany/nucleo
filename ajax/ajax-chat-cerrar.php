<?php 
$vars = explode(",",$_POST["inputVars"]);


$id_conv= $vars[0];
$id_usu= $vars[1];
$id_cliente= $vars[2];


$fecha = $fmt->class_modulo->date_formateado("America/La_Paz","Y-m-d H:i");
$sql="UPDATE mod_cliente_atencion_con SET
							mod_cli_ate_con_usu_id='$id_usu', 
							mod_cli_ate_con_fecha_fin='$fecha' 
							WHERE mod_cli_ate_con_id ='".$id_conv."'";
$fmt->query->consulta($sql);


$sql="UPDATE mod_cliente_atencion SET
							mod_cli_ate_estado_chat='2' 
							WHERE mod_cli_ate_id ='".$id_cliente."'";
$fmt->query->consulta($sql);
 

echo  $fecha;

$fmt->query->liberar_consulta($rs);


?>

