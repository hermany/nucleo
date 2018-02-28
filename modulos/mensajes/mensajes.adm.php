<?php
require_once('mensajes.class.php');

$form =new MENSAJES($fmt,$id_app,$id_item,$id_estado);

echo $fmt->header->header_modulo();

switch( $tarea ){
  case 'dashboard_mensajes': $form->dashboard_modulo();break;
  case 'busqueda': $form->busqueda();break;

  
  default: $form->dashboard_mensajes();break;
}
echo $fmt->footer->footer_modulo();

?>