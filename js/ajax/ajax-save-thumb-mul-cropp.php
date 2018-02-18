<?php

  $blob = $_FILES["croppedImage"];
 $dir = $_POST["dir"];
 $nombre = $_POST["nombre"];
 $ext = $_POST["ext"];
 $ruta="";
 $dato = explode("/", $dir);
 $num = count($dato);
 for($i=0;$i<($num-1);$i++){
	$ruta.= $dato[$i]."/";
 }

$nombre_thumb = $ruta.$nombre."-thumb.png";
$nombre_archivo = _RUTA_ROOT.$nombre_thumb;

move_uploaded_file($blob['tmp_name'], $nombre_archivo);

echo $nombre_thumb."?".rand();

?>
