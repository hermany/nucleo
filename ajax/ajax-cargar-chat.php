<?php 
$vars=explode(",",$_POST["inputVars"]);

$receptor = $vars[0];
$emisor = $vars[1];

//echo $_POST["inputVars"];

$consulta = "SELECT * FROM mensaje WHERE men_receptor_usu_id='$receptor' and men_emisor_usu_id='$emisor' and men_estado='1'";
$rs =$fmt->query->consulta($consulta);
$num=$fmt->query->num_registros($rs);
if($num>0){
	for ($i=0; $i < $num ; $i++) { 
		$row=$fmt->query->obt_fila($rs);
		$id_mensaje= $row["men_id"];
		$cuerpo = $row["men_cuerpo"];
		$fh = $row["men_fecha"];
		$fecha_hoy = $fmt->class_modulo->fecha_hoy("America/La_Paz");
		$fecha= $fmt->class_modulo->tiempo_restante($fh,$fecha_hoy);

		$sql= "SELECT mod_cli_ate_nombre FROM mod_cliente_atencion WHERE mod_cli_ate_id='$emisor'";
		$rsx =$fmt->query->consulta($sql);
		$fila = $fmt->query->obt_fila($rsx);
		$nombre_emisor = $fila["mod_cli_ate_nombre"];
		$siglas_emisor = $fmt->usuario->siglas_nombre($nombre_emisor);

		$sql="UPDATE mensaje SET
										men_estado='2' 
										WHERE men_id ='".$id_mensaje."'";
			$fmt->query->consulta($sql);

		$envio =  "<div class='fecha-hora'>$fecha</div>";
		$envio.= "<div class='bloque-mensaje bloque-mensaje-emisor'>
								<div class='info info-emisor'><div class='siglas siglas-emisor'>$siglas_emisor</div></div>
								<div class='mensaje mensaje-emisor'>$cuerpo</div>
							</div>";

	  echo "nuevo,".$emisor.",".$envio;
	}

	
}

?>