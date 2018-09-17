<?php
header('Content-Type: text/html; charset=utf-8');

class MENSAJE{

  var $fmt;

  function __construct($fmt){
    $this->fmt = $fmt;
  }


  function mail_ok(){
    return "<div role='alert' class='alert alert-success animated fadeIn' id='success_mail'><i class='icn icon icn-checkmark-circle'></i><span class='texto'> Su consulta se envió correctamente, nos pondremos en contacto con usted lo antes posible.</span></div>";
  }

  function mail_compartir_ok(){
    return "<div role='alert' class='alert alert-success animated fadeIn' id='success_mail'><i class='icn icon icn-checkmark-circle'></i><span class='texto'><span class='texto'> Su consulta se envió correctamente, muchas gracias por compartir nuestros productos.</span></div>";
  }

  function login_ok(){
    return "<div  class='alert alert-success btn animated fadeIn' ><i class='icn icon color-text-verde icn-checkmark-circle'></i><span class='texto'> Logín correcto. Redireccionando...</span></div>";
  }  

  public function alert_success($vars){
    $text = $vars["texto"];
    $icono = $vars["icono"];
    $color = $vars["color"];
    $clase = $vars["class"];
    $id= $vars["id"];

    if (empty($icono)){
      $icon = "icn-checkmark-circle";
    }else{
      $icon = "";
    }

    if(empty($color)){
      $color='color-text-verde';
    }else{
      $color=$color;
    }
    return "<div  class='alert alert-success btn animated fadeIn $clase' id='$id' ><i class='icn icon  $color $icon'></i><span class='texto'>".$text."</span></div>";
  }

  function no_existe_categorias_hijas(){
    return "<div  class='alert alert-warning col-xs-3 col-md-offset-4 col-xs- animated fadeIn color-text-negro-b' ><i class='icn icon  icn-danger'></i><span class='texto'> No existen categorias hijas...</span></div>";
  }

  function documento_subido(){
    return "<div  class='alert alert-success col-md-10 animated fadeIn color-text-negro-b' ><i class='icn icon icn-checkmark-circle color-text-verde'></i> <span class='texto'>Documento subido satisfactoriamente.</span></div>";
  }

  function pregunta_eliminar($nombre){
    return "<div class='modal-title'></div><div class='modal-body'> <i class='icn icon icn-trash'></i> <label>Estas seguro de eliminar '".$nombre."'</label><span>No podrás deshacer esta acción.<span> </div><div class='modal-footer'><a class='btn btn-small btn-default'>Cancelar</a><a class='btn btn-info btn-m-eliminar'></a></div>";
  }

  function programado(){
    return "<span class='programado'><i class='icn icon icn-clock'></i><span class='texto'> Programado</span></span>";
  }

}// fin class mensajes;

?>
