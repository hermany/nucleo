<?php
require_once("../../nucleo/clases/class-constructor.php");

$fmt = new CONSTRUCTOR();

require_once('kardex-config.class.php');

$fmt->get->validar_get( $_GET['tarea'] );
$tarea = $_GET['tarea'];

$form =new KARDEX_CONF($fmt);

	echo $fmt->header->header_modulo();

//echo '<script type="text/javascript" language="javascript" src="'._RUTA_WEB.'js/jquery.mixitup.js"></script>'."\n";
	echo '<link rel="stylesheet" href="'._RUTA_WEB.'css/estilos-kardex.css" rel="stylesheet" type="text/css">'."\n";

switch( $tarea ){
  case 'busqueda': $form->busqueda();break;

  case 'form_nuevo': $form->form_nuevo();break;
  case 'form_nuevo_empresa': $form->form_nuevo_empresa();break;
  case 'form_nuevo_cargo': $form->form_nuevo_cargo();break;
  case 'form_nueva_division': $form->form_nueva_division();break;
  case 'form_nuevo_departamento': $form->form_nuevo_departamento();break;
  case 'form_nuevo_cat': $form->form_nuevo_cat();break;

  case 'form_editar': $form->form_editar();break;
  case 'form_editar_empresa': $form->form_editar_empresa();break;
  case 'form_editar_cargo': $form->form_editar_cargo();break;
  case 'form_editar_division': $form->form_editar_division();break;
  case 'form_editar_departamento': $form->form_editar_departamento();break;
  case 'form_editar_cat': $form->form_editar_cat();break;

  case 'ingresar': $form->ingresar();break;
  case 'ingresar_empresa': $form->ingresar_empresa();break;
  case 'ingresar_cargo': $form->ingresar_cargo();break;
  case 'ingresar_division': $form->ingresar_division();break;
  case 'ingresar_departamento': $form->ingresar_departamento();break;
  case 'ingresar_cat': $form->ingresar_cat();break;

  case 'modificar': $form->modificar();break;
  case 'modificar_empresa': $form->modificar_empresa();break;
  case 'modificar_cargo': $form->modificar_cargo();break;
  case 'modificar_division': $form->modificar_division();break;
  case 'modificar_departamento': $form->modificar_departamento();break;
  case 'modificar_cat': $form->modificar_cat(); break;

  case 'activar': $form->activar();break;
  case 'activar_empresa': $form->activar_empresa();break;
  case 'activar_cargo': $form->activar_cargo();break;
  case 'activar_division': $form->activar_division();break;
  case 'activar_departamento': $form->activar_departamento();break;

  case 'eliminar': $form->eliminar();break;
  case 'eliminar_empresa': $form->eliminar_empresa();break;
  case 'eliminar_cargo': $form->eliminar_cargo();break;
  case 'eliminar_division': $form->eliminar_division();break;
  case 'eliminar_departamento': $form->eliminar_departamento();break;
  case 'eliminar_cat': $form->eliminar_cat();break;

  default: $form->busqueda();break;
}
//$fmt->class_sistema->update_htaccess();
echo $fmt->footer->footer_modulo();

?>
