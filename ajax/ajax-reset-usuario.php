<?php 

$id_usu= base64_decode($_POST['inputId'] );
$pw=  $_POST['inputPw'] ;
$pwa = base64_encode( $_POST['inputPw'] );

// echo $id_usu.":".$pwa;

$sql="UPDATE usuario SET
						usu_password='".$pwa."'
						WHERE usu_id ='".$id_usu."'";
$fmt->query->consulta($sql);

echo "ok";