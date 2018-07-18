<?php
header("Content-Type: text/html;charset=utf-8");

class DOCUMENTOS{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;

	function DOCUMENTOS($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
	}

	function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
		$this->fmt->class_pagina->head_mod();

		$botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nueva documento","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
		$this->fmt->class_pagina->head_modulo_inner("Lista de documentos", $botones); // bd, id modulo, botones

		$this->fmt->form->head_table('table_id');
		$this->fmt->form->thead_table('id:Archivo:Autor:Categoria:Fecha:Estado:Acciones');
		$this->fmt->form->tbody_table_open();
		$sql="SELECT * FROM documento ORDER BY doc_id asc";
		$rs =$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
		  for($i=0;$i<$num;$i++){
		    $fila=$this->fmt->query->obt_fila($rs);
				$fila_id = $fila["doc_id"];
				$nombre = $fila["doc_nombre"];
		    if (empty($fila["doc_id_dominio"])){ $aux=_RUTA_WEB; } else { $aux = $this->fmt->categoria->traer_dominio_cat_id($fila["doc_id_dominio"]); }
				  echo "<tr class='row row-".$fila["doc_id"]."' >";
				  echo '<td class="row-id">'.$fila['doc_id'].'</td>'; 
					echo '<td class="fila-url"><strong><a href="'.$aux.$fila["doc_url"].'" target="_blank">'.$fila["doc_nombre"].'</a></strong> ( '.$fila["doc_tipo_archivo"].' orden: '.$fila["doc_orden"].' )</td>';
					echo '<td class="">'.$this->fmt->usuario->nombre_usuario( $fila["doc_usuario"]).'</td>';
					echo '<td class="">';
								$this->fmt->categoria->traer_rel_cat_nombres($fila["doc_id"],'documento_categorias','doc_cat_cat_id','doc_cat_doc_id'); //$fila_id,$from,$prefijo_cat,$prefijo_rel
					echo '</td>';
					echo '<td class="">'.$fila['doc_fecha'].'</td>';
					echo '<td class="">';
					$this->fmt->class_modulo->estado_publicacion($fila["doc_activar"], $this->id_mod,"", $fila["doc_id"] );

					echo '</td>';
					?>
					<td class="td-user col-xl-offset-2 acciones">
						<?php
						echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar ".$fila_id,"btn btn-accion btn-m-editar ",$this->id_mod,"form_editar,".$fila_id);
						echo $this->fmt->class_pagina->crear_btn_m("","icn-trash","eliminar ".$fila_id,"btn btn-accion btn-fila-eliminar",$this->id_mod,"eliminar,".$fila_id,"",$nombre);
						?>
					</td>
			<?php
			echo "</tr>";
		  }
		}
		$this->fmt->form->tbody_table_close();
		$this->fmt->form->footer_table();
		$this->fmt->class_pagina->footer_mod();
		//$this->fmt->class_modulo->script_form("modulos/documentos/documentos.adm.php",$this->id_mod);
		$this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","10",true);
		$this->fmt->class_modulo->script_accion_modulo();

	}

	function form_nuevo($modo){

    $this->fmt->class_pagina->crear_head_form("Nuevo Lugar","","");
		$id_form="form-nuevo";

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-lugares");

		//$this->fmt->form->head_nuevo('Nuevo archivo','documentos',$this->id_mod,'','form_nuevo-doc','',''); //$nom,$archivo,$id_mod,$botones,$id_form,$class,$modo

		//$this->fmt->form->file_form_doc("<span class='obligatorio'>*</span> Cargar archivo (pdf, doc/x, pptx, xls/x, zip):","","form_nuevo","input-form-doc","","","docs","required"); //$nom,$ruta,$id_form,$class,$class_div,$id_div,$directorio_p

		$this->fmt->form->documentos_form($fila_id,$this->id_mod,"","Documentos:");

		$this->fmt->form->categoria_form('Categoria','inputCat',"0","","",""); //$label,$id,$cat_raiz,$cat_valor,$class,$class_div
		$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');
		//$this->fmt->form->input_form_sololectura('Fecha:','inputFecha','',$fecha,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

		$usuario = $this->fmt->sesion->get_variable('usu_id');
		$usuario_n = $this->fmt->sesion->get_variable('usu_nombre');
		$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		//$this->fmt->form->hidden_modulo($this->id_mod,"ingresar");
		$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
		$this->fmt->form->input_form('Orden:','inputOrden','','0','','','');
		// $this->fmt->form->botones_nuevo('form_nuevo-doc',$modo);
		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar"); //
		// $this->fmt->form->footer_page();

		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();

		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar archivo","","");
		$id_form="form_editar";

		$id = $this->id_item;
		$consulta= "SELECT * FROM documento WHERE doc_id='".$id."'";
		$rs =$this->fmt->query->consulta($consulta);
		$fila=$this->fmt->query->obt_fila($rs);

		?>
		<div class="body-modulo">
			<form class="form form-modulo"  method="POST" id="<?php echo $id_form ;?>">
				<div class="form-group" id="mensaje-form"></div>
		<?php
		$this->fmt->form->input_hidden_form("inputId",$id);
		$this->fmt->form->hidden_modulo($this->id_mod,"modificar");
		//$this->fmt->form->file_form_doc("<span class='obligatorio'>*</span> Cargar archivo para reemplazar (pdf, doc/x, pptx, xls/x, zip):","","form_editar","input-form-doc","","","docs"); //
		//if (empty($fila_dominio)){ $aux=_RUTA_WEB; } else { $aux = $this->fmt->categoria->traer_dominio_cat_id($fila['mul_id_dominio']); }
		echo "<div id='aux_editar'>";
		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre archivo actual:","inputNombreDoc","",$fila['doc_nombre'],"","","");
		$this->fmt->form->input_form('Nombre amigable:','inputNombreAmigableDoc','',$fila['doc_ruta_amigable'],'disabled','','');
		$this->fmt->form->input_form('Tags:','inputTags','',$fila['doc_tags'],'');
		$this->fmt->form->textarea_form('Descripción:','inputDescripcionDoc','',$fila['doc_descripcion'],'','3',''); //$label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje

		$this->fmt->form->input_form('Url archivo:','inputUrlDoc','',$fila['doc_url'],'');

		$this->fmt->form->input_form('Tipo de Archivo:','inputTipoDoc','',$fila['doc_tipo_archivo'],'');
		$this->fmt->form->input_form('Imagen:','inputImagenDoc','',$fila['doc_imagen'],'','','');
		$this->fmt->form->input_form('Tamaño:','inputTamanoDoc','',$fila['doc_tamano'],'','','');
		$this->fmt->form->input_form('Dominio:','','',$aux,'','','');
		echo "</div>";
		$this->fmt->form->input_hidden_form('inputDominioDoc',$fila['doc_id_dominio']);
		$cats_id = $this->fmt->categoria->traer_rel_cat_id($id,'documento_categorias','doc_cat_cat_id','doc_cat_doc_id'); //$fila_id,$from,$prefijo_cat,$prefijo_rel
		$this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"","");

		//$this->fmt->form->input_form_sololectura('Fecha:','inputFecha','',$fila['doc_fecha'],'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

		$this->fmt->form->input_form_date('{
				"label":"Fecha Inicio:",
				"id":"inputFecha",
				"format":"dd-mm-yyyy",
				"fecha":"'.$fila['doc_fecha'].'"
		}');

		$usuario_n = $this->fmt->usuario->nombre_usuario($fila['doc_usuario']);

		$this->fmt->form->input_form_sololectura('Usuario:','inputNombreusuario','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

		$this->fmt->form->input_hidden_form("inputUsuario",$fila["doc_usuario"]);
		$this->fmt->form->input_form('Orden:','inputOrden','',$fila['doc_orden'],'','','');
		$this->fmt->form->radio_activar_form($fila['doc_activar']);
		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
    //$this->fmt->class_modulo->script_form("modulos/documentos/documentos.adm.php",$this->id_mod);
    ?>
    <script>
			$(document).ready(function () {
					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
					var vars = "ajax-ruta-amigable";
					$("#inputNombre").keyup(function () {
							var value = $(this).val();
							//$("#inputNombreAmigable").val();
							$.ajax({
									url: ruta,
									type: "POST",
									data: { inputRuta:value, ajax:vars },
									success: function(datos){
										$("#inputNombreAmigableDoc").val(datos);
									}
							});
					});
			});
		</script>
			</form>
		</div>
    <?php
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	function ingresar(){

		$ingresar1 ="doc_cat_doc_id, doc_cat_cat_id, doc_cat_orden";
		$valor_cat= $_POST['inputCat'];
		$doc_id= $_POST['inputModItemDoc'];

		$num=count( $valor_cat );
		$num_doc=count( $doc_id );

		echo "num:".$num."\n";
		echo "num_doc:".$num_doc;

		for ($i=0; $i<$num;$i++){
			for ($j=0; $j < $num_doc; $j++) { 
				$valores1 = "'".$doc_id[$j]."','".$valor_cat[$i]."','".$j."'";
				$sql1="insert into documento_categorias (".$ingresar1.") values (".$valores1.")";
				//echo "\n";
				$this->fmt->query->consulta($sql1);
			}
		}

		$this->fmt->class_modulo->redireccionar($this->ruta_modulo,"1");
		
	}

	function modificar(){
		if ($_POST["estado-mod"]=="eliminar"){
		}else{

			echo $sql="UPDATE documento SET
						doc_nombre='".$_POST['inputNombreDoc']."',
						doc_url ='".$_POST['inputUrlDoc']."',
						doc_tags ='".$_POST['inputTags']."',
						doc_imagen ='".$_POST['inputImagenDoc']."',
						doc_tipo_archivo='".$_POST['inputTipoDoc']."',
						doc_ruta_amigable='".$_POST['inputRutaAmigableDoc']."',
						doc_descripcion='".$_POST['inputDescripcionDoc']."',
						doc_tamano='".$_POST['inputTamanoDoc']."',
						doc_fecha='".$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputFecha'])."',
						doc_usuario='".$_POST['inputUsuario']."',
						doc_orden='".$_POST['inputOrden']."',
						doc_activar='".$_POST['inputActivar']."'
						WHERE doc_id='".$_POST['inputId']."'";

			//exit(0);

			$this->fmt->query->consulta($sql);

			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"documento_categorias","doc_cat_doc_id");

			$ingresar1 ="doc_cat_doc_id, doc_cat_cat_id";
			$valor_cat= $_POST['inputCat'];
			$num=count( $valor_cat );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."'";
				$sql1="insert into documento_categorias (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}
		}
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}

	function activar(){
	    $this->fmt->class_modulo->activar_get_id("documento","doc_",$this->id_estado,$this->id_item);
	    $this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
  	}

  	function eliminar(){
  		$this->fmt->class_modulo->eliminar_get_id("documento","doc_",$this->id_item);
  		$this->fmt->class_modulo->eliminar_get_id("documento_categorias","doc_cat_",$this->id_item);
  		$this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
  	}

  	function busqueda_seleccion($modo,$valor){
	  	//var_dump($valor);
  		$this->fmt->form->head_modal('Busqueda Documentos',$modo);  //$nom,$archivo,$id_mod,$botones,$id_form,$class,$modo)
  		$this->fmt->form->head_table('table_id_modal');
		$this->fmt->form->thead_table('Archivo:Acciones');
		$this->fmt->form->tbody_table_open();

		$sql="SELECT doc_id,doc_url,doc_nombre,doc_tipo_archivo,doc_id_dominio  FROM documento ORDER BY doc_orden asc";
		$rs =$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
		  for($i=0;$i<$num;$i++){

		    list($fila_id,$fila_url,$fila_nombre,$fila_tipo,$fila_dominio)=$this->fmt->query->obt_fila($rs);

		    if (!empty($valor)){
				$num_v = count($valor);
				$class_a ='';
				$class_do ='';


				for ($j=0; $j<$num_v;$j++){
					if ( $fila_id ==$valor[$j]){
						$class_a ="on";
						$class_do ="on";
					}
				}
			}
			//echo $class_do;

		    if (empty($fila_dominio)){ $aux=_RUTA_WEB; } else { $aux = $this->fmt->categoria->traer_dominio_cat_id($fila_dominio); }
		    //var_dump($fila);
		    	echo "<tr>";
				echo '<td class="fila-url"><strong><a href="'.$aux.$fila_url.'" target="_blank">'.$fila_nombre.' ('.$fila_tipo.')</strong></a></td>';
				echo "<td class='acciones' id='d-".$fila_id."'><a class='btn btn-agregar ".$class_a."' value='".$fila_id."' id='b-".$fila_id."' nombre='".$fila_nombre." (".$fila_tipo.")' ><i class='icn-plus'></i> Agregar</a> <span class='agregado bt-".$fila_id." ".$class_do."'>Agregado</span></td>";
				echo "</tr>";
		    }
		}

		$this->fmt->form->tbody_table_close();
		$this->fmt->form->footer_table();
		$this->fmt->form->footer_page($modo);
	}


}

?>
