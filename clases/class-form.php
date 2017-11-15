<?PHP
header("Content-Type: text/html;charset=utf-8");

class FORM{

	var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

  function head_busqueda_simple($nom,$id_mod,$botones){
   $botones = $this->fmt->class_pagina->crear_btn_m($nom,"icn-plus",$nom,"btn btn-primary btn-menu-ajax",$id_mod,"form_nuevo");
    $this->fmt->class_pagina->crear_head( $id_mod, $botones); // bd, id modulo, botones
    ?>
    <div class="body-modulo">
    <?php
  }


  function head_editar($nom,$archivo,$id_mod,$botones,$id_form,$class){
    $botones = $this->fmt->class_pagina->crear_btn_m("volver","icn-chevron-left","Volver","btn btn-link  btn-volve btn-menu-ajax",$id_mod,"busqueda");
  	$this->fmt->class_pagina->crear_head_form($nom, $botones,"","",$id_mod);
		$nom_mod=  strtolower($this->fmt->class_modulo->nombre_modulo($id_mod));
    ?>
    <div class="body-modulo body-<?php echo $nom_mod; ?> col-xs-6 col-xs-offset-3 <?php echo $class; ?>">
      <form class="form form-modulo" action="<?php echo $archivo; ?>.adm.php?tarea=modificar&id_mod=<?php echo $id_mod; ?>"  enctype="multipart/form-data" method="POST" id="<?php echo $id_form; ?>">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
    <?php
  }

  function head_nuevo($nom,$archivo,$id_mod,$botones,$id_form,$class,$modo){
	 //echo "m:".$modo;
		if (empty($modo)){
	    $botones = $this->fmt->class_pagina->crear_btn_m("volver","icn-chevron-left","Volver","btn btn-link  btn-volve btn-menu-ajax",$id_mod,"busqueda");
	  	$action = "action='".$archivo.".adm.php?tarea=ingresar&id_mod=".$id_mod."'";
  		$mod="";
  	}else{
  		$mod="body-modal";
  		if (isset($_GET['id'])){
	  		$this->fmt->get->validar_get($_GET['id']);
	  		$id = "&id=".$_GET['id'];
  		}
  		$from =$_GET['from'];
  		$action = "action='".$archivo.".adm.php?tarea=ingresar&id_mod=".$id_mod."&modo=modal&from=".$from.$id."'";
  	}

  	$this->fmt->class_pagina->crear_head_form($nom, $botones,"","head-modal",$id_mod);
  	//echo $url;
		$nom_mod=  strtolower($this->fmt->class_modulo->nombre_modulo($id_mod));
    ?>
    <div class="body-modulo body-<?php echo $nom_mod; ?> col-xs-6 col-xs-offset-3 <?php echo $class; ?> <?php echo $mod; ?>">
      <form class="form form-modulo" <?php echo $action; ?>  enctype="multipart/form-data"  method="POST" id="<?php echo $id_form; ?>">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
    <?php
  }

  function head_modal($nom,$modo){
	 //echo "m:".$modo;
    ?>
    <div class="head-modulo head-m-<?php echo $modo; ?>">
		<h1 class="title-page pull-left"><i class=""></i> <?php echo $nom; ?></h1>
			<div class="head-botones pull-right">
				<a class="btn btn-actualizar-modal"><i class='icn-sync'></i> Actualizar</a>
			</div>
    </div>
    <div class="body-modal">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
    <?php
  }

  function hidden_modulo ($id_mod,$tarea){
	  ?>
	  		<input type="hidden" name="ajax" id="ajax" value="ajax-adm">
				<input type="hidden" name="inputIdMod" id="inputIdMod" value="<?php echo $id_mod; ?>">
				<input type="hidden" name="inputVars" id="inputVars" value="<?php echo $tarea; ?>">
	  <?php
  }

	function sizes_thumb($sizes){
		if (!empty($sizes)){
			$st = explode(",",$sizes);
			$c_st = count($st);
		}
		?>
		<select id="inputThumb" name="inputThumb" class="form-control">
			<?php

				for($i=0; $i < $c_st;$i++){
					$xst = explode(":",$st[$i]);
					echo "<option value='".$xst[0]."' >".$xst[0]."</option>";
				}
			?>
		</select>
		<?php
	}
	function finder($id,$id_mod,$url,$tipo_upload="individual",$tipo_archivo,$tipo){
		?>
		<link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/finder.css?reload" rel="stylesheet" type="text/css">
		<div class="modal-finder" >
			<div class="modal-finder-inner">
				<?php
				require_once(_RUTA_NUCLEO."modulos/finder/finder.adm.php");
				?>
			</div>
		</div>
		<!-- <script type="text/javascript" language="javascript" src="<?php echo _RUTA_WEB_NUCLEO; ?>nucleo/js/finder.js"></script> -->
		<?php
		$this->editar_multimedia();
	}

	function file_form_seleccion($nom,$ruta,$id_form,$class,$class_div,$id_div,$directorio_p,$sizethumb){
		if ($id_form == 'form-nuevo'){ $texto="para subir"; }else{ $texto="para reemplazar"; }
    ?>
    <div class="form-group <?php echo $class_div; ?>" id="<?php echo $id_div; ?>" >
      <label>Seleccionar ruta url <?php echo $texto; ?> : </label>
      <?php $this->fmt->archivos->select_archivos($ruta,$directorio_p); ?>
			<input type="hidden" value="<?php echo $sizethumb; ?>" id="inputThumb" name="inputThumb">

      <br/>
			<label><?php echo $nom; ?> :</label>
      <input type="file" ruta="<?php echo _RUTA_WEB; ?>" class="form-control <?php echo $class; ?>" id="inputArchivos" name="inputArchivos"  />
			<div id='prog'></div>
      <div id="respuesta"></div>
    </div>
    <?php
  }


	function file_form_nuevo($nom,$ruta,$id_form,$class,$class_div,$id_div,$directorio_p,$sizethumb){
  	//echo $ruta;
    ?>
    <div class="form-group <?php echo $class_div; ?>" id="<?php echo $id_div; ?>" >
      <label>Seleccionar ruta url para subir : </label>
      <?php $this->fmt->archivos->select_archivos($ruta,$directorio_p); ?>
			<br/><label>Seleccionar tamaño thumb (ancho x alto):</label>
			<?php $this->sizes_thumb($sizethumb); ?>
      <br/>
			<label><?php echo $nom; ?> :</label>
      <input type="file" ruta="<?php echo _RUTA_WEB; ?>" class="form-control <?php echo $class; ?>" id="inputArchivos" name="inputArchivos"  />
			<div id='prog'></div>
      <div id="respuesta"></div>
    </div>
		<script>
      $(function(){
        $(".<?php echo $class; ?>").on("change", function(){
        var formData = new FormData($("#<?php echo $id_form; ?>")[0]);
        var ruta = "<?php echo _RUTA_NUCLEO; ?>ajax/ajax-upload.php";
        $("#respuesta").toggleClass('respuesta-form');
        $.ajax({
            url: ruta,
            type: "POST",
            data: formData,
						async: true,
            contentType: false,
            processData: false,
						xhr: function() {
			        var xhr = $.ajaxSettings.xhr();
			        xhr.upload.onprogress = function(e) {
								var dat = Math.floor(e.loaded / e.total *100);
			          //console.log(Math.floor(e.loaded / e.total *100) + '%');
								$("#prog").html('<div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'+ dat +'" aria-valuemin="0" aria-valuemax="100" style="width: '+ dat +'%;">'+ dat +'%</div></div>');
			        };
			        return xhr;
				    },
            success: function(datos){
              	$("#respuesta").html(datos);
            }
          });
        });
      });
    </script>
    <?php
  }

	function file_form_nuevo_save_thumb($nom,$ruta,$id_form,$class,$class_div,$id_div,$directorio_p,$sizethumb,$imagen){
  	//echo $ruta;
    ?>
		<script type="text/javascript" language="javascript" src="<?php echo _RUTA_WEB_NUCLEO; ?>js/croppie.js"></script>
			<link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/croppie.css" rel="stylesheet" type="text/css">
    <div class="form-group <?php echo $class_div; ?>" id="<?php echo $id_div; ?>" >
      <label>Seleccionar ruta url para subir : </label>
      <?php $this->fmt->archivos->select_archivos($ruta,$directorio_p); ?>
			<br/><label>Seleccionar tamaño thumb (ancho x alto):</label>
			<?php $this->sizes_thumb($sizethumb); ?>
      <br/>
			<label><?php echo $nom; ?> :</label>
      <input type="file" ruta="<?php echo _RUTA_WEB; ?>" class="form-control <?php echo $class; ?>" id="inputArchivos" name="inputArchivos"  />
			<div id='prog'></div>
      <div id="respuesta"></div>
    </div>
		<script>
      $(function(){
        $(".<?php echo $class; ?>").on("change", function(){
        var formData = new FormData($("#<?php echo $id_form; ?>")[0]);
        formData.append("ajax","ajax-upload-mul-tumb");
        var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
        $("#respuesta").toggleClass('respuesta-form');
        $.ajax({
            url: ruta,
            type: "POST",
						async: true,
            data: formData,
            contentType: false,
            processData: false,
						xhr: function() {
			        var xhr = $.ajaxSettings.xhr();
			        xhr.upload.onprogress = function(e) {
								var dat = Math.floor(e.loaded / e.total *100);
			          //console.log(Math.floor(e.loaded / e.total *100) + '%');
								$("#prog").html('<div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'+ dat +'" aria-valuemin="0" aria-valuemax="100" style="width: '+ dat +'%;">'+ dat +'%</div></div>');
			        };
			        return xhr;
				    },
            success: function(datos){
              	$("#respuesta").html(datos);
            }
          });
        });

      });

	  function CargarCrop(){
		 var formData = new FormData($("#<?php echo $id_form; ?>")[0]);
		 formData.append("ajax","ajax-upload-mul-tumb");
        var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
        $("#respuesta").toggleClass('respuesta-form');
        $.ajax({
            url: ruta,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
						xhr: function() {
			        var xhr = $.ajaxSettings.xhr();
			        xhr.upload.onprogress = function(e) {
								var dat = Math.floor(e.loaded / e.total *100);
			          //console.log(Math.floor(e.loaded / e.total *100) + '%');
								$("#prog").html('<div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'+ dat +'" aria-valuemin="0" aria-valuemax="100" style="width: '+ dat +'%;">'+ dat +'%</div></div>');
			        };
			        return xhr;
				    },
            success: function(datos){
              	$("#respuesta").html(datos);
            }
          });
	  }


			function guardar_thumb(){
				size = 'viewport';
				$('.demo').croppie('result', {
				type: 'canvas',
				size: size
				}).then(function (resp) {

					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
					var ruta_url = $("#inputUrl").val();
					var nombre = $("#inputNombreArchivo").val();
					var ext = $("#inputTipo").val();

					datos = [{name: "imagen", value: resp},{name: "dir", value: ruta_url},{name: "nombre", value: nombre},{name: "ext", value: ext},{name:"ajax", value:"ajax-save-thumb-mul"}];
					$.ajax({
						url: ruta,
						type: 'post',
						async: true,
						data: datos,
						success: function(data) {
							$("#respuesta-thumb").html('<p class="text-success">El thumb se guardo correctamente.</p>');
						}
					});
				});
			}
			<?php
				if($imagen!="")
				echo "CargarCrop();";
			?>
    </script>
    <?php
  }



  function file_form_nuevo_croppie_thumb($nom,$ruta,$id_form,$class,$class_div,$id_div,$directorio_p,$sizethumb,$imagen){
  	//echo $ruta;
    ?>
    <div class="form-group <?php echo $class_div; ?>" id="<?php echo $id_div; ?>" >
      <label>Seleccionar ruta url para subir : </label>
      <?php $this->fmt->archivos->select_archivos($ruta,$directorio_p); ?>
			<br/><label>Seleccionar tamaño thumb (ancho x alto):</label>
			<?php $this->sizes_thumb($sizethumb); ?>
      <br/>
			<label><?php echo $nom; ?> :</label>
      <input type="file" ruta="<?php echo _RUTA_WEB; ?>" class="form-control <?php echo $class; ?>" id="inputArchivos" name="inputArchivos"  accept="image/*" />
			<div id='prog'></div>
      <div id="respuesta"></div>
    </div>
    <div id="ImagenPreview" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title">Vista Previa</h4>
	      </div>
	      <div class="modal-body" style="text-align: center;">
	        <img id="ImagePrev" src="">
	        <div id="prog_modal"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	        <button type="button" id="GuardarImg" class="btn btn-primary">Guardar</button>
	      </div>
	    </div>
	  </div>
		</div>
		<script>
      $(function(){
        $(".<?php echo $class; ?>").on("change", function(){
        var formData = new FormData($("#<?php echo $id_form; ?>")[0]);
        formData.append("ajax","ajax-upload-mul-tumb-cropp");
        var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
        $("#respuesta").toggleClass('respuesta-form');
        $.ajax({
            url: ruta,
            type: "POST",
            data: formData,
						async: true,
            contentType: false,
            processData: false,
						xhr: function() {
			        var xhr = $.ajaxSettings.xhr();
			        xhr.upload.onprogress = function(e) {
								var dat = Math.floor(e.loaded / e.total *100);
			          //console.log(Math.floor(e.loaded / e.total *100) + '%');
								$("#prog").html('<div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'+ dat +'" aria-valuemin="0" aria-valuemax="100" style="width: '+ dat +'%;">'+ dat +'%</div></div>');
			        };
			        return xhr;
				    },
            success: function(datos){
              	$("#respuesta").html(datos);
            }
          });
        });

			$("#GuardarImg").on("click", function(){
				guardar_thumb();
			});
  	});

	  function CargarCrop(){
		 var formData = new FormData($("#<?php echo $id_form; ?>")[0]);
		 formData.append("inputArchivosEdit", "<?php echo $imagen; ?>");
		 formData.append("ajax","ajax-upload-mul-tumb-cropp");
        var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
        $("#respuesta").toggleClass('respuesta-form');
        $.ajax({
            url: ruta,
            type: "POST",
						async: true,
            data: formData,
            contentType: false,
            processData: false,

            success: function(datos){
              	$("#respuesta").html(datos);
            }
          });
	  }


			function guardar_thumb(){
				var ext = $("#inputTipo").val();
				var dim = $("#inputThumb").val();
				var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
				var ruta_url = $("#inputUrl").val();
				var nombre = $("#inputNombreArchivo").val();
				var thm = dim.split("x");
				var ext_crop=ext;
				if(ext=="jpg"){
					ext_crop="jpeg";
				}
				 $('#image-cropp').cropper("getCroppedCanvas"<?php if($sizethumb!=""){ ?>, { width: thm[0], height: thm[1] }<?php } ?>).toBlob(function (blob) {
	           		var formData = new FormData();

			   		formData.append('croppedImage', blob);
			   		formData.append('dir', ruta_url);
			   		formData.append('nombre', nombre);
			   		formData.append('ext', ext);
			   		formData.append("ajax","ajax-save-thumb-mul-cropp");

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
									$("#prog_modal").html('<div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'+ dat +'" aria-valuemin="0" aria-valuemax="100" style="width: '+ dat +'%;">'+ dat +'%</div></div>');
				        };
				        return xhr;
					    },
				        success: function (data) {
				          $("#respuesta-thumb").html('<p class="text-success">El thumb se guardo correctamente.</p><img src="<?php echo _RUTA_WEB; ?>/'+data+'" width="'+thm[0]+'">');
						  $("#ImagenPreview").modal("hide");
				        },
				        error: function (data) {
				            console.log(data);
				        }
				    });



				});


			}
			<?php
				if($imagen!="")
				echo "CargarCrop();";
			?>
    </script>
    <?php
  }

	function multimedia_form($label,$input,$ruta,$thumb,$table,$col_id_extra,$col_id,$col_ruta,$col_dom,$id_mul=0){

  		$dom=0;
  		$aux="";

  		$sql="SELECT $col_id_extra, $col_id, $col_ruta, $col_dom FROM $table WHERE $col_id=$id_mul ";
		$rs=$this->fmt->query->consulta($sql,__METHOD__);
		$num =$this->fmt->query->num_registros($rs);
		$i=0;
		while($filax=$this->fmt->query->obt_fila($rs)){
			$prev[$i]=_RUTA_WEB.$filax[$col_ruta];
			$ids[$i]=$filax[$col_id_extra];
			$dom=$filax[$col_dom];
			$i++;
		}

		if($num>0){
			$aux.="initialPreview: [\n";
			$div="";
			foreach ($prev as $file) {
		        $aux.="$div '".$file."'";
		        $div=",";
		    }
			$aux.="],\n";
			$aux.="initialPreviewAsData: true,\n";
			$aux.="initialPreviewConfig: [\n";
			$div="";
			$data=explode("/", _RUTA_WEB);
			$dato=$data[3]."/sitios/".$data[3]."/".$ruta;

			for ($i=0; $i < count($ids); $i++) {
		        $aux.="$div {\n";
		        $nom_cap=explode($dato, $prev[$i]);
		        $ext = $this->fmt->archivos->saber_extension_archivo($prev[$i]);
				if($ext=="mp4"){
		        	$aux.="type: 'video', filetype: 'video/mp4',\n";
		        }
		        if($ext=="mp3"){
		        	$aux.="type: 'audio', filetype: 'audio/mp3',\n";
		        }
		        $aux.="caption: '".$nom_cap[1]."',\n";
		        $aux.="url: '"._RUTA_WEB."ajax.php',\n";
		        $aux.="key: ".$ids[$i].",\n";
		        $aux.="extra: {ajax: 'ajax-mul-delete-db', table: '".$table."', col_id: '".$col_id_extra."', col_ruta: '".$col_ruta."'}\n";
		        $aux.="}\n";
		        $div=",";
		    }
		    $aux.="],\n";
		}
		/*
		initialPreviewConfig: [
    {
        caption: 'desert.jpg',
        width: '120px',
        url: '/localhost/avatar/delete',
        key: 100,
        extra: {id: 100}
    },
    */

	  ?>
	  	<div class="form-group">
			<label class="control-label"><?php echo $label; ?></label>
			<input id="<?php echo $input; ?>" name="<?php echo $input; ?>[]" type="file" multiple class="file-loading">
			<div id="errorBlock" class="help-block"></div>
		</div>
		<link href="<?php echo _RUTA_WEB_NUCLEO; ?>css/fileinput.min.css" rel="stylesheet">
		<script src="<?php echo _RUTA_WEB_NUCLEO; ?>js/fileinput.min.js"></script>
		<script src="<?php echo _RUTA_WEB_NUCLEO; ?>js/fileinput.es.js"></script>

		<script>

			$(document).ready(function () {
				$("#<?php echo $input; ?>").fileinput({
			    	<?php echo $aux; ?>
			    	language: "es",
			        allowedFileExtensions: ["jpg", "png", "gif", "mp3", "mp4"],
			        maxFilePreviewSize: 20480,
			        uploadUrl: "<?php echo _RUTA_WEB; ?>ajax.php", // your upload server url
			        uploadExtraData: function() {
			            return {
			                input_img: "<?php echo $input; ?>",
			                ruta: "<?php echo $ruta; ?>",
			                thumb: "<?php echo $thumb; ?>",
			                dominio: "<?php echo _RUTA_WEB; ?>",
			                table: "<?php echo $table; ?>",
			                col_id: "<?php echo $col_id; ?>",
			                col_ruta: "<?php echo $col_ruta; ?>",
			                col_dom: "<?php echo $col_dom; ?>",
			                id_mul: "<?php echo $id_mul; ?>",
			                ajax: "ajax-mul-upload-db"
			            };
			        },
			        overwriteInitial: false
			    });
				$("#<?php echo $input; ?>").on("filepredelete", function(jqXHR) {
				    var abort = true;
				    if (confirm("¿ Esta seguro eliminar este archivo ?")) {
				        abort = false;
				    }
				    return abort; // you can also send any data/object that you can receive on `filecustomerror` event
				});
			});
		</script>
	  <?php
  }

	function file_form_doc($nom,$ruta,$id_form,$class,$class_div,$id_div,$directorio_p,$req){
		?>
		<div class="form-group">
			<label><?php echo $nom; ?></label>
			<div class="panel panel-default" >
				<div class="panel-body">
					<?php $this->fmt->archivos->select_archivos($ruta,$directorio_p,"inputRutaArchivosDocs"); ?>
					<input type="file" <?php echo $req; ?> ruta="<?php echo _RUTA_WEB; ?>" class="form-control <?php echo $class; ?>" id="inputArchivosDocs" name="inputArchivosDocs"  />
					<div id='prog'></div>
		      <div id="respuesta-docs"></div>
					<script>
					  $(function(){
					    $(".<?php echo $class; ?>").on("change", function(){
								var formData = new FormData($("#<?php echo $id_form; ?>")[0]);
								formData.append("ajax","ajax-upload-doc");
								var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
								$("#prog").html();
					      $.ajax({
					          url: ruta,
					          type: "POST",
										async: true,
					          data: formData,
					          contentType: false,
					          processData: false,
					          xhr: function() {
					            var xhr = $.ajaxSettings.xhr();
					            xhr.upload.onprogress = function(e) {
					              var dat = Math.floor(e.loaded / e.total *100);
					              //console.log(Math.floor(e.loaded / e.total *100) + '%');
					              $("#prog").html('<div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'+ dat +'" aria-valuemin="0" aria-valuemax="100" style="width: '+ dat +'%;">'+ dat +'%</div></div>');
					            };
					            return xhr;
					          },
					          success: function(datos){
					          $("#aux_editar").html("");
                      var myarr = datos.split(",");
                      var num = myarr.length;
                      if (myarr[0]=="editar"){
                        var i;
                        var url = myarr[1];
                        for (i = 2; i < num; i++) {
                          var datx = myarr[i].split('^');
                          var dx = datx[1];
                          $("#"+datx[0]).val(datx[1]);
                          $("#respuesta-docs").html('<div> <i class="icn-checkmark-circle color-text-verde" /> Archivo subido satisfactoriamente.</div>');
                        }
                      }else{
								$("#respuesta-docs").toggleClass('respuesta-form');
					            $("#respuesta-docs").html(datos);
                      }
					          }
					        });
							});
						});
					</script>
				</div>
			</div>
		</div>
		<?
	}

  function file_form_editar($nom,$ruta,$id_form,$class,$class_div,$id_div,$directorio_p,$sizethumb){
    ?>
    <div class="form-group <?php echo $class_div; ?>" id="<?php echo $id_div; ?>" >
      <label>Seleccionar ruta url para reemplazar archivo : </label>
      <?php $this->fmt->archivos->select_archivos($ruta,$directorio_p); ?>
			<br/><label>Seleccionar tamaño thumb (ancho x alto):</label>
			<?php $this->sizes_thumb($sizethumb); ?>
      <br/>
			<label><?php echo $nom; ?> :</label>
      <input type="file" ruta="<?php echo _RUTA_WEB; ?>" class="form-control <?php echo $class; ?>" id="inputArchivos" name="inputArchivos"  />
			<div id='prog'></div>
      <div id="respuesta"></div>
    </div>
		<script>
			$(function(){
				$(".<?php echo $class; ?>").on("change", function(){
					var formData = new FormData($("#<?php echo $id_form; ?>")[0]);
	        var ruta = "<?php echo _RUTA_WEB_NUCLEO; ?>ajax/ajax-upload.php";

					$("#url-imagen").html('');
					$.ajax({
	            url: ruta,
	            type: "POST",
							async: true,
	            data: formData,
	            contentType: false,
	            processData: false,
							xhr: function() {
				        var xhr = $.ajaxSettings.xhr();
				        xhr.upload.onprogress = function(e) {
									var dat = Math.floor(e.loaded / e.total *100);
				          //console.log(Math.floor(e.loaded / e.total *100) + '%');
									$("#prog").html('<div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'+ dat +'" aria-valuemin="0" aria-valuemax="100" style="width: '+ dat +'%;">'+ dat +'%</div></div>');
				        };
				        return xhr;
					    },
	            success: function(datos){

								$("#respuesta").toggleClass('respuesta-form');
								var myarr = datos.split(",");
								var num = myarr.length;
								if (myarr[0]=="editar"){

									var i;

									var url = myarr[1];
									for (i = 2; i < num; i++) {
										var datx = myarr[i].split('^');
										var dx = datx[1];

										$("#"+datx[0]).val(datx[1]); //cambia los valores por los nuevos
									}
								}
							  var datosx='<img src="'+ dx + url +'" class="img-responsive">';
								$("#respuesta").html(datosx);

							}
						});
				});
      });
		</script>
    <?php
  }

  function agregar_pestana($label,$id,$valor,$class,$class_div,$mensaje,$from,$id_item,$id_item_doc){
		$idx=$id;

	  ?>
		<!-- <link href="<?php echo _RUTA_WEB_NUCLEO; ?>css/summernote-bs3.css?" rel="stylesheet">
		<script src="<?php echo _RUTA_WEB_NUCLEO; ?>js/bootstrap.js"></script>
		<link href="<?php echo _RUTA_WEB_NUCLEO; ?>css/summernote.css" rel="stylesheet">
		<script src="<?php echo _RUTA_WEB_NUCLEO; ?>js/summernote.js"></script> -->

		<div class="form-group <?php echo $class_div; ?>">
      <label><?php echo $label; ?></label>
      <a class="btn btn-nuevo-pest pull-right"><i class="fa fa-plus"></i> Nueva Pestaña </a>
      <a class="btn btn-agregar-pest pull-right"><i class="fa fa-plus"></i> Agregar Pestaña </a>
      <?php if (!empty($mensaje)){ ?>
	  <p class="help-block"><?php echo $mensaje; ?></p>
	  <?php } ?>
	  <div class="" id="box-adiciones_pest">
		  <?php
			  $orden_pest=0;
			if (!empty($valor)){
				$sql="SELECT DISTINCT pes_id, pes_nombre, mod_pro_pes_contenido, mod_pro_pes_orden FROM $from, pestana WHERE $id_item_doc=pes_id and $id_item=$valor order by mod_pro_pes_orden asc ";
				$rs=$this->fmt->query->consulta($sql,__METHOD__);
				$num =$this->fmt->query->num_registros($rs);
				if ($num>0){

					for($i=0;$i<$num;$i++){
						$filax=$this->fmt->query->obt_fila($rs);
						echo '<div class="box-pest-agregado box-pest-'.$filax["pes_id"].'" "><input type="hidden" name="'.$id.'[]" id="'.$id.'[]" value="'.$filax["pes_id"].'" /> <label>'.$filax["pes_nombre"].'</label><a class="btn quitarpest" value="'.$filax["pes_id"].'" id="e-'.$filax["pes_id"].'" nombre="'.$filax["pes_nombre"].'"><i class="icn-close"></i></a><div class="form-group"><label for="textArea" class="col-lg-2 control-label">Contenido:<p></p>Orden:<input type="number" class="form-control" min="0" name="orden_pest'.$filax["pes_id"].'" id="orden_pest'.$filax["pes_id"].'" value="'.$filax["mod_pro_pes_orden"].'"></label><div class="col-lg-10"><textarea class="form-control text-note" rows="3" name="contenido'.$filax["pes_id"].'" id="contenido'.$filax["pes_id"].'">'.$filax["mod_pro_pes_contenido"].'</textarea></div></div></div>';

						$valor_ids[$i] = $filax["pes_id"];
						$orden_pest=$filax["mod_pro_pes_orden"];
					}

				}
			}
		  ?>
	  </div>
	  <?php
		  if (!empty($valor)){ $xvalor="&id=".$valor; }else{ $xvalor=""; }
		  $id_mod_docs = $this->fmt->class_modulo->get_modulo_id("Configuraciones EC");
		  $modo = "modal";
		  require_once(_RUTA_NUCLEO."modulos/productos/config-ec.class.php");
		  $form_e =new CONFIG_EC($this->fmt);
	  ?>
	  <div class="box-modal" id="box-modal-pest" style="display:none;">
		  <div id="respuesta-modal">
		  <?php
			//$form_e->form_nuevo($modo);

		  ?>
		  </div>
	  </div>

	  <div class="box-modal" id="box-modal-apest" style="display:none;">
		  <?php

			  $form_e->busqueda_seleccion($modo,$valor_ids);
		   ?>
	  </div>
	  <script>
		  	$(document).ready( function (){
		  		var orden_pst = <?php echo $orden_pest; ?>;
			  	$(".btn-nuevo-pest").click( function(){
				  $("#box-modal-pest").toggle();
				  $(".btn-nuevo-pest").toggleClass("on");
			  	});

			  	$(".btn-agregar-pest").click( function(){
				  $("#box-modal-apest").toggle();
				  $(".btn-agregar-pest").toggleClass("on");
				});

				$( "#box-modal-apest" ).on( "click", ".btn-agregar-pes", function(){
					orden_pst++;
				  var idv = $( this ).attr("value");
				  var nom = $( this ).attr("nombre");
				  $('#bp-' + idv).toggleClass("on");
				  $('.btp-' + idv).toggleClass("on");
				  $('#box-adiciones_pest').append('<div class="box-pest-agregado box-pest-'+idv+'"><input type="hidden" name="<?php echo $idx; ?>[]" id="<?php echo $idx; ?>[]" value="'+idv+'" /> <label>'+nom+'</label><a class="btn quitarpest" value="'+idv+'" id="e-'+idv+'" nombre="'+nom+'"><i class="icn-close"></i></a><div class="form-group"><label for="textArea" class="col-lg-2 control-label">Contenido:<p></p>Orden:<input type="number" class="form-control" min="0" name="orden_pest'+idv+'" id="orden_pest'+idv+'" value="'+orden_pst+'"></label><div class="col-lg-10"><textarea class="form-control text-note" rows="3" name="contenido'+idv+'" id="contenido'+idv+'"></textarea></div></div></div>');

				  	$(".quitarpest").off('click');
    				$(".quitarpest").on('click', function() {
	    				var ide = $( this ).attr("value");
	    				var nom = $( this ).attr("nombre");
	    				$('#bp-' + ide).toggleClass("on");
	    				$('.btp-' + ide).toggleClass("on");
	    				$('.box-pest-' + ide ).remove();
					});

					$('.text-note').summernote({
						height: 150,                 // set editor height
						minHeight: null,             // set minimum height of editor
						maxHeight: null,             // set maximum height of editor
						lang: 'es-ES',
						focus: false,
						toolbar: [
							['style', ['style','bold', 'italic', 'underline', 'clear','hr']],
						    ['font', ['strikethrough', 'superscript', 'subscript']],
						    ['fontsize', ['fontsize']],
						    ['color', ['color']],
						    ['table', ['table']],
						    ['para', ['ul', 'ol', 'paragraph']],
						    ['height', ['height']],
						    ['codeview',['codeview','fullscreen']],
							['mybutton', ['imagen','link']],
						]
					});
				});
				$(".quitarpest").click(function() {
	    				var ide = $( this ).attr("value");
	    				$('#bp-' + ide).toggleClass("on");
	    				$('.btp-' + ide).toggleClass("on");
	    				$('.box-pest-' + ide ).remove();
				});
				$( "#box-modal-apest" ).on( "click", ".btn-actualizar-modal", function() {
					  $("#box-modal-apest").html("cargando..");
					 var ruta = "<?php echo _RUTA_NUCLEO; ?>ajax/ajax-act-seccion.php";
					 var dato = [{name:"action", value:"pestana"},{name:"valor", value:"<?php echo $valor; ?>"}];
					 $.ajax({
					          url: ruta,
					          type: "POST",
										async: true,
					          data: dato,
							  success: function(datos){
							  	$("#box-modal-apest").html(datos);
									$('#table_id_modal_aux').DataTable({
										"language": {
							            "url": "http://52.36.176.142/mainter/js/spanish_datatable.json"
							            },
							            "pageLength": 25,
							            "order": [[ 0, 'asc' ]]
									});
							  }
					});
				});
				// $('.text-note').summernote({
				// 		height: 150,                 // set editor height
				// 		minHeight: null,             // set minimum height of editor
				// 		maxHeight: null,             // set maximum height of editor
				// 		lang: 'es-ES',
				// 		focus: false,
				// 		toolbar: [
				// 			['style', ['style','bold', 'italic', 'underline', 'clear','hr']],
				// 		    ['font', ['strikethrough', 'superscript', 'subscript']],
				// 		    ['fontsize', ['fontsize']],
				// 		    ['color', ['color']],
				// 		    ['table', ['table']],
				// 		    ['para', ['ul', 'ol', 'paragraph']],
				// 		    ['height', ['height']],
				// 		    ['codeview',['codeview','fullscreen']],
				// 			['mybutton', ['imagen','link']],
				// 		]
				// 	});
			});

       </script>

    </div>
		<?php
  }

  function agregar_documentos($label,$id,$valor,$class,$class_div,$mensaje,$from,$id_item,$id_item_doc){
	  $idx=$id;
	  ?>
		<div class="form-group <?php echo $class_div; ?>">
      <label><?php echo $label; ?></label>
      <a class="btn btn-nuevo-docs pull-right"><i class="fa fa-plus"></i> Nuevo documento </a>
      <a class="btn btn-agregar-docs pull-right"><i class="fa fa-plus"></i> Agregar documento </a>
      <?php if (!empty($mensaje)){ ?>
	  <p class="help-block"><?php echo $mensaje; ?></p>
	  <?php } ?>
	  <div class="" id="box-adiciones">
		  <?php

			if (!empty($valor)){
				$sql="SELECT DISTINCT doc_id,doc_nombre,doc_tipo_archivo FROM $from, documento WHERE $id_item_doc=doc_id and $id_item=$valor ";
				$rs=$this->fmt->query->consulta($sql,__METHOD__);
				$num =$this->fmt->query->num_registros($rs);
				if ($num>0){
					for($i=0;$i<$num;$i++){
						$filax=$this->fmt->query->obt_fila($rs);
						echo '<div class="box-doc-agregado box-doc-'.$filax["doc_id"].'">';
						echo '<input type="hidden" name="'.$id.'[]" id="'.$id.'[]" value="'.$filax["doc_id"].'" />';
						echo '<label>'.$filax["doc_nombre"].' ('.$filax["doc_tipo_archivo"].') </label>';
						echo '<a class="btn quitardoc" value="'.$filax["doc_id"].'" id="e-'.$filax["doc_id"].'" nombre="'.$filax["doc_nombre"].' ('.$filax["doc_tipo_archivo"].')"><i class="icn-close"></i></a>';
						echo '</div>';
						$valor_ids[$i] = $filax["doc_id"];
					}
				}
			}
		  ?>
	  </div>
	  <div class="box-modal" id="box-modal-docs" style="display:none;">
	  <?php
	  		if (!empty($valor)){ $xvalor="&id=".$valor; }else{ $xvalor=""; }
			$id_mod_docs = $this->fmt->class_modulo->get_modulo_id("Documentos");
			$modo = "modal";
		   require_once(_RUTA_NUCLEO."modulos/documentos/documentos.class.php");
			  $form =new DOCUMENTOS($this->fmt,$id_mod_docs);
	  ?>
		  <div id="respuesta-modal">
		  <?php
			//$form->form_nuevo($modo);
		  ?>
		  </div>
	  </div>

	  <div class="box-modal" id="box-modal-adocs" style="display:none;">
		  <?php

			  $form->busqueda_seleccion($modo,$valor_ids);
		   ?>
	  </div>
	  <script>
		  	$(document).ready( function (){
			  	$(".btn-nuevo-docs").click( function(){
				  $("#box-modal-docs").toggle();
				  $(".btn-nuevo-docs").toggleClass("on");
			  	});

			  	$(".btn-agregar-docs").click( function(){
				  $("#box-modal-adocs").toggle();
				  $(".btn-agregar-docs").toggleClass("on");
				});

				$("#box-modal-adocs").on('click', ".btn-agregar", function(){
				  var idv = $( this ).attr("value");
				  var nom = $( this ).attr("nombre");
				  $('#b-' + idv).toggleClass("on");
				  $('.bt-' + idv).toggleClass("on");
				  $('#box-adiciones').append('<div class="box-doc-agregado box-doc-'+idv+'" "><input type="hidden" name="<?php echo $idx; ?>[]" id="<?php echo $idx; ?>[]" value="'+idv+'" /> <label>'+nom+'</label><a class="btn quitardoc" value="'+idv+'" id="e-'+idv+'" nombre="'+nom+'"><i class="icn-close"></i></a></div>');
				  	/*
					  	<div id="'.$id.'[]" value=""></div>
					  	var ruta = "<?php echo _RUTA_WEB; ?>nucleo/ajax/ajax-doc-modal.php";

				$.ajax({
					          url: ruta,
					          type: "POST",
					          data: { inputFecha:inputUsuario },
							  success: function(datos){
								  $("#respuesta-modal").html(datos);
							  }
					});

					*/ $(".quitardoc").off('click');
    				$(".quitardoc").on('click', function() {
	    				var ide = $( this ).attr("value");
	    				var nom = $( this ).attr("nombre");
	    				$('#b-' + ide).toggleClass("on");
	    				$('.bt-' + ide).toggleClass("on");
	    				$('.box-doc-' + ide ).remove();
					});
				});
				$(".quitardoc").click(function() {
	    				var ide = $( this ).attr("value");
	    				$('#b-' + ide).toggleClass("on");
	    				$('.bt-' + ide).toggleClass("on");
	    				$('.box-doc-' + ide ).remove();
				});
				$( "#box-modal-adocs" ).on( "click", ".btn-actualizar-modal", function() {
					  $("#box-modal-adocs").html("cargando..");
					 var ruta = "<?php echo _RUTA_WEB; ?>nucleo/ajax/ajax-act-seccion.php";
					 var dato = [{name:"action", value:"documento"},{name:"valor", value:"<?php echo $valor; ?>"}];
					 $.ajax({
					          url: ruta,
					          type: "POST",
					          data: dato,
							  success: function(datos){
								  $("#box-modal-adocs").html(datos);
									$('#table_id_modal').DataTable({
										"language": {
							            "url": "http://52.36.176.142/mainter/js/spanish_datatable.json"
							            },
							            "pageLength": 25,
							            "order": [[ 0, 'asc' ]]
									});
							  }
					});
				});
				$(".btn-formmodal").click(function(){
					var datos = $("#respuesta-modal").serialize();
					alert(datos);
				});
			});
			function abrir_modulo_docs(datos){
			  $.ajax({
		    			url:"<?php echo _RUTA_WEB; ?>ajax.php",
		    			type:"post",
							async: true,
		    			data:datos,
		    			success: function(msg){
		            //alert(msg);
		            $("#respuesta-modal").html(msg);
		    			}
		    });
		  }
		       </script>
		    </div>
			<?php
   }

	function agregar_pedidos($label,$id,$valor,$class,$class_div,$mensaje){
 		$idx=$id;
 	  ?>
 		<div class="form-group <?php echo $class_div; ?>">
       <label><?php echo $label; ?></label>
       <?php if (!empty($mensaje)){ ?>
 	  <p class="help-block"><?php echo $mensaje; ?></p>
 	  <?php } ?>
		<div class="box-pedt-head box-pedt-head">
			<div class="form-group box-md-4">
				<label>Articulo:</label>
			</div>
			<div class="form-group box-md-1">
				<label>Cant.:</label>
			</div>
			<div class="form-group box-md-2">
				<label>Unidad:</label>
			</div>
			<div class="form-group box-md-3">
				<label>Observaciones:</label>
			</div>
			<div class="form-group box-md-1">
				<label>Quitar</label>
			</div>
		</div>
 	  <div class="" id="box-adiciones_pedt">
 		  <?php
 			  $orden_pedt=0;
 			if (!empty($valor)){
 				$sql="SELECT DISTINCT alm_id, alm_nombre, ped_alm_cantidad, ped_alm_unidad, ped_alm_observaciones FROM almacen, pedido_almacen WHERE ped_alm_id_almacen=alm_id and ped_alm_id_pedido=$valor order by alm_id asc ";
 				$rs=$this->fmt->query->consulta($sql,__METHOD__);
 				$num =$this->fmt->query->num_registros($rs);
 				if ($num>0){

 					for($i=0;$i<$num;$i++){
 						$filax=$this->fmt->query->obt_fila($rs);
 						echo '<div class="box-pedt-agregado box-pedt-'.$filax["alm_id"].'"><div class="form-group box-md-4"><input class="form-control " id="" name="" placeholder="" value="'.$filax["alm_nombre"].'" readonly=""><input type="hidden" name="'.$idx.'[]" id="'.$idx.'[]" value="'.$filax["alm_id"].'"/></div><div class="form-group box-md-1"><input class="form-control " id="cant'.$filax["alm_id"].'" name="cant'.$filax["alm_id"].'" value="'.$filax["ped_alm_cantidad"].'" ></div><div class="form-group box-md-2"><input class="form-control " id="unidad'.$filax["alm_id"].'" name="unidad'.$filax["alm_id"].'" placeholder="caja,bolsas,litros" value="'.$filax["ped_alm_unidad"].'" ></div><div class="form-group box-md-3"><input class="form-control " id="observacion'.$filax["alm_id"].'" name="observacion'.$filax["alm_id"].'" placeholder="" value="'.$filax["ped_alm_observaciones"].'" ></div><div class="form-group box-md-1"><a class="btn quitarpedt" value="'.$filax["alm_id"].'" id="e-'.$filax["alm_id"].'" nombre="'.$filax["alm_nombre"].'"><i class="icn-close"></i></a></div></div>';

 						$valor_ids[$i] = $filax["alm_id"];
 					}

 				}
 			}
 		  ?>
 	  </div>
 	  <div class="box-modal" id="box-modal-apedt">
 		  <?php
 			  require_once(_RUTA_NUCLEO."modulos/rrhh/inventario.class.php");
 			  $form_e =new INVENTARIO($this->fmt);
 			  $form_e->busqueda_seleccion('modal',$valor_ids);
 		   ?>
 	  </div>
 	  <script>
 		  	$(document).ready( function (){
 		  		var orden_pst = <?php echo $orden_pedt; ?>;
 			  	$(".btn-nuevo-pedt").click( function(){
 				  $("#box-modal-pedt").toggle();
 				  $(".btn-nuevo-pedt").toggleClass("on");
 			  	});

 			  	$(".btn-agregar-pedt").click( function(){
 				  $("#box-modal-apedt").toggle();
 				  $(".btn-agregar-pedt").toggleClass("on");
 				});

 				$( "#box-modal-apedt" ).on( "click", ".btn-agregar-ped", function(){
 					orden_pst++;
 				  var idv = $( this ).attr("value");
 				  var nom = $( this ).attr("nombre");
 				  $('#bp-' + idv).toggleClass("on");
 				  $('.btp-' + idv).toggleClass("on");
 				  $('#box-adiciones_pedt').append('<div class="box-pedt-agregado box-pedt-'+idv+'"><div class="form-group box-md-4"><input class="form-control " id="" name="" placeholder="" value="'+nom+'" readonly=""><input type="hidden" name="<?php echo $idx; ?>[]" id="<?php echo $idx; ?>[]" value="'+idv+'"/></div><div class="form-group box-md-1"><input class="form-control " id="cant'+idv+'" name="cant'+idv+'" value="" ></div><div class="form-group box-md-2"><input class="form-control " id="unidad'+idv+'" name="unidad'+idv+'" placeholder="caja,bolsas,litros" value="" ></div><div class="form-group box-md-3"><input class="form-control " id="observacion'+idv+'" name="observacion'+idv+'" placeholder="" value="" ></div><div class="form-group box-md-1"><a class="btn quitarpedt" value="'+idv+'" id="e-'+idv+'" nombre="'+nom+'"><i class="icn-close"></i></a></div></div>');

 				  $(".quitarpedt").off('click');
     			$(".quitarpedt").on('click', function() {
 	    				var ide = $( this ).attr("value");
 	    				var nom = $( this ).attr("nombre");
 	    				$('#bp-' + ide).toggleClass("on");
 	    				$('.btp-' + ide).toggleClass("on");
 	    				$('.box-pedt-' + ide ).remove();
 					});
				});

 				$(".quitarpedt").click(function() {
 	    				var ide = $( this ).attr("value");
 	    				$('#bp-' + ide).toggleClass("on");
 	    				$('.btp-' + ide).toggleClass("on");
 	    				$('.box-pedt-' + ide ).remove();
 				});

 				$( "#box-modal-apedt" ).on( "click", ".btn-actualizar-modal", function() {
 					  $("#box-modal-apedt").html("cargando..");
 					 var ruta = "<?php echo _RUTA_NUCLEO; ?>ajax/ajax-act-seccion.php";
 					 var dato = [{name:"action", value:"pestana"},{name:"valor", value:"<?php echo $valor; ?>"}];
 					 $.ajax({
 					          url: ruta,
 					          type: "POST",
										async: true,
 					          data: dato,
 							  success: function(datos){
 							  	$("#box-modal-apedt").html(datos);
 							  }
 					});
 				});

 			});

        </script>
     </div>
 		<?php
  }

  function head_table($id_tabla){
    ?><div class="table-responsive">
        <table class="table table-hover display" id="<?php echo $id_tabla; ?>">
    <?php
  }

  function thead_table($cab,$class){
    $valor = explode(":",$cab);
		$valor_clase = explode(":",$class);
    $num = count($valor);
    ?><thead>
      <tr>
        <?php
        for ($i=0; $i<$num;$i++){
          echo '<th class="'.$valor_clase[$i].'">'.$valor[$i].'</th>';
        }
        ?>
      </tr>
    </thead>
    <?php
  }

  function tbody_table_open(){
    ?>
    <tbody>
  	<?php
  }

	function tbody_table_close(){
    ?>
		</tbody>
  	<?php
  }

  function footer_table(){
    ?>
        </table> <!-- fin table-->
      </div>
    <?php
  }


  function input_hidden_form($id,$valor){
    ?>
    	<input type="hidden" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $valor; ?>" />
    <?php
  }
  function textarea_hidden_form($id,$valor){
    ?>
    	<textarea style="display:none" id="<?php echo $id; ?>" name="<?php echo $id; ?>" ><?php echo $valor; ?></textarea>
    <?php
  }
	function on_off_form($label,$id,$campo,$resumen,$valor=0,$class,$class_div){
		if ($valor==1){ $icn="on"; }else{ $icn="off";}
		if ($valor==1){ $v="0"; }else{ $v="1";}
		?>
		<div class="form-group <?php echo $class_div; ?>" >
			<a class="btn-on-off" valor="<?php echo $v; ?>" id="<?php echo $id; ?>" campo="<?php echo $campo; ?>"><i class="icn icn-btn-<?php echo $icn; ?>"></i></a>
			<label><?php echo $label; ?></label>
			<span><?php echo $resumen; ?></span>
		</div>
		<?php
	}
	function input_form($label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros){
    ?>
    <div class="form-group <?php echo $class_div; ?>" id="input-<?php echo $id; ?>" >
      <label><?php echo $label; ?></label>
      <input class="form-control <?php echo $class; ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>" validar="<?php echo $validar; ?>" placeholder="<?php echo $placeholder; ?>" value="<?php echo $valor; ?>" <?php echo $disabled; echo $otros; ?> />
			<?php if (!empty($mensaje)){ ?>
			<p class="help-block"><?php echo $mensaje; ?></p>
			<?php } ?>
			<div class="mensajes-aux"></div>
    </div>
    <?php
  }
	function input_date_form($label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros){
		$fecha = $this->fmt->class_modulo->estructurar_fecha_hora($valor);
    ?>
		<script type="text/javascript">
						$(function () {
								$('#<?php echo $id; ?>').datetimepicker({
									language:  'es',
									format: 'dd-mm-yyyy hh:ii',
									autoclose: true,
									minuteStep: 5,
									weekStart: 1,
									forceParse: 0,
									todayBtn: true
								});
						});
		</script>
    <div class="form-group form-date <?php echo $class_div; ?>" >
			<?php if (!empty($label)){ ?>
      <label><?php echo $label; ?></label>
			<?php } ?>
			<div class="group">
				<i class="icn icn-calendar-clock"></i>
				<input  data-date-format="yyyy-mm-dd hh:ii" class="form-control  date form-datetime <?php echo $class; ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>" validar="<?php echo $validar; ?>" placeholder="" value="<?php echo $fecha; ?>" <?php echo $disabled; echo $otros; ?> />
				<?php if (!empty($mensaje)){ ?>
				<p class="help-block"><?php echo $mensaje; ?></p>
				<?php } ?>
			</div>

    </div>

    <?php
  }

	function password_form($label,$id,$placeholder,$valor,$class,$class_div,$mensaje){
		?>
		<div class="form-group <?php echo $class_div; ?>" >
			<label><?php echo $label; ?></label>
			<input class="form-control" type="password" id="<?php echo $id; ?>" name="<?php echo $id; ?>"  placeholder="<?php echo $placeholder; ?>" value="<?php echo $valor; ?>" />
			<div id="msg-pass-<?php echo $id; ?>" class="mensaje-pw"><?php echo $mensaje; ?></div>
		</div>
		<?php
	}

	function ruta_amigable_form($id,$ruta="",$valor,$id_form,$ext="",$div_class){

		?>
			<div class="btn-link-ra <?php echo $div_class; ?>">
				<a class="btn-link-ra-<?php echo $id; ?>"><i class="icn icn-link"></i></a>
				<div class="block-link-ra block-link-ra-<?php echo $id; ?>">
					<div class="block-text">
						<span class="ruta_ini"><?php echo $ruta; ?></span>
						<input type="text"  name="<?php echo $id_form; ?>" id="<?php echo $id_form; ?>" value="<?php echo $valor; ?>" autocomplete="off">
						<span class="ruta_fin"><?php echo $ext; ?></span>
					</div>
					<input class="btn btn-small btn-full btn-copy" id="copy_btn" type="button" value="copy">
				</div>
			</div>
			<script language="JavaScript">
				$(document).ready( function (){
					$(".btn-link-ra-<?php echo $id; ?>").click( function(){
						$(".block-link-ra-<?php echo $id; ?>").toggleClass('on');
					});
					//ajustar_input();

					//$("#<?php //echo $id_form; ?>").on('keyup', function(){
						//ajustar_input();
					//});

					$("#<?php echo $id; ?>").on('keyup', function(){
						var ruta = $("#<?php echo $id; ?>").val();
						var nruta = convertir_url_amigable (ruta);  //core.js
						$("#<?php echo $id_form; ?>").val(nruta);
						//ajustar_input();
					});

					function convertir_url_amigable(text){

							var text = text.toLowerCase(); // a minusculas
 					      text = text.replace(/[áàäâå]/, 'a');
 					      text = text.replace(/[éèëê]/, 'e');
 					      text = text.replace(/[íìïî]/, 'i');
 					      text = text.replace(/[óòöô]/, 'o');
 					      text = text.replace(/[úùüû]/, 'u');
 					      text = text.replace(/[ýÿ]/, 'y');
 					      text = text.replace(/[ñ]/, 'n');
 					      text = text.replace(/[ç]/, 'c');
 					      text = text.replace(/['"`]/, '-');
 					      text = text.replace(/[^a-zA-Z0-9-]/, '');
 					      text = text.replace(/\s+/, '-');
 					      text = text.replace(/' '/, '-');
 					      text = text.replace(/(_)$/, '');
 					      text = text.replace(/(')$/, '');
 					      text = text.replace(/^(_)/, '');
 					      text = text.replace(/^(:)/, '-');
 					      text = text.replace(/ +/g,'-');
 					      text = text.replace(/-+/g,'-');
 					      return text;
					}


					// function ajustar_input() {
					// 		$('body').append('<span style="display:none" id="charw"></span>');
					// 		var texto = $("#<?php echo $id_form; ?>").val();//obtenemos su texto
					// 		var cm = $("#<?php echo $id_form; ?>").val().length  + 5;
					// 		// console.log("texto:"+texto);
					// 		$('#charw').html( texto );
					// 		// var	ancho = $('#charw').outerWidth() ;
					// 		var	ancho = cm * 10;
					// 		// console.log("ancho:"+ancho);
          //   	$("#<?php echo $id_form; ?>").css("width",ancho  +"px");
					// 		$('#charw').remove();
        	// }

					$("#copy_btn").click( function(){
						$("body").append("<input type='text' id='temp'>");
						var tx = "<?php echo $ruta; ?>"+ $("#<?php echo $id_form; ?>").val() + "<?php echo $ext; ?>";
						$("#temp").val(tx).select();
						 document.execCommand("copy");
						 $("#temp").remove();
					});

					// var iw = $("#<?php echo $id; ?>").width();
					// var ih = $("#<?php echo $id; ?>").height() ;
					// var px = $("#<?php echo $id; ?>");
					// var p = px.position();
					//
					// // console.log("xi:"+ iw);
					// console.log("yi:"+ ih);
					// //
					// $(".btn-link-ra").css("top","-"+ ih + 12+"px !important");
					// $(".btn-link-ra").css("left",p.left + iw - 30 );
					// var copyBtn = document.querySelector('#copy_btn');
					// copyBtn.addEventListener('click', function () {
					// var urlField = document.querySelector('#<?php echo $id_form; ?>');
					//
					// // create a Range object
					// var range = document.createRange();
					// // set the Node to select the "range"
					// range.selectNode(urlField);
					// // add the Range to the set of window selections
					// window.getSelection().addRange(range);
					//
					// // execute 'copy', can't 'cut' in this case
					// document.execCommand('copy');
					// }, false);
				});
			</script>
		<?php
	}



	function input_color($label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar){
    ?>
    <div class="form-group <?php echo $class_div; ?>">
      <label><?php echo $label; ?></label>
      <input type="color" class="form-control <?php echo $class; ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>" validar="<?php echo $validar; ?>" placeholder="<?php echo $placeholder; ?>" value="<?php echo $valor; ?>" <?php echo $disabled; ?> >
			<?php if (!empty($mensaje)){ ?>
			<p class="help-block"><?php echo $mensaje; ?></p>
			<?php } ?>
    </div>
    <?php
  }

	function input_mail($label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar){
    ?>
    <div class="form-group <?php echo $class_div; ?>">
      <label><?php echo $label; ?></label>
      <input type="email" class="form-control <?php echo $class; ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>" validar="<?php echo $validar; ?>" placeholder="<?php echo $placeholder; ?>" value="<?php echo $valor; ?>" <?php echo $disabled; ?>  >
			<?php if (!empty($mensaje)){ ?>
			<p class="help-block"><?php echo $mensaje; ?></p>
			<?php } ?>
    </div>
    <?php
  }

  function input_file($label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar){
    ?>
    <div class="form-group <?php echo $class_div; ?> ">
      <label><?php echo $label; ?></label>
      <input type="file" class="form-control <?php echo $class; ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>" validar="<?php echo $validar; ?>" placeholder="<?php echo $placeholder; ?>" value="<?php echo $valor; ?>" <?php echo $disabled; ?> >
			<?php if (!empty($mensaje)){ ?>
			<p class="help-block"><?php echo $mensaje; ?></p>
			<?php } ?>
    </div>
    <?php
  }

	function var_input_form($label,$id,$placeholder,$valor,$class,$class_div,$mensaje){
		$aux= '<div class="form-group '.$class_div.'" id="input-'.$id.'">
			<label>'.$label.'</label>
			<input class="form-control '.$class.'" id="'.$id.'" name="'.$id.'"  placeholder="'.$placeholder.'" value="'.$valor.'" />';
			if (!empty($mensaje)){
				$aux .='<p class="help-block">'.$mensaje.'</p>';
			}
			$aux .= '</div>';
			return $aux;
	}

  function input_form_sololectura($label,$id,$placeholder,$valor,$class,$class_div,$mensaje){
    ?>
    <div class="form-group <?php echo $class_div; ?>" id="input-<?php echo $id; ?>">
      <label><?php echo $label; ?></label>
      <input class="form-control <?php echo $class; ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>"  placeholder="<?php echo $placeholder; ?>" value="<?php echo $valor; ?>" readonly/>
			<?php if (!empty($mensaje)){ ?>
			<p class="help-block"><?php echo $mensaje; ?></p>
			<?php } ?>
    </div>
    <?php
  }

	function input_date($label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$id_input){
		date_default_timezone_set('America/La_Paz');
		switch ($valor) {
			case 'hoy':
				$va=date("d/m/Y");
			break;
			case 'mañana':
				$va= date('d',time()+84600)."/". date("m")."/".date("Y");
			break;
			default:
				$va=$valor;
			break;
		}
		?>
			<div class="<?php echo $class_div; ?> " >
				<label><?php echo $label; ?></label>
        <div class="form-group">
					<div class='input-group date <?php echo $class; ?>' id="<?php echo $id_input; ?>" >
						<input type="text" class="form-control add-on dp" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo  $va; ?>"/>
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
				</div>
			</div>
			<script type="text/javascript">
					$(function () {
							$('.dp').datetimepicker({agra
								format: 'DD/MM/YYYY',
								locale: 'es'
							});
					});
			</script>
		<?php
	}

	function categoria_form($label,$id,$cat_raiz,$cat_valor,$class,$class_div){
    ?>
    <div class="form-group <?php echo $class_div; ?>">
      <label><?php echo $label.":"; ?></label>
			<div class="group group-cat">
				<?php $this->fmt->categoria->arbol($id,$cat_raiz,$cat_valor);  ?>
			</div>
    </div>
    <?php
  }

  function textarea_form($label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje){
    ?>
    <div class="form-group <?php echo $class_div; ?>">
      <label><?php echo $label; ?></label>
      <textarea  class="form-control <?php echo $class; ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>" rows="<?php echo $rows; ?>"  placeholder="<?php echo $placeholder; ?>" ><?php echo $valor; ?></textarea>
			<?php if (!empty($mensaje)){ ?>
			<p class="help-block"><?php echo $mensaje; ?></p>
			<?php } ?>
    </div>
    <?php
  }

  function select_form($label,$id,$prefijo,$from,$id_select,$id_disabled,$class_div,$class_select){
    ?>
    <div class="form-group <?php echo $class_div; ?>">
      <label><?php echo $label; ?></label>
      <select class="form-control <?php echo $class_select; ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>">
    <?php
    $consulta ="SELECT ".$prefijo."id, ".$prefijo."nombre FROM ".$from;
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
		echo "<option class='' value='0'>Sin selección (0)</option>";
		if($num>0){
      for($i=0;$i<$num;$i++){
        $row=$this->fmt->query->obt_fila($rs);
				$fila_id=$row[$prefijo."id"];
				$fila_nombre=$row[$prefijo."nombre"];
        if ($fila_id==$id_select){  $aux="selected";  }else{  $aux=""; }
        if ($fila_id==$id_disabled){  $aux1="disabled";  }else{  $aux1=""; }
        echo "<option class='' value='$fila_id' $aux $aux1 > ".$fila_nombre;
        echo "</option>";
      }
    }
    ?>
      </select>
    </div>
    <?php
  }
	function input_icono_form ($label,$id,$icono,$class_div){
		?>
		<div class="form-group <?php echo $class_div; ?>">
			<label><?php echo $label; ?></label>
			<input class="form-control box-md-4" id="<?php echo $id; ?>" name="<?php echo $id; ?>"  placeholder="" value="<?php echo $icono; ?>"/>
			<span class="input-link"><a href="<?php echo _RUTA_WEB_NUCLEO; ?>includes/icons.php" target="_blank">ver iconos</a></span>
		</div>
		<?php
	}

	function input_color_form ($label,$id,$color,$class_div){
		?>
		<div class="form-group <?php echo $class_div; ?>">
			<label><?php echo $label; ?></label>
			<?php
			if (!empty($color)) {
				$color = $color;
			}else{
				$color="#ffffff";
			}
			 ?>
			<input type="color" class="form-control box-md-2 form-input-color" id="<?php  echo $id; ?>" name="<?php  echo $id; ?>"  value="<?php echo $color; ?>"/>
			<?php
				require_once( _RUTA_NUCLEO."includes/color.php");
			?>
		</div>
		<?php
	}

	function imagen_form ($label,$label_btn,$id,$id_item,$valor,$class_div){
		echo "<div class='form-group $class_div'>";
		if (!empty($label)){
			echo "<label>$label</label>";
		}
		echo "<a  class='btn btn-full btn-up-finder' insert='$id'><i class='icn icn-photo'></i> <span>$label_btn</span></a>";
		echo "<div class='box-image box-image-block'>";
		if (!empty($valor)){
			//$this->fmt->form->finder("inputImagen",$this->id_mod,$fila_imagen,"individual","imagenes"); //$id,$id_mod,$url,$tipo_upload="individual",$tipo_archivo
			echo "<a class='btn btn-eliminar-imagen'><i class='icn icn-close' /></a>";
			echo "<img class='img-catalogo img-file img-responsive' id='img-".$id_item."' src='"._RUTA_IMAGES.$valor."' />";
		}else{

		}
		echo "</div>";
		echo "</div>";
		$this->fmt->form->input_hidden_form($id,$valor);
		?>
		<?php
	}

	function btn_up_finder(){
		return '$(".btn-up-finder").click( function(){
				insert = $(this).attr("insert");
				item = $(this).attr("item");
				id_item = $(this).attr("id_item");
				tipo_item = $(this).attr("tipo_item");
				$(".modal-finder").appendTo("body");
				$(".modal-finder").addClass("on");
				//$(".finder-item").height(w);
				//resize_item();
				//console.log (w);
			});';
	}
	function video_unico($id,$valor,$titulo="Video principal",$class_div){
		?>
		<div class="form-group form-img-block <?php echo $class_div; ?>" id='box-form-mul-<?php echo $id; ?>'>
		<?php
		if (!empty($valor)){
			$aux_mul ="";
			$aux_btn ="off";
			$id_mul=$valor;
			$valorx = $this->fmt->archivos->convertir_url_mini($valor);
			$nom = $this->fmt->class_multimedia->traer_nombre_multimedia($id_mul);
			$tipo_mul = $this->fmt->class_multimedia->traer_tipo_multimedia($id_mul);
			$nombre = $nom."(".$tipo_mul.")";
		}else{
			$aux_mul ="off";
			$aux_btn ="";
			$id_mul="";
			$nombre="";
		}
		$this->fmt->form->input_hidden_form($id,$valor);
		//$this->fmt->form->input_hidden_form($id_tipo,$tipo);

		?>
		<div class="box-acciones box-acciones-mul <?php echo $aux_mul; ?>" id="box-mul-<?php echo $id; ?>" >
			<a class="btn btn-eliminar-mul" eliminar="<?php echo $id; ?>" id_mul="<?php echo $id_mul;?>" tipo_item="video-unico"><i class="icn icn-close" /></a>
			<a class="btn btn-editar-mul" id_mul="<?php echo $id_mul;?>" mul=""  tipo_item="video-unico"><i class="icn icn-pencil" /></a>
		</div>
		<div class="box-mul-nombre box-mul-nombre-<?php echo $id; ?> nombre"><?php echo $nombre; ?></div>

		<img class="img-responsive <?php echo $aux_mul; ?>"  id="img-<?php echo $id; ?>" src="<?php echo _RUTA_WEB_NUCLEO; ?>images/video-icon.png" />

		<a insert='<?php echo $id; ?>' vars='<?php echo $id_tipo; ?>' upload='video-unico' seleccion='simple' class='<?php echo $aux_btn; ?> btn btn-up-finder btn-up-finder-<?php echo $id; ?>'>
			<i class='icn icn-media-plus'></i>
			<span lang="es"><?php echo $titulo; ?></span>
		</a>
	</div>
		<?php
	  //$this->editar_multimedia();
	}
	function documentos_form($id,$id_mod,$class_div,$label_form="",$from,$prefijo,$prefijo_mod){
		echo "<div class='form-group form-documentos form-documentos-list $class_div'>";
			if (!empty($label_form)){
				?>
				<label class="label"><?php echo $label_form; ?></label>
				<?php
			}
			?>

			<ul class="box-docs" id='sortable-docs'>
				<?php
				$consulta = "SELECT DISTINCT doc_id,doc_nombre,doc_tags,doc_url,doc_tipo_archivo FROM documento,$from WHERE ".$prefijo.$prefijo_mod."id='".$id."' and ".$prefijo."doc_id=doc_id ORDER BY ".$prefijo."orden asc";
				$rs =$this->fmt->query->consulta($consulta,__METHOD__);
				$num=$this->fmt->query->num_registros($rs);
				$aux="";
				if($num>0){
					for($i=0;$i<$num;$i++){
						$row=$this->fmt->query->obt_fila($rs);
						$id_item=$row["doc_id"];
						$nombre=$row["doc_nombre"];
						$tag=$row["doc_tags"];
						$ruta=$row["doc_url"];
						$fila_tipo=$row["doc_tipo_archivo"];

						$nomx= $this->fmt->class_modulo->recortar_texto($nombre,"35")." (".$tipox.")";
						if ($fila_tipo=="xls"||$fila_tipo=="xlsx" ){
							$icon="icn-excel";
						}

						if ($fila_tipo=="pdf"){
							$icon="icn-pdf";
						}

						if ($fila_tipo=="doc"||$fila_tipo=="docx" ){
							$icon="icn-word";
						}

						if ($fila_tipo=="ppt" ||$fila_tipo=="pptx"){
							$icon="icn-powerpoint";
						}
						echo "<li item='".$id_item."'  id='doc-".$id_item."' class='box-doc-li box-doc-".$fila_tipo." ui-state-default'>";
						echo "<i class='icn icn-sorteable icn-reorder'></i>";
						echo "<div class='box-acciones-docs ui-state-disabled'>";

						echo "<a class='btn btn-eliminar-doc' eliminar='".$id_item."' tipo_item='documento' id_doc='".$id_item."'><i class='icn icn-close' /></a>";
						echo "<a class='btn btn-editar-doc' id_doc='".$id_item."'><i class='icn icn-pencil' /></a>";
						echo "<a target='_blank' href='"._RUTA_WEB."archivos/docs/".$ruta."' class='btn btn-link'><i class='icn icn-skip'/></a>";

						echo "</div>";

						echo  "<i class='icn icon-tipo ".$icon."'/></i><span class='nombre'>$nombre</span>";
						echo  "<input type='hidden' id='inputModItemDoc[]' name='inputModItemDoc[]' value='".$id_item."'  />";
						echo  "</li>";
					}
				}
				?>
				<li class='ui-state-disabled'><a insert='<?php echo $id; ?>' upload='documentos' seleccion='multiple' class='btn btn-full btn-up-finder btn-up-finder-<?php echo $id; ?>'><i class='icn icn-plus'></i>Agregar Documento</a></li>
			</ul>
			<script type="text/javascript">
				$(document).ready(function() {

					$(".btn-eliminar-doc").click(function(){
						var id = $(this).attr("eliminar");
						$("#doc-"+id).remove();
					});

					$( "#sortable-docs" ).sortable({
							start: function(event, ui) {
							var start_pos = ui.item.index();
							ui.item.data('start_pos', start_pos);
		        },
		        change: function(event, ui) {
							var start_pos = ui.item.data('start_pos');
							var index = ui.placeholder.index();
							var cant = <?php echo $num; ?>;
						},
		        update: function(event, ui) {
							// var count=0;
							// var amul="";
							// $("#sortable-docs li").each(function(i) {
							// 	var am = $(this).attr("mul");
							// 	amul  = amul +":"+ am;
							// 	count++;
							// 	$(this).attr("orden",count);
							// });
		        },
						cancel: ".ui-state-disabled"
		      });

	 				$( "#sortable" ).disableSelection();

					<?php
						$this->btn_editar_doc();
					?>

				});
			</script>
			<?php
		echo "</div>";
	}

	function agenda_form($id,$id_mod,$class_div,$label_form="",$from,$prefijo,$prefijo_mod){
		echo "<div class='form-group form-agenda form-agenda-list $class_div'>";
		if (!empty($label_form)){
			?>
			<label class="label"><?php echo $label_form; ?></label>
			<?php
		}
		?>
		<ul class="box-agenda" id='sortable-agenda'>
			<li class='ui-state-disabled'><a insert='<?php echo $id; ?>' class='btn btn-full btn-up-list-persona btn-up-list-<?php echo $id; ?>'><i class='icn icn-plus'></i>Agregar Persona</a></li>
		</ul>
		<script type="text/javascript">
			$(document).ready(function() {
				$(".btn-up-list-persona").click(function(){
					$(".modal-list").addClass('on');
					var id= $(this).attr("insert");
					var formdata_m = new FormData();
					formdata_m.append("ajax", "ajax-agenda-list");
					formdata_m.append("inputId", id );
					var ruta_m = "<?php echo _RUTA_WEB; ?>ajax.php";
					$.ajax({
						url:ruta_m,
						type:"post",
						data:formdata_m,
						processData: false,
						contentType: false,
						success: function(msg){
							$(".modal-list .modal-list-inner").html(msg);
						}
					});
				})
			});
		</script>
		<?php
	}

	function btn_editar_doc(){
		?>
		$(".btn-editar-doc").click(function(){
			$(".modal-editar").addClass("on");
			var id_doc= $(this).attr("id_doc");
			var formdata_m = new FormData();
			formdata_m.append("ajax", "ajax-editar-doc");
			formdata_m.append("inputItem", id_doc );
			var ruta_m = "<?php echo _RUTA_WEB; ?>ajax.php";
			$.ajax({
				url:ruta_m,
				type:"post",
				data:formdata_m,
				processData: false,
				contentType: false,
				success: function(msg){
					$(".modal-editar .modal-editar-inner").html(msg);
					$(".btn-cancelar-modal-doc").click(function(){
						$(".modal-editar .modal-editar-inner").html("");
						$(".modal-editar").removeClass("on");
					});
				}
			});
		});
		<?php
	}
	function productos_notas($id,$valor,$titulo="Añadir Producto",$class_div,$label_form=""){
		echo "<div class='form-group'>";
		if (!empty($label_form)){
			 ?>
			 <label class="label-unico-form label-<?php echo $class_div; ?>"><?php echo $label_form; ?></label>
			 <?php
		 	}
			echo "<div class='form-productos-notas $class_div'>";
			echo "<ul class='box-list-productos box-list-group' id='sortable-prod'>";

			$consulta = "SELECT DISTINCT mod_prod_id, mod_prod_nombre FROM mod_productos,nota_productos WHERE not_prod_not_id='$valor' and not_prod_prod_id=mod_prod_id  ORDER BY not_prod_orden asc";
			$rs =$this->fmt->query->consulta($consulta,__METHOD__);
			$num=$this->fmt->query->num_registros($rs);
			if($num>0){
			  for($i=0;$i<$num;$i++){
					$row=$this->fmt->query->obt_fila($rs);
			    $fila_id=$row["mod_prod_id"];
			  	$fila_nombre=$row["mod_prod_nombre"];
					echo '<div class="item-producto" id="item-prod-'.$fila_id.'"><i class="icn icn-reorder"></i> '.$fila_nombre.'<input type="hidden" id="<?php echo $id; ?>[]" name="<?php echo $id; ?>[]" value="'.$fila_id.'" /><i class="btn icn icn-close" iditem="'.$fila_id.'"></i></div>';
				}
			}
			echo "<li class='ui-state-disabled box-list'><a upload='producto' vars='' seleccion='multiple'  class='btn btn-adicionar-prod btn-up-list btn-full'><i class='icn icn-plus' /><span>$titulo</span></a></li>";
			echo "  </ul>";
			echo " </div>";
			echo "</div>";
			?>
			<script type="text/javascript">
				$(document).ready(function() {
					$(".btn-up-list").click(function(event) {
						$(".modal-list").addClass('on');
						var formdata_m = new FormData();
						formdata_m.append("ajax", "ajax-list-productos");
						formdata_m.append("inputId", "<?php echo $id; ?>" );
						var ruta_m = "<?php echo _RUTA_WEB; ?>ajax.php";
						$.ajax({
							url:ruta_m,
							type:"post",
							data:formdata_m,
							processData: false,
							contentType: false,
							success: function(msg){
								console.log(msg);
								$(".modal-list .modal-list-inner").html(msg);
							}
						});
					});

					$( "#sortable-prod" ).sortable({
							start: function(event, ui) {
							var start_pos = ui.item.index();
							ui.item.data('start_pos', start_pos);
		        },
		        change: function(event, ui) {
							var start_pos = ui.item.data('start_pos');
							var index = ui.placeholder.index();
							var cant = <?php echo $num; ?>;
						},
		        update: function(event, ui) {
		            //$('.connectedSortable li').removeClass('highlights');
							var count=0;
							var amul="";
							var prod="<?php echo $id_prod; ?>";
							$("box-list-productos li").each(function(i) {
								var am = $(this).attr("mul");
								amul  = amul +":"+ am;
								count++;
								$(this).attr("orden",count);
							});
		        },
						cancel: ".ui-state-disabled"
		      });

	 				$( "#sortable-prod" ).disableSelection();
				});
			</script>
			<?php

	}
	function imagen_unica_form($id,$valor,$titulo="Imagen principal",$class_div,$label_form=""){

		if (!empty($label_form)){
			 ?>
			 <label class="label-unico-form label-<?php echo $class_div; ?>"><?php echo $label_form; ?></label>
			 <?php
		 	}
		?>
		<div class="form-group form-img-block <?php echo $class_div; ?>" id='box-form-mul-<?php echo $id; ?>'>
			<?php
			if (!empty($valor)){
				$aux_mul ="";
				$aux_btn ="off";
				$id_mul=$this->fmt->class_multimedia->traer_id_multimedia_ruta_archivo($valor);
				$valorx = $this->fmt->archivos->convertir_url_mini($valor);
			}else{
				$aux_mul ="off";
				$aux_btn ="";
				$id_mul="";
			}
			$this->fmt->form->input_hidden_form($id,$valor);

			?>
	 		<div class="box-acciones box-acciones-mul <?php echo $aux_mul; ?>" id="box-mul-<?php echo $id; ?>" >
				<a class="btn btn-eliminar-mul" eliminar="<?php echo $id; ?>" id_mul="<?php echo $id_mul;?>" tipo_item="imagen-unica"><i class="icn icn-close" /></a>
				<a class="btn btn-editar-mul" id_mul="<?php echo $id_mul;?>" mul="<?php echo $valor; ?>"  tipo_item="imagen-unica"><i class="icn icn-pencil" /></a>
 			</div>
	 		<img class="img-responsive <?php echo $aux_mul; ?>" id="img-<?php echo $id; ?>" src="<?php echo _RUTA_IMAGES.$valorx; ?>" />
			<a insert='<?php echo $id; ?>' upload='imagen-unica' seleccion='simple' class='<?php echo $aux_btn; ?> btn btn-up-finder btn-up-finder-<?php echo $id; ?>'>
				<i class='icn icn-media-plus'></i>
				<span lang="es"><?php echo $titulo; ?></span>
			</a>
		</div>
		<?php
	}

	function modal_editar_multimedia(){
		?>
		<link  href="<?php echo _RUTA_WEB_NUCLEO; ?>css/cropper.min.css" rel="stylesheet">
		<script src="<?php echo _RUTA_WEB_NUCLEO; ?>js/cropper.min.js"></script>
		<div class="modal modal-editar-mul"><div class="modal-inner-editar-mul"></div></div>
		<?php
	}

	function btn_eliminar_mul_js(){
		return '$(".btn-eliminar-mul").click( function(){
			var id = $(this).attr("eliminar");
			var tipo = $(this).attr("tipo_item");
			console.log("eliminar "+id);
			if (tipo=="imagen-unica"){
				$("#box-mul-"+id).addClass("off");
				$("#img-"+id).addClass("off");
				$(".btn-up-finder-"+id).removeClass("off");
				$("#"+id).attr("value","");
			}
			if (tipo=="video-unico"){
				$("#box-mul-"+id).addClass("off");
				$("#img-"+id).addClass("off");
				$(".btn-up-finder-"+id).removeClass("off");
				$(".box-mul-nombre-"+id).html("");
				$("#"+id).attr("value","");
			}
			if (tipo=="multimedia"){
				$("#mul-"+id).remove();
			}
		});';
	}

	function btn_editar_mul_js(){
		$aux .= ' $(".btn-editar-mul").click( function(){

		          $(".modal-editar-mul .modal-inner-editar-mul").html("");
		          $(".modal-editar-mul").appendTo("#content-page");
		          $(".modal-editar-mul").addClass("on");
		          var id= $(this).attr("id_mul");
							console.log("btn_editar"+id);
		          var ruta_mul= $(this).attr("mul");
		          var tipoi = $(this).attr("tipo_item");
		          var formdata_m = new FormData();
		          formdata_m.append("ajax", "ajax-editar-mul");
		          formdata_m.append("inputItem", id );
		          formdata_m.append("inputRutaMul", ruta_mul );
		          formdata_m.append("inputTipoItem", tipoi );
		          var ruta_m = "'._RUTA_WEB.'ajax.php";
		          $.ajax({
	              url:ruta_m,
	              type:"post",
	              data:formdata_m,
	              processData: false,
	              contentType: false,
	              success: function(msg){
									//console.log(msg);
									$(".modal-editar-mul .modal-inner-editar-mul").css("background-image","none");
									$(".modal-editar-mul .modal-inner-editar-mul").html(msg);
									var hx = $(".modal-editar-mul .modal-inner-editar-mul").height() - 100;
									var wx = $(".modal-editar-mul .modal-inner-editar-mul").width();
									//console.log(hx);
									$(".tab-content-modal-editar-mul").height(hx);
									$(".btn-cancelar-modal").click( function(){
										$(".modal-editar-mul").removeClass("on");
									});
									$(".btn-aceptar-modal").click( function(){
										$(".modal-editar-mul").removeClass("on");
									});
									if (tipoi!="video-unico"){';
		$aux .= 			$this->fmt->class_modulo->script_tabs();
		$aux .='				var formData = new FormData($("#form-editar-cropp")[0]);
										formData.append("inputArchivosEdit", ruta_mul );
										formData.append("ajax","ajax-upload-cropp");
										var ruta = "'._RUTA_WEB.'ajax.php";
										$("#imagen-cropp").toggleClass("respuesta-form");
										$.ajax({
											url: ruta,
											type: "POST",
											async: true,
											data: formData,
											contentType: false,
											processData: false,
											success: function(datos){
												var wxc = wx * 0.6;
												var hxc = hx - 50;
												$("#imagen-cropp").html(datos);
												$(".contenedor-imagen").width(wxc);
												$(".contenedor-imagen").height(hxc);
											}
									 	});
									}
								}
		        	});
	      		});';
	  return $aux;
	}

	function editar_multimedia(){
		?>
		<script type="text/javascript">
		    $(document).ready(function() {
					<?php echo $this->btn_eliminar_mul_js(); ?>
					<?php echo $this->btn_editar_mul_js(); ?>
		    }); // fin document
		</script>
		<?php
	}

	function multimedia_form_block($id_item,$id_mod,$class_div,$label="Subir archivo",$label_form="",$from,$prefijo,$prefijo_mod){
		echo "<div class='form-group form-group-multimedia-block'>";
		 if ($label_form!=""){
			 ?>
			 <label class="label-<?php echo $class_div; ?>"><?php echo $label_form; ?></label>
			 <?php
		 }


		echo "<div class='form-control form-multimedia form-multimedia-list $class_div'>";
		echo "<ul class='box-multimedia' id='sortable'>";
		if ($prefijo_mod!=""){
    	$consulta = "SELECT DISTINCT mul_id,mul_nombre,mul_url_archivo, mul_embed  FROM multimedia,$from WHERE ".$prefijo.$prefijo_mod."id='".$id_item."' and ".$prefijo."mul_id=mul_id ORDER BY ".$prefijo."orden asc";
		}else{
			$consulta = "SELECT DISTINCT mod_prod_mul_prod_id,mod_prod_mul_mul_id,mul_url_archivo, mul_embed,mul_nombre FROM mod_productos_mul,multimedia WHERE mod_prod_mul_prod_id='$id_item' and mod_prod_mul_mul_id=mul_id  ORDER BY mod_prod_mul_orden asc";
		}

		$rs =$this->fmt->query->consulta($consulta,__METHOD__);
		$num=$this->fmt->query->num_registros($rs);
		$aux="";
		if($num>0){
		  for($i=0;$i<$num;$i++){
		    $row=$this->fmt->query->obt_fila($rs);

				if ($prefijo_mod!=""){
					$id_item=$id_item;
					$id_mul=$row["mul_id"];
				}else{
					$id_item=$row["mod_prod_id"];
					$id_mul=$row["mod_prod_mul_mul_id"];
				}


				$ruta=$row["mul_url_archivo"];
				$embed=$row[" mul_embed"];
				$nom=$row[" mul_nombre"];
		    $url = $this->fmt->archivos->convertir_url_mini($ruta);
				$extension = $this->fmt->archivos->saber_extension_archivo($ruta);
				$nombre_archivo=$this->fmt->archivos->saber_nombre_archivo($ruta);
				if($extension=="mp4"){
					//$link = _RUTA_WEB_NUCLEO."images/video-icon.png";
					$link =_RUTA_IMAGES."archivos/multimedia/".$nombre_archivo.".jpg";
					$link = $this->fmt->archivos->convertir_nombre_thumb($link);
				}else{
					$link=_RUTA_IMAGES.$url;
				}

		    echo  "<li id_mul='$id_mul' id='mul-$id_mul' title='$nom' orden='' class='box-image box-image-block box-image-mul ui-state-default'>";
		    echo  "<i class='icn icn-sorteable icn-reorder'></i>";
				echo  "<div class='box-acciones-img'>";
				echo  "<a class='btn btn-eliminar-mul' eliminar='$id_mul' tipo_item='multimedia' id_mul='$id_mul'><i class='icn icn-close' /></a>";
				echo  "<a id_prod='$id_item' id_mul='$id_mul' mul='$ruta'  class='btn btn-editar-mul'><i class='icn icn-pencil' /></a>";
				echo  "</div>";

				if ($extension=="embed" ){
					$rutax =   _RUTA_WEB_NUCLEO;
					echo "<div class='box-embed'>".$embed."</div>";
					$link = "images/video-icon.png";
				}else{
					$rutax = _RUTA_IMAGES;
					$link = $ruta;
				}
				if($extension=="mp4"){
					$rutax = _RUTA_WEB_NUCLEO;
					echo "<i class='icon-play icn icn-play-circle'></i>";
					$link = "images/video-icon.png";
					echo "<video muted  src='"._RUTA_IMAGES.$link."' ></video>";
				}

		    echo "<img class='img-catalogo img-file img-responsive' id='img-".$i."' src='".$rutax.$link."' />";
				echo "<input type='hidden' id='inputModItemMul[]' name='inputModItemMul[]' value='$id_mul'/>";
		    echo "</li>";

		  }
		}
		echo "<li class='ui-state-disabled box-image-mul'><a upload='multimedia' vars='$id_item:$id_mod' seleccion='multiple'  class='btn btn-adicionar-mul btn-up-finder '><i class='icn icn-upload' /><span>$label</span></a></li>";
		echo "</ul>";
		echo "</div>";
		echo "</div>";
		?>
		<script type="text/javascript">
			$(document).ready(function() {
				<?php echo $this->btn_eliminar_mul_js(); ?>
				<?php echo $this->btn_editar_mul_js(); ?>
				$( "#sortable" ).sortable({
						start: function(event, ui) {
						var start_pos = ui.item.index();
						ui.item.data('start_pos', start_pos);
	        },
	        change: function(event, ui) {
						var start_pos = ui.item.data('start_pos');
						var index = ui.placeholder.index();
						var cant = <?php echo $num; ?>;
					},
	        update: function(event, ui) {
	            //$('.connectedSortable li').removeClass('highlights');
						var count=0;
						var amul="";
						var prod="<?php echo $id_prod; ?>";
						$(".box-multimedia li").each(function(i) {
							var am = $(this).attr("mul");
							amul  = amul +":"+ am;
							count++;
							$(this).attr("orden",count);
						});
	        },
					cancel: ".ui-state-disabled"
	      });

 				$( "#sortable" ).disableSelection();
				// $(".btn-eliminar-img").click( function(){
				// 	//console.log("hola");
				// 	$("#<?php echo $id; ?>").attr("value","");
				// 	$(".image-block").html("<a item='' tipo_item='' class='btn btn-adicionar-imagen btn-adicionar-imagen-nuevo btn-up-finder'><i class='icn icn-media-plus' /><span>Imagen principal</span></a>");
				// 	$(".btn-up-finder").click( function(){
				// 		insert = $(this).attr("insert");
				// 		item = $(this).attr("item");
				// 		tipo_item = $(this).attr("tipo_item");
				// 		$('.modal-finder').appendTo('body');
				// 		$(".modal-finder").addClass("on");
				// 		//$(".finder-item").height(w);
				// 		//resize_item();
				// 		//console.log (w);
				// 	});
				// });

			});
		</script>
		<?php
	}

	function imagen_form_block ($id,$id_item,$valor,$class_div){
		$valorx= $this->fmt->archivos->convertir_url_mini($valor);
		echo "<div class='form-group $class_div'>";
			echo "<div class='box-image box-image-block image-block'>";
		  if (!empty($valor)){
		 	 echo "<div class='box-acciones-img'>";
			 echo "<a class='btn btn-eliminar-img'><i class='icn icn-close' /></a>";
			 echo "<a id_item='$id_item' mul='$valor' class='btn btn-editar-img' ><i class='icn icn-pencil' /></a>";
			 echo "</div>";
		 	 echo "<img class='img-catalogo img-file img-responsive' id='img-".$id_item."' src='"._RUTA_IMAGES.$valorx."' />";
		 }else{
			 echo "<a item='' tipo_item='' class='btn btn-adicionar-imagen btn-adicionar-imagen-nuevo btn-up-finder'><i class='icn icn-media-plus' /><span>Imagen principal</span></a>";
		 }
		  echo "</div>";

		$this->fmt->form->input_hidden_form($id,$valor);
		echo "<ul class='box-multimedia' id='sortable'>";
		$consulta = "SELECT DISTINCT mod_prod_mul_prod_id,mul_url_archivo FROM mod_productos_mul,multimedia WHERE mod_prod_mul_prod_id='$id_item' and mod_prod_mul_mul_id=mul_id  ORDER BY mod_prod_mul_orden asc";
		$rs =$this->fmt->query->consulta($consulta,__METHOD__);
		$num=$this->fmt->query->num_registros($rs);
		$aux="";
		if($num>0){
		  for($i=0;$i<$num;$i++){
		    list($id_prod,$ruta)=$this->fmt->query->obt_fila($rs);
		    $url = $this->fmt->archivos->convertir_url_mini($ruta);
				$extension = $this->fmt->archivos->saber_extension_archivo($ruta);
				$nombre_archivo=$this->fmt->archivos->saber_nombre_archivo($ruta);
				if($extension=="mp4"){
					//$link = _RUTA_WEB_NUCLEO."images/video-icon.png";
					$link =_RUTA_IMAGES."archivos/multimedia/".$nombre_archivo.".jpg";
					$link = $this->fmt->archivos->convertir_nombre_thumb($link);
				}else{
					$link=_RUTA_IMAGES.$url;
				}

		    echo  "<li prod='$id_prod' mul='$ruta' id='mul-$i' class='box-image box-image-block ui-state-default'>";
		    echo  "<i class='icn icn-sorteable icn-reorder'></i>";
				echo  "<div class='box-acciones-img'>";
				echo  "<a class='btn btn-eliminar-file' id_item='$id_prod' mul='$ruta'><i class='icn icn-close' /></a>";
				echo  "<a id_prod='$id_prod' id_mul='mul-$i' mul='$ruta' class='btn btn-editar-img'><i class='icn icn-pencil' /></a>";
				echo  "</div>";
				if($extension=="mp4"){
					echo "<i class='icn icn-play-circle icn-play'></i>";
				}
		    echo  "<img class='img-catalogo img-file img-responsive' id='img-".$i."' src='".$link."' />";

		    echo  "</li>";
		  }
		}
		echo "<li class='ui-state-disabled'><a item='$id_item' class='btn btn-adicionar-imagen btn-up-finder '><i class='icn icn-upload' /></a></li>";
		echo "</ul>";

		echo "</div>";
		$this->editar_multimedia();
		?>
		<script type="text/javascript">
			$(document).ready(function() {
				$(".btn-eliminar-file").click( function(){
					var id= $(this).attr("idm");
					var mul = $(this).attr("mul");
					var prodx = $(this).attr("prod");
					$("#"+id).remove();
					var formdata = new FormData();
					formdata.append("inputMul", mul );
					formdata.append("inputProd", prodx );
					formdata.append("ajax", "ajax-eliminar-mul-prod");
					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
					$.ajax({
								url:ruta,
								type:"post",
								data:formdata,
								processData: false,
								contentType: false,
								success: function(msg){
									//console.log(msg);
								}
					});
				});
				$( "#sortable" ).sortable({
						start: function(event, ui) {
						var start_pos = ui.item.index();
						ui.item.data('start_pos', start_pos);
	        },
	        change: function(event, ui) {
						var start_pos = ui.item.data('start_pos');
						var index = ui.placeholder.index();
						var cant = <?php echo $num; ?>;
					},
	        update: function(event, ui) {
	            //$('.connectedSortable li').removeClass('highlights');
						var count=0;
						var amul="";
						var prod="<?php echo $id_prod; ?>";
						$(".box-multimedia li").each(function(i) {
							var am = $(this).attr("mul");
							amul  = amul +":"+ am;
							count++;
						});
						//console.log ( "p:"+prod );

						var formdata = new FormData();
						formdata.append("inputVars", amul );
						formdata.append("inputCant", count );
						formdata.append("inputProd", prod );
						formdata.append("ajax", "ajax-orden-mul-prod");
						var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
						$.ajax({
									url:ruta,
									type:"post",
									data:formdata,
									processData: false,
									contentType: false,

									success: function(msg){
										//console.log(msg);
									}
						});
	        }
	      });

 				$( "#sortable" ).disableSelection();
				$(".btn-eliminar-img").click( function(){
					//console.log("hola");
					$("#<?php echo $id; ?>").attr("value","");
					$(".image-block").html("<a item='' tipo_item='' class='btn btn-adicionar-imagen btn-adicionar-imagen-nuevo btn-up-finder'><i class='icn icn-media-plus' /><span>Imagen principal</span></a>");
					$(".btn-up-finder").click( function(){
						insert = $(this).attr("insert");
						item = $(this).attr("item");
						tipo_item = $(this).attr("tipo_item");
						$('.modal-finder').appendTo('body');
						$(".modal-finder").addClass("on");
						//$(".finder-item").height(w);
						//resize_item();
						//console.log (w);
					});
				});

			});
		</script>
		<?php
	}

	function select_form_simple($label,$id,$options,$valores,$desabilitado,$defecto,$class_div){
			?>
			<div class="form-group <?php echo $class_div; ?>">
				<label><?php echo $label; ?></label>
				<select class="form-control" id="<?php echo $id; ?>" name="<?php echo $id; ?>">
				<?php
					$n = count($options);
					for($i=0;$i<$n;$i++){
						if ($valores[$i]==$defecto){  $aux="selected";  }else{  $aux=""; }
		        if ($valores[$i]==$desabilitado){  $aux1="disabled";  }else{  $aux1=""; }
		        echo "<option class='' value='".$valores[$i]."' $aux $aux1 > ".$options[$i];
		        echo "</option>";
					}
				?>
				</select>
		</div>
		<?php
	}

	function select_form_cat_id($label,$id,$id_item,$div_class,$id_padre=""){
		?>
		<div class="form-group <?php echo $div_class; ?>">
			<label><?php echo $label; ?></label>
			<select class="form-control" id="<?php echo $id; ?>" name="<?php echo $id; ?>">
				<?php $this->fmt->categoria->traer_opciones_cat($id_item,$id_padre); ?>
			</select>
		</div>
		<?php
	}

	function input_check_form_bd($label,$nombreinput,$prefijo,$from,$prefijo_rel,$from_rel,$row,$valor_row,$row_rel,$div_class,$ckecked="",$disabled=""){
		?>
		<div class="form-group form-fluid <?php echo $div_class; ?>">
			<label><?php echo $label; ?>  </label>
			<div class="group">
		<?php

		$consulta= "SELECT DISTINCT ".$prefijo."id,".$prefijo."nombre,".$prefijo."descripcion FROM $from WHERE ".$prefijo."activar=1  ORDER BY ".$prefijo."nombre  asc";
		$rs =$this->fmt->query->consulta($consulta,__METHOD__);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
		  for($i=0;$i<$num;$i++){
		    list($id_item,$nombre)=$this->fmt->query->obt_fila($rs);
				$ck="";
				$dk="";
				if (!empty($consulta_rel)){
					$consulta_rel="SELECT DISTINCT ".$prefijo_rel."id FROM $from_rel WHERE ".$row."=$valor_row and $rol_rel=$id_item";
					$rsx =$this->fmt->query->consulta($consulta_rel,__METHOD__);
					$numx=$this->fmt->query->num_registros($rsx);
					$fila=$this->fmt->query->obt_fila($rs);
					$id_rel = $fila[$fila_rel];
				}
				if ($id_rel==$id_item){ $ck="checked"; }
				if (!empty($ckecked)){
					$ckr = explode(",",$ckecked);
					$num_ck = count($ckr);
					for ($k=0; $k < $num_ck ; $k++) {
						if ($id_item==$ckr[$k]){
							$ck="checked";
						}
					}
				}
				if (!empty($disabled)){
					$dkr = explode(",",$disabled);
					$num_dk = count($dkr);
					for ($k1=0; $k1 < $num_dk ; $k1++) {
						if ($id_item==$dkr[$k1]){
							$dk="disabled";
						}
					}
				}

				?>
				<div class="checkbox">
					 <label>
						 <input type="checkbox" name="<?php echo $nombreinput[$i]; ?>" id="<?php echo $nombreinput[$i]; ?>" value="<?php echo $id_item; ?>" <?php echo $ck." ".$dk; ?>> <?php echo $nombre; ?>
					 </label>
				</div>
			<?php
			}
		}
		?>
		</div>
	</div>
		<?php
	}

	function input_check_form($label,$nombreinput,$valor,$campo){
		?>
		<div class="form-group <?php echo $div_class; ?>">
		<?php
		$campo_in[0]="1";
		$num = count($nombreinput);
		for($i=0;$i<$num;$i++){
			$ck="";
			if(in_array($valor[$i], $campo))
				$ck="checked";
			?>

				<div class="checkbox form-control form-control-free">
					 <label>
						 <input type="checkbox" name="<?php echo $nombreinput[$i]; ?>" id="<?php echo $nombreinput[$i]; ?>" value="<?php echo $valor[$i]; ?>" <?php echo $ck; ?>> <?php echo $label[$i]; ?>
					 </label>
				</div>
			<?php
		}
		?>
	</div>
		<?php
	}

	function check_form($label,$id,$valor,$campo,$check=""){
		?>
		<div class="form-group form-<?php echo $id; ?>">
			<label><?php echo $label; ?></label>
			<div class="group">
			<?php
			$num = count($valor);
			for($i=0;$i<$num;$i++){
				$ck="";
				if (!empty($check)){
					if($valor[$i] == $check[$i]){ $ck="checked"; }
				}
				?>
				<div class="checkbox">
					 <span>
						 <input type="checkbox" name="<?php echo $id; ?>[]" id="<?php echo $id; ?>[]" value="<?php echo $valor[$i]; ?>" <?php echo $ck; ?> > <?php echo $campo[$i]; ?>
					 </span>
				</div>
				<?php
			}
		?>
		</div>
	</div>
		<?php
	}
	function radio_form($label,$id,$valor,$campo,$check=""){
		//var_dump( $check );
		?>
		<div class="form-group form-<?php echo $id; ?>">
			<label><?php echo $label; ?></label>
			<div class="group">
			<?php
			$num = count($valor);
			for($i=0;$i<$num;$i++){
				$ck="";
				if (!empty($check)){
					if( $valor[$i]== $check ){ $ck="checked"; }
				}
				?>
				<div class="radio-box">
						<input type="radio" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo $valor[$i]; ?>" <?php echo $ck; ?> > <span><?php echo $campo[$i]; ?></span>
				</div>
				<?php
			}
		?>
		</div>
	</div>
		<?php
	}

  function radio_activar_form($valor){
    ?>
    <div class="form-group">
      <label class="radio-inline">
        <input type="radio" name="inputActivar" id="inputActivar" value="0" <?php if ($valor==0){ echo "checked"; } ?> > Desactivar
      </label>
      <label class="radio-inline">
        <input type="radio" name="inputActivar" id="inputActivar" value="1" <?php if ($valor==1){ echo "checked"; $aux="Activo"; } else { $aux="Activar"; } ?> > <?php echo $aux; ?>
      </label>
    </div>
    <?php
  }

	function vista_item($estado,$input_ref){
	?>
		<div class="form-vista">
			<?php
				if( $estado==1){
	      		echo "<a  class='btn btn-v-activar'  estado='1' ><i class='icn icn-eye-open'></i><span>Ocultar</span></a>";
	  		}else{
	      		echo "<a  class='btn btn-v-activar'  estado='0' ><i class='icn icn-eye-close'></i><span>Hacer visible</span></a>";
	  		};
	    ?>
			<input type="hidden" name="inputActivar" id="inputActivar" value="<?php echo $estado; ?>">
			<script type="text/javascript">
				$(document).ready(function() {
					$(".form-vista").prependTo("<?php echo $input_ref; ?> .group");
					$(".form-vista .btn-v-activar").hover( function(){
						$(".form-vista .btn-v-activar span").css("display","inline-block");
					});
					$(".form-vista .btn-v-activar").mouseleave(function(event) {
						/* Act on the event */
						$(".form-vista .btn-v-activar span").css("display","none");
					});
					$(".form-vista .btn-v-activar").click(function(event) {
						var estado = $(this).attr("estado");
						// console.log(estado);
						if ( estado==1 ){
							$(".form-vista .btn-v-activar i").removeClass();
							$(".form-vista .btn-v-activar").attr('estado','0');
							$(".form-vista .btn-v-activar i").addClass('icn icn-eye-close');
							$(".form-vista .btn-v-activar span").html('Hacer visible');
							$("#inputActivar").val('0');
						}else{
							$(".form-vista .btn-v-activar i").removeClass();
							$(".form-vista .btn-v-activar").attr('estado','1');
							$(".form-vista .btn-v-activar i").addClass('icn icn-eye-open');
							$(".form-vista .btn-v-activar span").html('Ocultar');
							$("#inputActivar").val('1');
						}
					});
				});
			</script>
		</div>
	<?php
	}

  function botones_editar($fila_id,$fila_nombre,$nombre,$id_mod){
    ?>
    <div class="form-group form-botones">
			<div class="group">
				<button type="button" class="btn btn-info  btn-actualizar btn-form hvr-fade" name="btn-accion" form="<?php echo $nombre; ?>" id="btn-activar" value="actualizar"><i class="icn-sync" ></i> Actualizar</button>
			</div>
    </div>
    <?php
  }

	function btn_actualizar($id_form,$id_mod,$tarea,$div_class){
		$this->fmt->form->hidden_modulo($id_mod,$tarea);
    ?>
    <div class="form-group form-botones box-botones-form clear-both <?php echo $div_class; ?>">
			<div class="group">
				<button type="button" class="btn btn-info  btn-actualizar btn-form hvr-fade" name="btn-accion" form="<?php echo $id_form; ?>" id="btn-activar" value="actualizar"><i class="icn-sync" ></i> Actualizar</button>
		 	</div>
    </div>
    <?php
  }

  function botones_nuevo($form,$id_mod,$modo="",$tarea){
		$this->fmt->form->hidden_modulo($id_mod,$tarea);
    ?>
    <div class="form-group form-botones box-botones-form">
			<div class="group">
       <button type="button" class="btn-accion-form btn btn-info  btn-guardar btn-form<?php echo $modo; ?>" name="btn-accion" form="<?php echo $form;?>" id="btn-guardar" value="guardar"><i class="icn-save" ></i> Guardar</button>
       <button type="button" class="btn-accion-form btn btn-success btn-form btn-activar" name="btn-accion" form="<?php echo $form;?>" id="btn-activar" value="activar"><i class="icn-eye-open" ></i> Activar</button>
			</div>
    </div>
    <?php
  }

	function botones_acciones_form($acciones="ver,editar,eliminar",$id_mod,$id_item,$nombre,$botones=""){
		//echo '  <td class="col-acciones acciones">';
		$acc_a = explode(",",$acciones);
		$n_a = count($acc_a);
		echo $botones;
		for ($i=0; $i < $n_a; $i++) {
			if ($acc_a[$i]=="ver"){
				echo $this->fmt->class_pagina->crear_btn_m("","icn-search","ver ".$id_item,"btn btn-accion btn-m-ver ",$id_mod,"form_ver,".$id_item); //$nom,$icon,$title,$clase,$id_mod,$vars,$attr,$nombre=""
			}
			if ($acc_a[$i]=="editar"){
			echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar ".$id_item,"btn btn-accion btn-m-editar ",$id_mod,"form_editar,".$id_item);
			}
			if ($acc_a[$i]=="eliminar"){
			echo $this->fmt->class_pagina->crear_btn_m("","icn-trash","eliminar ".$id_item,"btn btn-accion btn-fila-eliminar",$id_mod,"eliminar,".$id_item,"",$nombre);
			}
		}

		//echo '  </td>';
	}

	function btn_acciones_form($id_mod,$id_item,$nombre,$aux=""){
		echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar ".$id_item,"btn btn-accion btn-m-editar ",$id_mod,"form_editar,".$id_item);
		?>
		<a  title="eliminar <?php echo $id_item; ?>" type="button" ide="<?php echo $id_item; ?>" nombre="<?php echo $nombre; ?>" id_mod="<?php echo $id_mod; ?>" tarea="eliminar" vars="eliminar,<?php echo $id_item; ?>"  class="btn btn-fila-eliminar btn-accion <?php echo $aux; ?>"><i class="icn-trash"></i></a>
		<?php
	}

  function btn_nuevo($form,$modo,$id_mod,$tarea){
			$this->fmt->form->hidden_modulo($id_mod,$tarea);
    ?>
    <div class="form-group form-botones">
			<div class="group">
	       <button type="button" class="btn-accion-form btn btn-info  btn-guardar btn-form<?php echo $modo; ?> color-bg-celecte-b btn-lg" name="btn-accion" form="<?php echo $form;?>" id="btn-guardar" value="guardar"><i class="icn-save" ></i> Guardar</button>
	       <button type="button" class="btn-accion-form btn btn-success btn-form color-bg-verde btn-activar btn-lg" name="btn-accion" form="<?php echo $form;?>" id="btn-activar" value="activar"><i class="icn-eye-open" ></i> Activar</button>
		 	</div>
    </div>
    <?php
  }

  function footer_page($modo){
    if ($modo!="modal"){ ?>
      </form>
    <?php } ?>
    </div>
    <?php
  }

  function botones_acciones($id,$class,$href,$title,$icono,$tarea,$nom,$ide){
	  if (!empty($href)){ $auxr="href='".$href."'"; }else{$auxr="";}
	  ?>
		<a  id="<?php echo $id; ?>" type="button" class="<?php echo $class; ?>" <?php echo $auxr; ?> title="<?php echo $title; ?>" alt="<?php echo $title; ?>"
			tarea="<?php echo $tarea; ?>" nombre="<?php echo $nom; ?>" ide="<?php echo $ide; ?>" ><i class="<?php echo $icono; ?>" ></i></a>
		<?php
  }

  function form_head_form_editar($nom,$from,$prefijo,$id_mod,$class,$archivo,$id){
			$consulta= "SELECT * FROM ".$from." WHERE ".$prefijo."id='".$id."'";
			$rs =$this->fmt->query->consulta($consulta,__METHOD__);
			$fila=$this->fmt->query->obt_fila($rs);
			//var_dump($fila);
			$this->head_editar($nom,$archivo,$id_mod,'','form_editar',$class); //$nom,$archivo,$id_mod,$botones,$id_form,$class
			return $fila;
	}

}
?>
