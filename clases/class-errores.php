<?php
header('Content-Type: text/html; charset=utf-8');

class ERROR {

  var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

  function error_login(){
    return "<div role='alert' class='alert alert-danger animated fadeIn' id='error_login'><i class='icn-danger'></i> El email o password que ingresaste es incorrecto. Por favor intenta de nuevo.</div>";
  }

  function error_parametrizacion(){
    return "Error de parametrización, algun valor no esta correcto. ";
  }

  function error_mail(){
    return "<div role='alert' class='alert alert-danger animated fadeIn' id='error_mail'><i class='icn-danger'></i> Lo sentimos estamos presentando problemas con el servidor por favor inténtelo mas tarde.</div>";
  }

  function error_rol(){
    return "<div role='alert' class='alert alert-warning animated fadeIn' id='error_login'><i class='icn-danger'></i> El usuario ingresado no tiene un rol definido. Por favor comuniquese con su encargado de sistemas.</div>";
  }

  function error_rol_desactivado(){
    return "<div role='alert' class='alert alert-warning animated fadeIn' id='error_login'><i class='icn-danger'></i> El usuario ingresado existe pero esta desactivado. Por favor comuniquese con su encargado de sistemas.</div>";
  }

  function error_modulo_no_encontrado(){
    return "<div role='alert' class='alert alert-warning animated fadeIn' id='error_login'><i class='icn-danger'></i> El modulo no se ah encontrado.</div>";
  }
  function error_pag_no_encontrada(){
    echo $this->fmt->header->header_modulo();
    echo "<div class='body-error'><div role='alert' class='col-xs-4 col-xs-offset-4 alert alert-warning animated fadeIn' id='error_404'><i class='icn-danger'></i> 404 página no encontrada.</div></div>";
    echo $this->fmt->footer->footer_modulo();
  }
} //Fin class error
?>
