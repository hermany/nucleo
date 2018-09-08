<?php
header("Content-Type: text/html;charset=utf-8");

class CURSO{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;
	var $instructor;

	function CURSO($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
		require_once(_RUTA_NUCLEO."modulos/e-learning/instructor.class.php");
		$this->instructor = new INSTRUCTOR($this->fmt);
	}

	public function busqueda(){
		
		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones,"","","m-cursos.css");
		$this->fmt->class_pagina->head_mod();
    $this->fmt->class_pagina->head_modulo_inner("Lista de Cursos", $botones,"crear",$this->id_mod); // bd, id modulo, botones

    $this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Nombre:Categoria:Registró:Activar:Acciones');
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
  			//$row_fecha_inicio  = $row["cur_fecha_inicio"];
  			//$row_fecha_fin = $row["cur_fecha_fin"];
  			// $row_fecha_fin = $this->fmt->class_modulo->fecha_hora_compacta($row["cur_fecha_fin"],"d,m,a");
  			//$fecha = $this->formatear_fecha($row["cur_fecha_inicio"],$row["cur_fecha_fin"]);

				echo "<tr class='row row-".$row_id."'>";
				echo '  <td class="col-id">'.$row_id.'</td>';
				echo '  <td class="col-name">'.$row_nombre.'</td>';
				echo '  <td class="">';
				$this->fmt->categoria->traer_rel_cat_nombres($row_id,'curso_categorias','cur_cat_cat_id','cur_cat_cur_id'); 
				echo '	</td>';
				echo '  <td class="">'.$this->fmt->usuario->nombre_usuario($row_usu).'</td>';
				// echo '  <td class="col-fecha">'.$fecha.'</td>';
				echo '  <td class="col-activar">';
				 $this->fmt->class_modulo->estado_publicacion($row_activar,$this->id_mod,"",$row_id);
				echo '	</td>';
				echo '  <td class="col-acciones acciones">';
				$this->fmt->class_modulo->botones_tabla($row_id,$this->id_mod,$row_nombre);//
				echo '	</td>';
				echo '	</tr>';
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
		$ndias = $this->fmt->class_modulo->contador_dias($fecha_inicio,$fecha_fin);

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
			if ($ndias > 2){
				$fecha = "Del ".$fix." al ".$ffx;
			}else{
				$fecha = $fix." y ".$ffx;
			}
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
 
		// $this->fmt->form->input_form_date('{
		// 		"label":"Fecha Inicio:",
		// 		"id":"inputInicio",
		// 		"format":"dd-mm-yyyy",
		// 		"fecha":"'.$fecha.'"
		// }');		
		// $this->fmt->form->input_form_date('{
		// 		"label":"Fecha Fin:",
		// 		"id":"inputFin",
		// 		"format":"dd-mm-yyyy",
		// 		"fecha":"'.$fecha.'"
		// }');

		// $this->fmt->form->textarea_form('Información Importante:','inputImportante','','','editor-texto','textarea-cuerpo','','');
		// $this->fmt->form->textarea_form('Objetivo:','inputObjetivo','','','editor-texto','textarea-cuerpo','','');
		

		// echo $this->fmt->form->input_precio( array('id_precio' => 'inputPrecio1',
		// 																					 'valor_precio' => '',
		// 																					 'id_tipo' => 'inputTipoPrecio1',
		// 																					 'valor_tipo' => '',
		// 																					 'label' => 'Precio Público General:',
		// 																		));

		// echo $this->fmt->form->input_precio( array('id_precio' => 'inputPrecio2',
		// 																			 'valor_precio' => '',
		// 																			 'id_tipo' => 'inputTipoPrecio2',
		// 																			 'valor_tipo' => '',
		// 																			 'label' => 'Precio Comunidad Upsa:',
		// 																));
		
		// $this->fmt->form->input_form("A quién está dirigido:","inputDirigido","","");

		// $this->fmt->form->textarea_form('Certificación:','inputCertificacion','','','editor-texto','textarea-cuerpo','','');
		// $this->fmt->form->imagen_unica_form("inputImagenCer","","","form-normal","Imagen de Certificación:");



		
		// $this->fmt->form->input_form("Duración:","inputDuracion","","");
		
		// $this->fmt->form->textarea_form('Instructor:','inputInstructor','','','editor-texto','textarea-cuerpo','','');

		$this->fmt->form->imagen_unica_form("inputImagen","","","form-normal","Imagen relacionada:");
		$this->fmt->form->imagen_unica_form("inputBanner","","","form-normal","Banner:");

		// echo $this->fmt->form->select_num(array('label' => 'Tipo de Banner:',
		// 																				'id' => 'inputTipoBanner',
		// 																				'valores'=>'Texto Blanco,Texto Negro,Personalizado'));

		// echo $this->fmt->form->select_list(array('label' => 'Instructor:',
		// 															'id' => 'inputInstructor',
		// 															'icono_btn' => 'icn icn-user-plus',
		// 															'from'=>'mod_instructor',
		// 															'prefijo'=>'mod_ins_'));

		echo $this->fmt->form->select_num(array('label' => 'Tipo de Banner:',
																						'id' => 'inputTipoBanner',
																						'select_id' => '',
																						'valores'=>'Texto Blanco,Texto Negro,Personalizado'));

		echo $this->fmt->form->select_num(array('label' => 'Alinear Banner:',
																						'id' => 'inputAlinearBanner',
																						'select_id' => '',
																						'valores'=>'Arriba,Centro,Abajo'));

		$this->fmt->form->textarea_form('Contenido','inputContenido','','','editor-texto','textarea-cuerpo','','');

		echo $this->fmt->form->select_nodo(array('label' => 'Certificado:',
																	'id' => 'inputCertificado',
																	'from'=>'curso_certificado',
																	'prefijo'=>'cur_cer_',
																	'nivel_hijos'=>'0',
																	'label_item_inicial'=>'Seleccionar certificado',
																	'id_inicio'=>'1'));


		echo $this->input_instructores(array('label' => 'Instructores:',
																					'cur_id'=> ''));


		
		$this->fmt->form->categoria_form('Categoria','inputCat',"0","","",""); 

		$this->fmt->form->documentos_form("",$this->id_mod,"","Documentos relacionados:","curso_documentos","cur_doc_","cur_");

		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputContenido","480");
		// $this->fmt->class_modulo->modal_editor_texto("inputInstructor","480");
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

		$ingresar ="cur_nombre, cur_leyenda, cur_ruta_amigable, cur_resumen, cur_tags, cur_certificado_id,  cur_contenido_min, cur_instructor_id, cur_imagen, cur_banner, cur_alinear_banner, cur_tipo_banner, cur_estado, cur_usu_id, cur_fecha_registro, cur_activar";

		$valores  ="'".$_POST['inputNombre']."','".
					$_POST['inputLeyenda']."','".
					$_POST['inputRutaamigable']."','".
					$_POST['inputResumen']."','".
					$_POST['inputTags']."','".
					$_POST['inputCertificado']."','".
					$_POST['inputContenido']."','".
					$_POST['inputInstructor']."','".
					$_POST['inputImagen']."','".
					$_POST['inputBanner']."','".
					$_POST['inputAlinearBanner']."','".
					$_POST['inputTipoBanner']."','".
					$_POST['inputEstado']."','".
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

		$ingresar1 ="cur_doc_cur_id, cur_doc_doc_id, cur_doc_orden";
		$valor_cat= $_POST['inputModItemDoc'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."','".$i."'";
			$sql1="insert into curso_documentos (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin ingresar

	function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Curso","","");
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

		//$this->fmt->form->ruta_amigable_form("inputNombre","Ruta amigable:",$row["cur_ruta_amigable"],"inputRutaamigable",$id_form,"","","0"); //$id,$ruta="Ruta Amigable:",$valor,$id_form,$ext="",$div_class,$modo="0",$placeholder,$mensaje

		$this->fmt->form->ruta_amigable_form("inputNombre","",$row['cur_ruta_amigable'],"inputRutaamigable","","","1"); 
		$this->fmt->form->input_form("Leyenda:","inputLeyenda","",$row["cur_leyenda"]);
		$this->fmt->form->input_form("Resumen:","inputResumen","",$row["cur_resumen"]);
		$this->fmt->form->input_form("tags:","inputTags","",$row["cur_tags"]);
		 
 
		// $this->fmt->form->input_form_date('{
		// 		"label":"Fecha Inicio:",
		// 		"id":"inputInicio",
		// 		"format":"dd-mm-yyyy",
		// 		"fecha":"'.$row["cur_fecha_inicio"].'"
		// }');		
		// $this->fmt->form->input_form_date('{
		// 		"label":"Fecha Fin:",
		// 		"id":"inputFin",
		// 		"format":"dd-mm-yyyy",
		// 		"fecha":"'.$row["cur_fecha_fin"].'"
		// }');

		$this->fmt->form->textarea_form('Contenido:','inputContenido','',$row["cur_contenido_min"],'editor-texto','textarea-cuerpo','','');

		echo $this->fmt->form->select_nodo(array('label' => 'Certificado:',
																	'id' => 'inputCertificado',
																	'from'=>'curso_certificado',
																	'item_seleccionado'=> $row['cur_certificado_id'],
																	'prefijo'=>'cur_cer_',
																	'nivel_hijos'=>'0',
																	'label_item_inicial'=>'Seleccionar certificado',
																	'id_inicio'=>'1'));

		// $this->fmt->form->textarea_form('Información Importante:','inputImportante','',$row["cur_importante"],'','editor-texto','textarea-cuerpo','','');
		// $this->fmt->form->textarea_form('Objetivo:','inputObjetivo','',$row["cur_objetivo"],'editor-texto','textarea-cuerpo','','');

		//$this->fmt->form->input_form("Precio Publico General:","inputPrecio1","",$row["cur_precio_1"]);

		// echo $this->fmt->form->input_precio( array('id_precio' => 'inputPrecio1',
		// 																					 'valor_precio' => $row["cur_precio_1"],
		// 																					 'id_tipo' => 'inputTipoPrecio1',
		// 																					 'valor_tipo' => $row["cur_tipo_precio_1"],
		// 																					 'label' => 'Precio Público General:',
		// 																		));

		// echo $this->fmt->form->input_precio( array('id_precio' => 'inputPrecio2',
		// 																			 'valor_precio' => $row["cur_precio_2"],
		// 																			 'id_tipo' => 'inputTipoPrecio2',
		// 																			 'valor_tipo' => $row["cur_tipo_precio_2"],
		// 																			 'label' => 'Precio Comunidad Upsa:',
		// 																));

		// $this->fmt->form->input_form("Precio Comunidad Upsa:","inputPrecio2","",$row["cur_precio_2"]);
		
		// $this->fmt->form->input_form("A quién está dirigido:","inputDirigido","",$row["cur_dirigido"]);
		// $this->fmt->form->textarea_form('Certificación:','inputCertificacion','',$row["cur_certificacion"],'editor-texto','textarea-cuerpo','','');
		// $this->fmt->form->imagen_unica_form("inputImagenCer",$row["cur_certificacion_imagen"],"","form-normal","Imagen de Certificación:");
		
		// $this->fmt->form->input_form("Duración:","inputDuracion","",$row["cur_duracion"]);
		
		
		// $this->fmt->form->textarea_form('Instructor:','inputInstructor','',$row["cur_instructor"],'editor-texto','textarea-cuerpo','','');

		$this->fmt->form->imagen_unica_form("inputImagen",$row["cur_imagen"],"","form-normal","Imagen relacionada:");
		$this->fmt->form->imagen_unica_form("inputBanner",$row["cur_banner"],"","form-normal","Banner:");

		echo $this->fmt->form->select_num(array('label' => 'Tipo de Banner:',
																						'id' => 'inputTipoBanner',
																						'select_id' => $row['cur_tipo_banner'],
																						'valores'=>'Texto Blanco,Texto Negro,Personalizado'));

		echo $this->fmt->form->select_num(array('label' => 'Alinear Banner:',
																						'id' => 'inputAlinearBanner',
																						'select_id' => $row['cur_alinear_banner'],
																						'valores'=>'Arriba,Centro,Abajo'));

		// echo $this->fmt->form->select_list(array('label' => 'Instructor:',
		// 															'id' => 'inputInstructor',
		// 															'icono_btn' => 'icn icn-user-plus',
		// 															'item' => $row["cur_instructor_id"] ,
		// 															'from'=>'mod_instructor',
		// 															'prefijo'=>'mod_ins_'));

		echo $this->input_instructores(array('label' => 'Instructores:',
																					'cur_id'=> $row["cur_id"]));

		$cats_id = $this->fmt->categoria->traer_rel_cat_id($row["cur_id"],'curso_categorias','cur_cat_cat_id','cur_cat_cur_id');
		$this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"",""); 

		$this->fmt->form->documentos_form($row["cur_id"],$this->id_mod,"","Documentos relacionados:","curso_documentos","cur_doc_","cur_");

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputContenido","480");
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
						cur_certificado_id='".$_POST['inputCertificado']."',
						cur_contenido_min='".$_POST['inputContenido']."',
						cur_instructor_id='".$_POST['inputInstructor']."',
						cur_imagen='".$_POST['inputImagen']."',
						cur_banner='".$_POST['inputBanner']."',
						cur_tipo_banner='".$_POST['inputTipoBanner']."',
						cur_alinear_banner='".$_POST['inputAlinearBanner']."',
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

			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"curso_instructores","cur_ins_cur_id");
			$ingresar1 ="cur_ins_cur_id, cur_ins_ins_id, cur_ins_orden";
			$valor_ins= $_POST['inputIns'];
			$num=count( $valor_ins );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$_POST['inputId']."','".$valor_ins[$i]."','".$i."'";
				$sql1="insert into curso_instructores (".$ingresar1.") values (".$valores1.")";
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

	public function imagen_certificado($id){
	  $consulta = "SELECT cur_cer_imagen FROM curso_certificado WHERE cur_cer_id='$id'";
		$rs =$this->fmt->query->consulta($consulta);
		$row=$this->fmt->query->obt_fila($rs);
		return $row["cur_cer_imagen"];
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


	public function programacion($id_cur){
		$consulta ="SELECT * FROM curso_programacion WHERE cur_prg_cur_id='$id_cur'";
		$rs =$this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);		
		if($num>0){
			$row = $this->fmt->query->obt_fila($rs);
			return $row;
		}else{
			return 0;
		}

		$this->fmt->query->liberar_consulta();
	}

	public function esta_programado($id_cur,$fecha){
		$consulta ="SELECT  * FROM  curso_programacion  WHERE cur_prg_cur_id='$id_cur' and cur_prg_activar=1 and cur_prg_fecha_inicio >=' $fecha'";
		$rs =$this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);		
		if($num>0){
			return true;
		}else{
			return false;
		}

		$this->fmt->query->liberar_consulta();
	}

	public function input_instructores($var){
		$label = $var["label"]; 
		$cur_id = $var["cur_id"]; 

		$html.='';
		$html.='<script src="'._RUTA_WEB_NUCLEO.'js/m-cursos.js"  crossorigin="anonymous"></script>';
		$html.='<div class="form-group form-group-instructor">';
		$html.='	<label>'.$label.'</label>';
		$html.='	<div class="group group-instructores">';
		

		$consulta = "SELECT mod_ins_id,mod_ins_nombre FROM curso_instructores, mod_instructor WHERE cur_ins_cur_id='$cur_id' and cur_ins_ins_id=mod_ins_id ORDER BY mod_ins_nombre ASC";
		$rs =$this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);
		$xm ='';
		$act ='';

		if($num>0){
			for($i=0;$i<$num;$i++){
				$row=$this->fmt->query->obt_fila($rs);
				$item = $row['mod_ins_id'];
				$nombre = $row['mod_ins_nombre'];

				$xm.='<div class="item item-ins-'.$item.'">'.$nombre.' <i class="icn icn-close btn-quitar-item-ins" item="'.$item.'"></i><input name="inputIns[]" id="cat-'.$item.'" type="hidden" value="'.$item.'"></div>';
			}
			$act ='on';
		} 
 
		$html.='		<div class="list list-instructores '.$act.'">';
		$html.='		'.$xm;
		$html.='		</div>';
		$html.='		<a class="btn btn-full btn-agregar-instructor-list"><i class="icn icn-user-plus"></i> <span>Agregar Instructor</span></a>';
		$html.='		<div class="box-seleccion-instructor">';
		$html.='			<div class="buscador">';
		$html.='				<i class="icn icn-search"></i><input id="inputBuscadorIns" autocomplete="off " placeholder="Buscar Instructor">';
		$html.='			</div>';
		$html.='				<a class="btn btn-full btn-crear-instructor"><i class="icn icn-plus"></i></a>';
		$html.='			<div class="instructores">';

		$consultax = "SELECT * FROM mod_instructor ORDER BY mod_ins_nombre ASC";
		$rsx =$this->fmt->query->consulta($consultax);
		$numx=$this->fmt->query->num_registros($rsx);
		if($numx>0){
			for($i=0;$i<$numx;$i++){
				$row=$this->fmt->query->obt_fila($rsx);
				$ins_id = $row["mod_ins_id"];
				$ins_nombre = $row["mod_ins_nombre"];
				$html .='<div class="item-ins item-ins-'.$ins_id.'" item="'.$ins_id.'" nom="'.$ins_nombre.'">	<span>'.$ins_nombre.'</span></div>';
			}
		}

		$html.='			</div>';
		$html.='		</div>';
		$html.='	</div>';
		$html.='</div>';

		return $html;
	}

	public function instructores($id_cur){
			$consulta = "SELECT cur_ins_ins_id FROM curso_instructores  WHERE cur_ins_cur_id='$id_cur'  ORDER BY cur_ins_orden ASC";
			$rs =$this->fmt->query->consulta($consulta);
			$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					$row=$this->fmt->query->obt_fila($rs);
					$dato[$i] = $row["cur_ins_ins_id"];
				}
				return $dato;
			}else{
				return 0;
			}
			$this->fmt->query->liberar_consulta();
	}


	public function curso_datos($id_cur){
			$consulta = "SELECT * FROM curso WHERE cur_id='$id_cur'";
			$rs =$this->fmt->query->consulta($consulta);
			$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				$row=$this->fmt->query->obt_fila($rs);
				return $row;
			}else{
				return 0;
			}
			$this->fmt->query->liberar_consulta();
	}

	public function instructor($id){
		$consulta = "SELECT * FROM mod_instructor WHERE mod_ins_id='$id'";
			$rs =$this->fmt->query->consulta($consulta);
			$num=$this->fmt->query->num_registros($rs);
				if($num>0){
					$row=$this->fmt->query->obt_fila($rs);
					return $row;
				}else{
					return 0;
				}
			$this->fmt->query->liberar_consulta();
	}	


	public function cuenta_usuario($id){
		$consulta = "SELECT * FROM mod_cuenta_curso WHERE mod_cnc_usu_id='$id'";
			$rs =$this->fmt->query->consulta($consulta);
			$num=$this->fmt->query->num_registros($rs);
				if($num>0){
					$row=$this->fmt->query->obt_fila($rs);
					return $row;
				}else{
					return 0;
				}
			$this->fmt->query->liberar_consulta();
	}

	public function certificado($id){
		$consulta = "SELECT * FROM curso_certificado WHERE cur_cer_id='$id'";
		$rs =$this->fmt->query->consulta($consulta);
		$row=$this->fmt->query->obt_fila($rs);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
			return $row;
		}else{
			return 0;
		}
		$this->fmt->query->liberar_consulta();
	}

}