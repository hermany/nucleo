<?php
require_once("../clases/class-constructor.php");
$fmt = new CONSTRUCTOR();

$nombre = $_POST["inputNombre"];
$email = $_POST["inputEmail"];

$telefono  = $_POST["inputTelf"];
$motivo = $_POST["inputMotivo"];
$consulta = $_POST["inputConsulta"];

$mensaje = "<strong>Nombre: </strong>".$nombre."<br><strong>Email: </strong>".$email."<br><strong>Tel&eacute;fono: </strong>".$telefono."<br><strong>Motivo: </strong>".$motivo."<br><strong>Consulta: </strong>".$consulta;
if ( if (filter_var($email, FILTER_VALIDATE_EMAIL) !== FALSE){
  $sw = $fmt->mail->enviar('hterrazas@gmail.com','Mainter S.R.L.',$mensaje,"Contacto web - ".$nombre,"Landicorp S.A.");
  if($sw){
  echo "ok";
  }
  else{
  echo "false";
  }
else{
  echo "false";
}
?>
