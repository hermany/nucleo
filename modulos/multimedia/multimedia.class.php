<?php
header("Content-Type: text/html;charset=utf-8");

class MULTIMEDIA{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
		var $ruta_modulo;

	function MULTIMEDIA($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

  function busqueda(){
	 // $ruta_server=_RUTA_SERVER;
    $this->fmt->class_pagina->crear_head( $this->id_mod, $botones); // bd, id modulo, botones
    ?>
		<link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO; ?>css/m-multimedia.css" rel="stylesheet" type="text/css">
		<div class="body-modulo container-fluid">
			<div class="container">
				<?php
					
					
					$botones = $this->fmt->class_pagina->crear_btn_m("Crear embed","icn-plus","Crear Embed","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo_embed");

					$botones .= $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Crear","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");
					 //$nom,$icon,$title,$clase,$id_mod,$vars
					$this->fmt->class_pagina->head_modulo_inner("Multimedia activos", $botones); // bd, id modulo, botones
					$this->fmt->form->head_table("table_id");
					$this->fmt->form->thead_table('Previo:Archivo:Autor:Categoria:Fecha:Estado:Acciones','img-previo');
					$this->fmt->form->tbody_table_open();
					$consulta = "SELECT mul_id,mul_nombre,mul_url_archivo,mul_tipo_archivo,mul_id_dominio,mul_usuario,mul_fecha,mul_embed,mul_activar FROM multimedia ORDER BY mul_id desc";
					$rs =$this->fmt->query->consulta($consulta);
					$num=$this->fmt->query->num_registros($rs);
					if($num>0){
					  for($i=0;$i<$num;$i++){
					    $row=$this->fmt->query->obt_fila($rs);
							$fila_id = $row["mul_id"];
							$fila_nombre = $row["mul_nombre"];
							$fila_url = $row["mul_url_archivo"];
							$fila_tipo = $row["mul_tipo_archivo"];
							$fila_dominio = $row["mul_id_dominio"];
							$fila_usuario = $row["mul_usuario"];
							$fila_fecha = $row["mul_fecha"];
							$fila_embed = $row["mul_embed"];
							$fila_activar = $row["mul_activar"];
					    echo "<tr class='row row-".$fila_id."'>";
					      echo '<td class="img-previo">';
					      //echo $fila_url;
								//echo _MULTIPLE_SITE;
					      if( ($fila_tipo!="mp4") && ($fila_tipo!="embed")){
									if (_MULTIPLE_SITE!="off"){
										if (empty($fila_dominio)){
											$aux=_RUTA_WEB; } else { $aux = $this->fmt->categoria->traer_dominio_cat_id($fila_dominio);
										}
									}else{
										 $aux=_RUTA_WEB;
									}

									$mystring = $fila_url;
									$findme   = 'http';
									$pos = strpos($mystring, $findme);
									if ($pos===false){
										$imgx=$this->fmt->archivos->convertir_url_thumb($fila_url);
										if(file_exists(_RUTA_HOST.$imgx)){
											$img=$aux.$imgx;
										}else{
											$img=_RUTA_WEB_NUCLEO."images/image-icon.png";
											if ($fila_tipo=="mp3"){
												$img=_RUTA_WEB_NUCLEO."images/audio-icon.png";
											}
										}
									}else{
										$img = $fila_url;
									}

					      }else{
					        $img=_RUTA_WEB_NUCLEO."images/video-icon.png";
									if ($fila_tipo=="mp3"){
										$img=_RUTA_WEB_NUCLEO."images/audio-icon.png";
									}
					      }
					      echo '<img src="'.$img.'" width="60px">';
					      echo '</td>';
					      echo '<td class=""><strong>'.$fila_nombre.'</strong> ( '.$fila_tipo.' )</td>';
					      echo '<td class="">'.$this->fmt->usuario->nombre_usuario( $fila_usuario ).'</td>';
					      echo '<td class="">';
					        //$this->fmt->categoria->traer_rel_cat_nombres($fila_id,'multimedia_categorias','mul_cat_cat_id','mul_cat_mul_id'); //$fila_id,$from,$prefijo_cat,$prefijo_rel
					        $this->traer_rel_cat_nombres($fila_id); //$fila_id,$from,$prefijo_cat,$prefijo_rel
					      echo '</td>';
								$fh =$fila_fecha;
								$fecha_hoy= $this->fmt->class_modulo->fecha_hoy("America/La_Paz");
								$fecha= $this->fmt->class_modulo->tiempo_restante($fh,$fecha_hoy);
					      echo '<td class="">'.$fecha.'</td>';
					      echo '<td class="">';
					        $this->fmt->class_modulo->estado_publicacion($fila_activar,$this->id_mod,"",$fila_id);
					      echo '</td>';
					      ?>
					      <td class="td-user col-xl-offset-2 acciones">
					      <?php
					        echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar ".$fila_id,"btn btn-accion btn-m-editar ",$this->id_mod,"form_editar,".$fila_id);
					      ?>
					        <a  title="eliminar <?php echo $fila_id; ?>" type="button" nombre="<?php echo $fila_nombre; ?>" id="btn-m<?php echo $this->id_mod; ?>" id_mod="<?php echo $this->id_mod; ?>" vars="eliminar,<?php echo $fila_id; ?>" class="btn btn-fila-eliminar btn-accion "><i class="icn-trash"></i></a>
					      </td>
					      <?php
					    echo "</tr>";
					  }
					}else{
						echo "no hay registros";
					}
					$this->fmt->form->tbody_table_close();
					$this->fmt->form->footer_table();
				?>
			</div> <!-- fin container -->
		</div> <!-- fin container fluid -->
		<?php
		//$this->fmt->class_modulo->modal_editor_texto();
		$this->fmt->class_modulo->script_accion_modulo();
		$this->fmt->class_modulo->script_table("table_id",$this->id_mod,"asc","5","25",true);
  }

	function ordenar(){
		$id_cat = $this->id_item;

		$this->fmt->class_pagina->crear_head_form("Ordenar: ".$this->fmt->categoria->nombre_categoria($id_cat),"","");
		$id_form="form-editar";
		?>
		<div class="body-modulo">
		  <form class="form form-modulo form-ordenar"  method="POST" id="<?php echo $id_form?>">
				<ul id="orden-cat" class="list-group">
					<?php
					$sql="select mul_id, mul_nombre, mul_url_archivo, mul_cat_orden from multimedia, multimedia_categorias where mul_cat_mul_id=mul_id and mul_cat_cat_id='$id_cat' ORDER BY mul_cat_orden desc";

	        $rs =$this->fmt->query->consulta($sql);
	        $num=$this->fmt->query->num_registros($rs);
	        if($num>0){
		        for($i=0;$i<$num;$i++){
		          $row=$this->fmt->query->obt_fila($rs);
							$prod_id=$row["mul_id"];
							$prod_nom=$row["mul_nombre"];
							$prod_imagen=_RUTA_WEB.$this->fmt->archivos->convertir_url_thumb($row["mul_url_archivo"]);
							echo "<li id_var='$prod_id'><i class='icn icn-reorder'></i><span class='img-prod' style='background:url($prod_imagen)no-repeat center center'></span><span class='nombre'>$prod_nom</span></li>";
						}
					}
					?>
				</ul>
				<div class="form-group form-botones box-botones-form">
					<div class="group">
						<?php
						echo $this->fmt->class_pagina->crear_btn_m("Actualizar","icn-sync","update","btn btn-info btn-update",$this->id_mod,"ordenar_update");
						 ?>
					</div>
				</div>
			</form>
			<script type="text/javascript">
				$(document).ready(function() {
					$("#orden-cat" ).sortable();
					$(".btn-update").click(function(){
						var formdata = new FormData();
						var id_mod = $(this).attr("id_mod");
						var vars = $(this).attr("vars");
						formdata.append("inputVars", vars);
						formdata.append("cat", "<?php echo $id_cat;?>");
						formdata.append("ajax", "ajax-adm");
						formdata.append("inputIdMod", id_mod);
						$('#orden-cat li').each(function(index){
						  var id_var = $(this).attr("id_var");
						  console.log(id_var);
						  var orden = index+1;
						  formdata.append("id_item[]", id_var);
						  //formdata.append("orden[]", orden);
						});

						var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";

						$.ajax({
						      url:ruta,
						      type:"post",
						      data:formdata,
						      processData: false,
						    contentType: false,
						      success: function(msg){

						        $("#popup-div").html(msg);
						      }
						});
					});
				});
			</script>
		</div>
		<?php
  }

	function ordenar_update(){
		$id_cat=$_POST["cat"];
		$this->fmt->class_modulo->eliminar_fila($id_cat,"multimedia_categorias","mul_cat_cat_id");
		$ingresar2 ="mul_cat_mul_id,mul_cat_cat_id,mul_cat_orden";
		$valor_doc= $_POST['id_item'];
		$num=count( $valor_doc );
		for ($i=0; $i<$num;$i++){
			$valores2 = "'".$valor_doc[$i]."','".$id_cat."','".$i."'";
			$sql2="insert into multimedia_categorias (".$ingresar2.") values (".$valores2.")";
			$this->fmt->query->consulta($sql2);
		}
	 $this->fmt->class_modulo->redireccionar($this->ruta_modulo,"1");
	}

	function traer_rel_cat_nombres($fila_id){
		$consulta = "SELECT DISTINCT cat_id, cat_nombre FROM categoria, multimedia_categorias WHERE mul_cat_mul_id='".$fila_id."' and cat_id = mul_cat_cat_id";
		$rs = $this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);
		if ($num>0){
			for ($i=0;$i<$num;$i++){
				$row=$this->fmt->query->obt_fila($rs);
				echo "- <a class='btn-menu-ajax' id_mod='".$this->id_mod."' vars='ordenar,".$row["cat_id"]."' > ".$row["cat_nombre"]."</a><br/>";
			}
		}
	}

	function form_nuevo_embed(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Embed","","");
		$id_form="form-nuevo";
		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"");
		$this->fmt->form->input_form('Nombre Archivo:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','editor-texto','','3',''); //$label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje,$editor=false

				//$this->fmt->form->input_form('Dominio:','inputDominio','',$aux,'','','');

		$this->fmt->form->textarea_form('Embed:','inputEmbed','','','','','3','');

		$this->fmt->form->input_hidden_form("inputTipo","embed");

		$this->fmt->form->categoria_form('Categoria','inputCat',"0","","","");
		//$label,$id,$cat_raiz,$cat_valor,$class,$class_div
		$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');
		$this->fmt->form->input_form_sololectura('Fecha:','inputFecha','',$fecha,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$usuario = $this->fmt->sesion->get_variable('usu_id');
		$usuario_n = $this->fmt->sesion->get_variable('usu_nombre');
		$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
		$this->fmt->form->input_form('Orden:','inputOrden','','','','','');
		//$this->fmt->form->radio_activar_form();
		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");

		$this->fmt->class_pagina->footer_form_mod();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

  function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Multimedia","","");
		$id_form="form-nuevo";

		?>
		<div class="body-modulo">
			<form class="form form-modulo form-images"  method="post" id="formup">
				<div class="form-group">
					<label>Subir Archivo:</label>
					<div class="btn  btn-full btn-subir-file"><i class="icn icn-folder-up"></i>Subir Archivo</div>
				  <input type="file" class="inputArchivo btn input-file" multiple="multiple"  name="inputArchivo[]" id="inputArchivo" >
					<div class="progress"></div>
				</div>
			</form>
			<script>
				$(function(){
			// $("#respuesta").html('<?php
			// if($fila["mul_tipo_archivo"]!="mp4")
			// 	echo '<img src="'.$aux.$fila['mul_url_archivo'].'" class="img-responsive" />';
			// else
			// 	echo '<video width="320" height="240" controls><source src="'.$aux.$fila['mul_url_archivo'].'" >Tu Navegador no soporta video en html5</video>';
			// ?>');
				$("#inputArchivo").change( function(){
					var formData = new FormData();
					var arc =document.getElementById("inputArchivo");
					var archivo = arc.files;
					console.log( archivo.length );
					formData.append("cant_file",archivo.length);
					for(i=0; i<archivo.length; i++){
						//formData.append('file',archivo[i]);
						formData.append("file-"+i,archivo[i]);
					}

					//console.log( file )
					//formData.append("inputId",<?php echo $fila['mul_id']; ?>);
					formData.append("ajax","ajax-upload-mul");
					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
					$("#respuesta").toggleClass('respuesta-form');
					$("#respuesta").html('');
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
									$(".progress").html('<div class="progress-bar"><span  style="width: '+ dat +'%;"><span></div><label>'+ dat +'%</label>');
								};
								return xhr;
							},
							success: function(datos){
								$(".progress").fadeOut("slow");
								console.log(datos);
								$("#aux_editar").html("");
								$("#aux_editar").html(datos);

								// var myarr = datos.split(",");
								// var num = myarr.length;
								// if (myarr[0]=="editar"){
								// 	//alert("termino");
								// 	var i;
								// 	var url = myarr[1];
								// 	for (i = 2; i < num; i++) {
								// 		var datx = myarr[i].split('^');
								// 		var dx = datx[1];
								// 		//datosx += dat[0]+'-'+dat[1]+"<br/>";
								// 		$("#"+datx[0]).val(datx[1]); //cambia los valores por los nuevos
								// 	}
								// 	var datosx='<img src="'+ dx + url +'" class="img-responsive">';
								// 	$("#respuesta").html(datosx);
								// }else{
								// 	$("#respuesta").html(datos);
								// }
							},
							error:  function(datos) {
								alert( "Data Saved: " + datos );
							}
						});
					});
				});
			</script>


			<form class="form form-modulo form-multimedia"  method="POST" id="<?php echo $id_form?>">
				<div id='aux_editar'>
				</div>
				<?php


				$this->fmt->form->input_form('Leyenda:','inputLeyenda','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

				$this->fmt->form->input_form('Texto Alternativo:','inputTextoalternativo','',$fila['mul_texto_alternativo'],'','','');


				$this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','editor-texto','','3',''); //$label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje,$editor=false

				//$this->fmt->form->input_form('Dominio:','inputDominio','',$aux,'','','');

				$this->fmt->form->textarea_form('Embed:','inputEmbed','','','','','3','');

				$this->fmt->form->categoria_form('Categoria','inputCat',"0","","","");
				//$label,$id,$cat_raiz,$cat_valor,$class,$class_div
				$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');
				$this->fmt->form->input_form_sololectura('Fecha:','inputFecha','',$fecha,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$usuario = $this->fmt->sesion->get_variable('usu_id');
				$usuario_n = $this->fmt->sesion->get_variable('usu_nombre');
				$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
				$this->fmt->form->input_form('Orden:','inputOrden','','','','','');
				//$this->fmt->form->radio_activar_form();
				$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");

				?>
				</form>
		</div>
		<?php
		$this->fmt->class_modulo->modal_script($this->id_mod);
  }

	function form_editar(){
		$id = $this->id_item;
		$consulta= "SELECT * FROM multimedia WHERE mul_id='".$id."'";
		$rs =$this->fmt->query->consulta($consulta);
		$fila=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->crear_head_form("Editar Multimedia","","");
		$id_form="form-editar";

		?>
		<div class="body-modulo">
			<form class="form form-modulo form-images"  method="post" id="formup">
				<div class="form-group">
					<label>Subir Archivo:</label>
				  <input type="file" class="inputArchivo btn input-file" multiple="multiple"  name="inputArchivo[]" id="inputArchivo" >
				  <div class="btn  btn-full btn-subir-file"><i class="icn icn-folder-up"></i>Subir Archivo</div>
					<div class="progress"></div>
				</div>
			</form>
			<script>
				$(function(){
			// $("#respuesta").html('<?php
			// if($fila["mul_tipo_archivo"]!="mp4")
			// 	echo '<img src="'.$aux.$fila['mul_url_archivo'].'" class="img-responsive" />';
			// else
			// 	echo '<video width="320" height="240" controls><source src="'.$aux.$fila['mul_url_archivo'].'" >Tu Navegador no soporta video en html5</video>';
			// ?>');
				$("#inputArchivo").change( function(){
					var formData = new FormData();
					var arc =document.getElementById("inputArchivo");
					var archivo = arc.files;
					console.log( archivo.length );
					formData.append("cant_file",archivo.length);
					for(i=0; i<archivo.length; i++){
						//formData.append('file',archivo[i]);
						formData.append("file-"+i,archivo[i]);
					}

					//console.log( file )
					formData.append("inputId",<?php echo $fila['mul_id']; ?>);
					formData.append("ajax","ajax-upload-mul");
					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
					$("#respuesta").toggleClass('respuesta-form');
					$("#respuesta").html('');
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
									$(".progress").html('<div class="progress-bar"><span  style="width: '+ dat +'%;"><span></div><label>'+ dat +'%</label>');
								};
								return xhr;
							},
							success: function(datos){
								$(".progress").fadeOut("slow");
								console.log(datos);
								$("#aux_editar").html("");
								$("#aux_editar").html(datos);

								// var myarr = datos.split(",");
								// var num = myarr.length;
								// if (myarr[0]=="editar"){
								// 	//alert("termino");
								// 	var i;
								// 	var url = myarr[1];
								// 	for (i = 2; i < num; i++) {
								// 		var datx = myarr[i].split('^');
								// 		var dx = datx[1];
								// 		//datosx += dat[0]+'-'+dat[1]+"<br/>";
								// 		$("#"+datx[0]).val(datx[1]); //cambia los valores por los nuevos
								// 	}
								// 	var datosx='<img src="'+ dx + url +'" class="img-responsive">';
								// 	$("#respuesta").html(datosx);
								// }else{
								// 	$("#respuesta").html(datos);
								// }
							},
							error:  function(datos) {
								alert( "Data Saved: " + datos );
							}
						});
					});
				});
			</script>


			<form class="form form-modulo form-multimedia"  method="POST" id="<?php echo $id_form?>">
				<?php
				$this->fmt->form->input_hidden_form("inputId",$id);
				echo "<div id='aux_editar'>";
					$cats_id = $this->fmt->categoria->traer_rel_cat_id($id,'multimedia_categorias','mul_cat_cat_id','mul_cat_mul_id');
					$aux=_RUTA_IMAGES;
					// if (_MULTIPLE_SITE!="off"){
					// 						if (!empty($fila['mul_id_dominio'])){ $aux=_RUTA_WEB; } else { $aux =$this->fmt->categoria->traer_dominio_cat_id($fila['mul_id_dominio']); }
					// }
					$imge = $fila["mul_url_archivo"];
					$tipo = $fila["mul_tipo_archivo"];

					if(!empty($imge) &&  $tipo!="mp4" && $tipo!="embed"){
						$mystring = $imge;
						$findme   = 'http';
						$pos = strpos($mystring, $findme);
						if ($pos===false){
							if(file_exists(_RUTA_HOST.$imge)){
							 	$img=$aux.$imge;
							}else{
								$img=_RUTA_WEB_NUCLEO."images/image-icon.png";
							}
						}else{
							$img=$imge."aqui";
						}
						?>
						<div class="block-image">
							<?php //echo $aux.$img; ?>
							<img class="img-file img-responsive" src="<?php echo $img; ?>" alt="img">
						</div>
						<?php
						}
						if ($tipo=="embed"){
							?>
							<div class="block-embed">
								<?php echo $fila["mul_embed"]; ?>
							</div>
							<?php
						}
						if ($tipo=="mp4"){
							?>
							<div class="block-image">
								<video controls src="<?php echo _RUTA_IMAGES.$fila["mul_url_archivo"]; ?>"></video>
							</div>
							<?php
						}

				$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre archivo:","inputNombre","",$fila['mul_nombre'],"","","En minúsculas");

				$this->fmt->form->input_form('Url archivo:','inputUrl','',$fila['mul_url_archivo'],'');
				$this->fmt->form->input_form('Tipo archivo:','inputTipo','',$fila['mul_tipo_archivo'],'');

				$this->fmt->form->input_form('Dimensión:','inputDimension','',$fila['mul_dimension'],'','','');
				$this->fmt->form->input_form('Tamaño:','inputTamano','',$fila['mul_tamano'],'','','');
				echo "</div>";

				echo "<div class='clearfix'></div><br/></br/>";

				$this->fmt->form->input_form('Leyenda:','inputLeyenda','',$fila['mul_leyenda'],'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

				$this->fmt->form->input_form('Texto Alternativo:','inputTextoalternativo','',$fila['mul_texto_alternativo'],'','','');


				$this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$fila['mul_descripcion'],'editor-texto','','3',''); //$label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje,$editor=false

				//$this->fmt->form->input_form('Dominio:','inputDominio','',$aux,'','','');

				$this->fmt->form->textarea_form('Embed:','inputEmbed','',$fila['mul_embed'],'','','3','');

				$this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"","");
				//$label,$id,$cat_raiz,$cat_valor,$class,$class_div
				$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');
				$this->fmt->form->input_form_sololectura('Fecha:','inputFecha','',$fecha,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$usuario = $this->fmt->sesion->get_variable('usu_id');
				$usuario_n = $this->fmt->sesion->get_variable('usu_nombre');
				$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
				$this->fmt->form->input_form('Orden:','inputOrden','',$fila['mul_orden'],'','','');
				$this->fmt->form->input_form('Url:','inputLink','',$fila['mul_url'],'','','');
				$this->fmt->form->input_form('Destino:','inputDestino','',$fila['mul_destino'],'','','');
				$this->fmt->form->radio_activar_form($fila['mul_activar']);
				$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");

				?>
				</form>
		</div>
		<?php
		$this->fmt->class_modulo->modal_editor_texto();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

  function ingresar(){
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
		if (_MULTIPLE_SITE!="off"){
			$id_dominio=$_POST['inputDominio'];
		}else{
			$id_dominio=0;
		}

		$ingresar ="mul_nombre,mul_url_archivo,mul_ruta_amigable,mul_tipo_archivo,mul_leyenda,mul_texto_alternativo,mul_descripcion,mul_dimension,mul_tamano,mul_id_dominio,mul_embed,mul_url,mul_destino,mul_fecha,mul_usuario,mul_activar";
		$valores  ="'".$_POST['inputNombre']."','".
									 $_POST['inputUrl']."','".
									 $_POST['inputRutaamigable']."','".
									 $_POST['inputTipo']."','".
									 $_POST['inputLeyenda']."','".
									 $_POST['inputTextoalternativo']."','".
									 $_POST['inputDescripcion']."','".
									 $_POST['inputDimension']."','".
									 $_POST['inputTamano']."','".
									 $id_dominio."','".
									 $_POST['inputEmbed']."','".
									 $_POST['inputLink']."','".
									 $_POST['inputDestino']."','".
									 $_POST['inputFecha']."','".
									 $_POST['inputUsuario']."','".
									 $activar."'";

		$sql="insert into multimedia (".$ingresar.") values (".$valores.")";
		$this->fmt->query->consulta($sql);

		$sql="select max(mul_id) as id from multimedia";
		$rs= $this->fmt->query->consulta($sql);
		$fila = $this->fmt->query->obt_fila($rs);
	  $id = $fila ["id"];

		$ingresar1 ="mul_cat_mul_id, mul_cat_cat_id, mul_cat_orden";
		$valor_cat= $_POST['inputCat'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){

			$sql="select max(mul_cat_orden) as idx from multimedia_categorias where mul_cat_cat_id=".$valor_cat[$i];
			$rs= $this->fmt->query->consulta($sql);
			$fila = $this->fmt->query->obt_fila($rs) ;
			$x = $fila["idx"] + 1;
			$valores1 = "'".$id."','".$valor_cat[$i]."','".$x."'" ;
			$sql1="insert into multimedia_categorias (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}
		//echo "multimedia.adm.php?id_mod=".$this->id_mod;
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}

	function modificar(){
		if ($_POST["estado-mod"]=="eliminar"){
		}else{

		   		$sql="UPDATE multimedia SET
						mul_nombre='".$_POST['inputNombre']."',
						mul_url_archivo ='".$_POST['inputUrl']."',
						mul_tipo_archivo='".$_POST['inputTipo']."',
						mul_leyenda='".$_POST['inputLeyenda']."',
						mul_texto_alternativo='".$_POST['inputTextoalternativo']."',
						mul_descripcion='".$_POST['inputDescripcion']."',
						mul_dimension='".$_POST['inputDimension']."',
						mul_tamano='".$_POST['inputTamano']."',
						mul_id_dominio='".$this->fmt->categoria->traer_id_cat_dominio($_POST['inputDominio'])."',
						mul_fecha='".$_POST['inputFecha']."',
						mul_embed='".$_POST['inputEmbed']."',
						mul_url='".$_POST['inputLink']."',
						mul_destino='".$_POST['inputDestino']."',
						mul_usuario='".$_POST['inputUsuario']."',
						mul_activar='".$_POST['inputActivar']."'
						WHERE mul_id='".$_POST['inputId']."'";

					$this->fmt->query->consulta($sql);


					$sql="DELETE FROM multimedia_categorias WHERE mul_cat_mul_id='".$_POST['inputId']."'";
					$this->fmt->query->consulta($sql);

					$up_sqr7 = "ALTER TABLE multimedia_categorias AUTO_INCREMENT=1";
					$this->fmt->query->consulta($up_sqr7);

					$valor_cat= $_POST['inputCat'];



					$ingresar1 ="mul_cat_mul_id, mul_cat_cat_id, mul_cat_orden";

					$num=count( $valor_cat );
					for ($i=0; $i<$num;$i++){

						$sql="select max(mul_cat_orden) as id from multimedia_categorias where mul_cat_cat_id=".$valor_cat[$i];
						$rs= $this->fmt->query->consulta($sql);
						$fila = $this->fmt->query->obt_fila($rs) ;
						$x = $fila["id"] + 1;
						$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."','".$x."'" ;
						echo $sql1="insert into multimedia_categorias (".$ingresar1.") values (".$valores1.")";
						$this->fmt->query->consulta($sql1);
					}
		}
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}


}
?>
