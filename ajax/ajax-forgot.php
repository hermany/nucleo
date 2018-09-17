<?php
header('Content-Type: text/html; charset=utf-8');

$cat = $fmt->get->get_categoria_index();
$pla = $fmt->get->get_plantilla_index($fmt->query,$cat);

$email= $_POST['inputEmail'];


$sql="SELECT  usu_password,usu_email,usu_nombre,usu_apellidos  FROM usuario WHERE usu_email='".$email."' ";
$rs = $fmt->query->consulta($sql,__METHOD__);
$num= $fmt->query->num_registros($rs);
//echo "num:".$num;


if (!empty($email)){
  if (filter_var($email, FILTER_VALIDATE_EMAIL) !== FALSE){
  	if($num > 0){
  		$row = $fmt->query->obt_fila($rs);
  		// RXN0YSBlcyB1bmEgY2xhdmUgcGFyYSBmb3Jnb3QgenVuZGk=
  		$mensaje = file_get_contents(_RUTA_NUCLEO._PLANTILLA_MAIL); 
  		$enlace = $fmt->enlace->datos_id(_ID_LOGO);
      $nom = $row["usu_nombre"].' '.$row["usu_apellidos"];
      $e1 = base64_encode($email);
      $e2 = base64_encode($row["usu_password"]);
      $link = _RUTA_WEB.'forgot?tr=email-reset&c='.$e1.'-'.$e2.'-RXN0YSBlcyB1bmEgY2xhdmUgcGFyYSBmb3Jnb3QgenVuZGk';
      $reset = file_get_contents(_RUTA_NUCLEO."views/mail/mail-reset.htm"); 
      $reset = str_replace("#nombre#", $nom, $reset);
      $reset = str_replace("#link#", $link, $reset);

      $contenido_pie = $fmt->contenido->datos_id(_ID_CONTENIDO_PIE);

  		$mensaje = str_replace("#logo#", "<img height='60px' src='"._RUTA_IMAGES.$enlace["enl_imagen"]."' />", $mensaje);
      $mensaje = str_replace("#cuerpo#", $reset, $mensaje);
      $mensaje = str_replace("#pie#",'®2018 Cenace-Upsa', $mensaje);
      $mensaje = str_replace("#social#",_SOCIAL, $mensaje);
  		$mensaje = str_replace("#mensaje-pie#",strip_tags($contenido_pie["conte_cuerpo"],'<span></span>'), $mensaje);

  		$fmt->mail->enviar($email,$nom,$mensaje,'Solicitud de restablecimiento de contraseña.',_CORREO); //$correo,$nombre_c,$mensaje,$asunto,$nombre,$seguridad="tls"
			echo "ok";
		}else {
		  echo "error-no-registro";
		}
  }else{
  	echo "error-mail";
  }
}else{
	echo "error-mail";
}