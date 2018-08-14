<?php 
// require_once("../config.php");
// header("Content-Type: text/html;charset=utf-8");
// require_once(_RUTA_NUCLEO."clases/class-constructor.php");
// $fmt = new CONSTRUCTOR;
$vars= explode(",",$_POST["inputVars"]);
$id_receptor= $vars[0];
$id_emisor = $vars[1];

$mensaje = limpiar_cadena($vars[2]);

$fecha =  $fmt->class_modulo->fecha_hoy("America/La_Paz");

if(!empty($mensaje)){
	//echo $mensaje;
	$ingresar = "men_emisor_usu_id,men_receptor_usu_id,men_cuerpo,men_fecha,men_canal,men_estado";
 	$valores  = "'".$id_emisor."','".$id_receptor."','".$mensaje."','".$fecha."','2','1'";
  $sql="insert into mensaje (".$ingresar.") values (".$valores.")";
  $fmt->query->consulta($sql,__METHOD__);

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