<?php
require_once(_RUTA_NUCLEO."clases/class-constructor.php");
$fmt = new CONSTRUCTOR();



$fmt->sesion->iniciar_sesion();
$fmt->sesion->cerrar_sesion();

// $fmt->get->validar_get($_GET["cat"]);
// $fmt->get->validar_get($_GET["pla"]);
// $cat =$_GET["cat"];
// $pla =$_GET["pla"];
// $link="index.php?&cat=".$cat."&pla=".$pla;
// $link=_RUTA_WEB."login";
$link=_RUTA_WEB;
header("Location:".$link);
?>
