<?php
require_once("../../nucleo/clases/class-constructor.php");
$fmt = new CONSTRUCTOR();

require_once('grupos.class.php');

$fmt->get->validar_get( $_GET['tarea'] );
$tarea = $_GET['tarea'];

$form =new GRUPOS($fmt);

echo $fmt->header->header_modulo();

switch( $tarea ){
  case 'busqueda': $form->busqueda();break;
  case 'form_nuevo': $form->form_nuevo();break;
  case 'form_editar': $form->form_editar();break;
  case 'ingresar': $form->ingresar();break;
  case 'modificar': $form->modificar();break;
  case 'activar': $form->activar();break;
  case 'eliminar': $form->eliminar();break;
  default: $form->busqueda();break;
}
echo $fmt->footer->footer_modulo();

?>
