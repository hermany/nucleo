<?php
  //var_dump($_POST);
  $output_dir = _RUTA_HOST."archivos/docs/";
  //echo "f:".$_POST["ito"];
  $cant =  $_POST["cant_file"];

  for ($i=0; $i < $cant; $i++) {

    $file = $_FILES["file-".$i];
    $nombre = strtolower ( $file["name"] );
    $nombre_url= $fmt->get->convertir_url_amigable($nombre);

    $var = array ('.pdf','.doc','.docx','.xls','.xlsx','.ppt','.pptx');
    $inputNombre = str_replace($var,'', $nombre);
    $tipo = $file["type"];
    $inputTipo = $fmt->archivos->saber_extension_archivo($nombre);
    $ruta_amigable = $fmt->get->convertir_url_amigable( $fmt->archivos->saber_nombre_archivo($nombre) );

    $ruta_provisional = $file["tmp_name"];
    $size = $file["size"];
    $inputSize = $fmt->archivos->formato_size_archivo($size);
    //$dimensiones = getimagesize($ruta_provisional);
    //$width = $dimensiones[0];
    //$height = $dimensiones[1];
    //$dimension = $width." x ".$height;

    if ($tipo != 'application/pdf' && $tipo != 'application/msword' && $tipo != 'application/vnd.ms-excel' && $tipo != 'application/vnd.ms-powerpoint' && $tipo!='application/vnd.openxmlformats-officedocument.wordprocessingml.document' && $tipo!='application/vnd.openxmlformats-officedocument.presentationml.presentation' && $tipo!='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ){
      echo "<span class='error'>Error [$tipo], el archivo no es valido (pdf,doc,docx,xls,xlsx,ppt,pptx)</span>";
    }else if ($size > 1024*1024*100){
      echo "<span class='error'>Error, el tamaño máximo permitido es un 100MB</span>";
    }else{
      //echo "sin errores";
      //echo $output_dir.$nombre_url;
      chmod($output_dir, 0755);
      move_uploaded_file($_FILES["file-".$i]["tmp_name"],$output_dir.$nombre_url);


      $inputUrl= "archivos/docs/".$nombre_url;

      $usuario = $fmt->sesion->get_variable('usu_id');
      //$src, $dst, $width, $height, $crop=0



      $activar=1;

      $url_x ="archivos/docs/".$nombre_url;
      //
      $ingresar ="doc_nombre,doc_ruta_amigable,doc_descripcion,doc_url,doc_imagen,doc_tipo_archivo,doc_tamano,doc_tags,doc_fecha,doc_usuario,doc_orden,doc_activar";

      $fecha_hoy= $fmt->class_modulo->fecha_hoy("America/La_Paz");

      $valores  ="'".$inputNombre."','".
                     $ruta_amigable."','','".
                     $inputUrl."','','".
                     $inputTipo."','".
                     $size ."','','".
                     $fecha_hoy."','".
                     $usuario."','0','".
                     $activar."'";

      $sql="insert into documento (".$ingresar.") values (".$valores.")";
      $fmt->query->consulta($sql,__METHOD__);

      $sql="select max(doc_id) as id from documento";
			$rs= $fmt->query->consulta($sql);
			$fila = $fmt->query->obt_fila($rs);
			$id = $fila ["id"];

      // $nom= $fmt->class_modulo->recortar_texto($fila_nombre,"8")."...";

      if ($inputTipo=="xls" || $inputTipo=="xlsx" ){
        $icon="icn-excel";
      }


      if ($inputTipo=="pdf"){
        $icon="icn-pdf";
      }


      if ($inputTipo=="doc"|| $inputTipo=="docx" ){
        $icon="icn-word";
      }


      if ($inputTipo=="ppt" || $inputTipo=="pptx"){
        $icon="icn-powerpoint";
      }


      $nom= $inputNombre." (".$inputTipo.")";
      echo "<li class='finder-item item-doc' item='".$id."' tipo_item='".$inputTipo."' id='item-s-$i' url=' '  url_mini=' ' style='' ><i class='icn $icon'></i><span class='nombre' title='$inputNombre'>".$nom."</span><span class='etiquetas'>".$tags."</span></li>";

    }
  }
?>
