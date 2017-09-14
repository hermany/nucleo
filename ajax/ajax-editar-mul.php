<?php

$ruta_mul= $_POST["inputRutaMul"];
$id_item = $_POST["inputItem"];
$tipo_item = $_POST["inputTipoItem"];

$consulta = "SELECT * FROM multimedia WHERE mul_id='$id_item'";
$rs =$fmt->query->consulta($consulta,__METHOD__);
$num=$fmt->query->num_registros($rs);
$fila=$fmt->query->obt_fila($rs);
$imgx = _RUTA_IMAGES."archivos/multimedia/".$fmt->archivos->url_add($fila['mul_url_archivo'],"-web");

if (!$fmt->archivos->existe_archivo($ruta_archivo)){
  $imgx = _RUTA_IMAGES.$fila['mul_url_archivo'];
}

$ext =$fmt->archivos->saber_extension_archivo($ruta_mul);
if (( $ext=="jpg" || $ext="jpge" || $ext="gif" || $ext="png" ) && ( $tipo_item!="video-unico") ){
  $botones_ext ='<a class="category" id="tab-cropp" idtab="cropp"><i class="icn icn-crop"></i>Cropp</a>';
}
  echo '<div class="group-tabs">
          <div class="tab">
            <label class="title">EDITAR MULTIMEDIA</label>
            <span class="group">
              <a class="category active" id="tab-dg" idtab="dg"><i class="icn icn-order"></i> Datos generales</a>
              '.$botones_ext.'
            </span>
          </div>
        </div>
        <div class="body-modal">
          <div class="tab-content tab-content-modal-editar-mul on" id="content-dg">
            <div class="block-img">';
              $tt =$fila["mul_tipo_archivo"];
              if ( $tt=="jpg" || $tt="jpge" || $tt="gif" || $tt="png" ){
                echo '<div class="image" style="background:url('.$imgx.')no-repeat center center"></div><div class="preloader"></div>';
              }
              if ( $tt=='embed'){
              echo '<div class="box-video">'.$fila["mul_embed"].'</div><div class="preloader"></div>';
              }
              if ( $tt=='mp4'){
              echo '<div class="box-video"><video controls>
            <source src="'.$imgx.'" type="video/mp4"></video></div><div class="preloader"></div>';
              }

  echo      '</div><div class="block-form">
              <form class="form form-modulo form-multimedia" method="POST" id="form-editar">
              		<input type="hidden" id="inputId" name="inputId" value="1">';
  $fmt->form->input_form("<span class='obligatorio'>*</span> Nombre archivo:","inputNombre","",$fila['mul_nombre'],"","","En minúsculas");
  $fmt->form->input_form('Url archivo:','inputUrl','',$fila['mul_url_archivo'],'');
  $fmt->form->input_form('Tipo archivo:','inputTipo','',$fila['mul_tipo_archivo'],'');
  $fmt->form->input_form('Dimensión:','inputDimension','',$fila['mul_dimension'],'','','');
  $fmt->form->input_form('Tamaño:','inputTamano','',$fila['mul_tamano'],'','','');
  $fmt->form->input_form('Leyenda:','inputLeyenda','',$fila['mul_leyenda'],'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

  $fmt->form->input_form('Texto Alternativo:','inputTextoalternativo','',$fila['mul_texto_alternativo'],'','','');

  $fmt->form->textarea_form('Descripción:','inputDescripcion','',$fila['mul_descripcion'],'editor-texto','','3','');

  $cats_id = $fmt->categoria->traer_rel_cat_id($fila['mul_id'],'multimedia_categorias','mul_cat_cat_id','mul_cat_mul_id');

  $fmt->form->textarea_form('Embed:','inputEmbed','',$fila['mul_embed'],'','','3','');

  $fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"","");
  //$label,$id,$cat_raiz,$cat_valor,$class,$class_div

  echo        '</form>
            </div>
          </div>
          <div class="tab-content tab-content-modal-editar-mul" id="content-cropp">
            <form class="form form-editar-cropp" method="POST" id="form-editar-cropp">
              <div id="imagen-cropp"></div>
            </form>
          </div>
        </div>
          <div class="footer-modal">
            <div class="bloque-botones pull-right">
              <a class="btn btn-full btn-cancelar-modal">Cancelar</a>
              <a class="btn btn-primary btn-aceptar-modal">Aceptar</a>
            </div>
          </div>
        </div>';
?>
