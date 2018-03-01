<?php 

$vars = explode(",",$_POST["inputVars"]);

$usu = $vars[1];
$canal = $vars[2];
$rol= $vars[3];
$estado = (int) $vars[0];

//echo $estado.":".$usu.":".$canal.":".$rol;

if ($rol==1 || $rol==4){

	if ($estado==0){
		$est=1;
	}
	if ($estado==1){
		$est=0;
	}

	$fechaHora= $fmt->class_modulo->fecha_hoy("America/La_Paz");

	$sql="UPDATE canal_usuarios SET
							canal_usu_estado='".$est."'
							WHERE canal_usu_usu_id ='".$usu."' and canal_usu_canal_id='".$canal."'";
	$fmt->query->consulta($sql);

	$ingresar = "canal_usu_est_usu_id,canal_usu_est_canal_id,canal_usu_est_fecha,canal_usu_est_estado";
  $valores  = "'".$usu."','".$canal."','".$fechaHora."','".$est."'";
  $sql="insert into canal_usuarios_estados (".$ingresar.") values (".$valores.")";
  $fmt->query->consulta($sql,__METHOD__);

	echo "estado:".$est;
	
}else{
	echo "error:rol";
}

?>