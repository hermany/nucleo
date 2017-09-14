<?php

  $output_dir = _RUTA_HOST."archivos/multimedia/";

  if($_POST["cant_file"]==1){
    $file = $_FILES["file-0"];
    $nombre = strtolower ( $file["name"]);
    $nombre_url= $fmt->get->convertir_url_amigable($nombre);

    $var = array ('.jpg','.gif','.png','.mp3','.mp4','quicktime');
    $inputNombre = str_replace($var,'',$fmt->get->convertir_url_amigable($nombre));
    $tipo = $file["type"];
    $var_tipo = array ('image/','audio/','video/');
    $inputTipo = str_replace($var_tipo,'',$tipo);
    $ruta_provisional = $file["tmp_name"];
    $size = $file["size"];
    $inputSize = $fmt->archivos->formato_size_archivo($size);
    $dimensiones = getimagesize($ruta_provisional);
    $width = $dimensiones[0];
    $height = $dimensiones[1];
    $dimension = $width." x ".$height;

    if ( $tipo == 'video/mp4'){
      $width = 100;
      $height = 100;
    }

    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif' && $tipo != 'audio/mp3' && $tipo != 'video/mp4' && $tipo != 'audio/quicktime'){
      echo "<span class='error'>Error, el archivo no es valido (jpg,jpeg,png,gif)</span>";
    }else if ($size > 1024*1024*100){
      echo "<span class='error'>Error, el tamaño máximo permitido es un 100MB</span>";
    }else if ($width > 10000 || $height > 10000){
      echo "<span class='error'>Error la anchura y la altura maxima permitida es 10000px</span>";
    }else if($width < 60 || $height < 60){
      echo "<span class='error'>Error la anchura y la altura mínima permitida es 100px</span>";
    }else{
      //echo "sin errores";

      //Sube archivo
      move_uploaded_file($_FILES["file-0"]["tmp_name"],$output_dir.$nombre_url);

      if ($tipo == 'image/jpg' || $tipo == 'image/jpeg' || $tipo == 'image/png' || $tipo == 'image/gif'){
        //Crea mini-thumb.
        $nombre_t=$fmt->archivos->convertir_url_mini($nombre_url);
        $nombre_w=$fmt->archivos->url_add($nombre_url,"-web");
        $nombre_wx=$fmt->archivos->url_add($nombre_url,"-thumb");
        $nombre_tm=$fmt->archivos->url_add($nombre_url,"-mini");
        //$nombre_tb=$fmt->archivos->convertir_url_thumb($nombre_url);

        $src =  $output_dir.$nombre_url;

        $fmt->archivos->crear_thumb($src,$output_dir.$nombre_t,100,100,1);
        $fmt->archivos->crear_thumb($src,$output_dir.$nombre_tm,180,180,0);

        if (($width > 250) && ( $height > 250)){
          $fmt->archivos->crear_thumb($src,$output_dir.$nombre_wx,250,250,0);
          if (($width > 900) || ( $height > 600 )){
            $fmt->archivos->crear_thumb($src,$output_dir.$nombre_w,900,600,0);
          }
        }else{
          $fmt->archivos->crear_thumb($src,$output_dir.$nombre_wx,$width,$height,0);
          $fmt->archivos->crear_thumb($src,$output_dir.$nombre_w,$width,$height,0);
        }


      }


      $inputUrl= "archivos/multimedia/".$nombre_url;

      ?>
      <div class="box-image">
        <?php if ($tipo == 'image/jpg' || $tipo == 'image/jpeg' || $tipo == 'image/png'){ ?>
        <img class="img-file img-responsive" src="<?php echo _RUTA_WEB."archivos/multimedia/".$nombre_w; ?>" alt="img">
        <?php } ?>
        <?php if ($tipo == 'video/mp4'){ ?>
        <video src="<?php echo _RUTA_WEB."archivos/multimedia/".$nombre_url; ?>" preload="auto" autoplay="" controls="on" poster="<?php echo _RUTA_WEB_NUCLEO."images/video-icon.png";?>" style="" loop></video>
          <?php } ?>
      </div>
      <?php
      $fmt->form->input_form('<span class="obligatorio">*</span> Nombre archivo:','inputNombre','',$inputNombre,'','','En minúsculas');
      $fmt->form->input_form('Url archivo:','inputUrl','',$inputUrl,'');
      $fmt->form->input_form('Tipo archivo:','inputTipo','',$inputTipo,'');
      //$fmt->form->input_form('Leyenda:','inputLeyenda','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
      //$fmt->form->input_form('Texto Alternativo:','inputTextoalternativo','','','','','');
      //$fmt->form->textarea_form('Descripción:','inputDescripcion','','','','3',''); //$label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje
      $fmt->form->input_form('Dimensión:','inputDimension','',$dimension,'','','');
      $fmt->form->input_form('Tamaño:','inputTamano','',$inputSize,'','','');
      //$fmt->form->input_form('Dominio:','','',$inputDominio,'','','');
      //$fmt->form->input_hidden_form('inputDominio',$fmt->categoria->traer_id_cat_dominio($inputDominio));

    }
  }else{
    echo "num archivos:".$_POST["cant_file"];
  }

?>
