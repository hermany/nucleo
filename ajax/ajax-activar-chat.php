<?php 
$vars = explode(",",$_POST["inputVars"]);

require_once(_RUTA_NUCLEO.'modulos/mensajes/mensajes.class.php');
require_once(_RUTA_NUCLEO.'modulos/crm/topicos-asistencia.class.php');
$mensajes = new MENSAJES($fmt);
$topicos = new TOPICOS_ASISTENCIA($fmt);

$usuario = $fmt->sesion->get_variable("usu_id");

$id_emisor= $vars[0];
$id_receptor= $vars[1];
$canal = $vars[2];
$rol= $vars[3];

$datos_emisor = $mensajes->datos_cliente_atencion($id_emisor);

$nombre_emisor = $datos_emisor['mod_cli_ate_nombre'];

$siglas_emisor = $fmt->usuario->siglas_nombre($nombre_emisor);


$consulta = "SELECT * FROM mod_cliente_mensajes WHERE mod_cli_men_cli_id='$id_emisor' and mod_cli_men_canal='$canal'  ORDER BY mod_cli_men_fecha ASC";
// $consulta = "SELECT * FROM mensaje WHERE men_emisor_usu_id='$id_emisor' and men_receptor_usu_id='$id_receptor' and men_canal='$canal' and men_estado='1' ORDER BY men_fecha ASC";

$rs =$fmt->query->consulta($consulta);
$num=$fmt->query->num_registros($rs);
$envio="<div class='charla '>";

$envio .='<div class="header">';
$envio .='<div class="info"><span>Conversación con: '.$nombre_emisor."</span></br>";
// $envio .='<div class="info"><span>Conversación con: '.$nombre_emisor."</span> <span class='topico'></span></br>";
$envio .='</div>';
$envio .='<a class="btn-cerrar-atencion btn-small btn-full" conv=""> Cerrar Atención</a>';
$envio .='</div>';
$envio .='<div class="box-conversacion box-conversacion-'.$id_emisor.'">';

$fecha = $fmt->class_modulo->date_formateado("America/La_Paz","Y-m-d H:i");
$envio .=  "<div class='fecha-hora'>$fecha</div>";

$n=0;

if($num>0){
	for($i=0;$i<$num;$i++){
		$row=$fmt->query->obt_fila($rs);
		$row_id = $row["mod_cli_men_id"];
		$conv = $row["mod_cli_men_con_id"];
		$datos_conv = $mensajes->datos_conversacion($conv);
		$datos_topico= $topicos->datos_topico_asistencia($datos_conv["mod_cli_ate_con_topico_id"]);
		$topico_emisor = $datos_topico['mod_tpa_nombre'];

		$row_usu = $row["mod_cli_men_usu_id"];
		$row_tipo = $row["mod_cli_men_tipo"];

		$nomb = $fmt->usuario->nombre_usuario($row_usu);
		$imagen_usu = $fmt->usuario->imagen_usuario($row_usu);
		$siglas_usu = $fmt->usuario->siglas_nombre($nomb);
		$mensaje = $row["mod_cli_men_cuerpo"];
		// $fecha_men= $fmt->class_modulo->diferencia_tiempo($fecha,$row["men_fecha"]) ;
		$fecha_men = $fmt->class_modulo->diferencia_tiempo(array('fecha_ini' => $row["mod_cli_men_fecha"],
																														 'fecha_fin'=> $fecha)) ;


		if(!empty($imagen_usu)){
			$img_1 = _RUTA_IMAGES.$imagen_usu;
			$aux = "<div class='imagen imagen-receptor' style='background:url($img_1) no-repeat center center'></div>";
		}else{
			$aux ="<div class='siglas siglas-receptor'>$siglas_usu</div>";
		}

		$n ++;

		if($row_tipo=='1'){
			$txt ='receptor';
		}else{
			$txt ='emisor';
		}

		$envio .= "<div class='bloque-mensaje bloque-mensaje-".$txt."'>
								<div class='info info-".$txt."'><div class='siglas siglas-".$txt."'>$siglas_emisor</div></div>
								<div class='mensaje mensaje-".$txt."' id='mensaje-$n' tipo='".$txt."'>";		
		$envio .= $mensaje."";

		$envio .="	</div>";

		if ($row_usu==0){
			$envio .=  "<div class='topico'><b>Tópico:</b> ".$topico_emisor."</div>";
		}

		$res='';

		if ($txt=='receptor' && $row_usu==$usuario){
			$res='Respondido por: '.$nomb;
		} 
		$envio .=  "<div class='fecha-hora fecha-".$txt."'>$fecha_men $res</div>";
		$envio .="</div>";

		$sql="UPDATE mod_cliente_mensajes SET
										mod_cli_men_estado='2' 
										WHERE mod_cli_men_id ='".$row_id."'";
		$fmt->query->consulta($sql);
		

	}
	if ($datos_conv["mod_cli_ate_con_fecha_fin"]!="0000-00-00 00:00:00"){
			$envio .=  '<div class="mensaje-cerrar">Conversación Cerrada '.$datos_conv["mod_cli_ate_con_fecha_fin"].' </div>';
		}
}

$envio .="	</div>";
$envio .='</div>';
$envio .='</div>';

$envio .='<div class="bloque-enviar-mensajes">';
$envio .='<input type="text" class="form-control" autocomplete="off" placeholder="Escribir un mensaje..." id="inputMensajeReceptor" last="'.$txt.'" conv="'.$conv.'" mensaje="'.$num.'" usu="'.$id_receptor.'" cliente="'.$id_emisor.'" >';
$envio .='</div>';

//$conversacion = $mensajes->datos_conversacion($conv);



echo $n."~".$envio."~".$conv;

$fmt->query->liberar_consulta($rs);


?>

