<?php
header("Content-Type: text/html;charset=utf-8");

class LISTALUGAR{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function LISTALUGAR($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	public function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod,""); // bd, id modulo, botones
		?>
		<link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/m-listas-lugares.css?reload" rel="stylesheet" type="text/css">
		<div class="body-modulo container-fluid">
			<div class="container">
      <?php
			$this->fmt->class_pagina->head_modulo_inner("Estructura de Listas (Taxonomía)", "");
      // $this->fmt->categoria->arbol_editable('mod_lista','mod_list_',$this->id_mod); //$select,$from,$where,$orderby,$ruta_modulo,$prefijo
      $this->fmt->categoria->arbol_editable_mod('mod_lista','mod_list_',"mod_list_id_padre=0",$this->ruta_modulo,"estructura-listas-lugares",$this->id_mod); //$from,$prefijo,$where,$url_modulo,$class_div,$id_mod
			?>
			</div>
    </div>
    <script type="text/javascript">
    	$(document).ready(function() {
        $(".btn-contenedores").remove();

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
				
				$(".btn-ordenar-i").click(function(e){
					var id_mod = "<?php echo $this->id_mod; ?>";
					var cat = $( this ).attr("id_padre");
					var variables = "ordenar,"+cat;
					var ruta = "ajax-adm";
					var datos = {ajax:ruta, inputIdMod:id_mod , inputVars : variables };
					abrir_modulo(datos);
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

							$(".modal-form .modal-inner").html(msg);
							var wbm = $(".modal-form .modal-inner").height();
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

    	});
    </script>
    <?php
	}

	public function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nueva Lista Lugar","","");
		$id_form="form-nuevo";
		$id = $this->id_item;

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-lugares");

		$this->fmt->form->input_form("* Nombre:","inputNombre","","","input-lg","","","",""); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros
		$this->fmt->form->input_form("Descripcion:","inputDescripcion","","");
		$this->fmt->form->input_form("tags:","inputTags","","");
		$this->fmt->form->textarea_form('Resumen:','inputResumen','','','','3','');
		$this->fmt->form->input_info('{"label":"Padre:",
                    							 "id":"inputPadre",
								                   "value":"'.$id.'"}');

		$this->fmt->form->imagen_unica_form("inputImagen","","","form-normal","Imagen relacionada:");
		$this->fmt->form->imagen_unica_form("inputBanner","","","form-normal","Banner:");
		$this->fmt->form->input_form("Rango Edades:","inputRango","Ej. 12-24,24-34","");
		
		$this->fmt->form->categoria_form('Categoria','inputCat',"0","","",""); //

		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} //fin form_nuevo

	function ingresar(){
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
		$ingresar ="mod_list_nombre, mod_list_descripcion, mod_list_tags, mod_list_resumen, mod_list_id_padre, mod_list_imagen, mod_list_banner, mod_list_rango_edades, mod_list_activar";

		$valores  ="'".$_POST['inputNombre']."','".
					$_POST['inputDescripcion']."','".
					$_POST['inputTags']."','".
					$_POST['inputResumen']."','".
					$_POST['inputPadre']."','".
					$_POST['inputImagen']."','".
					$_POST['inputBanner']."','".
					$_POST['inputRango']."','".
					$activar."'";
		$sql="insert into mod_lista (".$ingresar.") values (".$valores.")";
		$this->fmt->query->consulta($sql);

		$sql="select max(mod_list_id) as id from mod_lista";
		$rs= $this->fmt->query->consulta($sql);
		$fila = $this->fmt->query->obt_fila($rs);
		$id = $fila ["id"];

		$ingresar1 ="mod_list_cat_lug_id, mod_list_cat_cat_id, mod_list_cat_orden";
		$valor_cat= $_POST['inputCat'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$id."','".$valor_cat[$i]."','".$i."'";
			$sql1="insert into mod_lista_categorias (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin ingresar

	function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Lista Lugar","","");
		$id_form="form-editar";

		$id = $this->id_item;
		$consulta= "SELECT * FROM mod_lista WHERE mod_list_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-lugares");
		
		// $this->fmt->form->hidden_modulo($this->id_mod,"modificar");
		$this->fmt->form->input_form("* Nombre:","inputNombre","",$row["mod_list_nombre"],"input-lg","","");
		$this->fmt->form->input_hidden_form("inputId",$id);

		$this->fmt->form->input_form("Descripcion:","inputDescripcion","",$row["mod_list_descripcion"]);
		$this->fmt->form->input_form("Tags:","inputTags","",$row["mod_list_tags"]);
		$this->fmt->form->textarea_form('Resumen:','inputResumen','',$row["mod_list_resumen"],'','','');
		// echo "imagen:".$row['mod_list_imagen'];
		$this->fmt->form->imagen_unica_form("inputImagen",$row['mod_list_imagen'],"","form-normal","Imagen relacionada:");
		$this->fmt->form->imagen_unica_form("inputBanner",$row['mod_list_banner'],"","form-normal","Banner:");
		$this->fmt->form->input_form("Rango de edades:","inputRango","",$row['mod_list_rango']);

		// $this->fmt->form->select_form_nodo("Categoría padre:","inputPadre",$fila['cat_id_padre']);
		$this->fmt->form->select_form_nodo('{
																				"label":"Categoría padre:",
																				"id":"inputPadre",
																				"item":"'.$id.'",
																				"id_padre":"'.$row["mod_list_id_padre"].'",
																				"id_inicio":"0",
																				"from":"mod_lista",
																				"prefijo":"mod_list_"
																			}');

		$cats_id = $this->fmt->categoria->traer_rel_cat_id($id,'mod_lista_categorias','mod_list_cat_cat_id','mod_list_cat_list_id'); //$fila_id,$from,$prefijo_cat,$prefijo_rel
		$this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"",""); //

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} // fin form_editar

	function modificar(){

		$sql="UPDATE mod_lista SET
				mod_list_nombre='".$_POST['inputNombre']."',
				mod_list_descripcion='".$_POST['inputDescripcion']."',
				mod_list_tags ='".$_POST['inputTags']."',
				mod_list_resumen ='".$_POST['inputResumen']."',
				mod_list_imagen ='".$_POST['inputImagen']."',
				mod_list_banner='".$_POST['inputBanner']."',
				mod_list_id_padre='".$_POST['inputPadre']."',
				mod_list_rango_edades='".$_POST['inputRango']."'
				WHERE mod_list_id='".$_POST['inputId']."'";
		//echo $sql;
		$this->fmt->query->consulta($sql);

		$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"mod_lista_categorias","mod_list_cat_list_id");
		$ingresar1 ="mod_list_cat_list_id, mod_list_cat_cat_id, mod_list_cat_orden";
		$valor_cat= $_POST['inputCat'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."','".$i."'";
			$sql1="insert into mod_lista_categorias (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin modificar
}