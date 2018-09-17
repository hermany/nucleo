<?php
$cat = $fmt->get->get_categoria_index();
$pla = $fmt->get->get_plantilla_index($fmt->query,$cat);

$email= $_POST['inputEmail'];
$pw= base64_encode( $_POST['password'] );
//echo $email." : ".$pw;

$sql="SELECT usu_id, usu_nombre, usu_email, usu_activar FROM usuario WHERE usu_email='".$email."' and usu_password='".$pw."' ";
$rs = $fmt->query->consulta($sql,__METHOD__);
$num= $fmt->query->num_registros($rs);
//echo "num:".$num;

if($num > 0){
  //list($usu_id, $usu_nombre, $usu_email, $usu_activar) = $fmt->query->obt_fila($rs);

  $row = $fmt->query->obt_fila($rs);
  $usu_id  = $row["usu_id"];
  $usu_nombre  = $row["usu_nombre"];
  $usu_email = $row["usu_email"];
  $usu_activar = $row["usu_activar"];

  $fmt->sesion->set_variable("usu_id",$usu_id);
  $fmt->sesion->set_variable("usu_nombre",$usu_nombre);
  $fmt->sesion->set_variable("usu_mail",$usu_email);
  $fmt->sesion->set_variable("usu_rol",$usu_rol);
  $fmt->sesion->set_variable("usu_sitio","zun");

  //$fmt->sesion->get_nombre();
  //$fmt->sesion->imprimir();
  //$redireccion = new REDIRECCION();
  $ruta = $fmt->redireccion->login($cat,$pla,$usu_id);
  
  if ($usu_activar=='0'){
    echo "rol-desactivado";
    $fmt->sesion->cerrar_sesion();
  } else {
    if ($fmt->usuario->id_rol_usuario($usu_id)) {
      $fmt->sesion->set_variable("usu_rol",$fmt->usuario->id_rol_usuario($usu_id));
      echo $ruta;
    } else{
      echo "sin-rol";
      $fmt->sesion->cerrar_sesion();
    }
  }
} else {
  echo "false";
}
