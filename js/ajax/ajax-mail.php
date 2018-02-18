<?php
require_once("../clases/class-constructor.php");
$fmt = new CONSTRUCTOR();

$nombre = $_POST["inputNombre"];
$email = $_POST["inputEmail"];
$telefono  = $_POST["inputTelf"];
$motivo = $_POST["inputMotivo"];
$consulta = $_POST["inputConsulta"];
$mensaje = "<strong>Nombre: </strong>".$nombre."<br><strong>Email: </strong>".$email."<br><strong>Tel&eacute;fono: </strong>".$telefono."<br><strong>Motivo: </strong>".$motivo."<br><strong>Consulta: </strong>".$consulta;
//$sw = $fmt->mail->enviar('marketing_landicorp@landicorp.com.bo',$mensaje,"Contacetenos Web - ".$nombre,"Landicorp");
$sw = $fmt->mail->enviar('marketing_landicorp@landicorp.com.bo','Landicorp S.A.',$mensaje,"Contacto web - ".$nombre,"Landicorp S.A.");
if($sw){
echo "ok";
}
else{
echo "false";
}
?>
