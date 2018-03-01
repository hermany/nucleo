<?php 
$usu=$_POST["inputVars"];
$canal = 2;

if ($canal==2){
	$consulta = "SELECT * FROM mod_cliente_atencion WHERE mod_cli_ate_usu_id='$usu' and mod_cli_ate_estado='1'";
	$rs =$fmt->query->consulta($consulta);
	$num=$fmt->query->num_registros($rs);
	if($num>0){
		for ($i=0; $i < $num ; $i++) { 
			$row=$fmt->query->obt_fila($rs);

			$nombre_emisor = $row["mod_cli_ate_nombre"];
			$cuerpo = $row["mod_cli_ate_consulta"];
			$id_emisor = $row["mod_cli_ate_id"];
			$fecha = $row["mod_cli_ate_fecha_registro"];
			$ci_emisor = $row["mod_cli_ate_ci"];

			$fechax = explode(" ", $fecha);
			$horax = explode(":",$fechax[1]);
			$hora = $horax[0].":".$horax[1];
			//$hora =$fmt->class_modulo->date_formateado("America/La_Paz","H:i");
			$siglas_emisor = $fmt->usuario->siglas_nombre($nombre_emisor);

			// Cambiar Estado
			$sql="UPDATE mod_cliente_atencion SET
										mod_cli_ate_estado='2' 
										WHERE mod_cli_ate_id ='".$id_emisor."'";
			$fmt->query->consulta($sql);

			$envio = "nuevo,<a class='info-emisor btn-activar-chat-emisor animated flash' emisor='$id_emisor' id='emisor-$id_emisor'><div class='siglas siglas-emisor'>$siglas_emisor</div><span class='nombre'>$nombre_emisor</span><span class='hora'>$hora <i class='icn icn-check-label' id='check-$id_emisor' ></i></span></a>,";

			$envio .="<div class='charla off' id='charla-$id_emisor'>
									<div class='header'>
											<div class='info info-emisor'>
													<div class='siglas siglas-emisor'>$siglas_emisor</div>
													<span class='nombre'>$nombre_emisor - $ci_emisor</span>
											</div>
											<a class='btn btn-cerrar-atencion btn-full'>Cerrar atención</a>
									</div>
									<div class='box-conversacion' id='conversacion-$id_emisor'>
									</div>
									<div class='bloque-enviar-mensaje'>
										<input class='input-$id_emisor'  id='inputEnviarMensaje' tipe='text' emisor='$id_emisor' placeholder='Escribe un mensaje...' >
										<a class='btn btn-enviar-mensaje-emisor' emisor='$id_emisor' >Enviar</a>
									</div>
								</div>";

			echo $envio;

		} // for
	}	// if > 0
} // $canal
?>