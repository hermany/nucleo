<?php
header('Content-Type: text/html; charset=utf-8');

class ERRORES{

  var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

  function error_login(){
    return "<div role='alert' class='alert alert-danger animated fadeIn' id='error_login'><i class='icn icon icn-danger'></i> <span class='texto'> El email o password que ingresaste es incorrecto. Por favor intenta de nuevo.</span></div>";
  }

  function error($vars){
    $text = $vars["texto"];
    $icono = $vars["icono"];
    $color = $vars["color"];
    $clase = $vars["class"];
    $id= $vars["id"];

    if (empty($icono)){
      $icon = "icn-danger";
    }else{
      $icon = "";
    }

    if(empty($color)){
      $color='color-text-verde';
    }else{
      $color=$color;
    }

    return "<div role='alert' class='$clase alert alert-danger animated fadeIn' id='$id'><i class='icn  icon $color $icon'></i> <span class='texto'> $text </span></div>";
  }

  function error_parametrizacion(){
    return "Error de parametrización, algun valor no esta correcto. ";
  }

  function error_mail(){
    return "<div role='alert' class='alert alert-danger animated fadeIn' id='error_mail'><i class='icn icon icn-danger'></i> <span class='texto'> Lo sentimos estamos presentando problemas con el servidor por favor inténtelo mas tarde.</span></div>";
  }

  function error_rol(){
    return "<div role='alert' class='alert alert-warning animated fadeIn' id='error_login'><i class='icn icon icn-danger'></i> <span class='texto'> El usuario ingresado no tiene un rol definido. Por favor comuniquese con su encargado de sistemas</span></div>";
  }  

  function error_mail_no_registrado(){
    return "<div role='alert' class='alert alert-warning animated fadeIn' id='error_login' lang='es'><i class='icn icon icn-danger'></i> <span class='texto'>El e-mail ingresado no esta registrado, intenta crear una cuenta. :)</span></div>";
  }

  function error_rol_desactivado(){
    return "<div role='alert' class='alert alert-warning animated fadeIn' id='error_login'><i class='icn icon icn-danger'></i> <span class='texto'> El usuario ingresado existe pero esta desactivado. Por favor comuniquese con su encargado de sistemas.</span></div>";
  }

  function error_modulo_no_encontrado(){
    return "<div role='alert' class='alert alert-warning animated fadeIn' id='error_login'><i class='icn icon icn-danger'></i> <span class='texto'> El modulo no se ah encontrado.</span></div>";
  }
  function error_pag_no_encontrada(){
    echo $this->fmt->header->header_modulo();
    echo "<div class='body-error'><div role='alert' class='col-xs-4 col-xs-offset-4 alert alert-warning animated fadeIn' id='error_404'><i class='icn icon icn-danger'></i> <span class='texto'> 404 página no encontrada.</span></div></div>";
    echo $this->fmt->footer->footer_modulo();
  }
} //Fin class error
?>
