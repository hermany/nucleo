<?php
  //if(_MULTIPLE_SITE=="on")
  $ruta_server=_RUTA_IMAGES;
  /*else
  $ruta_server=_RUTA_HT;*/
  $output_dir = $ruta_server.$_POST["inputRutaArchivos"];

  if(isset($_POST["inputArchivosEdit"])){
  	$inputUrl=$_POST["inputArchivosEdit"];
  	$inputDominio = _RUTA_IMAGES;
  	$ruta_provisional = $inputDominio.$inputUrl;
  	$dato = pathinfo($ruta_provisional);
  	$inputThumbs = "150x150";
  	$thumb_s= explode("x",$inputThumbs);
    //$inputNombre = $fmt->get->convertir_url_amigable($dato["filename"]);
	  //$inputTipo = $dato["extension"];

	  //$size = filesize($ruta_server.$_POST["inputRutaArchivos"]."/".$dato["basename"]);
    //$inputSize = $fmt->archivos->formato_size_archivo($size);
    //$dimensiones = getimagesize($ruta_provisional);
	  $width = $dimensiones[0];
    $height = $dimensiones[1];
    $dimension = $width." x ".$height;
	  $img_crop=$fmt->archivos->convertir_url_thumb( $inputUrl );
    //echo _RUTA_HOST.$img_crop;
    if (!$fmt->archivos->existe_archivo(_RUTA_HOST.$img_crop)){
      $img_crop = "";
    }else{
      $img_cropp = '<img src="'.$inputDominio.$img_crop.'">';
    }
	  //$imagen_png=$fmt->archivos->convertir_url_extension($img_crop,"png");
	 echo '<div class="contenedor-imagen">';
          echo "<div id='prog_modal_cropp'></div>";
          echo "<img id='image-cropp' width='100%' src='".$inputDominio.$inputUrl."'>";
          echo '</div>';
          echo "</div>";
          echo '<div id="respuesta-thumb">'.$img_cropp.'</div>';
          echo '<div class="contenedor-button">';
          $consulta ="SELECT mul_conf_cropp FROM multimedia_conf";
          $rs =$fmt->query->consulta($consulta,__METHOD__);
          list($sizes_thumbs)=$fmt->query->obt_fila($rs);
          $thumbs = explode(",",$sizes_thumbs);
          $numt = count ($thumbs);
          if ($numt>0){
            for ($i=0; $i < $numt ; $i++) {
              $des = explode(":",$thumbs[$i]);
              $desx = explode("x",$des[0]);
              $w = $desx[0];
              $h = $desx[1];
              echo '<a class="btn btn-tb btn-full" w="'.$w.'" h="'.$h.'">'.$des[0].' <span>'.$des[1].'<span></a>';
            }
            $des = explode(":",$thumbs[0]);
            $desx = explode("x",$des[0]);
          }else{
            $desx[0]="100";
            $desx[1]="100";
          }


          echo '</br><a class="ration btn btn-info" rt="1.7777777777777777"> 16:9 </a>';
          echo '<a class="ration btn btn-info" rt="1.3333333333333333"> 4:3 </a>';
          echo '<a class="ration btn btn-info" rt="1"> 1:1 </a>';
          echo '<a class="ration btn btn-info active" rt="none"> Libre </a>';
          echo '<a id="btn-reset-thumb" class="btn btn-info"> Reset </a>';
          echo '<a id="btn-save-thumb" w="'.$desx[0].'" h="'.$desx[1].'" class="btn btn-success"> Guardar Thumb </a>';
           echo '</div>';
          // $fmt->form->input_form('<span class="obligatorio">*</span> Nombre archivo:','inputNombreArchivo','',$inputNombre,'','','En minúsculas');
          // $fmt->form->input_form('Url archivo:','inputUrl','',$inputUrl,'');
          // $fmt->form->input_form('Tipo archivo:','inputTipo','',$inputTipo,'');
          //
          // $fmt->form->input_form('Dimensión:','inputDimension','',$dimension,'','','');
          // $fmt->form->input_form('Tamaño:','inputTamano','',$inputSize,'','','');
          // $fmt->form->input_form('Dominio:','','',$inputDominio,'','','');
          // $fmt->form->input_hidden_form('inputDominio',$fmt->categoria->traer_id_cat_dominio($inputDominio));
  }
  else if(isset($_FILES["inputArchivos"])){
  }

?>
<style>
      .contenedor-imagen {
			    width: 100%;
			    position: relative;
			    margin:20px;
			    text-align: center;
          float: left;
			}
			.contenedor-button {
        width: 35%;
        position: absolute;
        text-align: right;
        /* float: left; */
        /* clear: right; */
        bottom: 60px;
        right: 20px;
        /* border: 1px solid #ccc; */
        padding: 10px;
			}
			.contenedor-button a {
			    margin: 4px;
			}
			#respuesta-thumb {
        text-align: center;
        margin-bottom: 10px;
        float: left;
        border: 1px solid #eee;
        display: inline-block;
        width: 250px;
        margin-top: 20px;
        color: #333;
        min-height: 200px;
			}

      #prog_modal_cropp{
        position: absolute;
        /*border: 1px solid red;*/
        top: 35px;
        right: 30px;
        width: 120px;
        height: 30px;
      }
  </style>

          <!-- <script type="text/javascript" language="javascript" src="<?php echo _RUTA_WEB_SERVER; ?>nucleo/js/cropper.min.js"></script>
          <link rel="stylesheet" href="<?php echo _RUTA_WEB_SERVER;?>nucleo/css/cropper.min.css" rel="stylesheet" type="text/css"> -->
<script type="text/javascript" language="javascript">
$(document).ready(function () {
          var result;
          DefaultCropBoxOptionObj = {
            width: <?php echo $desx[0]; ?>,
            height:  <?php echo $desx[1]; ?>,
          };
          result=$('#image-cropp').cropper({
            built: function() {
              $(this).cropper('setCropBoxData', DefaultCropBoxOptionObj);
            }
          });


          $(".btn-tb").click(function(){
            var wx = $(this).attr('w');
            var hx = $(this).attr('h');
            $("#btn-save-thumb").attr('w', wx);
            $("#btn-save-thumb").attr('h', hx);

            result=$('#image-cropp').cropper('destroy').cropper({
              built: function() {
                $(this).cropper('setCropBoxData',{ width: + wx,height: + hx });
              }
            });
          });


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
          var wx = $(this).attr('w');
          var hx = $(this).attr('h');
          result=$('#image-cropp').cropper('getCroppedCanvas', { width: + wx,height: + hx }).toBlob(function (blob) {
            var formData = new FormData();
            var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
            formData.append('croppedImage', blob);
            formData.append('dir', '<?php echo $inputUrl; ?>');
			   		//formData.append('nombre', nombre);
			   		//formData.append('ext', ext);
			   		formData.append("ajax","ajax-save-cropp");
            $.ajax(ruta, {
               method: "POST",
               data: formData,
               async: true,
               processData: false,
               contentType: false,
               xhr: function() {
               var xhr = $.ajaxSettings.xhr();
               xhr.upload.onprogress = function(e) {
                 var dat = Math.floor(e.loaded / e.total *100);
                 //console.log(Math.floor(e.loaded / e.total *100) + '%');
                 $("#prog_modal_cropp").html('<div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'+ dat +'" aria-valuemin="0" aria-valuemax="100" style="width: '+ dat +'%;">'+ dat +'%</div></div>');
               };
               return xhr;
             },
               success: function (data) {
                 //console.log(data);
                 $("#respuesta-thumb").html('<p class="text-success">El thumb se guardo correctamente.</p><img src="<?php echo _RUTA_WEB; ?>'+data+'" width="100%">');
                 $("#ImagenPreview").modal("hide");
                 var explode_2 = function(){
             			$("#respuesta-thumb .text-success").remove();
             		}
             		setTimeout(explode_2, 800);
               },
               error: function (data) {
                   console.log(data);
               }
           });
			   });
      });
});
</script>
