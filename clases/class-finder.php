<?PHP
header("Content-Type: text/html;charset=utf-8");

class FINDER{

	var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

  function finder_window(){

    ?>
		<script type="text/javascript">
			$(".modal-finder").ready(function() {
			$(".btn-up-finder").click(function(event) {
				var insert = $(this).attr("insert");
				var upload = $(this).attr("upload");
				var vars= $(this).attr("vars");
				var seleccion = $(this).attr("seleccion");
				//console.log("log2:"+insert+":"+upload+":"+vars+":"+seleccion);
				cargar_finder(insert,upload,vars,seleccion);
			}); //.btn-up-finder

				<?php $this->carga_finder(); ?>

	    }); //fin document
    </script>
    <?php
		$this->fmt->form->modal_editar_multimedia();
		$this->fmt->form->editar_multimedia();
  }

	function carga_finder(){
		?>
		function cargar_finder(insert,upload,vars,seleccion){
			$(".modal-finder .modal-finder-inner").html("");
			//console.log("log1:"+insert+":"+upload+":"+vars+":"+seleccion);
			$(".modal-finder").addClass('on');

			var form_finder = new FormData();
			form_finder.append("insert", insert);
			form_finder.append("upload", upload);
			form_finder.append("seleccion", seleccion);
			form_finder.append("vars", vars);
			form_finder.append("ajax", "ajax-finder");
			var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
			$.ajax({
				url:ruta,
				type:"post",
				data:form_finder,
				processData: false,
				contentType: false,
				success: function(msg){
					$(".modal-finder .modal-finder-inner").html(msg);
					$(".btn-finder-cancelar").click(function(event) {
						$(".modal-finder").removeClass('on');
					});

					if ( upload == "imagen-unica" ){
						$(".group .category").hide();
						$(".box-upload-docs").hide();
						$(".box-upload-embed").hide();
						$(".box-album").hide();
						$(".group #tab-todos").show();
						$(".group #tab-imagenes").show();
					}
					if ( upload == "video-unico" ){
						$(".group .category").hide();
						$(".box-upload-docs").hide();
						$(".box-upload-embed").show();
						$(".box-album").hide();
						$(".group #tab-videos").show();
						$(".group #tab-todos").show();
					}
					if ( upload == "multimedia" ){
						$(".group .category").hide();
						$(".group #tab-videos").show();
						$(".group #tab-imagenes").show();
						$(".group #tab-albums").hide();
						$(".box-upload-docs").hide();
						$(".box-album").hide();
						$(".box-upload-embed").hide();
						$(".group #tab-todos").show();
					}
					if ( upload == "documentos" ){
						$(".group .category").hide();
						$(".group #tab-videos").hide();
						$(".group #tab-imagenes").hide();
						$(".box-upload-embed").hide();
						$(".group #tab-albums").hide();
						$("#formup").hide();
						$(".box-upload-docs").show();
						$(".box-album").hide();
						$(".group #tab-todos").show();
						$(".group #tab-documentos").show();
					}

					if(upload=="insertar-editor-texto"){
						$(".group .category").hide();
						$(".box-upload-docs").hide();
						$(".box-upload-embed").hide();
						$(".box-album").hide();
						$(".group #tab-todos").show();
						$(".group #tab-imagenes").show();
					}

					<?php  echo $this->finder_item();  ?>

					 $(".btn-finder-insertar").click(function(){

						 x_id = $(".finder-item[seleccionado='on']").attr("id");
						 xn_id = $(".finder-item[seleccionado='on']").size();
						 x_url = $(".finder-item[seleccionado='on']").attr("url");
						 x_mul_item = $(".finder-item[seleccionado='on']").attr("item");
						 x_nombre = $(".finder-item[seleccionado='on']").attr("nombre");
						 x_urlmini = $(".finder-item[seleccionado='on']").attr("url_mini");
						 x_tipo_item = $(".finder-item[seleccionado='on']").attr("tipo_item");
						 x_id_item = $(".finder-item[seleccionado='on']").attr("id_item");
						 x_item_embed = $(".finder-item[seleccionado='on']").attr("embed");

						if ( upload == "imagen-unica" ){
							//console.log(insert+":"+upload+":"+x_url+":"+x_urlmini+":"+vars);
							$("#"+insert).attr("value",x_url);
							$("#"+vars).attr("value",x_tipo_item);
							$("#box-mul-"+insert+" .btn-editar-mul").attr("mul",x_url);
							$("#box-mul-"+insert+" .btn-editar-mul").attr("id_mul",x_mul_item);
							$("#box-mul-"+insert+" .btn-eliminar-mul").attr("id_mul",x_mul_item);
							$("#img-"+insert).attr("src","<?php echo _RUTA_WEB; ?>"+x_urlmini);
							$("#img-"+insert).removeClass('off');
							$(".btn-up-finder-"+insert).addClass('off');
							$("#box-mul-"+insert).removeClass('off');
						}
						if ( upload == "video-unico" ){
							console.log(insert+":"+upload+":"+x_mul_item);

							$("#"+insert).attr("value",x_mul_item);
							$("#box-mul-"+insert+" .btn-editar-mul").attr("mul",x_url);
							$("#box-mul-"+insert+" .btn-editar-mul").attr("id_mul",x_mul_item);
							$("#box-mul-"+insert+" .btn-eliminar-mul").attr("id_mul",x_mul_item);
							$(".box-mul-nombre-"+insert).html(x_nombre);
							$("#img-"+insert).removeClass('off');
							$("#box-mul-"+insert).removeClass('off');
							$("#img-"+insert).attr("src","<?php echo _RUTA_WEB_NUCLEO; ?>images/video-icon.png");
							$(".btn-up-finder-"+insert).addClass('off');
						}

						if(upload=="insertar-editor-texto"){
			        var imgx =  "<img src='<?php echo _RUTA_WEB; ?>"+x_url+"' >";
			        var ix = "<?php echo _RUTA_WEB; ?>"+x_url;
		          $('#'+insert).summernote('editor.restoreRange');
		          $('#'+insert).summernote('editor.focus');
		          $('#'+insert).summernote('editor.insertImage',ix);
						}

						if (upload == "multimedia"){
							var elementos = $(".finder-item[seleccionado='on']");
							var size = elementos.size();
							//var arrayID = [];
							var aux="";
							$.each( elementos, function(i, val){
								var temp_id = $(val).attr('id');
								var temp_id_mul = $("#"+temp_id).attr('item');
								var temp_tipo_item = $("#"+temp_id).attr('tipo_item');
								var temp_url = $("#"+temp_id).attr('url');
								var temp_url_mini = $("#"+temp_id).attr('url_mini');
								var temp_embed = $("#"+temp_id).attr('embed');

								aux = aux + "<li item='"+x_id_item+"' id_mul='"+temp_id_mul+"' id='mul-"+temp_id_mul+"' class='box-image box-mul-"+temp_tipo_item+" box-image-block box-image-mul ui-state-default'>";
								aux = aux + "<i class='icn icn-sorteable icn-reorder'></i>";
								aux = aux +  "<div class='box-acciones-img'>";
								aux = aux +  "<a class='btn btn-eliminar-mul' eliminar='"+temp_id_mul+"' tipo_item='multimedia' id_mul='"+temp_id_mul+"'><i class='icn icn-close' /></a>";
								aux = aux +  "<a id_mul='"+temp_id_mul+"' mul="+temp_url+" tipo_item='multimedia'   class='btn btn-editar-mul'><i class='icn icn-pencil' /></a>";
								aux = aux + "</div>";

								if (temp_tipo_item=="embed" ){
									ruta = "<?php echo _RUTA_WEB_NUCLEO; ?>";
									aux = aux + "<div class='box-embed'>"+temp_embed+"</div>";
									url_final = "images/video-icon.png";
								}else{
									ruta = "<?php echo _RUTA_IMAGES; ?>";
									url_final = temp_url_mini;
								}
								if(temp_tipo_item=="mp4"){
									ruta = "<?php echo _RUTA_WEB_NUCLEO; ?>";
									aux = aux + "<i class='icon-play icn icn-play-circle'></i>";
									url_final = "images/video-icon.png";
									aux = aux + "<video muted  src='<?php echo _RUTA_IMAGES; ?>"+temp_url+"' ></video>";
								}
								aux = aux +  "<img class='img-catalogo img-file img-responsive' id='img-"+i+"' src='"+ruta+url_final+"' />";
								aux = aux +  "<input type='hidden' id='inputModItemMul[]' name='inputModItemMul[]' value='"+temp_id_mul+"'  />"
								aux = aux +  "</li>";
							});
							$(".box-multimedia").prepend( aux );
							$(".btn-eliminar-mul").click(function(){
								var id = $(this).attr("eliminar");
								//console.log(id);
								$("#mul-"+id).remove();
							});
						}

						if (upload == "documentos"){
							var elementos = $(".finder-item[seleccionado='on']");
							var size = elementos.size();
							var aux="";
							$.each( elementos, function(i, val){
								var temp_id = $(val).attr('id');
								var temp_item = $("#"+temp_id).attr('item');
								var temp_tipo_item = $("#"+temp_id).attr('tipo_item');
								var temp_url = $("#"+temp_id).attr('url');
								var temp_url_mini = $("#"+temp_id).attr('url_mini');
								var temp_embed = $("#"+temp_id).attr('embed');
								var nom = $("#"+temp_id).attr('nombre');

								aux = aux + "<li item='"+temp_item+"'  id='doc-"+temp_item+"' class='box-doc-li box-doc-"+temp_tipo_item+" ui-state-default'>";
								aux = aux + "<i class='icn icn-sorteable icn-reorder'></i>";
								aux = aux + "<div class='box-acciones-docs ui-state-disabled'>";

								aux = aux + "<a class='btn btn-eliminar-doc' eliminar='"+temp_item+"' tipo_item='documento' id_doc='"+temp_item+"'><i class='icn icn-close' /></a>";
								aux = aux + "<a class='btn btn-editar-doc' id_doc='"+temp_item+"'><i class='icn icn-pencil' /></a>";
								aux = aux + "<a target='_blank' href='<?php echo _RUTA_WEB; ?>archivos/docs/"+temp_url+"' class='btn btn-link'><i class='icn icn-skip'/></a>";
								
								aux = aux + "</div>";

								aux = aux +  "<i class='icn icon-tipo icn-"+ temp_tipo_item +"'/></i> <span class='nombre'>"+ nom +"</span>";
								aux = aux +  "<input type='hidden' id='inputModItemDoc[]' name='inputModItemDoc[]' value='"+temp_item+"'  />";
								aux = aux +  "</li>";
							});
							$("#sortable-docs").prepend( aux );
							$(".btn-eliminar-doc").click(function(){
								var id = $(this).attr("eliminar");
								//console.log(id);
								$("#doc-"+id).remove();
							});
						}
						$(".modal-finder").removeClass('on');

					 });

					var formdata_img = new FormData();
					formdata_img.append("ajax", "ajax-finder-img");
					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
					$.ajax({
						url:ruta,
						type:"post",
						data:formdata_img,
						processData: false,
						contentType: false,
						success: function(msg){
							//console.log(msg);
							$("#finder-imagenes").html(msg);
							<?php  echo $this->finder_item();  ?>
							$('#filtrar-imagenes').keyup(function () {
						    var rex = new RegExp($(this).val(), 'i');
						    $('#finder-imagenes li').hide();
						    $('#finder-imagenes li').filter(function () {
						        return rex.test($(this).text());
						    }).show();
						  });
						}
					});

					var formdata_alb = new FormData();
					formdata_alb.append("ajax", "ajax-finder-alb");
					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
					$.ajax({
						url:ruta,
						type:"post",
						data:formdata_alb,
						processData: false,
						contentType: false,
						success: function(msg){
							//console.log(msg);
							$("#finder-albums").html(msg);
							<?php  echo $this->finder_item();  ?>
						}
					});

					var formdata_audio = new FormData();
					formdata_audio.append("ajax", "ajax-finder-audio");
					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
					$.ajax({
						url:ruta,
						type:"post",
						data:formdata_audio,
						processData: false,
						contentType: false,
						success: function(msg){
							//console.log(msg);
							$("#finder-audio").html(msg);
							<?php  echo $this->finder_item();  ?>
						}
					});

					var formdata_docs = new FormData();
					formdata_docs.append("ajax", "ajax-finder-docs");
					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
					$.ajax({
						url:ruta,
						type:"post",
						data:formdata_docs,
						processData: false,
						contentType: false,
						<?php echo $this->cargar_reload("content-documentos"); ?>
						success: function(msg){
							//console.log(msg);
							$("#content-documentos .progreso").hide();
							$("#finder-documentos").html(msg);
							<?php  echo $this->finder_item();  ?>
						}
					});

					var formdata_video = new FormData();
					formdata_video.append("ajax", "ajax-finder-video");
					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
					$.ajax({
						url:ruta,
						type:"post",
						data:formdata_video,
						processData: false,
						contentType: false,
						success: function(msg){
							//console.log(msg);
							$("#finder-videos").html(msg);
							<?php  echo $this->finder_item();  ?>
						}
					});

					$(".group-tabs .category").click(function(){
						var idf = $(this).attr("idtab");
						// alert(idf);
						$(".tab-content").removeClass("on");
						$(".category").removeClass("active");
						$("#tab-"+idf).addClass("active");
						$("#content-" + idf).addClass("on");
					});

					$(".btn-subir-embed").click(function(){
						$(".block-form-embed").toggleClass('on');
					});

					$(".btn-crear-album").click(function(){
						if ($(this).attr("activo")==0){
						 $("#finder-imagenes .finder-item").append('<span class="checkbox-item"><input name="inputCheckAlbum[]" type="checkbox"/></span>');
						 $("#finder-videos .finder-item").append('<span class="checkbox-item"><input name="inputCheckAlbum[]" type="checkbox"/></span>');
						 $("#finder-audio .finder-item").append('<span class="checkbox-item"><input name="inputCheckAlbum[]" type="checkbox"/></span>');
						 $(".btn-crear-album").addClass('disabled');
						 $(".btn-crear-album").html('<i class="icn icn-unchecked"></i> Seleccionar imagenes');
					 }else{
						 $(".block-form-album").addClass('on');
					 }

						$(".checkbox-item input").change(function() {
							$(".checkbox-item").each(function() {
								if( $(".checkbox-item input").is(':checked') ) {
									$(".btn-crear-album").removeClass("disabled");
									$(".btn-crear-album").html('<i class="icn icn-checked"></i> Selección completa');
									$('.btn-crear-album').attr('activo','1');
								}else{
									$(".btn-crear-album").addClass("disabled");
									$(".btn-crear-album").html('<i class="icn icn-unchecked"></i> Seleccionar imagenes');
									$('.btn-crear-album').attr('activo','0');
								}
							});
						});
					});

					$(".btn-agregar-album").click(function(){
						var form_crear_alb = new FormData();
						var inputNombre = $("#form-album #inputNombre").val();
						var inputTags = $("#form-album #inputTags").val();
						var inputDescripcion = $("#form-album #inputDescripcion").val();
						var mul = Array();
						var i=0;
						$('.checkbox-item input:checked').each(function(){
								mul[i] =  $(this).parent().parent().attr("item");
								i = i + 1;
						});
						form_crear_alb.append("inputNombre", inputNombre);
						form_crear_alb.append("inputTags", inputTags);
						form_crear_alb.append("inputDescripcion", inputDescripcion);
						form_crear_alb.append("inputMul", mul);
						form_crear_alb.append("ajax", "ajax-save-album");
						var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
						$.ajax({
							url:ruta,
							type:"post",
							data:form_crear_alb,
							processData: false,
							contentType: false,
							success: function(msg){
								//console.log(msg);
								var res = msg.split(",");
								if (res[2]=="images/image-icon.png"){
									ruta_url = "<?php echo _RUTA_WEB_NUCLEO; ?>";
								}else{
									ruta_url = "<?php echo _RUTA_IMAGES; ?>";
								}
								$("#finder-figures").append("<li class='finder-item' id='mul-"+res[0]+"' item='"+res[0]+"' url='' tipo_item='album'  style='background:url("+ruta_url+res[2]+") no-repeat; background-size:cover'><i class='icn icn-albums'></i><span class='texto-embed'>"+res[1]+"</span></li>");
								<?php  echo $this->finder_item();  ?>
							}
						});
						$("#finder-imagenes .finder-item .checkbox-item").remove();
						$("#finder-videos .finder-item .checkbox-item").remove();
						$("#finder-audio .finder-item .checkbox-item").remove();
						$(".block-form-album").removeClass('on');
						$(".btn-crear-album").html('<i class="icn icn-plus"></i>Crear Album');
						$('.btn-crear-album').attr('activo','0');
					}); // fin .btn-agregar-embed

					$(".btn-cancelar-album").click(function(){
						$("#finder-imagenes .finder-item .checkbox-item").remove();
						$("#finder-videos .finder-item .checkbox-item").remove();
						$("#finder-audio .finder-item .checkbox-item").remove();
						$(".btn-crear-album").html('<i class="icn icn-plus"></i>Crear Album');
						$('.btn-crear-album').attr('activo','0');
						$(".block-form-album").removeClass('on');
					});



					$(".btn-agregar-embed").click(function(){
						var formdata_e = new FormData();
						var inputNombre = $("#inputNombre").val();
						var inputTags = $("#inputTags").val();
						var inputEmbed = $("#inputEmbed").val();
						formdata_e.append("inputNombre", inputNombre);
						formdata_e.append("inputTags", inputTags);
						formdata_e.append("inputEmbed", inputEmbed);
						formdata_e.append("ajax", "ajax-save-embed");
						var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
						$.ajax({
									url:ruta,
									type:"post",
									data:formdata_e,
									processData: false,
									contentType: false,
									success: function(msg){
										console.log(msg);
										var res = msg.split(",");

										$("#finder-figures").append("<li class='finder-item' id='mul-"+res[0]+"' item='"+res[0]+"' url='' tipo_item='embed' nombre='"+res[1]+"'  style='background:url(<?php echo _RUTA_WEB_NUCLEO; ?>images/embed-icon.png) no-repeat; background-size:cover'><span class='video-embed'>" + res[2] + "</span><span class='texto-embed'>"+res[1]+"</span></li>");
										$(".block-form").removeClass('on');
										$("#inputNombre").attr("value","");
										$("#inputTags").attr("value","");
										$("#inputEmbed").attr("value","");
										<?php  echo $this->finder_item();  ?>
									}
						});
					}); // fin .btn-agregar-embed

					<?php  //echo $this->finder_item();  ?>

					var xd = 0;
					$("#inputArchivoFinder").change( function(){
						xd = xd + 1;
						//resize_item();
						var formData = new FormData();
						var arc = document.getElementById("inputArchivoFinder");
						var archivo = arc.files;
						console.log( "cantidad archivos:"+archivo.length );
						formData.append("cant_file",archivo.length);
						var num =archivo.length;
						for(i=0; i< num; i++){
							formData.append("file-"+i,archivo[i]);
							$("#finder-figures").append("<li class='finder-item' tipo_item='' id='todos-mul-temporal-"+ i +"' class='todos-mul-temporal' url_mini='' url='' style=''><div class='progreso'>0%</div></li>");
						}
						formData.append("ajax","ajax-finder-up");
						formData.append("x_item",xd);
						var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
						//$("#finder-figures").append("</li>");
						//resize_item();
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
									for(i=0; i< num; i++){
										$("#todos-mul-temporal-"+i+" .progreso").html(dat+"%");
									}	
								};
								return xhr;
							},
							success: function(datos){
								for(i=0; i< num; i++){
									$("#todos-mul-temporal-"+i).remove();
								}
								$("#finder-figures").append(datos);
								<?php  echo $this->finder_item();  ?>
							}
						});
					}); // fin inputArchivoFinder

					var xdx = 0;
					$("#inputDocFinder").change( function(){
						xdx = xdx + 1;
						//resize_item();
						var formData = new FormData();
						var arc = document.getElementById("inputDocFinder");
						var archivo = arc.files;
						//console.log( archivo.length );
						formData.append("cant_file",archivo.length);
						var num =archivo.length;
						for(i=0; i< num; i++){
							formData.append("file-"+i,archivo[i]);
							$("#finder-figures").append("<li class='finder-item' tipo_item='' id='todos-mul-temporal-"+ i +"' class='todos-mul-temporal' url_mini='' url='' style=''><div class='progreso'>0%</div></li>");
						}
						formData.append("ajax","ajax-finder-docs-up");
						formData.append("x_item",xd);
						var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
						$("#finder-figures").append("</li>");
						//resize_item();
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
									for(i=0; i< num; i++){
										$("#todos-mul-temporal-"+i+" .progreso").html(dat+"%");
									}
									//resize_item();
								};
								return xhr;
							},
							success: function(datos){
								for(i=0; i< num; i++){
									$("#todos-mul-temporal-"+i).remove();
								}
								//$("#finder-figures #todos-mul-temporal").remove();
								$("#finder-figures").append(datos);
								<?php  echo $this->finder_item();  ?>
							}
						});
					});

				}
			});

		}
		<?php
	}

	function cargar_reload($id){
		return 'xhr: function() {
			var xhr = $.ajaxSettings.xhr();
			xhr.upload.onprogress = function(e) {
				var dat = Math.floor(e.loaded / e.total *100);
				$("#'.$id.'").append("<div class=\'progreso\'><span class=\'progreso-inner\'><span class=\'texto\'>"+ dat +"%</span></span></div></li>");
			};
			return xhr;
		},';
	}
	function finder_item(){
		return '$(".finder-item").click(function(){
			var id = $(this).attr("id");
			var id_x = $(this).attr("seleccionado");
			if (seleccion!="multiple"){
			  $(".finder-item").removeClass("on");
			  $(".finder-item").attr("seleccionado","off");
			}
			if (id_x == "on"){
				$(".finder #"+id).removeClass("on");
				$(".finder #"+id).attr("seleccionado","off");
			}else{
				$(".finder #"+id).addClass("on");
				$(".finder #"+id).attr("seleccionado","on");
			}
			//console.log(id_x);
		});';
	}

}
