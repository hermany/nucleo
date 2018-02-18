<?php

  //if(_MULTIPLE_SITE=="on")
  $ruta_server=_RUTA_SERVER;
  /*else
  $ruta_server=_RUTA_HT;*/

  $output_dir =  $ruta_server.$_POST["inputRutaArchivosDocs"];
  if(isset($_FILES["inputArchivosDocs"])){
    $error = $_FILES["inputArchivosDocs"]["error"];
    if(!is_array($_FILES["inputArchivosDocs"]["name"])){ //un solo archivo
      $file = $_FILES["inputArchivosDocs"];
      $nombre = strtolower ( $file["name"]);
      $nombre_url= $fmt->get->convertir_url_amigable($nombre);
      $extension = $fmt->archivos->saber_extension_archivo($nombre);
      $inputNombre = str_replace(".".$extension,"",$nombre);
      $ruta_provisional = $file["tmp_name"];
      $size = $file["size"];

      $inputSize = $fmt->archivos->formato_size_archivo($size);

      $pdf_tipo="application/pdf";
      $zip="application/zip";
      $doc_tipo="application/msword";
      $xls_tipo="application/octet-stream";
      //$ppt_tipo="application/vnd.ms-powerpoint ppt";
      $docx_tipo="application/vnd.openxmlformats-officedocument.wordprocessingml.document";
      $pptx_tipo="application/vnd.openxmlformats-officedocument.presentationml.presentation";
      $xlsx_tipo ="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
      $inputUrl= $_POST["inputRutaArchivosDocs"]."/".$nombre_url;

      if ( ($tipo != $pdf_tipo) && ($tipo != $xlsx_tipo) && ($tipo != $zip_tipo) && ($tipo != $docx_tipo) && ($tipo !=   $doc_tipo) && ($tipo != $xls_tipo) && ($tipo != $doc_tipo) && ($tipo!=$xls_tipo) && ($tipo!=$pptx_tipo)){
        echo "Error, el archivo no es valido (pdf,zip,doc/x,pptx,xls/s)";
      }else if ($size > 1024*1024*11){
        echo "Error, el tamaño máximo permitido es un 11MB";
      }else{
        move_uploaded_file($_FILES["inputArchivosDocs"]["tmp_name"],$output_dir."/".$nombre_url);
        $ruta_v = explode ("/",$inputUrl);
        $inputDominio = _RUTA_WEB;

        if ( $ruta_v[1]=="sitios"){
          $c = strlen ($ruta_v[0] );
          $inputUrl = substr($inputUrl, $c +1 );
          $inputDominio = $fmt->categoria->traer_dominio_cat_ruta($ruta_v[1]."/".$ruta_v[0]);
        }

        $usuario = $fmt->sesion->get_variable('usu_id');
        $usuario_n = $fmt->usuario->nombre_usuario($usuario);

        if (!isset($_POST["inputId"])){
          echo '<div><i class="icn-checkmark-circle color-text-verde" /> Archivo subido satisfactoriamente.</div><br/>';
          $fmt->form->input_form('<span class="obligatorio">*</span> Nombre archivo:','inputNombreDoc','',$inputNombre,'','','');
          $fmt->form->input_form('Tags:','inputTags','','','');
          $fmt->form->textarea_form('Descripción:','inputDescripcionDoc','','','','3',''); //$label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje
          $fmt->form->input_form('Nombre amigable:','inputNombreAmigableDoc','',$nombre_url,'','','');
          $fmt->form->input_form('Url archivo:','inputUrlDoc','',$inputUrl,'');
          $fmt->form->input_form('Tipo de Archivo:','inputTipoDoc','',$extension,'');
          $fmt->form->input_form('Imagen:','inputImagenDoc','','','','','');
          $fmt->form->input_form('Tamaño:','inputTamanoDoc','',$inputSize,'','','');
          $fmt->form->input_form('Dominio:','','',$inputDominio,'','','');
          $fmt->form->input_hidden_form('inputDominioDoc',$fmt->categoria->traer_id_cat_dominio($inputDominio));
        }else{
          $url =$inputUrl;
          $rt .= "editar";
          $rt .= ','.$url;
          $rt .= ',inputNombre^'.$inputNombre;
          $rt .= ',inputUrl^'.$url;
          $rt .= ',inputTipo^'.$extension;
          $rt .= ',inputNombreAmigable^'.$nombre_url;
          $rt .= ',inputTamano^'.$inputSize;
          $rt .= ',inputDominio^'.$inputDominio;
          $rt .= ',inputNombreusuario^'.$usuario_n;
          $rt .= ',inputUsuario^'.$usuario;
          //echo $rt;
        }
      }

    }

  }
?>
