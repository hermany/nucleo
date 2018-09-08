<?php 
require_once(_RUTA_NUCLEO.'modulos/mensajes/mensajes.class.php');
require_once(_RUTA_NUCLEO.'modulos/crm/topicos-asistencia.class.php');
$mensajes = new MENSAJES($fmt);
$topicos = new TOPICOS_ASISTENCIA($fmt);

$usu=$_POST["inputVars"];
$canal = 2;
 

// $consulta = "SELECT * FROM mensaje WHERE men_receptor_usu_id='$receptor'";
$consulta = "SELECT * FROM mod_cliente_atencion WHERE mod_cli_ate_estado='1' and mod_cli_ate_canal=$canal";
$rs =$fmt->query->consulta($consulta);
$num=$fmt->query->num_registros($rs);
if($num>0){
	for ($i=0; $i < $num ; $i++) { 
		$row=$fmt->query->obt_fila($rs);
		$emisor = $row["mod_cli_ate_id"];
		$nom = $row["mod_cli_ate_nombre"]." - ".$row["mod_cli_ate_ci"].$row["mod_cli_ate_ext"];
		// $estado = $row["men_estado"];
		// $emisor_array = $mensajes->datos_cliente_atencion($emisor);
		$sigla = $fmt->usuario->siglas_nombre($nom);
		//$nom = $emisor_array['mod_cli_ate_nombre']." - ".$emisor_array['mod_cli_ate_ci'].$emisor_array['mod_cli_ate_ext'];

		echo 'ok~'.$emisor.'~<a class="bloque-mensaje bloque-mensaje-emisor bloque-mensaje-emisor-'.$emisor.' btn-activar-chat-emisor" estado="1" emisor="'.$emisor.'"><div class="info info-emisor"><div class="siglas siglas-emisor">'.$sigla.'</div><div class="nombre">'.$nom.'</div><div class="estado estado-1"></div></div></a>';

		$sql="UPDATE mod_cliente_atencion SET
										mod_cli_ate_estado='2' 
										WHERE mod_cli_ate_id ='".$emisor."'";
		$fmt->query->consulta($sql);
	}
 
}else{
	$consulta = "SELECT DISTINCT mod_cli_men_cli_id FROM mod_cliente_mensajes WHERE mod_cli_men_estado='1' and mod_cli_men_cli_id!=0";
$rs =$fmt->query->consulta($consulta);
$num=$fmt->query->num_registros($rs);
$aux="";
if($num>0){
	for($i=0;$i<$num;$i++){
		$row=$fmt->query->obt_fila($rs);
		$row_id  = $row["mod_cli_men_cli_id"];
		if($i==0){
			$ua ='';
		}else{
			$ua =',';
		}
		$aux .= $ua.$row_id;
	}
	echo 'mensaje~'.$aux;
}else{
	echo "";
}
$fmt->query->liberar_consulta($rs);
}

 




?>