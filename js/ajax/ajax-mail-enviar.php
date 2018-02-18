<?php
require_once("../clases/class-constructor.php");
$fmt = new CONSTRUCTOR();

$nombre = $_POST["inputNombre"];
$email = $_POST["inputEmail"];
$email_friend  = $_POST["inputEmailAmigo"];
$asunto = $_POST["inputAsunto"];
$cuerpo = $_POST["InputMensaje"];
$mensaje = "<strong>Tu amigo </strong>".$nombre." con <strong>Email: </strong>".$email."<br> Pens&oacute; que te puede interesar en este producto:<br>".$cuerpo;
$mails = explode(",", $email_friend);
$num=count($mails);
for($i=0;$i<$num;$i++){
	$sw = $fmt->mail->enviar($mails[$i],'Mainter S.R.L.',$mensaje,$asunto." - ".$nombre,"Landicorp S.A.");
}
$sw = $fmt->mail->enviar($email,'Mainter S.R.L.',$mensaje,$asunto." - ".$nombre,"Landicorp S.A.");
if($sw){
echo "ok";
}
else{
echo "false";
}
?>
