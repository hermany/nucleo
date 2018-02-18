<?php
//$output_dir = _RUTA_HOST."archivos/multimedia/";
 $blob = $_FILES["croppedImage"];
 $dir = $_POST["dir"];
 // $nombre = $_POST["nombre"];
 // $ext = $_POST["ext"];
 $ruta="";
 // $dato = explode("/", $dir);
 // $num = count($dato);
 // for($i=0;$i<($num-1);$i++){
 // $ruta.= $dato[$i]."/";
 // }

$nombre_thumb = $fmt->archivos->convertir_url_thumb( $dir );
$nombre_archivo =  _RUTA_HOST.$nombre_thumb;

move_uploaded_file($blob['tmp_name'], $nombre_archivo);
//
echo $nombre_thumb."?".rand();

?>
