<?php
require_once("../../nucleo/clases/class-constructor.php");

$fmt = new CONSTRUCTOR();

require_once('kardex.class.php');

$fmt->get->validar_get( $_GET['tarea'] );
$tarea = $_GET['tarea'];

$form =new KARDEX($fmt);

echo $fmt->header->header_modulo();

//echo '<script type="text/javascript" language="javascript" src="'._RUTA_WEB.'js/jquery.mixitup.js"></script>'."\n";
echo '<link rel="stylesheet" href="'._RUTA_WEB.'css/estilos-kardex.css" rel="stylesheet" type="text/css">'."\n";

switch( $tarea ){
  case 'busqueda': $form->busqueda();break;
  case 'list_papelera': $form->list_papelera();break;
  case 'form_nuevo': $form->form_nuevo();break;
  case 'form_editar': $form->form_editar();break;
  case 'ingresar': $form->ingresar();break;
  case 'modificar': $form->modificar();break;
  case 'activar': $form->activar();break;
  case 'eliminar': $form->eliminar();break;
  case 'restaurar_id': $form->restaurar_id();break;
  case 'restaurar': $form->restaurar();break;
  case 'vaciar_id': $form->vaciar_id();break;
  case 'vaciar': $form->vaciar();break;
  default: $form->busqueda();break;
}
//$fmt->class_sistema->update_htaccess();
echo $fmt->footer->footer_modulo();

?>
