<?php
require_once('contenedor.class.php');

$form =new CONTENEDOR($fmt,$id_mod,$id_item,$id_estado);

echo $fmt->header->header_modulo();

switch( $tarea ){
//  case 'busqueda': $form->busqueda();break;
  case 'form_editar': $form->form_editar();break;
  case 'form_nuevo': $form->form_nuevo();break;
  case 'editar_contenidos': $form->editar_contenidos();break;
 	case 'ingresar': $form->ingresar();break;
  case 'modificar': $form->modificar();break;
  // case 'activar': $form->activar();break;
  // case 'eliminar': $form->eliminar();break;
  default: $form->editar_contenidos();break;
}
echo $fmt->footer->footer_modulo();

?>
