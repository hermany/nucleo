<?php 

require_once(_RUTA_NUCLEO.'modulos/mensajes/mensajes.class.php');
$mensajes = new MENSAJES($fmt);

require_once(_RUTA_NUCLEO.'modulos/crm/topicos-asistencia.class.php');
$topicos = new TOPICOS_ASISTENCIA($fmt);


$vars=explode(",", $_POST["inputVars"]);
$usu = $vars[0];
$id_emisor = $vars[1];
$num_mensajes = $vars[2];
$canal = 2;

$cliente = $mensajes->datos_cliente_atencion($id_emisor);

// if ($canal==2){
// 	$consulta = "SELECT * FROM mod_cliente_atencion WHERE mod_cli_ate_usu_id='$usu' and mod_cli_ate_estado='1'";
// 	$rs =$fmt->query->consulta($consulta);
// 	$num=$fmt->query->num_registros($rs);
// 	if($num>0){
// 		for ($i=0; $i < $num ; $i++) { 
// 			$row=$fmt->query->obt_fila($rs);

// 			$nombre_emisor = $row["mod_cli_ate_nombre"];
// 			$cuerpo = $row["mod_cli_ate_consulta"];
// 			$id_emisor = $row["mod_cli_ate_id"];
// 			$fecha = $row["mod_cli_ate_fecha_registro"];
// 			$ci_emisor = $row["mod_cli_ate_ci"];

// 			$fechax = explode(" ", $fecha);
// 			$horax = explode(":",$fechax[1]);
// 			$hora = $horax[0].":".$horax[1];
// 			//$hora =$fmt->class_modulo->date_formateado("America/La_Paz","H:i");
// 			$siglas_emisor = $fmt->usuario->siglas_nombre($nombre_emisor);

// 			// Cambiar Estado
// 			$sql="UPDATE mod_cliente_atencion SET
// 										mod_cli_ate_estado='2' 
// 										WHERE mod_cli_ate_id ='".$id_emisor."'";
// 			$fmt->query->consulta($sql);

// 			$envio = "nuevo,<a class='info-emisor btn-activar-chat-emisor animated flash' emisor='$id_emisor' id='emisor-$id_emisor'><div class='siglas siglas-emisor'>$siglas_emisor</div><span class='nombre'>$nombre_emisor</span><span class='hora'>$hora <i class='icn icn-check-label' id='check-$id_emisor' ></i></span></a>,";

// 			$envio .="<div class='charla off' id='charla-$id_emisor'>
// 									<div class='header'>
// 											<div class='info info-emisor'>
// 													<div class='siglas siglas-emisor'>$siglas_emisor</div>
// 													<span class='nombre'>$nombre_emisor - $ci_emisor</span>
// 											</div>
// 											<a class='btn btn-cerrar-atencion btn-full'>Cerrar atención</a>
// 									</div>
// 									<div class='box-conversacion' id='conversacion-$id_emisor'>
// 									</div>
// 									<div class='bloque-enviar-mensaje'>
// 										<input class='input-$id_emisor'  id='inputEnviarMensaje' tipe='text' emisor='$id_emisor' placeholder='Escribe un mensaje...' >
// 										<a class='btn btn-enviar-mensaje-emisor' emisor='$id_emisor' >Enviar</a>
// 									</div>
// 								</div>";

// 			echo $envio;

// 		} // for
// 	}	// if > 0
// } // $canal

$envio='';
 

$consulta = "SELECT * FROM mod_cliente_mensajes WHERE mod_cli_men_cli_id='$id_emisor' and mod_cli_men_tipo='0' and mod_cli_men_estado=1";
// $consulta = "SELECT * FROM mensaje WHERE men_emisor_usu_id='$emisor' and men_receptor_usu_id='$receptor' and men_estado=1";
$rs =$fmt->query->consulta($consulta);
$num=$fmt->query->num_registros($rs);
if($num>0){
	for ($i=0; $i < $num ; $i++) { 
		$row=$fmt->query->obt_fila($rs);
		$row_id= $row['mod_cli_men_id'];
		$row_usu= $row['mod_cli_men_usu_id'];
		$row_conv= $row['mod_cli_men_con_id'];
		
		$datos_conv = $mensajes->datos_conversacion($row_conv);
		$datos_topico= $topicos->datos_topico_asistencia($datos_conv["mod_cli_ate_con_topico_id"]);
		$topico_emisor = $datos_topico['mod_tpa_nombre'];

		$cuerpo = $row['mod_cli_men_cuerpo'];
		$fh = $row['mod_cli_men_fecha'];

		// $imagen_usu =  $fmt->usuario->imagen_usuario($id_emisor);
		$nombre_usu =  $cliente['mod_cli_ate_nombre'];
		$sigla_usu = $fmt->usuario->siglas_nombre($nombre_usu);

		$fecha_hoy = $fmt->class_modulo->fecha_hoy("America/La_Paz");
		$fecha = $fmt->class_modulo->tiempo_restante($fh,$fecha_hoy);
		
		$num_mensajes++;

		// if(!empty($imagen_usu) && $imagen_usu != 'images/user/user-default.png'){
		// 	$img_1 = _RUTA_IMAGES.$imagen_usu;
		// 	$aux = "<div class='imagen imagen-emisor' style='background:url($img_1) no-repeat center center'></div>";
		// }else{
		 	$aux ="<div class='siglas siglas-emisor'>".$sigla_usu."</div>";
		//}

		$envio .=  "<div class='bloque-mensaje bloque-mensaje-emisor'><div class='info info-emisor'>$aux</div><div class='mensaje mensaje-emisor' id='mensaje-emisor-".$num_mensajes."'>".$cuerpo."</div>";

		$primer = $mensajes->primer_mensaje_cliente($row_id,$row_conv);
		if ($row_usu==0 && $primer==true){
			$envio .=  "<div class='topico'><b>Tópico:</b> ".$topico_emisor."</div>";
		}
		$envio .=  "<div class='fecha-hora fecha-emisor'>$fecha</div>";
		// $envio .=  "<div class='fecha-hora'>$fecha</div>";
		
		$envio .=  "</div>";

		$sql="UPDATE mod_cliente_mensajes SET
								mod_cli_men_estado='2' 
								WHERE mod_cli_men_id ='".$row_id."'";
		$fmt->query->consulta($sql);
	}
	// $n = $num + $num_mensajes;
	echo "ok~".$envio."~".$num_mensajes."~".$id_emisor;	
}

//echo $imagen_usu;

?>