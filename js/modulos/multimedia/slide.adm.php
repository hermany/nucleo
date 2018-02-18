<?php
require_once('slide.class.php');

$form =new SLIDE($fmt,$id_mod,$id_item,$id_estado);

echo $fmt->header->header_modulo();

switch( $tarea ){
  case 'busqueda': $form->busqueda();break;
  case 'form_nuevo': $form->form_nuevo();break;
  case 'form_editar': $form->form_editar();break;
  case 'ingresar': $form->ingresar();break;
  case 'modificar': $form->modificar();break;
  default: $form->busqueda();break;
}
echo $fmt->footer->footer_modulo();
?>
