<?php

 $data = $_POST["imagen"];
 $dir = $_POST["dir"];
 $nombre = $_POST["nombre"];
 $ext = $_POST["ext"];
 $ruta="";
 $dato = explode("/", $dir);
 $num = count($dato);
 for($i=0;$i<($num-1);$i++){
	$ruta.= $dato[$i]."/";
 }

 $d_ex=explode(".", $dir);
 if($ext!=end($d_ex)){
	 $ext=end($d_ex);
 }

 list($type, $data) = explode(';', $data);
 list(, $data)      = explode(',', $data);
 //echo $data;
 $data = base64_decode($data);
 //$ifp = fopen("../image.png", "a+");
$nombre_archivo = _RUTA_ROOT.$ruta.$nombre."-thumb.".$ext;

//file_put_contents($nombre_archivo, $data);


if($ifp = fopen($nombre_archivo, "w+") or die(print_r(error_get_last(),true))){
    fwrite($ifp, $data);
    fclose($ifp);
}
 //file_put_contents('image.png', $data);
 //$file = uniqid() . '.png';

 //$success = file_put_contents($file, $data);

?>
