<?php
header("Content-Type: text/html;charset=utf-8");

class CONTENIDOS{
	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function CONTENIDOS($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}
/************** Busqueda ***************/

	function busqueda(){
		// $ruta_server=_RUTA_SERVER;

		 $this->fmt->class_pagina->crear_head( $this->id_mod, $botones); // bd, id modulo, botones
		 ?>
		 <div class="body-modulo container-fluid">
			 <div class="container">
				 <?php
					 $botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Crear","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
					 $this->fmt->class_pagina->head_modulo_inner("Contenidos web activos", $botones); // bd, id modulo, botones
					 $this->fmt->form->head_table("table_id");
					 $this->fmt->form->thead_table('#:Titulo:Creado por:Categorías:Fecha:Estado:Acciones');
					 $this->fmt->form->tbody_table_open();
					 $consulta="select conte_id, conte_titulo, conte_fecha, conte_activar, conte_id_usuario from contenido ORDER BY conte_id desc";
					 $rs =$this->fmt->query->consulta($consulta,__METHOD__);
					 $num=$this->fmt->query->num_registros($rs);
					 if($num>0){
						 for($i=0;$i<$num;$i++){
							 $row=$this->fmt->query->obt_fila($rs);
							 $fila_id= $row["conte_id"];
							 $fila_nombre= $row["conte_titulo"];
							 $fila_fecha= $row["conte_fecha"];
							 $fila_activar= $row["conte_activar"];
							 $fila_usuario= $row["conte_id_usuario"];
							 echo "<tr class='row row-".$fila_id."'>";
								 echo '<td class="">'.$fila_id.'</td>';
								 echo '<td class=""><strong>'.$fila_nombre.'</strong></td>';
								 echo '<td class="">'.$this->fmt->usuario->nombre_usuario( $fila_usuario ).'</td>';
								 echo '<td class="">';
									 $this->fmt->categoria->traer_rel_cat_nombres($fila_id,'contenido_categorias','conte_cat_cat_id','conte_cat_conte_id'); //$fila_id,$from,$prefijo_cat,$prefijo_rel
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
									 echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar","btn btn-accion btn-m-editar ",$this->id_mod,"form_editar,".$fila_id);
								 ?>
									 <a  title="eliminar <?php echo $fila_id; ?>" type="button" nombre="<?php echo $fila_nombre; ?>" id="btn-m<?php echo $this->id_mod; ?>" id_mod="<?php echo $this->id_mod; ?>" vars="eliminar,<?php echo $fila_id; ?>" class="btn btn-fila-eliminar btn-accion"><i class="icn-trash"></i></a>
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
		 $this->fmt->class_modulo->modal_editor_texto("inputCuerpo");
		 $this->fmt->class_modulo->script_accion_modulo();
		 $this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","25",true);
	} // Fin busqueda

/************** Formulario form_nuevo ***************/

	function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Contenido","","");
		$id_form="form-nuevo";
		?>
		<div class="body-modulo">
			<form class="form form-modulo form-multimedia"  method="POST" id="<?php echo $id_form?>">
				<?php
				$this->fmt->form->input_form("<span class='obligatorio'>*</span> Titulo:","inputTitulo","","","input-lg","","");
				$this->fmt->form->input_form("Nombre Amigable:","inputNombreAmigable","","","","","","");
				$this->fmt->form->input_hidden_form("inputId",$id);
				//$this->fmt->form->hidden_modulo($this->id_mod,"modificar");
				$this->fmt->form->input_form("Subtitulo:","inputSubtitulo","","","","","");

				$this->fmt->form->textarea_form('Cuerpo:','inputCuerpo','','','editor-texto','textarea-cuerpo','',''); //label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje

				$this->fmt->form->input_form('Tags:','inputTags',"",'','');
				// if($fila['conte_foto']!=""){
				// 	$imagen=_RUTA_WEB.$fila['conte_foto'];
				// 	$nombre_archivo = $this->fmt->archivos->convertir_nombre_thumb($imagen);
				// 	if(file_exists($nombre_archivo))
				// 		$imagen=$nombre_archivo;
				//
				//
				// 	echo "<img width='200px' src='".$imagen."'>";
				// }
				// $this->fmt->form->input_hidden_form('inputImagen',$fila['conte_foto']);
				?>
				<!-- <div class="form-group">
					<label>Imagen:</label>
					<div class="panel panel-default" >
						<div class="panel-body">
						<?php
						//$this->fmt->form->file_form_nuevo_save_thumb('Cargar Archivo (max 8MB)','','form_editar','form-file','','box-file-form','archivos/contenidos','476x268');  //$nom,$ruta,$id_form,$class,$class_div,$id_div
						?>
						</div>
					</div>
				</div> -->
				<?php
				//$this->fmt->form->agregar_documentos("Documentos:","inputDoc",$fila["conte_id"],"","","","contenidos_documento","conte_doc_conte_id","conte_doc_doc_id"); //$label,$id,$valor,$class,$class_div,$mensaje,$from,$id_item

				$this->fmt->form->categoria_form('Categoria','inputCat',"0","","",""); //$label,$id,$cat_raiz,$cat_valor,$class,$class_div
				$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');
				$this->fmt->form->input_form('Fecha:','inputFecha','',$fecha,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$usuario = $this->fmt->sesion->get_variable('usu_id');
				$usuario_n =  $this->fmt->usuario->nombre_usuario( $usuario );
				$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
				$this->fmt->form->input_form("Clase:","inputClase","","","","","");
				$this->fmt->form->radio_activar_form();
				$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
				?>
			</form>
		</div>
		<?php
		//$this->fmt->form->textarea_form('Cuerpo:','inputCuerpo','',$fila["conte_cuerpo"],'editor-texto','textarea-cuerpo','','3',''); //label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputCuerpo");
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} //Fin function form modificar


	function ingresar(){
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
		if($_POST["inputTitulo"]!=""){
			if(isset($_POST["inputUrl"]))
				$imagen=$_POST["inputUrl"];
			else
				$imagen=$_POST['inputImagen'];

			$ingresar ="conte_titulo, conte_ruta_amigable, conte_subtitulo, conte_cuerpo, conte_foto, conte_fecha, conte_id_usuario, conte_clase, conte_tag, conte_id_dominio, conte_activar";
			$valores  ="'".$_POST['inputTitulo']."','".
						$_POST['inputNombreAmigable']."','".
						$_POST['inputSubtitulo']."','".
						$_POST['inputCuerpo']."','".
						$imagen."','".
						$_POST['inputFecha']."','".
						$_POST['inputUsuario']."','".
						$_POST['inputClase']."','".
						$_POST['inputTags']."','".
						$_POST['inputDominio']."','".
						$activar."'";
			$sql="insert into contenido (".$ingresar.") values (".$valores.")";
			$this->fmt->query->consulta($sql,__METHOD__);

			$sql="select max(conte_id) as id from contenido";
			$rs= $this->fmt->query->consulta($sql,__METHOD__);
			$fila = $this->fmt->query->obt_fila($rs);
			$id = $fila ["id"];

			$ingresar1 = "conte_cat_conte_id,conte_cat_cat_id";
			$valor_cat=$_POST['inputCat'];
			$num_cat=count( $valor_cat );

			for ($i=0; $i<$num_cat;$i++){
				$valores1 = "'".$id."','".$valor_cat[$i]."'";
				$sql1="insert into contenido_categorias (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1,__METHOD__);
			}

			$ingresar1 = "conte_doc_conte_id,conte_doc_doc_id";
			$valor_doc= $_POST['inputDoc'];
			$num=count( $valor_doc );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$id."','".$valor_doc[$i]."'";
				$sql1="insert into contenido_documentos  (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1,__METHOD__);
			}

		}
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");

	}

	function form_editar(){
		$id = $this->id_item;
		$consulta= "SELECT * FROM contenido WHERE conte_id='".$id."'";
		$rs =$this->fmt->query->consulta($consulta,__METHOD__);
		$fila=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->crear_head_form("Editar Contenido","","");
		$id_form="form-editar";

		//$this->fmt->form->finder("inputCuerpo",$this->id_mod,"","individual-complejo","imagenes");

		 //$id,$id_mod,$url,$tipo_upload="individual",$tipo_archivo

		?>
		<div class="body-modulo">
			<form class="form form-modulo form-multimedia"  method="POST" id="<?php echo $id_form?>">
				<?php
				$this->fmt->form->input_form("<span class='obligatorio'>*</span> Titulo:","inputTitulo","",$fila["conte_titulo"],"input-lg","","");
				if (!empty($fila['conte_ruta_amigable'])){
					$valor_ra = $fila['conte_ruta_amigable'];
				}else{
					$valor_ra = $this->fmt->get->convertir_url_amigable($fila['conte_titulo']);
				}
				$this->fmt->form->input_form("Nombre Amigable:","inputNombreAmigable","",$valor_ra,"","","","");
				$this->fmt->form->input_hidden_form("inputId",$id);
				//$this->fmt->form->hidden_modulo($this->id_mod,"modificar");
				$this->fmt->form->input_form("Subtitulo:","inputSubtitulo","",$fila["conte_subtitulo"],"","","");

				$this->fmt->form->textarea_form('Cuerpo:','inputCuerpo','',$fila["conte_cuerpo"],'editor-texto','textarea-cuerpo','',''); //label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje

				$this->fmt->form->input_form('Tags:','inputTags',"",$fila["conte_tag"],'');
				// if($fila['conte_foto']!=""){
				// 	$imagen=_RUTA_WEB.$fila['conte_foto'];
				// 	$nombre_archivo = $this->fmt->archivos->convertir_nombre_thumb($imagen);
				// 	if(file_exists($nombre_archivo))
				// 		$imagen=$nombre_archivo;
				//
				//
				// 	echo "<img width='200px' src='".$imagen."'>";
				// }
				// $this->fmt->form->input_hidden_form('inputImagen',$fila['conte_foto']);
				?>
				<!-- <div class="form-group">
					<label>Imagen:</label>
					<div class="panel panel-default" >
						<div class="panel-body">
						<?php
						//$this->fmt->form->file_form_nuevo_save_thumb('Cargar Archivo (max 8MB)','','form_editar','form-file','','box-file-form','archivos/contenidos','476x268');  //$nom,$ruta,$id_form,$class,$class_div,$id_div
						?>
						</div>
					</div>
				</div> -->
				<?php
				//$this->fmt->form->agregar_documentos("Documentos:","inputDoc",$fila["conte_id"],"","","","contenidos_documento","conte_doc_conte_id","conte_doc_doc_id"); //$label,$id,$valor,$class,$class_div,$mensaje,$from,$id_item
				// if ($fila["conte_foto"]){ $text="Actualizar"; }else{ $text="Cargar archivo";  }
				// $this->fmt->form->imagen_form("Imagen:",$text,"inputImagen",$fila["conte_id"],$fila["conte_foto"]);
				$this->fmt->form->imagen_unica_form("inputImagen",$fila["conte_foto"],"","form-normal","Imagen relacionada:");

				$this->fmt->form->multimedia_form_block($fila['conte_id'],$this->id_mod,"","","Multimedia Adicional:","contenido_multimedia","conte_mul_","conte_");//$id_item,$id_mod,$class_div,$label="Subir archivo",$label_form=""

				$this->fmt->form->documentos_form($fila['conte_id'],$this->id_mod,"","Documentos relacionados:","contenido_documentos","conte_doc_","conte_");

				$cats_id = $this->fmt->categoria->traer_rel_cat_id($fila["conte_id"],'contenido_categorias','conte_cat_cat_id','conte_cat_conte_id');
				$this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"",""); //$label,$id,$cat_raiz,$cat_valor,$class,$class_div
				$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');
				$this->fmt->form->input_form('Fecha:','inputFecha','',$fila["conte_fecha"],'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$usuario = $this->fmt->sesion->get_variable('usu_id');
				$usuario_n =  $this->fmt->usuario->nombre_usuario( $usuario );
				$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
				$this->fmt->form->input_form("Clase:","inputClase","",$fila["conte_clase"],"","","");
				$this->fmt->form->radio_activar_form($fila['conte_activar']);
				$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
				?>
			</form>
		</div>
		<?php
		//$this->fmt->form->textarea_form('Cuerpo:','inputCuerpo','',$fila["conte_cuerpo"],'editor-texto','textarea-cuerpo','','3',''); //label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje
	  $this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputCuerpo");
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	function modificar(){
		if ($_POST["estado-mod"]=="eliminar"){
		}else{
			if($_POST["inputTitulo"]!=""){
				if(isset($_POST["inputUrl"]))
					$imagen=$_POST["inputUrl"];
				else
					$imagen=$_POST['inputImagen'];

				$sql="UPDATE contenido SET
							conte_titulo='".$_POST['inputTitulo']."',
							conte_ruta_amigable ='".$_POST['inputNombreAmigable']."',
							conte_subtitulo ='".$_POST['inputSubtitulo']."',
							conte_cuerpo='".$_POST['inputCuerpo']."',
							conte_foto='".$imagen."',
							conte_fecha='".$_POST['inputFecha']."',
							conte_id_usuario='".$_POST['inputUsuario']."',
							conte_tag='".$_POST['inputTags']."',
							conte_clase='".$_POST['inputClase']."',
							conte_id_dominio='".$_POST['inputDominio']."',
							conte_activar='".$_POST['inputActivar']."'
							WHERE conte_id='".$_POST['inputId']."'";

				$this->fmt->query->consulta($sql,__METHOD__);

				$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"contenido_categorias","conte_cat_conte_id");  //$valor,$from,$fila

				$ingresar1 = "conte_cat_conte_id,conte_cat_cat_id";
				$valor_cat=$_POST['inputCat'];
				$num_cat=count( $valor_cat );

				for ($i=0; $i<$num_cat;$i++){
					$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."'";
					$sql1="insert into contenido_categorias  (".$ingresar1.") values (".$valores1.")";
					$this->fmt->query->consulta($sql1,__METHOD__);
				}

				$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"contenido_documentos","conte_doc_conte_id");

				$ingresar1 = "conte_doc_conte_id,conte_doc_doc_id,conte_doc_orden";
				$valor_doc= $_POST['inputModItemDoc'];
				$num=count( $valor_doc );
				for ($i=0; $i<$num;$i++){
					$valores1 = "'".$_POST['inputId']."','".$valor_doc[$i]."','$i'";
					$sql1="insert into contenido_documentos  (".$ingresar1.") values (".$valores1.")";
					$this->fmt->query->consulta($sql1,__METHOD__);
				}

				$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"contenido_multimedia","conte_mul_conte_id");

				$ingresar1 = "conte_mul_conte_id,conte_mul_mul_id,conte_mul_orden";
				$valor_mul= $_POST['inputModItemMul'];
				$num=count( $valor_mul );
				for ($i=0; $i<$num;$i++){
					$valores1 = "'".$_POST['inputId']."','".$valor_mul[$i]."','$i'";
					$sql1="insert into contenido_multimedia  (".$ingresar1.") values (".$valores1.")";
					$this->fmt->query->consulta($sql1,__METHOD__);
				}


			}
		}
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}
	function eliminar(){
  		$this->fmt->class_modulo->eliminar_get_id("contenidos","conte_",$this->id_item);
  		$this->fmt->class_modulo->eliminar_get_id("contenidos_categorias","conte_cat_conte_",$this->id_item);
  		$this->fmt->class_modulo->eliminar_get_id("contenidos_documentos","conte_doc_conte_",$this->id_item);
  		$this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
  	}

  	function activar(){
	    $this->fmt->class_modulo->activar_get_id("contenidos","conte_",$this->id_estado,$this->id_item);
	   $this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
  	}
}

?>
