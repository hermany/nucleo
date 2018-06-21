<?php 
$vars = explode(",",$_POST["inputVars"]);
$tarea= $vars[0];
$tiempo = $vars[1];
$proy= $vars[2];

$id_usu = $fmt->sesion->get_variable("usu_id");

if ( (!empty($tiempo))  && ($proy!=0) ){
$fecha = $fmt->class_modulo->fecha_hoy('America/La_Paz');

$ingresar = "mod_tar_descripcion,mod_tar_fecha_hora_inicio,mod_tar_estado,mod_tar_activar";
$valores  = "'".$tarea."','".$fecha."','1','1'";
$sql="insert into mod_tarea (".$ingresar.") values (".$valores.")";
$fmt->query->consulta($sql,__METHOD__);

$sql="select max(mod_tar_id) as id from mod_tarea";
$rs= $fmt->query->consulta($sql);
$fila = $fmt->query->obt_fila($rs);
$id = $fila ["id"];

$ingresar1 ="mod_tar_proy_tar_id,mod_tar_proy_proy_id,mod_tar_proy_usu_id,mod_tar_proy_orden"; 
$valores1 = "'".$id."','".$proy."','".$id_usu."','0'";
$sql1="insert into mod_tarea_proyectos (".$ingresar1.") values (".$valores1.")";
$fmt->query->consulta($sql1);
 
	echo "ok:$id";
}else{
	echo "error";
}

?>