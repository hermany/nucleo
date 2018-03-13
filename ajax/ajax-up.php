<?php
  $cant =  $_POST["cant_file"];
  $tipo =   $_POST["tipo"];

  // echo $cant.":".$tipo.":".$seleccion;
  $retorno ='';

  for ($i=0; $i < $cant; $i++) {

    $file = $_FILES["file-".$i];
    $nombre = strtolower ( $file["name"] );
    $nombre_url= $fmt->get->convertir_url_amigable($nombre);
    $extension = $fmt->archivos->saber_extension_archivo($nombre);
    $var = array ('.pdf','.doc','.docx','.xls','.xlsx','.ppt','.pptx');
    $inputNombre = str_replace($var,'', $nombre);
    $ruta_provisional = $file["tmp_name"];
    $ruta_amigable = $fmt->get->convertir_url_amigable( $fmt->archivos->saber_nombre_archivo($nombre) );
    $size = $file["size"];
    $tipo_file = $file["type"];
    $inputSize = $fmt->archivos->formato_size_archivo($size);
    $usuario = $fmt->sesion->get_variable('usu_id');

    if(isset($file)){
      $error = $_FILES["file-".$i]["error"];
      if(!is_array($nombre)){
         if ($tipo=="documentos"){
            $output_dir = _RUTA_HOST."archivos/docs/";
            if ($tipo_file != 'application/pdf' && $tipo_file != 'application/msword' && $tipo_file != 'application/vnd.ms-excel' && $tipo_file != 'application/vnd.ms-powerpoint' && $tipo_file!='application/vnd.openxmlformats-officedocument.wordprocessingml.document' && $tipo_file!='application/vnd.openxmlformats-officedocument.presentationml.presentation' && $tipo_file!='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ){
            echo "error: [$tipo_file], el archivo no es valido (pdf,doc,docx,xls,xlsx,ppt,pptx)";
            }else if ($size > 1024*1024*100){
            echo "error, el tamaño máximo permitido es un 100MB";
            }else{
              chmod($output_dir, 0755);

              if ($fmt->archivos->existe_archivo($output_dir.$nombre_url)){
                $nombre_url= $fmt->archivos->url_add($nombre_url,"-1");
                $inputNombre = $inputNombre."-1";
                $ruta_amigable = $ruta_amigable."-1";
              }

              move_uploaded_file($_FILES["file-".$i]["tmp_name"],$output_dir.$nombre_url);
              $inputUrl= "archivos/docs/".$nombre_url;

              $ingresar ="doc_nombre,doc_ruta_amigable,doc_descripcion,doc_url,doc_imagen,doc_tipo_archivo,doc_tamano,doc_tags,doc_fecha,doc_usuario,doc_orden,doc_activar";

              $fecha_hoy= $fmt->class_modulo->fecha_hoy("America/La_Paz");
              $activar =1;

              $valores  ="'".$inputNombre."','".
                             $ruta_amigable."','','".
                             $inputUrl."','','".
                             $extension."','".
                             $size ."','','".
                             $fecha_hoy."','".
                             $usuario."','".
                             $i."','".
                             $activar."'";

              $sql="insert into documento (".$ingresar.") values (".$valores.")";
              $fmt->query->consulta($sql,__METHOD__);

              $sql="select max(doc_id) as id from documento";
              $rs= $fmt->query->consulta($sql);
              $fila = $fmt->query->obt_fila($rs);
              $id = $fila ["id"];

              if ($extension=="xls" || $extension=="xlsx" ){
                $icon="icn-excel";
              }

              if ($extension=="pdf"){
                $icon="icn-pdf";
              }

              if ($extension=="doc"|| $extension=="docx" ){
                $icon="icn-word";
              }

              if ($extension=="ppt" || $extension=="pptx"){
                $icon="icn-powerpoint";
              }

             $retorno .= $inputNombre.",".$extension.",".$id.",".$icon."&";

            }
         }         
         if ($tipo=="multimedia"){

         }         
         if ($tipo=="imagenes" or $tipo=="imagen"){

         }         
         if ($tipo=="audio" or $tipo=="audios"){

         }         
         if ($tipo=="video" or $tipo=="videos"){

         }
      }else{
        echo "error:".$error;
      }
    }else{
      echo "error";
    }
  }

  echo $tipo.":".$cant.":".$retorno;
?>