<?php
require_once('categorias.class.php');

$form =new CATEGORIAS($fmt,$id_mod,$id_item,$id_estado);

echo $fmt->header->header_modulo();

switch( $tarea ){
  case 'busqueda': $form->busqueda();break;
  case 'form_nuevo': $form->form_nuevo();break;
  case 'form_editar': $form->form_editar();break;
  case 'ordenar': $form->ordenar();break;
  case 'ordenar_update': $form->ordenar_update();break;
  case 'ingresar': $form->ingresar();break;
  case 'modificar': $form->modificar();break;
  case 'activar': $form->activar();break;
  case 'eliminar': $form->eliminar();break;
  default: $form->busqueda();break;
}
//$fmt->class_sistema->update_htaccess();
echo $fmt->footer->footer_modulo();

?>
