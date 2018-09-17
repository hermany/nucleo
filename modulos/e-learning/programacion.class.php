<?php
header("Content-Type: text/html;charset=utf-8");
 
class PROGRAMACION{
	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;
	var $curso;

	public function PROGRAMACION($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
		require_once(_RUTA_NUCLEO."modulos/e-learning/cursos.class.php");
		$this->curso = new CURSO($this->fmt);
	}

	public function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones,$var,$div_class,'m-programacion-cursos.css'); //$id_mod,$botones,$var,$div_clas,$css_nucleo
		$this->fmt->class_pagina->head_mod('mod-programacion');
    $this->fmt->class_pagina->head_modulo_inner("Programación de Cursos", $botones,"crear",$this->id_mod); // bd, id modulo, botones

    $this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Curso:Categoria:Fecha:Portada:Pre-Reg:Activar:Acciones');
    $this->fmt->form->tbody_table_open();

  	// $sql="DELETE FROM curso_fechas";
		// $this->fmt->query->consulta($sql,__METHOD__);
		$up_sqr6 = "ALTER TABLE curso_fechas AUTO_INCREMENT=1";

  	$consulta = "SELECT * FROM curso_programacion";

  	$rs =$this->fmt->query->consulta($consulta);
  	$num=$this->fmt->query->num_registros($rs);
  	if($num>0){
  		for($i=0;$i<$num;$i++){
  			$row=$this->fmt->query->obt_fila($rs);
  			$row_id = $row["cur_prg_id"];
  			$row_activar = $row["cur_prg_activar"];
  			$row_nombre = $this->curso->nombre_curso($row["cur_prg_cur_id"]);
  			
  			$row_portada = $row["cur_prg_portada"];
  			
  			// $row_fecha_inicio  = $row["cur_prg_fecha_inicio"];
  			// $row_fecha_fin = $row["cur_prg_fecha_fin"];
  			// $row_fecha_fin = $this->fmt->class_modulo->fecha_hora_compacta($row["cur_prg_fecha_fin"],"d,m,a");
  			// $fecha = $this->curso->formatear_fecha($row["cur_prg_fecha_inicio"],$row["cur_prg_fecha_fin"]);

  			$fhx = $this->fechas_programacion($row_id);
  			$con_fh = count($fhx) -1;
       	$con_fhx = count($fhx);
        $cur_fecha_inicio = $fhx[0];
        $cur_fecha_fin = $fhx[$con_fh];
        $fecha = $this->curso->formatear_fecha($cur_fecha_inicio,$cur_fecha_fin);


  			$id_cat = $this->curso->categoria_curso($row["cur_prg_cur_id"]);
  			$num_cats = count($id_cat);
  			$cats ='';

  			for ($j=0; $j < $num_cats; $j++) { 
  				$cats .=  " -".$this->fmt->categoria->nombre_categoria($id_cat[$j]).'</br>';
  			}

				echo "<tr class='row row-".$row_id."'>";
				echo '  <td class="col-id">'.$row_id.'</td>';
				echo '  <td class="col-name">'.$row_nombre.'</td>';
				echo '  <td class="col-name">'.$cats.'</td>';
				echo '  <td class="">'.$fecha.'</td>';
				echo '  <td class="col-portada">';
				if ($row_portada==0){ echo ''; }
				if ($row_portada==1){ echo 'portada';}
				echo '	</td>';
				echo '  <td class="col-pre-registros"></td>';
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
    $this->fmt->class_pagina->footer_mod();
		$this->fmt->class_modulo->script_accion_modulo();
	}

	public function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nueva Programación","","");
		$id_form="form-nuevo";
		$id = $this->id_item;

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-programacion");

		echo $this->fmt->form->select_list(array('label' => 'Curso:',
																	'id' => 'inputCurso',
																	'icono_btn' => 'icn icn-plus',
																	'from'=>'curso',
																	'prefijo'=>'cur_'));

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

		echo $this->fmt->form->adicionar_fecha(array('label' => 'Fecha:',
																								 'id' => 'inputFecha',
																								 'format' => 'dd-mm-yyyy',
																								 'fecha' => $fecha ));

		$this->fmt->form->textarea_form('Importante:','inputDetalles','','','editor-texto','textarea-cuerpo','','');


		echo $this->fmt->form->input_precio(array('id_precio' => 'inputPrecio1',
																							 'valor_precio' => '',
																							 'id_tipo' => 'inputTipoPrecio1',
																							 'valor_tipo' => '',
																							 'label' => 'Precio Público General:'));

		echo $this->fmt->form->input_precio(array('id_precio' => 'inputPrecio2',
																					 'valor_precio' => '',
																					 'id_tipo' => 'inputTipoPrecio2',
																					 'valor_tipo' => '',
																					 'label' => 'Precio Comunidad Upsa:'));

		$label[0]="Slide Portada";
		      $nombreinput[0]="inputSlide";
		      $valor_in[0]="1";
		      $campo_in[0]="";
		      $this->fmt->form->input_check_form($label,$nombreinput,$valor_in,$campo_in);

		$this->fmt->form->documentos_form("",$this->id_mod,"","Documentos relacionados:","curso_programacion_docs","cur_prg_doc_","cur_prg_");

		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputDetalles","300");
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	public function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Programación","","");
		$id_form="form-editar";


		$id = $this->id_item;
		$consulta= "SELECT * FROM curso_programacion WHERE cur_prg_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-programacion");
		

		echo $this->fmt->form->select_list(array('label' => 'Curso:',
																	'id' => 'inputCurso',
																	'icono_btn' => 'icn icn-plus',
																	'item' => $row["cur_prg_cur_id"],
																	'from'=>'curso',
																	'prefijo'=>'cur_'));

		$this->fmt->form->input_hidden_form("inputId",$id);
 
		// $this->fmt->form->input_form_date('{
		// 		"label":"Fecha Inicio:",
		// 		"id":"inputInicio",
		// 		"format":"dd-mm-yyyy",
		// 		"fecha":"'.$row["cur_prg_fecha_inicio"].'"
		// }');	

		// $this->fmt->form->input_form_date('{
		// 		"label":"Fecha Fin:",
		// 		"id":"inputFin",
		// 		"format":"dd-mm-yyyy",
		// 		"fecha":"'.$row["cur_prg_fecha_fin"].'"
		// }');

		$fecha = $this->fechas_programacion($id);

		//var_dump($fecha);
		if ($fecha!=0){
			$num_fecha = count($fecha);
			$fe ='';
			for ($i=0; $i < $num_fecha; $i++) { 
				if ($i==0){
					$aux='';
				}else{
					$aux=',';
				}
				$fe .= $aux.$fecha[$i];
			}
			//echo $fe;
		}else{
			$fe = $this->fmt->class_modulo->fecha_hoy('America/La_Paz');
		}

		echo $this->fmt->form->adicionar_fecha(array('label' => 'Fecha:',
																								 'id' => 'inputFecha',
																								 'format' => 'dd-mm-yyyy',
																								 'fecha' => $fe ));

		$this->fmt->form->textarea_form('Importante:','inputDetalles','',$row["cur_prg_detalles"],'editor-texto','textarea-cuerpo','','');

		echo $this->fmt->form->input_precio( array('id_precio' => 'inputPrecio1',
																							 'valor_precio' => $row["cur_prg_precio_1"],
																							 'id_tipo' => 'inputTipoPrecio1',
																							 'valor_tipo' => $row["cur_prg_tipo_precio_1"],
																							 'label' => 'Precio Público General:'));

		echo $this->fmt->form->input_precio( array('id_precio' => 'inputPrecio2',
																					 'valor_precio' => $row["cur_prg_precio_2"],
																					 'id_tipo' => 'inputTipoPrecio2',
																					 'valor_tipo' => $row["cur_prg_tipo_precio_2"],
																					 'label' => 'Precio Comunidad Upsa:'));
		$label[0]="Slide Portada";
    $nombreinput[0]="inputSlide";
    $valor_in[0]="1";
    $campo_in[0]=$row["cur_prg_portada"];
    $this->fmt->form->input_check_form($label,$nombreinput,$valor_in,$campo_in);

    $this->fmt->form->documentos_form($row["cur_prg_id"],$this->id_mod,"","Documentos relacionados:","curso_programacion_docs","cur_prg_doc_","cur_prg_");

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputDetalles","380");
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	public function ingresar(){
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
		
		$id_usu = $this->fmt->sesion->get_variable("usu_id");

		$ingresar ="cur_prg_cur_id, cur_prg_detalles, cur_prg_fecha_inicio, cur_prg_fecha_fin, cur_prg_precio_1, cur_prg_precio_2, cur_prg_tipo_precio_1, cur_prg_tipo_precio_2, cur_prg_usu_id, cur_prg_portada, cur_prg_activar";

		$valores  ="'".$_POST['inputCurso']."','".
					$_POST['inputDetalles']."','".
					$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputInicio'])."','".
					$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputFin'])."','".
					$_POST['inputPrecio1']."','".
					$_POST['inputPrecio2']."','".
					$_POST['inputTipoPrecio1']."','".
					$_POST['inputTipoPrecio2']."','".
					$id_usu."','".
					$_POST['inputSlide']."','".
					$activar."'";
		$sql="insert into curso_programacion (".$ingresar.") values (".$valores.")";
		 
		$this->fmt->query->consulta($sql);

		$sql="select max(cur_prg_id) as id from curso_programacion";
		$rs= $this->fmt->query->consulta($sql);
		$fila = $this->fmt->query->obt_fila($rs);
		$id = $fila ["id"];


		$ingresar1 ="cur_doc_cur_id, cur_doc_doc_id, cur_doc_orden";
		$valor_cat= $_POST['inputModItemDoc'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$id."','".$valor_cat[$i]."','".$i."'";
			$sql1="insert into curso_programacion_docs (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}

		$ingresar1 ="cur_fec_prg_id, cur_fec_fecha, cur_fec_orden";
		$valor_fe= $_POST['inputFecha'];
		$num=count( $valor_fe );
		for ($i=0; $i<$num;$i++){
			$fecha = $this->fmt->class_modulo->desestructurar_fecha_hora($valor_fe[$i]);
			$valores1 = "'".$_POST['inputId']."','".$fecha."','".$i."'";
			$sql1="insert into curso_fechas (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}

		// $ingresar1 ="cur_prg_cur_id, cur_cat_cat_id, cur_cat_orden";
		// $valor_cat= $_POST['inputCat'];
		// $num=count( $valor_cat );
		// for ($i=0; $i<$num;$i++){
		// 	$valores1 = "'".$id."','".$valor_cat[$i]."','".$i."'";
		// 	$sql1="insert into curso_categorias (".$ingresar1.") values (".$valores1.")";
		// 	$this->fmt->query->consulta($sql1);
		// }

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");	
	}

	public function modificar(){
	 $id_usu = $this->fmt->sesion->get_variable("usu_id");

	 $sql="UPDATE curso_programacion SET
					cur_prg_cur_id='".$_POST['inputCurso']."',
					cur_prg_detalles='".$_POST['inputDetalles']."',
					cur_prg_fecha_inicio='".$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputInicio'])."',
					cur_prg_fecha_fin='".$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputFin'])."',
					cur_prg_precio_1='".$_POST['inputPrecio1']."',
					cur_prg_precio_2='".$_POST['inputPrecio2']."',
					cur_prg_tipo_precio_1='".$_POST['inputTipoPrecio1']."',
					cur_prg_tipo_precio_2='".$_POST['inputTipoPrecio2']."',
					cur_prg_usu_id='".$id_usu."',
					cur_prg_portada='".$_POST['inputSlide']."'
					WHERE cur_prg_id='".$_POST['inputId']."'";
		// echo $sql;
		$this->fmt->query->consulta($sql);

		$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"curso_programacion_docs","cur_prg_doc_cur_prg_id");
		$ingresar1 ="cur_prg_doc_cur_prg_id, cur_prg_doc_doc_id, cur_prg_doc_orden";
		$valor_cat= $_POST['inputModItemDoc'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."','".$i."'";
			$sql1="insert into curso_programacion_docs (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}


		$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"curso_fechas","cur_fec_prg_id");
		$ingresar1 ="cur_fec_prg_id, cur_fec_fecha, cur_fec_orden";
		$valor_fe= $_POST['inputFecha'];
		$num=count( $valor_fe );
		for ($i=0; $i<$num;$i++){
			$fecha = $this->fmt->class_modulo->desestructurar_fecha_hora($valor_fe[$i]);
			$valores1 = "'".$_POST['inputId']."','".$fecha."','".$i."'";
			$sql1="insert into curso_fechas (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}


		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}

	public function fechas_programacion($id_prog){
			$consulta = "SELECT cur_fec_fecha FROM curso_fechas  WHERE cur_fec_prg_id='$id_prog' ORDER BY cur_fec_fecha ASC";
			$rs =$this->fmt->query->consulta($consulta);
			$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					$row=$this->fmt->query->obt_fila($rs);
					$fecha[$i] = $row["cur_fec_fecha"];
				}
				return $fecha;
			}else{
				return 0;
			}
			
			$this->fmt->query->liberar_consulta();
	}

	public function  programacion_curso_id($id_prog){
		$consulta = "SELECT cur_prg_cur_id FROM curso_programacion  WHERE cur_prg_id='$id_prog'";
		$rs =$this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
			$row=$this->fmt->query->obt_fila($rs);
			return  $row["cur_prg_cur_id"];
		}else{
			return 0;
		}
		$this->fmt->query->liberar_consulta();
	}

	public function  datos_programacion($id_prog){
		$consulta = "SELECT * FROM curso_programacion  WHERE cur_prg_id='$id_prog'";
		$rs =$this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
			$row=$this->fmt->query->obt_fila($rs);
			return  $row;
		}else{
			return 0;
		}
		$this->fmt->query->liberar_consulta();
	}

} // Fin class
?>