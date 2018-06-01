<?php
require_once('timer.class.php');

$form =new TIMER($fmt,$id_app,$id_item,$id_estado);

echo $fmt->header->header_modulo();

switch( $tarea ){
  case 'dashboard_timer': $form->dashboard_timer();break;
  case 'busqueda': $form->busqueda();break;

  
  default: $form->dashboard_timer();break;
}
echo $fmt->footer->footer_modulo();

?>