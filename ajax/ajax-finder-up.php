<?php
  //var_dump($_POST);
  $output_dir = _RUTA_HOST."archivos/multimedia/";
  //echo "f:".$_POST["ito"];
  $cant =  $_POST["cant_file"];

  for ($i=0; $i < $cant; $i++) {

    $file = $_FILES["file-".$i];
    $nombre = strtolower ( $file["name"] );
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

    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif' && $tipo != 'audio/mp3' && $tipo != 'video/mp4'){
      echo "<span class='error'>Error, el archivo no es valido (jpg,jpeg,png,gif)</span>";
    }else if ($size > 1024*1024*100){
      echo "<span class='error'>Error, el tamaño máximo permitido es un 100MB</span>";
    }else if ($width > 10000 || $height > 10000){
      echo "<span class='error'>Error la anchura y la altura maxima permitida es 10000px</span>";
    }else if( (($tipo != 'audio/mp3') && ($width < 10)) || (($tipo != 'audio/mp3') &&($height < 10)) ){
      echo "<span class='error'>Error la anchura y la altura mínima permitida es 10px</span>";
    }else{
      //echo "sin errores";
      //echo $output_dir.$nombre_url;
      chmod($output_dir, 0755);
      move_uploaded_file($_FILES["file-".$i]["tmp_name"],$output_dir.$nombre_url);

      if ($tipo == 'image/jpg' || $tipo == 'image/jpeg' || $tipo == 'image/png' || $tipo == 'image/gif'){
        //Crea mini-thumb.
       //$nombre_t=$fmt->archivos->convertir_url_mini($nombre_url);
        
        $nombre_thumb=$fmt->archivos->url_add($nombre_url,"-thumb");
        $nombre_mini=$fmt->archivos->url_add($nombre_url,"-mini");
        $nombre_medium=$fmt->archivos->url_add($nombre_url,"-medium");
        $nombre_web=$fmt->archivos->url_add($nombre_url,"-web");
        //$nombre_tb=$fmt->archivos->convertir_url_thumb($nombre_url);

        $src =  $output_dir.$nombre_url;

        if (($width > 60) && ( $height > 60)){
          $fmt->archivos->crear_thumb($src,$output_dir.$nombre_mini,60,60,0);
        }else{
          $fmt->archivos->crear_thumb($src,$output_dir.$nombre_mini,$width,$height,0);
        }

        if (($width > 180) && ( $height > 180)){
          $fmt->archivos->crear_thumb($src,$output_dir.$nombre_thumb,180,180,0);
        }else{
          $fmt->archivos->crear_thumb($src,$output_dir.$nombre_thumb,$width,$height,0);
        }
        if (($width > 900) || ( $height > 600 )){
          $fmt->archivos->crear_thumb($src,$output_dir.$nombre_medium,450,450,0);
        }else{
          $fmt->archivos->crear_thumb($src,$output_dir.$nombre_medium,$width,$height,0);
        }

        if (($width > 900) || ( $height > 600 )){
          $fmt->archivos->crear_thumb($src,$output_dir.$nombre_web,900,600,0);
        }else{
          $fmt->archivos->crear_thumb($src,$output_dir.$nombre_web,$width,$height,0);
        }


        // if (($width > 250) && ( $height > 250)){
        //   $fmt->archivos->crear_thumb($src,$output_dir.$nombre_medium,450,450,0);

        //   if (($width > 900) || ( $height > 600 )){
        //     $fmt->archivos->crear_thumb($src,$output_dir.$nombre_web,900,600,0);
        //   }else{
        //     $fmt->archivos->crear_thumb($src,$output_dir.$nombre_web,$width,$height,0);
        //   }
        // }else{
        //   $fmt->archivos->crear_thumb($src,$output_dir.$nombre_web,$width,$height,0);
        //   $fmt->archivos->crear_thumb($src,$output_dir.$nombre_medium,$width,$height,0);
        // }
      }
      if ($tipo == 'video/mp4'){
         $ffmpeg = "/usr/bin/ffmpeg";
         $videoFile = $output_dir.$nombre_url;
         $nombre_arc=$fmt->archivos->saber_nombre_archivo($nombre_url);

         $magenfile = $output_dir . $nombre_arc . ".jpg";
         $imagenFile= $fmt->archivos->convertir_nombre_thumb($magenfile);
         $size = "200x200";
         $second = 5;
         $cmd = "$ffmpeg -i $videoFile -an -ss $second -s $size $imagenFile";
         shell_exec($cmd);
      }
      $inputUrl= "archivos/multimedia/".$nombre_url;
      $url_mini ="archivos/multimedia/".$nombre_thumb;

      $usuario = $fmt->sesion->get_variable('usu_id');
      //$src, $dst, $width, $height, $crop=0



      $activar=1;

      $url_x ="archivos/multimedia/".$nombre_url;
      //
      $ingresar ="mul_nombre,mul_url_archivo,mul_ruta_amigable,mul_tipo_archivo,mul_dimension,mul_tamano,mul_fecha,mul_usuario,mul_activar";

      $fecha_hoy= $fmt->class_modulo->fecha_hoy("America/La_Paz");

      $valores  ="'".$inputNombre."','".
                     $url_x."','".
                     $nombre_url."','".
                     $inputTipo."','".
                     $dimension."','".
                     $size ."','".
                     $fecha_hoy."','".
                     $usuario."','".
                     $activar."'";

      $sql="insert into multimedia (".$ingresar.") values (".$valores.")";
      $fmt->query->consulta($sql,__METHOD__);

      $sql="select max(mul_id) as id from multimedia";
			$rs= $fmt->query->consulta($sql);
			$fila = $fmt->query->obt_fila($rs);
			$id = $fila ["id"];

      $nom= $fmt->class_modulo->recortar_texto($fila_nombre,"8")."...";

      if ($tipo == 'image/jpg' || $tipo == 'image/jpeg' || $tipo == 'image/png' || $tipo == 'image/gif'){
        //echo  "archivos/multimedia/".$nombre_url.":".$i.":archivos/multimedia/".$nombre_t.":".$inputTipo.":".$inputNombre.",";
        echo "<li class='finder-item' title='$inputNombre' tipo_item='$inputItem' id='mul-$id' url_mini='$url_mini' url='$inputUrl' style='background:url("._RUTA_IMAGES.$inputUrl.")no-repeat; background-size:cover'><span class='nombre' title='$inputNombre'>$nom</span></li>";
      }
      if ($tipo =='video/mp4'){
        echo "<li class='finder-item item-video' title='$inputNombre' tipo_item='$inputItem' id='mul-$id' url_mini='$url_mini' url='$inputUrl' style='background:url("._RUTA_WEB_NUCLEO."images/video-icon.png)no-repeat; background-size:cover'><video muted controls src='"._RUTA_IMAGES.$inputUrl."' ></video><span class='nombre' title='$inputNombre'>$nom</span></li>";
      }
      if ($tipo =='audio/mp3'){
        echo "<li class='finder-item item-audio' title='$inputNombre' tipo_item='$inputItem' id='mul-$id' url_mini='' url='' style='background:url("._RUTA_WEB_NUCLEO."images/audio-icon.png)no-repeat; background-size:cover'><audio controls><source src='"._RUTA_IMAGES.$inputUrl."' type='audio/mpeg'></audio><span class='nombre' title='$inputNombre'>$nom</span></li>";
      }
    }
  }
?>
