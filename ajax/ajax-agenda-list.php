<?php
if ($fmt->get->validar_get($_POST["inputId"])){
  $id_item = $_POST["inputId"];

  echo '<link rel="stylesheet" href="'._RUTA_WEB_NUCLEO.'css/m-agenda.css?reload" rel="stylesheet" type="text/css">';
  echo '<div class="title title-head title-agenda"><i class="icn icn-contact"></i> Agenda</label>';
  echo '  <div class="box-buscar"><i class="icn icn-search"></i><i class="icn icn-close"></i><input type="" value="" placeholder="Buscar persona" /></div>';
  echo '</div>';
  echo '<div class="block-form">';
    $consulta = "SELECT * FROM mod_agenda WHERE mod_agd_activar='1' ORDER BY mod_agd_nombre";
    $rs =$fmt->query->consulta($consulta,__METHOD__);
    $num=$fmt->query->num_registros($rs);
    if($num>0){
      for($i=0;$i<$num;$i++){
        list($fila_id,$nombre,$foto)=$fmt->query->obt_fila($rs);
        if (empty($foto)){
				  $img = _RUTA_WEB_NUCLEO."images/user/user-mini.png";
				}else{
				  $img = _RUTA_IMAGES.$foto;
				}
        echo "<div class='persona' item='$fila_id'><div class='img' style='background:url($img)no-repeat center center'></div>$nombre <i class='icn icn-checkmark-circle'></i></div>";
      }
    }
  echo '</div>
        <div class="footer-modal">
          <div class="bloque-botones pull-right">
            <a class="btn btn-full btn-cancelar-modal-doc">Cancelar</a>
            <a class="btn btn-primary btn-actualizar-modal-doc">Insertar</a>
          </div>
        </div>';
}else{
  $fila=$fmt->error->error_pag_no_encontrada();
}

?>
