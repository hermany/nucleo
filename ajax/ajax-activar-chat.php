<?php 
$vars = explode(",",$_POST["inputVars"]);

require_once(_RUTA_NUCLEO.'modulos/mensajes/mensajes.class.php');
$mensajes = new MENSAJES($fmt);

$id_emisor= $vars[0];
$id_receptor= $vars[1];
$canal = $vars[2];
$rol= $vars[3];

$datos_emisor = $mensajes->datos_cliente_atencion($id_emisor);

$nombre_emisor = $datos_emisor[0];
$siglas_emisor = $fmt->usuario->siglas_nombre($nombre_emisor);

$consulta = "SELECT * FROM mensaje WHERE men_emisor_usu_id='$id_emisor' and men_receptor_usu_id='$id_receptor' and men_canal='$canal' and men_estado='1' ORDER BY men_fecha ASC";
$rs =$fmt->query->consulta($consulta);
$num=$fmt->query->num_registros($rs);
$envio="";

$envio .='<div class="header">';
$envio .='<div class="info">Conversación con: '.$nombre_emisor;
$envio .='</div>';
$envio .='<a class="btn-cerrar-atencion btn-small btn-full"> Cerrar Atención</a>';
$envio .='</div>';
$envio .='<div class="box-conversacion ">';

$fecha = $fmt->class_modulo->date_formateado("America/La_Paz","d-m-Y H:i");
if(!empty($imagen_usu)){
			$img_1 = _RUTA_IMAGES.$imagen_usu;
			$aux = "<div class='imagen imagen-receptor' style='background:url($img_1) no-repeat center center'></div>";
		}else{
			$aux ="<div class='siglas siglas-receptor'>$siglas_usu</div>";
		}

		$envio .=  "<div class='fecha-hora'>$fecha</div>";


if($num>0){
	$envio.= "<div class='bloque-mensaje bloque-mensaje-emisor'>
								<div class='info info-emisor'><div class='siglas siglas-emisor'>$siglas_emisor</div></div>
								<div class='mensaje mensaje-emisor' id='mensaje-1' tipo='emisor'>";

	for($i=0;$i<$num;$i++){
		$row=$fmt->query->obt_fila($rs);
		$row_id = $row["men_id"];

		$mensaje = $row["men_cuerpo"];
		$fecha = $row["men_fecha"];

		

		
		$envio.= $mensaje."</br>";

	}
}
$envio .="	</div>";
$envio .="	</div>";
$envio .='</div>';
echo $envio;

$fmt->query->liberar_consulta($rs);


?>