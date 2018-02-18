<?php
require_once('apps-config.class.php');
$form =new APP_CONFIG($this->fmt,$this->$id_mod,$this->$id_item,$this->$id_estado);
switch( $tarea ){
  case 'busqueda': $form->busqueda();break;
  case 'form_nuevo': $form->form_nuevo();break;
  case 'form_editar': $form->form_editar();break;
  case 'ingresar': $form->ingresar();break;
  case 'modificar': $form->modificar();break;
  default: $form->busqueda();break;
}

?>
