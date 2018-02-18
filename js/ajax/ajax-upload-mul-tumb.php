<?php

  //if(_MULTIPLE_SITE=="on")
  $ruta_server=_RUTA_SERVER;
 /* else
  $ruta_server=_RUTA_HT;*/

  $output_dir = $ruta_server.$_POST["inputRutaArchivos"];
  if(isset($_POST["inputArchivosEdit"])){
  	$inputUrl=$_POST["inputArchivosEdit"];
  	$inputDominio = _RUTA_WEB;
  	$ruta_provisional = $inputDominio.$inputUrl;
  	$dato = pathinfo($ruta_provisional);
  	$thumb_s= explode("x",$_POST["inputThumb"]);
    $inputNombre = $fmt->get->convertir_url_amigable($dato["filename"]);
	$inputTipo = $dato["extension"];

	$size = filesize($ruta_server.$_POST["inputRutaArchivos"]."/".$dato["basename"]);
    $inputSize = $fmt->archivos->formato_size_archivo($size);
    $dimensiones = getimagesize($ruta_provisional);
	$width = $dimensiones[0];
    $height = $dimensiones[1];
    $dimension = $width." x ".$height;

	 echo '<div class="contenedor-imagen">';
          echo "<img width='100%' src='".$inputDominio.$inputUrl."'>";
          echo '</div></br></br>';
          ?>
          <style>
          .contenedor-imagen {
			    width: 100%;
			    position: relative;
			    margin: 0 auto;
			    text-align: center;
			}
			.contenedor-button {
			    width: 100%;
			    position: relative;
			    text-align: right;
			}
          </style>
          <script type="text/javascript" language="javascript" src="<?php echo _RUTA_WEB_SERVER; ?>nucleo/js/croppie.js"></script>
          <link rel="stylesheet" href="<?php echo _RUTA_WEB_SERVER;?>nucleo/css/croppie.css" rel="stylesheet" type="text/css">
            <div class="demo"></div>
            <script>
            $(document).ready(function () {
              $('.demo').croppie({
                  url: '<?php echo $inputDominio.$inputUrl; ?>',
                  enableExif: true,
                  boundary: { width: 476, height: 476 },
                  viewport: {
                      width: <?php echo  $thumb_s[0] ?>,
                      height: <?php echo  $thumb_s[1] ?>,
                      type: 'square'
                  }
              });
              $("#btn-save-thumb").click(function(){

	           	guardar_thumb();

			   });
            });
            </script>
          <?php
          echo "</div>";
          echo '<div id="respuesta-thumb"></div>';
          echo '<div class="contenedor-button">';
          echo '<a id="btn-save-thumb" class="btn btn-warning"><font><font>Guardar Thumb </font></font></a>';
           echo '</div>';
          $fmt->form->input_form('<span class="obligatorio">*</span> Nombre archivo:','inputNombreArchivo','',$inputNombre,'','','En minúsculas');
          $fmt->form->input_form('Url archivo:','inputUrl','',$inputUrl,'');
          $fmt->form->input_form('Tipo archivo:','inputTipo','',$inputTipo,'');

          $fmt->form->input_form('Dimensión:','inputDimension','',$dimension,'','','');
          $fmt->form->input_form('Tamaño:','inputTamano','',$inputSize,'','','');
          $fmt->form->input_form('Dominio:','','',$inputDominio,'','','');
          $fmt->form->input_hidden_form('inputDominio',$fmt->categoria->traer_id_cat_dominio($inputDominio));
  }
  else if(isset($_FILES["inputArchivos"])){

    $error = $_FILES["inputArchivos"]["error"];

    if(!is_array($_FILES["inputArchivos"]["name"])){ //un archivo

      $file = $_FILES["inputArchivos"];
      $nombre = strtolower ( $file["name"]);
      $nombre_url= $fmt->get->convertir_url_amigable($nombre);
      $var = array ('.jpg','.gif','.png','.mp3','.mp4','quicktime');
      $inputNombre = str_replace($var,'',$fmt->get->convertir_url_amigable($nombre));
      $tipo = $file["type"];
      $var_tipo = array ('image/','audio/','video/');

      $ruta_provisional = $file["tmp_name"];
      $size = $file["size"];
      $inputSize = $fmt->archivos->formato_size_archivo($size);
      $dimensiones = getimagesize($ruta_provisional);
      $width = $dimensiones[0];
      $height = $dimensiones[1];
      $dimension = $width." x ".$height;

      $thumb_s= explode("x",$_POST["inputThumb"]);
      if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif' && $tipo != 'audio/mp3' && $tipo != 'video/mp4' && $tipo != 'audio/quicktime'){
        echo "Error, el archivo no es valido (jpg,jpeg,png,gif)";
      }else if ($size > 1024*1024*8){
        echo "Error, el tamaño máximo permitido es un 8MB";
      }else if ($width > 1900 || $height > 1900){
        echo "Error la anchura y la altura maxima permitida es 500px";
      }else if($width < 60 || $height < 60){
        echo "Error la anchura y la altura mínima permitida es 60px";
      }else{
        move_uploaded_file($_FILES["inputArchivos"]["tmp_name"],$output_dir."/".$nombre_url);
        $src = $_POST["inputRutaArchivos"]."/".$nombre_url;
        $nombre_t=$fmt->archivos->convertir_nombre_thumb($nombre_url);

        $fmt->archivos->crear_thumb($ruta_server.$src,$ruta_server.$_POST["inputRutaArchivos"].'/mini-'.$nombre_t,$thumb_s[0],$thumb_s[1],1);
        //$src, $dst, $width, $height, $crop=0
        $inputUrl= $_POST["inputRutaArchivos"]."/".$nombre_url;
        $inputTipo = $fmt->archivos->saber_extension_archivo($inputUrl);
        $ruta_v = explode ("/",$inputUrl);
        $inputDominio = _RUTA_WEB;

        if ( $ruta_v[1]=="sitios"){
          $c = strlen ($ruta_v[0] );
          $inputUrl = substr($inputUrl, $c +1 );
          $inputDominio = $fmt->categoria->traer_dominio_cat_ruta($ruta_v[1]."/".$ruta_v[0]);
        }

       // if (!isset($_POST["inputId"])){
          	echo '<div class="contenedor-imagen">';
          echo "<img width='100%' src='".$inputDominio.$inputUrl."'>";
          echo '</div></br></br>';
          ?>
          <style>
          .contenedor-imagen {
			    width: 100%;
			    position: relative;
			    margin: 0 auto;
			    text-align: center;
			}
			.contenedor-button {
			    width: 100%;
			    position: relative;
			    text-align: right;
			}
          </style>
            <div class="demo"></div>
            <script>
            $(document).ready(function () {
              $('.demo').croppie({
                  url: '<?php echo $inputDominio.$inputUrl; ?>',
                  enableExif: true,
                  boundary: { width: 476, height: 476 },
                  viewport: {
                      width: <?php echo  $thumb_s[0] ?>,
                      height: <?php echo  $thumb_s[1] ?>,
                      type: 'square'
                  }
              });
              $("#btn-save-thumb").click(function(){

	           	guardar_thumb();

			   });
            });
            </script>
          <?php
          echo "</div>";
          echo '<div id="respuesta-thumb"></div>';
          echo '<div class="contenedor-button">';
          echo '<a id="btn-save-thumb" class="btn btn-warning"><font><font>Guardar Thumb </font></font></a>';
           echo '</div>';
          $fmt->form->input_form('<span class="obligatorio">*</span> Nombre archivo:','inputNombreArchivo','',$inputNombre,'','','En minúsculas');
          $fmt->form->input_form('Url archivo:','inputUrl','',$inputUrl,'');
          $fmt->form->input_form('Tipo archivo:','inputTipo','',$inputTipo,'');

          $fmt->form->input_form('Dimensión:','inputDimension','',$dimension,'','','');
          $fmt->form->input_form('Tamaño:','inputTamano','',$inputSize,'','','');
          $fmt->form->input_form('Dominio:','','',$inputDominio,'','','');
          $fmt->form->input_hidden_form('inputDominio',$fmt->categoria->traer_id_cat_dominio($inputDominio));

       /* } else {

          $url =$inputUrl;
          $rt .= "editar";
          $rt .= ','.$url;
          $rt .= ',inputNombre^'.$inputNombre;
          $rt .= ',inputUrl^'.$url;
          $rt .= ',inputTipo^'.$inputTipo;
          $rt .= ',inputLeyenda^';
          $rt .= ',inputTextoalternativo^';
          $rt .= ',inputDescripcion^';
          $rt .= ',inputDimension^'.$dimension;
          $rt .= ',inputTamano^'.$inputSize;
          $rt .= ',inputDominio^'.$inputDominio;
          echo $rt;
        }
*/
      }
    }else{ // varios archivos
      $ret = array();
      $ret = $_FILES["inputArchivos"]["name"];
      $num = count($ret);
    }
  }

?>
