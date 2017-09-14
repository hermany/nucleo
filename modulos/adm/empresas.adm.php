<?php
require_once('empresas.class.php');

$form =new EMPRESAS($fmt,$id_mod,$id_item,$id_estado);

	echo $fmt->header->header_modulo();

//echo '<script type="text/javascript" language="javascript" src="'._RUTA_WEB.'js/jquery.mixitup.js"></script>'."\n";
	echo '<link rel="stylesheet" href="'._RUTA_WEB.'css/estilos-kardex.css" rel="stylesheet" type="text/css">'."\n";

switch( $tarea ){
  case 'busqueda': $form->busqueda();break;

  case 'form_nuevo': $form->form_nuevo();break;
  case 'form_nuevo_empresa': $form->form_nuevo_empresa();break;


  case 'form_editar': $form->form_editar();break;
  case 'form_editar_empresa': $form->form_editar_empresa();break;


  case 'ingresar': $form->ingresar();break;
  case 'ingresar_empresa': $form->ingresar_empresa();break;


  case 'modificar': $form->modificar();break;
  case 'modificar_empresa': $form->modificar_empresa();break;


  case 'activar': $form->activar();break;
  case 'activar_empresa': $form->activar_empresa();break;


  case 'eliminar': $form->eliminar();break;
  case 'eliminar_empresa': $form->eliminar_empresa();break;

  default: $form->busqueda();break;
}
//$fmt->class_sistema->update_htaccess();
echo $fmt->footer->footer_modulo();

?>
