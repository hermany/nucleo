<?php 
// require_once("../config.php");
// header("Content-Type: text/html;charset=utf-8");
// require_once(_RUTA_NUCLEO."clases/class-constructor.php");
// $fmt = new CONSTRUCTOR;
$vars= explode(",",$_POST["inputVars"]);
$id_cliente= $vars[0];
$id_usu = $vars[1];
$mensaje = limpiar_cadena($vars[2]);
$id_conv = $vars[3];
$id_topico = $vars[4];




$fecha =  $fmt->class_modulo->fecha_hoy("America/La_Paz");

if(!empty($mensaje)){
	//echo $mensaje;
	$ingresar = "mod_cli_men_tipo, mod_cli_men_cli_id, mod_cli_men_usu_id, mod_cli_men_con_id, mod_cli_men_cuerpo, mod_cli_men_fecha, mod_cli_men_canal, mod_cli_men_estado";
 	$valores  = "'1','".$id_cliente."','".$id_usu."','".$id_conv."','".$mensaje."','".$fecha."','2','1'";
  $sql="insert into mod_cliente_mensajes (".$ingresar.") values (".$valores.")";
  $fmt->query->consulta($sql,__METHOD__);


  $consulta = "SELECT mod_cli_ate_con_usu_id FROM mod_cliente_atencion_con WHERE mod_cli_ate_con_id='$id_conv'";
	$rs =$fmt->query->consulta($consulta);
	$row=$fmt->query->obt_fila($rs);

	$sql="UPDATE mod_cliente_atencion SET
										mod_cli_ate_estado_chat='2' 
										WHERE mod_cli_ate_id='".$id_cliente."'";
		$fmt->query->consulta($sql);		

	if ($row["mod_cli_ate_con_usu_id"]==0) {
		$sql="UPDATE mod_cliente_atencion_con SET
								mod_cli_ate_con_usu_id='$id_usu' 
								WHERE mod_cli_ate_con_id='$id_conv'";
		$fmt->query->consulta($sql);

		

		// $sql="UPDATE mod_cliente_mensajes SET
		// 								mod_cli_men_estado='2' 
		// 								WHERE mod_cli_men_cli_id ='".$id_cliente."'";
		// $fmt->query->consulta($sql);

	}else{
		if ($row["mod_cli_ate_con_usu_id"]!=$id_usu){
			$ingresar = "mod_cli_ate_con_cli_id, mod_cli_ate_con_usu_id, mod_cli_ate_con_fecha_ini,	 mod_cli_ate_con_fecha_fin, mod_cli_ate_con_topico_id, mod_cli_ate_con_sol, mod_cli_ate_con_valor";
			$valores  = "'".$id_cliente."','".$id_usu."','".$fecha."','','".$id_topico."','0','0'";
			$sql="insert into mod_cliente_atencion_con (".$ingresar.") values (".$valores.")";
			$fmt->query->consulta($sql,__METHOD__);
		}
	}

	echo "send";
} 

 function limpiar_cadena($valor){
		$valor = str_ireplace("SELECT","",$valor);
		$valor = str_ireplace("COPY","",$valor);
    $valor = str_ireplace("DELETE","",$valor);
    $valor = str_ireplace("query","",$valor);
		$valor = str_ireplace("QUERY","",$valor);
		$valor = str_ireplace("DROP","",$valor);
		$valor = str_ireplace("DUMP","",$valor);
		$valor = str_ireplace(" OR ","",$valor);
		$valor = str_ireplace(" AND ","",$valor);
		$valor = str_ireplace("AND ","",$valor);
		$valor = str_ireplace("%","",$valor);
		$valor = str_ireplace("LIKE","",$valor);
		$valor = str_ireplace("--","",$valor);
		$valor = str_ireplace("^","",$valor);
		$valor = str_ireplace("[","",$valor);
		$valor = str_ireplace("]","",$valor);
		$valor = str_ireplace("\'","",$valor);
		$valor = str_ireplace("=","",$valor);
		$valor = str_ireplace("&","",$valor);
		//$valor = addslashes($valor);
		return $valor;
}

?>