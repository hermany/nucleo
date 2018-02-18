<?php
require_once('config.class.php');

$form =new CONFIG($fmt,$id_mod,$id_item,$id_estado);

echo $fmt->header->header_modulo();

switch( $tarea ){
  case 'form_editar': $form->form_editar();break;
  case 'modificar': $form->modificar();break;
  default: $form->form_editar();break;
}
echo $fmt->footer->footer_modulo();

?>
