<?php

require_once('apps.class.php');
$form =new APP($fmt,$id_mod,$id_item,$id_estado);
echo $fmt->header->header_modulo();
switch( $tarea ){
  case 'dashboard_modulo': $form->dashboard_modulo();break;
  case 'busqueda': $form->busqueda();break;
  case 'form_nuevo': $form->form_nuevo();break;
  case 'form_editar': $form->form_editar();break;
  case 'ingresar': $form->ingresar();break;
  case 'modificar': $form->modificar();break;
  case 'activar': $form->activar();break;
  case 'eliminar': $form->eliminar();break;
  default: $form->dashboard_modulo();break;
}
echo $fmt->footer->footer_modulo();

?>
