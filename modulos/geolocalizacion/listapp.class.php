<?php 
header("Content-Type: text/html;charset=utf-8");

class LISTA_APP{
	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function LISTA_APP($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	public function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones);
		$this->fmt->class_pagina->head_mod();

		$botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nuevo Punto","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
    $this->fmt->class_pagina->head_modulo_inner("Lista de Listas Apps", $botones); // bd, id modulo, botones

    $this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Nombre:Activar:Acciones');
    $this->fmt->form->tbody_table_open();

  	$consulta = "SELECT mod_list_app_id, mod_list_app_nombre, mod_list_app_activar FROM mod_lista_app";
  	$rs =$this->fmt->query->consulta($consulta);
  	$num=$this->fmt->query->num_registros($rs);
  	if($num>0){
  		for($i=0;$i<$num;$i++){
  			$row=$this->fmt->query->obt_fila($rs);
  			$row_id = $row["mod_list_app_id"];
  			$row_nombre = $row["mod_list_app_nombre"];
  			$row_activar = $row["mod_list_app_activar"];

				echo "<tr class='row row-".$row_id."'>";
				echo '  <td class="col-id">'.$row_id.'</td>';
				echo '  <td class="col-name">'.$row_nombre.'</td>';
				// echo '  <td class="col-padre">';
				// $this->fmt->categoria->traer_rel_cat_nombres($row_id,'mod_lista_categorias','mod_list_cat_cat_id','mod_list_cat_list_id'); 
				// echo '	</td>';
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

	public function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nueva Lista","","");
		$id_form="form-nuevo";
		$id = $this->id_item;

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-lista");

		$this->fmt->form->input_form("* Nombre:","inputNombre","","","input-lg","","","",""); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros
		$this->fmt->form->input_form("Descripcion:","inputDescripcion","","");
		$this->fmt->form->input_form("Tags:","inputTags","","");
		$this->fmt->form->input_form("Url:","inputUrl","","");
		$this->fmt->form->input_form("Icono:","inputIcono","","");
		$this->fmt->form->input_form("Orden:","inputOrden","","");
		
		$this->fmt->form->textarea_form('Resumen:','inputResumen','','','','3','');

		$this->fmt->form->imagen_unica_form("inputImagen","","","form-normal","Imagen relacionada:");
		$this->fmt->form->imagen_unica_form("inputBanner","","","form-normal","Banner:");
		$this->fmt->form->input_form("Rango Edades:","inputRango","Ej. 12-24,24-34","");
		
		echo $this->fmt->form->select_num (array('label' => 'Rank:',
																					 	'id' => 'inputRank',
																					 	'select_id' => '5',
																					 	'valores' => 'Seleccionar,1-Top,2-Medium,3-Basico,4-Semi,5-Normal'));

		//$this->fmt->form->categoria_form('Categoria','inputCat',"0","","",""); //

		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	public function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Lista","","");
		$id_form="form-editar";

		$id = $this->id_item;
		$consulta= "SELECT * FROM mod_lista_app WHERE mod_list_app_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-lista");
		
		// $this->fmt->form->hidden_modulo($this->id_mod,"modificar");
		$this->fmt->form->input_form("* Nombre:","inputNombre","",$row["mod_list_app_nombre"],"input-lg","","");
		$this->fmt->form->input_hidden_form("inputId",$id);

		$this->fmt->form->input_form("Descripcion:","inputDescripcion","",$row["mod_list_app_descripcion"]);
		$this->fmt->form->input_form("Tags:","inputTags","",$row["mod_list_app_tags"]);
		$this->fmt->form->input_form("Url:","inputUrl","",$row["mod_list_app_url"]);
		$this->fmt->form->input_form("Icono:","inputIcono","",$row["mod_list_app_icon"]);
		$this->fmt->form->textarea_form('Resumen:','inputResumen','',$row["mod_list_app_resumen"],'','','');
		// echo "imagen:".$row['mod_list_imagen'];
		$this->fmt->form->imagen_unica_form("inputImagen",$row['mod_list_app_imagen'],"","form-normal","Imagen relacionada:");
		$this->fmt->form->imagen_unica_form("inputImagen",$row['mod_list_app_banner'],"","form-normal","Banner:");
		$this->fmt->form->input_form("Rango de edades:","inputRango","",$row['mod_list_app_rango_edades']);

		echo $this->fmt->form->select_num (array('label' => 'Rank:',
																					 	'id' => 'inputRank',
																					 	'select_id' => $row['mod_list_app_rank'],
																					 	'valores' => 'Seleccionar,1-Top,2-Medium,3-Basico,4-Semi,5-Normal'));

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	public function ingresar(){
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
		$ingresar ="mod_list_app_nombre, mod_list_app_tags, mod_list_app_descripcion, mod_list_app_resumen, mod_list_app_imagen, mod_list_app_banner, mod_list_app_url, mod_list_app_icon, mod_list_app_orden, mod_list_app_rango_edades, mod_list_app_rank, mod_list_app_activar";

		$valores  ="'".$_POST['inputNombre']."','".
					$_POST['inputTags']."','".
					$_POST['inputDescripcion']."','".
					$_POST['inputResumen']."','".
					$_POST['inputImagen']."','".
					$_POST['inputBanner']."','".
					$_POST['inputUrl']."','".
					$_POST['inputIcono']."','".
					$_POST['inputOrden']."','".
					$_POST['inputRango']."','".
					$_POST['inputRank']."','".
					$activar."'";
		$sql="insert into mod_lista_app (".$ingresar.") values (".$valores.")";
		$this->fmt->query->consulta($sql);

		// $sql="select max(mod_list_id) as id from mod_lista";
		// $rs= $this->fmt->query->consulta($sql);
		// $fila = $this->fmt->query->obt_fila($rs);
		// $id = $fila ["id"];

 
		// $ingresar1 ="mod_list_cat_list_id, mod_list_cat_cat_id, mod_list_cat_orden";
		// $valor_cat= $_POST['inputCat'];
		// $num=count( $valor_cat );
		// for ($i=0; $i<$num;$i++){
		// 	$valores1 = "'".$id."','".$valor_cat[$i]."','".$i."'";
		// 	$sql1="insert into mod_lista_categorias (".$ingresar1.") values (".$valores1.")";
		// 	$this->fmt->query->consulta($sql1);
		// }

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin ingresar

	function modificar(){

		$sql="UPDATE mod_lista_app SET
				mod_list_app_nombre='".$_POST['inputNombre']."',
				mod_list_app_descripcion='".$_POST['inputDescripcion']."',
				mod_list_app_tags ='".$_POST['inputTags']."',
				mod_list_app_resumen ='".$_POST['inputResumen']."',
				mod_list_app_imagen ='".$_POST['inputImagen']."',
				mod_list_app_banner='".$_POST['inputBanner']."',
				mod_list_app_url='".$_POST['inputUrl']."',
				mod_list_app_icon='".$_POST['inputIcono']."',
				mod_list_app_rango_edades='".$_POST['inputRango']."',
				mod_list_app_orden='".$_POST['inputOrden']."',
				mod_list_app_rank='".$_POST['inputRank']."'
				WHERE mod_list_app_id='".$_POST['inputId']."'";
		//echo $sql;
		$this->fmt->query->consulta($sql);

		// $this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"mod_lista_categorias","mod_list_cat_list_id");
		// $ingresar1 ="mod_list_cat_list_id, mod_list_cat_cat_id, mod_list_cat_orden";
		// $valor_cat= $_POST['inputCat'];
		// $num=count( $valor_cat );
		// for ($i=0; $i<$num;$i++){
		// 	$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."','".$i."'";
		// 	$sql1="insert into mod_lista_categorias (".$ingresar1.") values (".$valores1.")";
		// 	$this->fmt->query->consulta($sql1);
		// }

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin modificar

}

?>