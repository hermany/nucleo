<?php
require_once("../../nucleo/clases/class-constructor.php");

$fmt = new CONSTRUCTOR();

require_once('inventario.class.php');

$fmt->get->validar_get( $_GET['tarea'] );
$tarea = $_GET['tarea'];

$form =new INVENTARIO($fmt);

	echo $fmt->header->header_modulo();

//echo '<script type="text/javascript" language="javascript" src="'._RUTA_WEB.'js/jquery.mixitup.js"></script>'."\n";
	//echo '<link rel="stylesheet" href="'._RUTA_WEB.'css/estilos-kardex.css" rel="stylesheet" type="text/css">'."\n";

switch( $tarea ){
  case 'busqueda': $form->busqueda();break;
  case 'form_nuevo': $form->form_nuevo();break;

  case 'form_nuevo_cat': $form->form_nuevo_cat();break;

  case 'form_editar': $form->form_editar();break;

  case 'form_editar_cat': $form->form_editar_cat();break;

  case 'ingresar': $form->ingresar();break;

  case 'ingresar_cat': $form->ingresar_cat();break;

  case 'modificar': $form->modificar();break;

  case 'modificar_cat': $form->modificar_cat(); break;

  case 'activar_cat': $form->activar_cat();break;

  case 'activar': $form->activar();break;

  case 'eliminar': $form->eliminar();break;

  case 'eliminar_item': $form->eliminar_item();break;

  default: $form->busqueda();break;
}
//$fmt->class_sistema->update_htaccess();
echo $fmt->footer->footer_modulo();

?>
