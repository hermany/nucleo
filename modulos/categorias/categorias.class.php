<?php
header("Content-Type: text/html;charset=utf-8");

class CATEGORIAS{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function CATEGORIAS($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){
    $this->fmt->class_pagina->crear_head( $this->id_mod,""); // bd, id modulo, botones
    // $sql="SELECT mod_id FROM modulos where mod_id_padre=".$this->id_mod;
		// $rs=$this->fmt->query->consulta($sql);
		// $fila=$this->fmt->query->obt_fila($rs);
    ?>
		<link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/m-categorias.css?reload" rel="stylesheet" type="text/css">
		<div class="body-modulo container-fluid">
			<div class="container">
      <?php
			$this->fmt->class_pagina->head_modulo_inner("Estructura de Categorías (Taxonomía)", "");
      $this->fmt->categoria->arbol_editable('categoria','cat_',$this->id_mod); //$select,$from,$where,$orderby,$ruta_modulo,$prefijo
			?>
			</div>
    </div>
    <script>
				$(".btn-editar-i").click(function(e){
					var id_mod = "<?php echo $this->id_mod; ?>";
					var cat = $( this ).attr("cat");
					var variables = "form_editar,"+cat;
					var ruta = "ajax-adm";
					var datos = {ajax:ruta, inputIdMod:id_mod , inputVars : variables };
					abrir_modulo(datos);
				});
				$(".btn-nuevo-i").click(function(e){
					var id_mod = "<?php echo $this->id_mod; ?>";
					var cat = $( this ).attr("cat");
					var variables = "form_nuevo,"+cat;
					var ruta = "ajax-adm";
					var datos = {ajax:ruta, inputIdMod:id_mod , inputVars : variables };
					abrir_modulo(datos);
				});
				$(".btn-contenedores").click(function(e){
					var cat = $( this ).attr("cat");
					var ruta = $( this ).attr("ruta");
					console.log(cat+":"+ruta);
					redireccionar_tiempo("<?php echo _RUTA_WEB; ?>dashboard/estructura-contenidos/"+cat,0);
					// var id_mod = "<?php echo $fila["mod_id"]; ?>";
					// var cat = $( this ).attr("cat");
					// var variables = "editar_contenidos,"+cat;
					// var ruta = "ajax-adm";
					// var datos = {ajax:ruta, inputIdMod:id_mod , inputVars : variables };
					// abrir_modulo(datos);
				});
				function abrir_modulo(datos){
					$(".modal-form").addClass("on");
					$(".modal-form").addClass("<?php echo $url_a; ?>");
					$(".body-page").css("overflow-y","hidden");
					//console.log(datos);

					$.ajax({
						url:"<?php echo _RUTA_WEB; ?>ajax.php",
						type:"post",
						data:datos,
						success: function(msg){

							$("#modal .modal-inner").html(msg);
							var wbm = $(".modal .modal-inner").height();
              var wbmx = wbm - 108;
              console.log("body-modulo:"+wbmx);
              $(".body-modulo").height(wbmx);

						},
						complete : function() {
							$('.preloader-page').fadeOut('slow');
							// var wmi =   $("#modal .modal-inner").width();
							// var hmi =   $("#modal .modal-inner").height();
							// var x_wmi = Math.round(wmi /2);
							// var y_hmi = Math.round(hmi /2);
							// $("#modal .modal-inner").css("margin-left","-"+x_wmi+"px");
							// $("#modal .modal-inner").css("margin-top","-"+y_hmi+"px");
						}
					});
				}
	  $(".btn-eliminar-i").click(function(e){
				e.preventDefault();
				var nom= $(this).attr('nombre');
				var variables = $(this).attr('vars');
				var id_mod= $(this).attr('id_mod');

				$(".modal-form").addClass("on");
				$(".content-page").css("overflow-y","hidden");
				$(".modal-form .modal-inner").addClass("mensaje-eliminar");
				$(".modal-form .modal-inner").html('<div class="modal-title"></div><div class="modal-body"> <i class="icn icn-trash"></i> <label>"'+nom+'" se eliminará, estas seguro de eliminarlo. </label><span>No podrás deshacer esta acción.<span> </div><div class="modal-footer"><a class="btn btn-cancelar btn-small btn-full">Cancelar</a><a class="btn btn-info btn-m-eliminar btn-small" id_mod="'+id_mod+'" vars="'+variables+'" >Eliminar</a></div>');

				$(".btn-cancelar").on("click",function(e){
					e.preventDefault();
					$(".modal").removeClass("on");
					$(".modal .modal-inner").removeClass("mensaje-eliminar");
					$(".modal .modal-inner").html(" ");
					$(".content-page").css("overflow-y","auto");
				});

				$(".btn-m-eliminar").on("click",function(e){
					e.preventDefault();
					var variables = $(this).attr('vars');
					var id_mod= $(this).attr('id_mod');
					var ruta='ajax-eliminar';
					var datos= { ajax:ruta,inputIdMod:id_mod, inputVars:variables };
					//alert(variables);
					accion_modulo(datos);
				});

			});

			$(".btn-activar-i").click(function(e){
				var cat = $( this ).attr("cat");
				var estado = $( this ).attr("estado");
				var id_mod = "<?php echo $this->id_mod; ?>";
				var variables =  $( this ).attr("vars");
				var ruta = "ajax-activar";
				var datos = {ajax:ruta, inputIdMod:id_mod , inputVars : variables };
				accion_modulo(datos);
			});


			function accion_modulo(datos){
				//console.log(datos);
				$.ajax({
					url:"<?php echo _RUTA_WEB; ?>ajax.php",
					type:"post",
					async: true,
					data:datos,
					success: function(msg){
						console.log("pag:" + msg );
						var variables = msg;
						var cadena = variables.split(':');
						var accion = cadena[0];
						var id_item = cadena[1];
						var estado = cadena[2];
						var id_mod = cadena[3];
						switch ( accion ){
							case 'activar':
								//console.log(id_mod+"-"+id_item+"-"+estado);
								$("#btn-p-"+id_mod+"-"+id_item+" i").removeClass();
								$("#btn-pi-"+id_item).removeClass();
								if(estado==1){
									$("#btn-pi-"+id_item).addClass("icn-eye-open");
									$("#btn-pi-"+id_item).attr("vars","activar,"+id_item+",0");
								}else{
									$("#btn-pi-"+id_item).addClass("icn-eye-close");
									$("#btn-pi-"+id_item).attr("vars","activar,"+id_item+",1");
								}
								$(".content-page").css("overflow-y","auto");
							break;
							case 'eliminar':
								console.log(id_item);
								$(".modal").removeClass("on");
								$(".modal .modal-inner").removeClass("mensaje-eliminar");
								$(".modal .modal-inner").html("");
								$(".row-"+id_item).addClass("removiendo");
								$("#nodo-"+id_item).addClass("removiendo");
								setTimeout(function() {
									$(".row-"+id_item).remove();
									$("#nodo-"+id_item).remove();
									$(".content-page").css("overflow-y","auto");
								}, 1500 );

							break;
							default:
							alert("no hay una acción determinada, revisar error base de datos");
						}
					}
				});
			};
    </script>
    <?php
		$this->fmt->class_modulo->script_accion_modulo();
  }

  function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Categoria","","");
		$id_form="form-editar";

		$id = $this->id_item;
		$sql="select * from categoria	where cat_id='".$id."'";
		$rs=$this->fmt->query->consulta($sql,__METHOD__);
		$fila=$this->fmt->query->obt_fila($rs);
		//$this->fmt->form->finder("inputImagen",$this->id_mod,$fila["cat_imagen"],"individual","imagenes");
		?>
		<div class="body-modulo">
			<form class="form form-modulo"  method="POST" id="<?php echo $id_form ;?>">
				<div class="form-group" id="mensaje-form"></div>
				<?php
					$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","",$fila["cat_nombre"],"input-lg","","");
					$this->fmt->form->input_hidden_form("inputId",$id);
					$this->fmt->form->ruta_amigable_form("inputNombre",_RUTA_WEB,$fila['cat_ruta_amigable'],"inputRutaamigable"); //$id,$ruta,$valor,$form
					$this->fmt->form->textarea_form('Descripcion:','inputDescripcion','',$fila["cat_descripcion"],'','','3',''); //label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje
					$this->fmt->form->select_form_cat_id("Categoría padre:","inputPadre",$fila['cat_id_padre']); //$label,$id,$id_item,$div_class
					$this->fmt->form->input_icono_form("Icono:","inputIcono",$fila["cat_icono"]); //($label,$id,$icono,$class_div
					$this->fmt->form->input_color_form("Color:","inputColor",$fila["cat_color"]); //($label,$id,$color="#ffff",$class_div
					//if ($fila["cat_imagen"]){ $text="Actualizar"; $aux=_RUTA_WEB; }else{ $text="Cargar archivo"; $aux=""; }
					$this->fmt->form->imagen_unica_form("inputImagen",$fila["cat_imagen"],"","","Imagen relacionada:");
					//$id,$valor,$titulo="Imagen principal",$class_div,$label_form="" //$label,$label_btn,$id,$id_item,$valor,$img,$class_div
					$this->fmt->form->input_form('Orden:','inputOrden','',$fila['cat_orden'],'box-md-2','','');
					?>
					<div class="form-group">
						<a class="btn btn-link btn-collapse" href="#collapseAvanzado" collapse='collapseAvanzado'>
	            <span>Avanzado</span>
	          </a>
          	<div class="collapse form-collapse" id="collapseAvanzado">
							<div class="well">
								<div class="form-group">
				          <label>Plantilla principal:</label>
				          <select class="form-control" id="inputPlantilla" name="inputPlantilla">
				          <?php $this->fmt->plantilla->traer_opciones_plantilla($fila['cat_id_plantilla']); ?>
				          </select>
				        </div>
								<div class="form-group">
	                <label>Tipo Categoria:</label>
	                <select class="form-control" id="inputTipo" name="inputTipo">
	                  <?php $this->fmt->categoria->opciones_tipo_cat($fila["cat_id"]); ?>
	                </select>
	              </div>
								<?php
									$this->fmt->form->input_form("Ruta Meta:","inputMeta","",$fila["cat_meta"],"","","");
									$this->fmt->form->input_form("Favicon:","inputFavicon","",$fila["cat_favicon"],"","","");
									$this->fmt->form->input_form("Clase:","inputClase","",$fila["cat_clase"],"","","");
									$this->fmt->form->input_form("Url:","inputUrl","",$fila["cat_url"],"","","");
								 ?>
								 <div class="form-group">
	                 <label>Destino:</label>
	                 <select class="form-control" id="inputDestino" name="inputDestino">
	                   <?php $this->fmt->categoria->opciones_destino($fila["cat_destino"]); ?>
	                 </select>
	               </div>
								 <?php
								 	$this->fmt->form->input_form("Ruta Sitio:","inputRutasitio","",$fila["cat_ruta_sitio"],"","","");
								 	$this->fmt->form->input_form("Ruta Dominio:","inputDominio","",$fila["cat_dominio"],"","","");
								  ?>
							</div> <!-- fin well -->
						</div> <!-- fin div collapse -->
					</div>  <!-- fin form-group -->
					<?php
					$this->fmt->form->radio_activar_form($fila['cat_activar']);
					$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
					 ?>
      </form>
    </div>
    <?php
		$this->fmt->finder->finder_window();
    $this->fmt->class_modulo->modal_script($this->id_mod);
  }

  function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nueva Categoria","","");
		$id_form="form-nuevo";
		$id = $this->id_item;
		$this->fmt->form->finder("inputImagen",$this->id_mod,"","individual","imagenes");
		?>
		<div class="body-modulo">
			<form class="form form-modulo"  method="POST" id="<?php echo $id_form ;?>">
				<div class="form-group" id="mensaje-form"></div>
				<?php

					$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","","","input-lg","","");
					$this->fmt->form->ruta_amigable_form("inputNombre",_RUTA_WEB,"","inputRutaamigable"); //$id,$ruta,$valor,$form
					$this->fmt->form->textarea_form('Descripcion:','inputDescripcion','','','','','3',''); //label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje
					$this->fmt->form->select_form_cat_id("Categoría padre:","inputPadre",$id); //$label,$id,$id_item,$div_class,$id_padre
					$this->fmt->form->input_icono_form("Icono:","inputIcono",""); //($label,$id,$icono,$class_div
					$this->fmt->form->input_color_form("Color:","inputColor",""); //($label,$id,$color="#ffff",$class_div
					$this->fmt->form->imagen_form("Imagen:","Cargar Imagen","inputImagen","",""); //$label,$label_btn,$id,$id_item,$valor,$img,$class_div
					$this->fmt->form->input_form('Orden:','inputOrden','',$fila['cat_orden'],'box-md-2','','');
					?>
					<div class="form-group">
						<a class="btn btn-link btn-collapse" href="#collapseAvanzado" collapse='collapseAvanzado'>
							<span>Avanzado</span>
						</a>
						<div class="collapse form-collapse" id="collapseAvanzado">
							<div class="well">
								<div class="form-group">
									<label>Plantilla principal:</label>
									<select class="form-control" id="inputPlantilla" name="inputPlantilla">
									<?php $this->fmt->plantilla->traer_opciones_plantilla($fila['cat_id_plantilla']); ?>
									</select>
								</div>
								<div class="form-group">
									<label>Tipo Categoria:</label>
									<select class="form-control" id="inputTipo" name="inputTipo">
										<?php $this->fmt->categoria->opciones_tipo_cat(); ?>
									</select>
								</div>
								<?php
									$this->fmt->form->input_form("Ruta Meta:","inputMeta","","","","","");
									$this->fmt->form->input_form("Favicon:","inputFavicon","","","","");
									$this->fmt->form->input_form("Clase:","inputClase","","","","","");
									$this->fmt->form->input_form("Url:","inputUrl","","","","","");
								 ?>
								 <div class="form-group">
									 <label>Destino:</label>
									 <select class="form-control" id="inputDestino" name="inputDestino">
										 <?php $this->fmt->categoria->opciones_destino(); ?>
									 </select>
								 </div>
								 <?php
									$this->fmt->form->input_form("Ruta Sitio:","inputRutasitio","","","","","");
									$this->fmt->form->input_form("Ruta Dominio:","inputDominio","","","","","");
									?>
							</div> <!-- fin well -->
						</div> <!-- fin div collapse -->
					</div>  <!-- fin form-group -->
					<?php
					$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar"); //$id_form,$id_mod,$tarea
					 ?>
			</form>
		</div>
		<?php
		$this->fmt->class_modulo->modal_script($this->id_mod);
  }

  function modificar(){

    $sql="UPDATE categoria SET
          cat_nombre='".$_POST['inputNombre']."',
          cat_descripcion='".$_POST['inputDescripcion']."',
          cat_ruta_amigable='".$_POST['inputRutaamigable']."',
          cat_imagen ='".$_POST['inputImagen']."',
          cat_icono='".$_POST['inputIcono']."',
          cat_color='".$_POST['inputColor']."',
          cat_clase='".$_POST['inputClase']."',
          cat_meta='".$_POST['inputMeta']."',
          cat_id_padre='".$_POST['inputPadre']."',
          cat_id_plantilla='".$_POST['inputPlantilla']."',
          cat_tipo='".$_POST['inputTipo']."',
          cat_url ='".$_POST['inputUrl']."',
          cat_destino='".$_POST['inputDestino']."',
          cat_favicon='".$_POST['inputFavicon']."',
          cat_ruta_sitio='".$_POST['inputRutasitio']."',
          cat_dominio='".$_POST['inputDominio']."',
					cat_orden='".$_POST['inputOrden']."',
          cat_activar='".$_POST['inputActivar']."'
          WHERE cat_id='".$_POST['inputId']."'";
    $this->fmt->query->consulta($sql,__METHOD__);
		$this->fmt->class_sistema->update_htaccess();
    $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }

  function ingresar(){
    if ($_POST["estado-mod"]=="activar"){ $activar=1; }else{ $activar=0;}

    $ingresar ="cat_nombre, cat_descripcion, cat_ruta_amigable, cat_imagen,cat_orden, cat_icono, cat_color, cat_clase, cat_meta, cat_id_padre, cat_id_plantilla, cat_tipo, cat_url, cat_destino, cat_favicon, cat_dominio,cat_ruta_sitio, cat_activar";
		$valores  ="'".$_POST['inputNombre']."','".
									 $_POST['inputDescripcion']."','".
                   $_POST['inputRutaamigable']."','".
                   $_POST['inputImagen']."','".
                   $_POST['inputOrden']."','".
                   $_POST['inputIcono']."','".
                   $_POST['inputColor']."','".
                   $_POST['inputClase']."','".
                   $_POST['inputMeta']."','".
                   $_POST['inputPadre']."','".
                   $_POST['inputPlantilla']."','".
                   $_POST['inputTipo']."','".
									 $_POST['inputUrl']."','".
									 $_POST['inputDestino']."','".
									 $_POST['inputFavicon']."','".
									 $_POST['inputDominio']."','".
									 $_POST['inputRutasitio']."','".
									 $activar."'";

		$sql="insert into categoria (".$ingresar.") values (".$valores.")";

		$this->fmt->query->consulta($sql,__METHOD__);

		$this->fmt->class_sistema->update_htaccess();
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }

  function activar(){
    $estado = $this->id_estado;
    if ($estado=='1'){ $estado=0; }else{ $estado=1; }
      $sql="update categoria set
        cat_activar='".$estado."' where cat_id='".$this->id_item."'";
    $this->fmt->query->consulta($sql,__METHOD__);
    $this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
  }

  function eliminar(){
		$id= $this->id_item;
		$sql="DELETE FROM categoria WHERE cat_id='".$id."'";
		$this->fmt->query->consulta($sql,__METHOD__);
		$up_sqr6 = "ALTER TABLE categoria AUTO_INCREMENT=1";
		$this->fmt->query->consulta($up_sqr6,__METHOD__);
		$this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
	}
}

?>
