<?php
header("Content-Type: text/html;charset=utf-8");

class CURSO{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function CURSO($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	public function busqueda(){
		
		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones);
		$this->fmt->class_pagina->head_mod();
    $this->fmt->class_pagina->head_modulo_inner("Lista de Cursos", $botones,"crear",$this->id_mod); // bd, id modulo, botones

    $this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Nombre:Categoria:Registró:Fechas:Activar:Acciones');
    $this->fmt->form->tbody_table_open();

  	$consulta = "SELECT * FROM curso";

  	$rs =$this->fmt->query->consulta($consulta);
  	$num=$this->fmt->query->num_registros($rs);
  	if($num>0){
  		for($i=0;$i<$num;$i++){
  			$row=$this->fmt->query->obt_fila($rs);
  			$row_id = $row["cur_id"];
  			$row_nombre = $row["cur_nombre"];
  			$row_usu = $row["cur_usu_id"];
  			$row_activar = $row["cur_activar"];
  			$row_fecha_inicio  = $row["cur_fecha_inicio"];
  			$row_fecha_fin = $row["cur_fecha_fin"];
  			// $row_fecha_fin = $this->fmt->class_modulo->fecha_hora_compacta($row["cur_fecha_fin"],"d,m,a");
  			$fecha = $this->formatear_fecha($row["cur_fecha_inicio"],$row["cur_fecha_fin"]);

				echo "<tr class='row row-".$row_id."'>";
				echo '  <td class="col-id">'.$row_id.'</td>';
				echo '  <td class="col-name">'.$row_nombre.'</td>';
				echo '  <td class="">';
				$this->fmt->categoria->traer_rel_cat_nombres($row_id,'curso_categorias','cur_cat_cat_id','cur_cat_cur_id'); 
				echo '	</td>';
				echo '  <td class="">'.$this->fmt->usuario->nombre_usuario($row_usu).'</td>';
				echo '  <td class="col-fecha">'.$fecha.'</td>';
				echo '  <td class="col-activar">';
				 $this->fmt->class_modulo->estado_publicacion($row_activar,$this->id_mod,"",$row_id);
				echo '	</td>';
				echo '  <td class="col-acciones acciones">';
				$this->fmt->class_modulo->botones_tabla($row_id,$this->id_mod,$row_nombre);//
				echo '	</td>';
  		}
  	}
  	$this->fmt->query->liberar_consulta();
		
		$this->fmt->form->tbody_table_close();
    $this->fmt->form->footer_table();
		
		$this->fmt->class_pagina->footer_mod();
    $this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","10",true);
		$this->fmt->class_modulo->script_accion_modulo();
	}

	public function formatear_fecha($fecha_inicio,$fecha_fin,$modo="normal"){
		$f1 = explode(" ",$fecha_inicio);
		$f2 = explode(" ",$fecha_fin);
  	$fi = explode("-",$f1[0]);
		$ff = explode("-",$f2[0]);
		$mesi= $this->fmt->class_modulo->num_mes($fi[1],$modo);
		$mesf= $this->fmt->class_modulo->num_mes($ff[1],$modo);

		if ($fecha_inicio!=$fecha_fin){
			
			if ($fi[0] != $ff[0]){
				$fix = $fi[2]." de ".$mesi." de ".$fi[0];
			}else{
				if ($fi[1] != $ff[1]){
					$fix= $fi[2]." de ".$mesi; 
				}else{
					$fix= $fi[2];
				}
			}
			$ffx = $ff[2]." de ".$mesf." de ".$ff[0];
			$fecha = "Del ".$fix." al ".$ffx;
		}else{
			$fecha = $fi[2]." de ".$mesi." de ".$fi[0];;
		}
		// return $fecha_iniciox." : ".$fecha_finx;
		return $fecha;
	}

	public function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Curso","","");
		$id_form="form-nuevo";
		$id = $this->id_item;

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-cursos");

		$this->fmt->form->input_form("* Nombre del curso:","inputNombre","","","","","","",""); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros
		$this->fmt->form->ruta_amigable_form("inputNombre","","","inputRutaamigable","","","1");
		$this->fmt->form->input_form("Leyenda:","inputLeyenda","","");
		$this->fmt->form->input_form("Resumen:","inputResumen","","");
		$this->fmt->form->input_form("tags:","inputTags","","");
		$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');
 
		$this->fmt->form->input_form_date('{
				"label":"Fecha Inicio:",
				"id":"inputInicio",
				"format":"dd-mm-yyyy",
				"fecha":"'.$fecha.'"
		}');		
		$this->fmt->form->input_form_date('{
				"label":"Fecha Fin:",
				"id":"inputFin",
				"format":"dd-mm-yyyy",
				"fecha":"'.$fecha.'"
		}');

		// $this->fmt->form->textarea_form('Información Importante:','inputImportante','','','editor-texto','textarea-cuerpo','','');
		// $this->fmt->form->textarea_form('Objetivo:','inputObjetivo','','','editor-texto','textarea-cuerpo','','');
		$this->fmt->form->textarea_form('Contenido','inputContenido','','','editor-texto','textarea-cuerpo','','');

		$this->fmt->form->input_form("Precio Publico General:","inputPrecio1","","");
		$this->fmt->form->input_form("Precio Comunidad Upsa:","inputPrecio2","","");
		
		// $this->fmt->form->input_form("A quién está dirigido:","inputDirigido","","");
		$this->fmt->form->textarea_form('Certificación:','inputCertificacion','','','editor-texto','textarea-cuerpo','','');
		$this->fmt->form->imagen_unica_form("inputImagenCer","","","form-normal","Imagen de Certificación:");
		
		// $this->fmt->form->input_form("Duración:","inputDuracion","","");
		
		// $this->fmt->form->textarea_form('Instructor:','inputInstructor','','','editor-texto','textarea-cuerpo','','');

		$this->fmt->form->imagen_unica_form("inputImagen","","","form-normal","Imagen relacionada:");
		$this->fmt->form->imagen_unica_form("inputBanner","","","form-normal","Banner:");
		
		$this->fmt->form->categoria_form('Categoria','inputCat',"0","","",""); 

		$this->fmt->form->documentos_form("",$this->id_mod,"","Documentos relacionados:","curso_documentos","cur_doc_","cur_");

		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputImportante","200");
		$this->fmt->class_modulo->modal_editor_texto("inputObjetivo","200");
		$this->fmt->class_modulo->modal_editor_texto("inputCerfigicacion","200");
		$this->fmt->class_modulo->modal_editor_texto("inputContenido","480");
		$this->fmt->class_modulo->modal_editor_texto("inputInstructor","480");
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} //fin form_nuevo

	function ingresar(){
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
		$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');
		$id_usu = $this->fmt->sesion->get_variable("usu_id");

		$ingresar ="cur_nombre, cur_leyenda, cur_ruta_amigable, cur_resumen, cur_tags, cur_importante, cur_objetivo, cur_dirigido, cur_certificacion, cur_certificacion_imagen, cur_duracion, cur_contenido_min, cur_instructor, cur_instructor_id, cur_imagen, cur_banner, cur_precio_1,cur_precio_2, cur_estado, cur_fecha_inicio, cur_fecha_fin, cur_usu_id, cur_fecha_registro, cur_activar";

		$valores  ="'".$_POST['inputNombre']."','".
					$_POST['inputLeyenda']."','".
					$_POST['inputRutaamigable']."','".
					$_POST['inputResumen']."','".
					$_POST['inputTags']."','".
					$_POST['inputImportante']."','".
					$_POST['inputObjetivo']."','".
					$_POST['inputDirigido']."','".
					$_POST['inputCertificacion']."','".
					$_POST['inputImagenCer']."','".
					$_POST['inputDuracion']."','".
					$_POST['inputContenido']."','".
					$_POST['inputInstructor']."','".
					$_POST['inputInstructorId']."','".
					$_POST['inputImagen']."','".
					$_POST['inputBanner']."','".
					$_POST['inputPrecio1']."','".
					$_POST['inputPrecio2']."','".
					$_POST['inputEstado']."','".
					$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputInicio'])."','".
					$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputFin'])."','".
					$id_usu."','".
					$fecha."','".
					$activar."'";
		 $sql="insert into curso (".$ingresar.") values (".$valores.")";
		 
		$this->fmt->query->consulta($sql);

		$sql="select max(cur_id) as id from curso";
		$rs= $this->fmt->query->consulta($sql);
		$fila = $this->fmt->query->obt_fila($rs);
		$id = $fila ["id"];

 
		$ingresar1 ="cur_cat_cur_id, cur_cat_cat_id, cur_cat_orden";
		$valor_cat= $_POST['inputCat'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$id."','".$valor_cat[$i]."','".$i."'";
			$sql1="insert into curso_categorias (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}		

		$ingresar2 ="cur_doc_cur_id, cur_doc_doc_id, cur_doc_orden";
		$valor_cat2= $_POST['inputCat'];
		$num2=count( $valor_cat2 );
		for ($i=0; $i<$num2;$i++){
			$valores2 = "'".$id."','".$valor_cat2[$i]."','".$i."'";
			$sql12="insert into curso_documentos (".$ingresar2.") values (".$valores2.")";
			$this->fmt->query->consulta($sql12);
		}

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin ingresar

	function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Lugar","","");
		$id_form="form-editar";


		$id = $this->id_item;
		$consulta= "SELECT * FROM curso WHERE cur_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-cursos");
		
		// $this->fmt->form->hidden_modulo($this->id_mod,"modificar");
		$this->fmt->form->input_form("* Nombre:","inputNombre","",$row["cur_nombre"],"","","");
		$this->fmt->form->input_hidden_form("inputId",$id);

		$this->fmt->form->ruta_amigable_form("inputNombre","",$row["cur_ruta_amigable"],"inputRutaamigable","","","1");
		$this->fmt->form->input_form("Leyenda:","inputLeyenda","",$row["cur_leyenda"]);
		$this->fmt->form->input_form("Resumen:","inputResumen","",$row["cur_resumen"]);
		$this->fmt->form->input_form("tags:","inputTags","",$row["cur_tags"]);
		 
 
		$this->fmt->form->input_form_date('{
				"label":"Fecha Inicio:",
				"id":"inputInicio",
				"format":"dd-mm-yyyy",
				"fecha":"'.$row["cur_fecha_inicio"].'"
		}');		
		$this->fmt->form->input_form_date('{
				"label":"Fecha Fin:",
				"id":"inputFin",
				"format":"dd-mm-yyyy",
				"fecha":"'.$row["cur_fecha_fin"].'"
		}');

		$this->fmt->form->textarea_form('Contenido:','inputContenido','',$row["cur_contenido_min"],'editor-texto','textarea-cuerpo','','');

		// $this->fmt->form->textarea_form('Información Importante:','inputImportante','',$row["cur_importante"],'','editor-texto','textarea-cuerpo','','');
		// $this->fmt->form->textarea_form('Objetivo:','inputObjetivo','',$row["cur_objetivo"],'editor-texto','textarea-cuerpo','','');

		$this->fmt->form->input_form("Precio Publico General:","inputPrecio1","",$row["cur_precio_1"]);
		$this->fmt->form->input_form("Precio Comunidad Upsa:","inputPrecio2","",$row["cur_precio_2"]);
		
		// $this->fmt->form->input_form("A quién está dirigido:","inputDirigido","",$row["cur_dirigido"]);
		$this->fmt->form->textarea_form('Certificación:','inputCertificacion','',$row["cur_certificacion"],'editor-texto','textarea-cuerpo','','');
		$this->fmt->form->imagen_unica_form("inputImagenCer",$row["cur_certificacion_imagen"],"","form-normal","Imagen de Certificación:");
		
		// $this->fmt->form->input_form("Duración:","inputDuracion","",$row["cur_duracion"]);
		
		
		// $this->fmt->form->textarea_form('Instructor:','inputInstructor','',$row["cur_instructor"],'editor-texto','textarea-cuerpo','','');

		$this->fmt->form->imagen_unica_form("inputImagen",$row["cur_imagen"],"","form-normal","Imagen relacionada:");
		$this->fmt->form->imagen_unica_form("inputBanner",$row["cur_banner"],"","form-normal","Banner:");


		$cats_id = $this->fmt->categoria->traer_rel_cat_id($row["cur_id"],'curso_categorias','cur_cat_cat_id','cur_cat_cur_id');
		$this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"",""); 

		$this->fmt->form->documentos_form($row["cur_id"],$this->id_mod,"","Documentos relacionados:","curso_documentos","cur_doc_","cur_");

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputImportante","200");
		$this->fmt->class_modulo->modal_editor_texto("inputObjetivo","200");
		$this->fmt->class_modulo->modal_editor_texto("inputCerfigicacion","200");
		$this->fmt->class_modulo->modal_editor_texto("inputContenido","480");
		$this->fmt->class_modulo->modal_editor_texto("inputInstructor","480");
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} // fin form_editar

	function modificar(){
		$id_usu = $this->fmt->sesion->get_variable("usu_id");

		$sql="UPDATE curso SET
						cur_nombre='".$_POST['inputNombre']."',
						cur_ruta_amigable ='".$_POST['inputRutaamigable']."',
						cur_leyenda ='".$_POST['inputLeyenda']."',
						cur_resumen ='".$_POST['inputResumen']."',
						cur_tags ='".$_POST['inputTags']."',
						cur_importante ='".$_POST['inputImportante']."',
						cur_objetivo='".$_POST['inputObjetivo']."',
						cur_dirigido='".$_POST['inputDirigido']."',
						cur_certificacion='".$_POST['inputCertificacion']."',
						cur_certificacion_imagen='".$_POST['inputImagenCer']."',
						cur_duracion='".$_POST['inputDuracion']."',
						cur_contenido_min='".$_POST['inputContenido']."',
						cur_instructor='".$_POST['inputInstructor']."',
						cur_imagen='".$_POST['inputImagen']."',
						cur_banner='".$_POST['inputBanner']."',
						cur_precio_1='".$_POST['inputPrecio1']."',
						cur_precio_2='".$_POST['inputPrecio2']."',
						cur_fecha_inicio='".$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputInicio'])."',
						cur_fecha_fin='".$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputFin'])."',
						cur_usu_id='".$id_usu."',
						cur_estado='".$_POST['inputEstado']."'
						WHERE cur_id='".$_POST['inputId']."'";
			//echo $sql;
			$this->fmt->query->consulta($sql);

			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"curso_categorias","cur_cat_cur_id");
			$ingresar2 ="cur_cat_cur_id,cur_cat_cat_id,cur_cat_orden";
			$valor_cat2= $_POST['inputCat'];
			$num2=count( $valor_cat2 );
			for ($i=0; $i<$num2;$i++){
				$valores2 = "'".$_POST['inputId']."','".$valor_cat2[$i]."','".$i."'";
				$sql2="insert into curso_categorias (".$ingresar2.") values (".$valores2.")";
				$this->fmt->query->consulta($sql2);
			}

			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"curso_documentos","cur_doc_cur_id");
			$ingresar1 ="cur_doc_cur_id, cur_doc_doc_id, cur_doc_orden";
			$valor_cat= $_POST['inputModItemDoc'];
			$num=count( $valor_cat );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."','".$i."'";
				$sql1="insert into curso_documentos (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}

			$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} //modificar

	public function categoria_curso($id){
		$consulta = "SELECT cur_cat_cat_id FROM curso_categorias WHERE cur_cat_cur_id='$id'";
		$rs =$this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
			for($i=0;$i<$num;$i++){
				$row=$this->fmt->query->obt_fila($rs);
				$row_id[$i] = $row["cur_cat_cat_id"];
			}
		}
		return $row_id;
		$this->fmt->query->liberar_consulta();
	}

	public function nombre_curso($id){
	  $consulta = "SELECT cur_nombre FROM curso WHERE cur_id='$id'";
		$rs =$this->fmt->query->consulta($consulta);
		$row=$this->fmt->query->obt_fila($rs);
		return $row["cur_nombre"];
		$this->fmt->query->liberar_consulta();
	}

	public function id_curso($ruta_amigable){
	  $consulta = "SELECT cur_id FROM curso WHERE cur_ruta_amigable='$ruta_amigable'";
		$rs =$this->fmt->query->consulta($consulta);
		$row=$this->fmt->query->obt_fila($rs);
		return $row["cur_id"];
		$this->fmt->query->liberar_consulta();
	}

	public function fecha_curso($id){
	  $consulta = "SELECT cur_fecha_inicio,cur_fecha_fin FROM curso WHERE cur_id='$id'";
		$rs =$this->fmt->query->consulta($consulta);
		$row=$this->fmt->query->obt_fila($rs);
		$row_fecha_inicio  = $row["cur_fecha_inicio"];
		$row_fecha_fin = $row["cur_fecha_fin"];
		// $row_fecha_fin = $this->fmt->class_modulo->fecha_hora_compacta($row["cur_fecha_fin"],"d,m,a");
		return $this->formatear_fecha($row["cur_fecha_inicio"],$row["cur_fecha_fin"]);

		$this->fmt->query->liberar_consulta();
	}

	public function seleccionar_curso($id_valor="",$id_nombre=""){

		$html = '';
		$html .= '<div class="box-seleccionar-curso">';
		$html .= '	<a class="btn btn-full btn-small btn-seleccionar-curso btn-toggle" toggle="box-seleccionar"><i class="icn icn-sort-desc"></i></a>';
		$html .= '	<div class="box-seleccionar" id="box-seleccionar">';
		$html .= '		<div class="buscador">';
		$html .= '			<i class="icn icn-search"></i>';
		$html .= '			<input class="text" placeholder="Buscar curso" id="inputBuscador" search="resultados" />';
		$html .= '		</div>';
		$html .= '		<div class="resultados" id="resultados">';
		$consulta = "SELECT * FROM curso WHERE cur_activar=1 ORDER BY cur_id desc";
  	$rs =$this->fmt->query->consulta($consulta);
  	$num=$this->fmt->query->num_registros($rs);
  	if($num>0){
  		for($i=0;$i<$num;$i++){
  			$row=$this->fmt->query->obt_fila($rs);
  			$row_id = $row["cur_id"];
  			$row_nombre = $row["cur_nombre"];
  			$row_fecha = $this->fmt->class_modulo->estructurar_fecha_var($row["cur_fecha_inicio"],"mini");
  			$html .= '<a class="item item-'.$row_id.'" id_valor="'.$id_valor.'" id_nombre="'.$id_nombre.'" item="'.$row_id.'" nombre="'.$row_nombre.'" > <i class="icn icn-plus"></i> <span>'.$row_fecha.' - '.$row_nombre.'</span></a>';
  		}
  	}
		$html .= '		</div>';
		$html .= '	</div>';
		$html .= '</div>';
		 return $html;
	}



}