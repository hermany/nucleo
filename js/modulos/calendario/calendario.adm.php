<?php
require_once("../../nucleo/clases/class-constructor.php");
$fmt = new CONSTRUCTOR();

require_once('calendario.class.php');
$fmt->get->validar_get( $_GET['tarea'] );
$tarea = $_GET['tarea'];

$form =new CALENDARIO($fmt);

echo $fmt->header->header_modulo();

	switch($tarea)
	{
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

echo $fmt->footer->footer_modulo();
 ?>
