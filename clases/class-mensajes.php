<?php
header('Content-Type: text/html; charset=utf-8');

class MENSAJE{

  var $fmt;

  function __construct($fmt){
    $this->fmt = $fmt;
  }


  function mail_ok(){
    return "<div role='alert' class='alert alert-success animated fadeIn' id='success_mail'><i class='icn icn-checkmark-circle'></i> Su consulta se envió correctamente, nos pondremos en contacto con usted lo antes posible.</div>";
  }

  function mail_compartir_ok(){
    return "<div role='alert' class='alert alert-success animated fadeIn' id='success_mail'><i class='icn-checkmark-circle'></i> Su consulta se envió correctamente, muchas gracias por compartir nuestros productos.</div>";
  }

  function login_ok(){
    return "<div  class='alert alert-success btn animated fadeIn' ><i class='color-text-verde icn-checkmark-circle'></i> Logín correcto. Redireccionando...</div>";
  }

  function no_existe_categorias_hijas(){
    return "<div  class='alert alert-warning col-xs-3 col-md-offset-4 col-xs- animated fadeIn color-text-negro-b' ><i class='icn-danger'></i> No existen categorias hijas...</div>";
  }

  function documento_subido(){
    return "<div  class='alert alert-success col-md-10 animated fadeIn color-text-negro-b' ><i class='icn-checkmark-circle color-text-verde'></i> Documento subido satisfactoriamente.</div>";
  }

  function pregunta_eliminar($nombre){
    return "<div class='modal-title'></div><div class='modal-body'> <i class='icn icn-trash'></i> <label>Estas seguro de eliminar '".$nombre."'</label><span>No podrás deshacer esta acción.<span> </div><div class='modal-footer'><a class='btn btn-small btn-default'>Cancelar</a><a class='btn btn-info btn-m-eliminar'></a></div>";
  }

  function programado(){
    return "<span class='programado'><i class='icn icn-clock'></i> Programado</span>";
  }

}// fin class mensajes;

?>
