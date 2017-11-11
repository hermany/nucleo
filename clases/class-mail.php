<?PHP
header('Content-Type: text/html; charset=utf-8');

class MAIL {

  var $fmt;

  	function __construct($fmt) {
    	$this->fmt = $fmt;
	}

	function traer_smtp(){
		$row["smtp"]=_SMTP;
		$row["port"]=_PUERTO;
		$row["salida_correo"]=_CORREO;
		$row["contracena_sa"]=_PASSWORD_MAIL;
		$row["entrada_correo"]=_CORREO;
		$row["seguridad_mail"]=_SEGURIDAD_MAIL;
		return $row;
	}

	function enviar($correo,$nombre_c,$mensaje,$asunto,$nombre,$seguridad="tls"){
		require_once(_RUTA_NUCLEO."includes/phpmailer/PHPMailerAutoload.php");

		$rw=$this->traer_smtp();


		$mail = new PHPMailer;

		$mail->SetLanguage('es');
		$mail->isSMTP();                                     // Set mailer to use SMTP
		//$mail->SMTPDebug = 0;
		$mail->Host = $rw['smtp'];  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = $rw['salida_correo'];                 // SMTP username
		$mail->Password = $rw['contracena_sa'];                           // SMTP password
		$mail->SMTPSecure =$rw['seguridad_mail'];                               // Enable TLS encryption, `ssl` also accepted
		$mail->Port = $rw['port'];                                    // TCP port to connect to

		$mail->setFrom($rw['entrada_correo'], $nombre);

		//$mail->addReplyTo($rw['entrada_correo'], $nombre);
		$mail->addAddress($correo, $nombre_c);     // Add a recipient

		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = utf8_decode($asunto);
		$mail->Body    = $mensaje;
		//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		$exito = $mail->Send();

	   if(!$exito)
	   {
	   	return false;
	   }
	   else{
			return true;
	   }
	}
}
?>
