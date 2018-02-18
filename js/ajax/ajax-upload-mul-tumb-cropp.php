<?php
  //if(_MULTIPLE_SITE=="on")
  $ruta_server=_RUTA_SERVER;
  /*else
  $ruta_server=_RUTA_HT;*/
  $output_dir = $ruta_server.$_POST["inputRutaArchivos"];

  if(isset($_POST["inputArchivosEdit"])){
  	$inputUrl=$_POST["inputArchivosEdit"];
  	$inputDominio = _RUTA_WEB;
  	$ruta_provisional = $inputDominio.$inputUrl;
  	$dato = pathinfo($ruta_provisional);
  	$inputThumbs = $_POST["inputThumb"];
  	$thumb_s= explode("x",$inputThumbs);
    $inputNombre = $fmt->get->convertir_url_amigable($dato["filename"]);
	$inputTipo = $dato["extension"];

	$size = filesize($ruta_server.$_POST["inputRutaArchivos"]."/".$dato["basename"]);
    $inputSize = $fmt->archivos->formato_size_archivo($size);
    $dimensiones = getimagesize($ruta_provisional);
	$width = $dimensiones[0];
    $height = $dimensiones[1];
    $dimension = $width." x ".$height;
	$img_crop=$fmt->archivos->convertir_url_thumb( $inputUrl );
	$imagen_png=$fmt->archivos->convertir_url_extension($img_crop,"png");
	 echo '<div class="contenedor-imagen">';
          echo "<img id='image-cropp' width='100%' src='".$inputDominio.$inputUrl."'>";
          echo '</div></br></br>';

          echo "</div>";
          echo '<div id="respuesta-thumb"><img src="'.$inputDominio.$imagen_png.'" width="350"></div>';
          echo '<div class="contenedor-button">';
          echo '<a class="ration btn btn-info" rt="1.7777777777777777"><font><font>16:9</font></font></a>';
          echo '<a class="ration btn btn-info" rt="1.3333333333333333"><font><font>4:3</font></font></a>';
          echo '<a class="ration btn btn-info" rt="1"><font><font>1:1</font></font></a>';
          echo '<a class="ration btn btn-info active" rt="none"><font><font>Libre</font></font></a>';
          echo '<a id="btn-reset-thumb" class="btn btn-warning"><font><font>Reset</font></font></a>';
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
      $var = array ('.jpg','.jpeg','.gif','.png','.mp3','.mp4','quicktime');
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
	  $inputThumbs = $_POST["inputThumb"];
      $thumb_s= explode("x",$inputThumbs);
      if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif' && $tipo != 'audio/mp3' && $tipo != 'video/mp4' && $tipo != 'audio/quicktime'){
        echo "Error, el archivo no es valido (jpg,jpeg,png,gif)";
      }else if ($size > 1024*1024*8){
        echo "Error, el tamaño máximo permitido es un 8MB";
      }else if ($width > 1900 || $height > 1900){
        echo "Error la anchura y la altura maxima permitida es 500px";
      }else if($width < 60 || $height < 60){
        echo "Error la anchura y la altura mínima permitida es 60px";
      }else{

      	$ext_arch=$fmt->archivos->saber_extension_archivo($nombre_url);
	  	$nombre_arch = $fmt->archivos->saber_nombre_archivo($nombre_url);
	  	$nombre_original = $nombre_arch."_original.".$ext_arch;

        move_uploaded_file($_FILES["inputArchivos"]["tmp_name"],$output_dir."/".$nombre_original);
        if($width>960)
        	$fmt->archivos->crear_thumb($ruta_server.$_POST["inputRutaArchivos"]."/".$nombre_original,$ruta_server.$_POST["inputRutaArchivos"].'/'.$nombre_url,"960","720",1);
        else
        	//move_uploaded_file($_FILES["inputArchivos"]["tmp_name"],$output_dir."/".$nombre_url);
        	$fmt->archivos->crear_thumb($ruta_server.$_POST["inputRutaArchivos"]."/".$nombre_original,$ruta_server.$_POST["inputRutaArchivos"].'/'.$nombre_url,$width,$height,1);

        $src = $_POST["inputRutaArchivos"]."/".$nombre_original;
        $nombre_t=$fmt->archivos->convertir_nombre_thumb($nombre_url);
		//echo $ruta_server.$_POST["inputRutaArchivos"].'/mini-'.$nombre_t;
        $fmt->archivos->crear_thumb($ruta_server.$src,$ruta_server.$_POST["inputRutaArchivos"].'/mini-'.$nombre_t,"100","100",1);
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
          echo "<img id='image-cropp' width='100%' src='".$inputDominio.$inputUrl."'>";
          echo '</div></br></br>';

          echo "</div>";
          echo '<div id="respuesta-thumb"></div>';
          echo '<div class="contenedor-button">';

          echo '<a class="ration btn btn-info" rt="1.7777777777777777"><font><font>16:9</font></font></a>';
          echo '<a class="ration btn btn-info" rt="1.3333333333333333"><font><font>4:3</font></font></a>';
          echo '<a class="ration btn btn-info" rt="1"><font><font>1:1</font></font></a>';
          echo '<a class="ration btn btn-info active" rt="none"><font><font>Libre</font></font></a>';
          echo '<a id="btn-reset-thumb" class="btn btn-warning"><font><font>Reset</font></font></a>';
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
    }else{ // varios archivos
      $ret = array();
      $ret = $_FILES["inputArchivos"]["name"];
      $num = count($ret);
    }
  }

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
			.contenedor-button a {
			    margin-left: 5px;
			}
			#respuesta-thumb {
			    text-align: center;
			    margin-bottom: 10px;
			}
          </style>
          <script type="text/javascript" language="javascript" src="<?php echo _RUTA_WEB_SERVER; ?>nucleo/js/cropper.min.js"></script>
          <link rel="stylesheet" href="<?php echo _RUTA_WEB_SERVER;?>nucleo/css/cropper.min.css" rel="stylesheet" type="text/css">
            <script>

            $(document).ready(function () {
            	var result;
              	result=$('#image-cropp').cropper();
			  	$("#btn-reset-thumb").click(function(){
					 result=$('#image-cropp').cropper('destroy').cropper("reset");
				});
				$(".ration").click(function(){
					$(".ration").removeClass("active");
					var rt = $(this).attr("rt");
					if(rt=="none")
						result=$('#image-cropp').cropper('destroy').cropper();
					else{
						result=$('#image-cropp').cropper('destroy').cropper({
							aspectRatio: rt,
						});
					}
					$(this).addClass("active");
				});
              $("#btn-save-thumb").click(function(){
	          		$('#image-cropp').cropper("getCroppedCanvas"<?php if($inputThumbs!=""){ ?>, { width: <?php echo $thumb_s[0]; ?>, height: <?php echo $thumb_s[1]; ?> }<?php } ?>).toBlob(function (blob) {
	           			 blobURL = URL.createObjectURL(blob);
	           			 $("#ImagePrev").attr("src",blobURL);
	           			 $("#ImagenPreview").modal("show");
				    });

			   });
            });
            </script>
