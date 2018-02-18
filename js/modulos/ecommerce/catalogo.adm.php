<?php

require_once('catalogo.class.php');

$form =new CATALOGO_PRODUCTOS($fmt,$id_mod,$id_item,$id_estado);

echo $fmt->header->header_modulo();

switch( $tarea ){
  case 'busqueda': $form->busqueda();break;
  default: $form->busqueda();break;
}
//$fmt->class_sistema->update_htaccess();
echo $fmt->footer->footer_modulo();

?>
