<?php
$nom = $_POST["inputNombre"];
$tags = $_POST["inputTags"];
$embed = $_POST["inputEmbed"];
$rutaa= $fmt->get->convertir_url_amigable($nom);

$id_usu = $fmt->sesion->get_variable('usu_id');
$fecha = $fmt->class_modulo->fecha_hoy("America/La_Paz");

$ingresar ="mul_nombre,mul_tags,mul_ruta_amigable,mul_embed,mul_tipo_archivo,mul_fecha,mul_usuario,mul_activar";
$valores  ="'".$nom."','".$tags."','".$rutaa."','".$embed."','embed','".$fecha."','".$id_usu."','1'";
$sql="insert into multimedia (".$ingresar.") values (".$valores.")";
$fmt->query->consulta($sql,__METHOD__);

$sql="select max(mul_id) as id from multimedia";
$rs= $fmt->query->consulta($sql,__METHOD__);
$fila = $fmt->query->obt_fila($rs);
echo  $fila["id"].",".$nom.",".$embed;

//echo $nom.":".$tags.":".$embed;
?>
