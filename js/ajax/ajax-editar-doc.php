<?php
if ($fmt->get->validar_get($_POST["inputItem"])){
  $id_item = $_POST["inputItem"];
  $consulta = "SELECT * FROM documento WHERE doc_id='$id_item'";
  $rs =$fmt->query->consulta($consulta,__METHOD__);
  $num=$fmt->query->num_registros($rs);
  $fila=$fmt->query->obt_fila($rs);

  echo '<label class="title title-head">Editar documento</label>';
  echo      '</div><div class="block-form">
              <form class="form form-modulo form-multimedia" method="POST" id="form-editar">
              <input type="hidden" id="inputId" name="inputId" value="'.$fila["doc_id"].'">';
              $fmt->form->input_form("<span class='obligatorio'>*</span> Nombre documento:","inputNombre","",$fila['doc_nombre'],"","","");
              $fmt->form->input_form('Tags:','inputTags','',$fila['doc_tags'],'','','');
              $fmt->form->input_form('Ruta amigable:','inputRutaAmigable','',$fila['doc_ruta_amigable'],'');
              $fmt->form->textarea_form('Descripción:','inputDescripcion','',$fila['doc_descripcion'],'editor-texto','','3','');
              $fmt->form->input_form('Url:','inputUrl','',$fila['doc_url'],'');
              $fmt->form->input_form('Tipo archivo:','inputTipo','',$fila['doc_tipo_archivo'],'','','');

  echo        '</form>
            </div>
          </div>
        </div>
          <div class="footer-modal">
            <div class="bloque-botones pull-right">
              <a class="btn btn-full btn-cancelar-modal-doc">Cancelar</a>
              <a class="btn btn-primary btn-actualizar-modal-doc"><i class="icn icn-sync"/> Actualizar</a>
            </div>
          </div>
        </div>';
}else{
  $fila=$fmt->error->error_pag_no_encontrada();
}

?>
