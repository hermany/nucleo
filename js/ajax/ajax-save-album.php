<?php
$nom = $_POST["inputNombre"];
$tags = $_POST["inputTags"];
$des = $_POST["inputDescripcion"];
$mul = $_POST["inputMul"];
$rutaa= $fmt->get->convertir_url_amigable($nom);

$id_usu = $fmt->sesion->get_variable('usu_id');
$fecha = $fmt->class_modulo->fecha_hoy("America/La_Paz");

$ingresar ="alb_ruta_amigable,alb_nombre,alb_descripcion,alb_tags,alb_fecha,alb_usuario,alb_activar";
$valores  ="'".$nom."','".$rutaa."','".$des."','".$tags."','".$fecha."','".$id_usu."','1'";
$sql="insert into album (".$ingresar.") values (".$valores.")";
$fmt->query->consulta($sql,__METHOD__);

$sql="select max(alb_id) as id from album";
$rs= $fmt->query->consulta($sql,__METHOD__);
$fila = $fmt->query->obt_fila($rs);
$id =$fila["id"];


$ingresar ="alb_mul_alb_id,alb_mul_mul_id,alb_mul_orden";
$id_mul = explode(",",$mul);
$cant_mul = count($id_mul);
for ($i=0; $i < $cant_mul; $i++) {
  $valores  ="'".$id."','".$id_mul[$i]."','".$i."'";
  $sql="insert into album_multimedia (".$ingresar.") values (".$valores.")";
  $fmt->query->consulta($sql,__METHOD__);
}
$img = $fmt->class_multimedia->imagen_album($fila["id"]);

echo  $fila["id"].",".$nom.",".$img;

//echo $nom.":".$tags.":".$des.":".$mul;
?>
