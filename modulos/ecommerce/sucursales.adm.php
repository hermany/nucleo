<?php
require_once('sucursales.class.php');
$form =new SUCURSALES($fmt,$id_mod,$id_item,$id_estado);
echo $fmt->header->header_modulo();

//echo '<script type="text/javascript" language="javascript" src="'._RUTA_WEB.'js/jquery.mixitup.js"></script>'."\n";
switch( $tarea ){
  case 'busqueda': $form->busqueda();break;
  case 'form_nuevo': $form->form_nuevo();break;
  case 'form_editar': $form->form_editar();break;
  case 'ingresar': $form->ingresar();break;
  case 'modificar': $form->modificar();break;
  default: $form->busqueda();break;
}

echo $fmt->footer->footer_modulo();

?>
