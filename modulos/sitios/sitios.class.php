<?php
header("Content-Type: text/html;charset=utf-8");

class SITIOS{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function SITIOS($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod, $botones);


		echo '<div class="list-items container">';
			$consulta = "SELECT sitio_id, sitio_nombre FROM sitio  ORDER BY sitio_orden DESC";
			$rs =$this->fmt->query->consulta($consulta);
			$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					$row=$this->fmt->query->obt_fila($rs);
					$fila_id = $row["sitio_id"];
					$fila_nombre = $row["sitio_nombre"];
					?>
					<div class="item item-<?php echo $fila_id; ?> row-<?php echo $fila_id; ?>">
						<div class="items-inners">
							<label for=""><?php echo $fila_nombre ?></label>
							<div class="botones">
								<?php 
									$this->fmt->class_modulo->botones_tabla($fila_id,$this->id_mod,$fila_nombre);//$id_item,$id_mod,$nombre_item
								?>
							</div>
						</div>
					</div>
					<?php
				}
			}
			echo "<a class='item item-nuevo btn-accion btn-menu-ajax' vars='form_nuevo' id_mod='".$this->id_mod."'><span><i class='icn icn-plus'></i>Nuevo Sitio<span></a>";
			$this->fmt->query->liberar_consulta();
		echo '</div>';
		$this->fmt->class_pagina->footer_body_modulo_inner();
		$this->fmt->class_modulo->script_accion_modulo();
	} //fin busqueda

	function form_nuevo(){
		$this->fmt->class_pagina->inicio_form("Nuevo Sitio","form_nuevo","form-nuevo-sitio");
		$this->fmt->form->input_form("* Nombre:","inputNombre","","","input-lg","","");
		$this->fmt->form->ruta_amigable_form("inputNombre","Ruta Url:","","inputRutaamigable","","","1");
		$this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','3','');
		// $this->fmt->form->input_form("Icono:","inputIcono");
		// $this->fmt->form->input_form("Url:","inputUrl");
		// $this->fmt->form->select_target("inputDestino"); //id,$target,$class,$class_option
		$this->fmt->form->select_form_num("Tipo:","inputTipo","Normal,Logeado,Site"); //$label,$id,$valores,$select,$class_div,$class_select
		$this->fmt->form->input_form("Carpeta:","inputCarpeta");
		$this->fmt->form->roles_usuarios_checkbox("Roles:","inputRol"); //label,$id,$rol,$class_div,$class_selec
		$this->fmt->form->categoria_form('Categoria','inputCat',"0","","",""); //

		$this->fmt->form->botones_nuevo("form_nuevo",$this->id_mod,"","ingresar");

		$this->fmt->class_pagina->fin_form();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}// fin form_nuevo	

	function form_editar(){
		$id_form="form-editar";
		$id = $this->id_item;
		$consulta= "SELECT * FROM sitio WHERE sitio_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->inicio_form("Editar Sitio",$id_form,"form-editar-sitio");
		$this->fmt->form->input_form("* Nombre:","inputNombre","",$row['sitio_nombre'],"input-lg","",""); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros
		$this->fmt->form->input_hidden_form("inputId",$id);
		$this->fmt->form->ruta_amigable_form("inputNombre","Ruta Url:",$row['sitio_ruta_amigable'],"inputRutaamigable","","","1");
		$this->fmt->form->textarea_form('Descripción:','inputDescripcion',$row['sitio_descripcion'],'','','3','');
		// $this->fmt->form->input_form("Icono:","inputIcono",$row['sitio_icono']);
		// $this->fmt->form->input_form("Url:","inputUrl",$row['sitio_url']);
		// $this->fmt->form->select_target("inputDestino",$row['sitio_target']); //id,$target,$class,$class_option
		$this->fmt->form->select_form_num("Tipo:","inputTipo","Normal,Logeado,Site",$row['sitio_tipo']); //$label,$id,$valores,$select,$class_div,$class_select
		$this->fmt->form->input_form("Carpeta:","inputCarpeta",$row['sitio_carpeta']);
		$id_ck= $this->fmt->categoria->traer_rel_cat_id($row["sitio_id"],'sitio_roles','sitio_rol_rol_id','sitio_rol_sitio_id');
		$this->fmt->form->roles_usuarios_checkbox("Roles:","inputRol",$id_ck); //label,$id,$rol,$class_div,$class_selec

		$cats_id = $this->fmt->categoria->traer_rel_cat_id($row["sitio_id"],'sitio_categorias','sitio_cat_cat_id','sitio_cat_sitio_id');
		$this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"",""); //
		$this->fmt->form->input_form("Orden:","inputOrden",$row['sitio_orden']);

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");

		$this->fmt->class_pagina->fin_form();
		$this->fmt->class_modulo->modal_script($this->id_mod);

	}// fin form_editar

	function ingresar(){
		if ($_POST["estado-mod"]=="activar"){ $activar=1; }else{ $activar=0;}

		$ingresar = "sitio_nombre, sitio_descripcion, sitio_ruta_amigable, sitio_carpeta, sitio_tipo, sitio_orden, sitio_activar";
		$valores  = "'".$_POST['inputNombre']."','".
										$_POST['inputDescripcion']."','".
										$_POST['inputRutaamigable']."','".
										$_POST['inputCarpeta']."','".
										$_POST['inputTipo']."','0','".
										$activar."'";

		$sql="insert into sitio (".$ingresar.") values (".$valores.")";

		$this->fmt->query->consulta($sql,__METHOD__);

		$sql="select max(sitio_id) as id from sitio";
		$rs= $this->fmt->query->consulta($sql,__METHOD__);
		$fila = $this->fmt->query->obt_fila($rs);
		$id = $fila ["id"];
		$nom = $_POST['inputNombre'];

		$rol = $_POST['inputRol'];
		$ingresar1 ="sitio_rol_sitio_id, sitio_rol_rol_id";
		$valores1 = "'".$id."','".$rol."'";
		$sql1="insert into sitio_roles (".$ingresar1.") values (".$valores1.")";
		$this->fmt->query->consulta($sql1,__METHOD__);

		$ingresar1 = "sitio_cat_sitio_id, sitio_cat_cat_id, sitio_cat_orden";
		$valor_cat=$_POST['inputCat'];
		$num_cat=count( $valor_cat );

		for ($i=0; $i<$num_cat;$i++){
			$valores1 = "'".$id."','".$valor_cat[$i]."','".$i."'";
			$sql1="insert into sitio_categorias (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1,__METHOD__);
		}

		$nuevo_sitio="$('.item-nuevo').before('<div class=\"item item-$id animated flash row-$id\"><div class=\"items-inners\"><label>$nom</label><div class=\"botones\"><a class=\"btn btn-accion btn-menu-ajax \" title=\"editar $id\" id=\"btn-m-$id\" id_mod=\"$this->id_mod\" nombre=\"\" vars=\"form_editar,$id\"><i class=\"icn-pencil\"></i><span></span></a> <a class=\"btn btn-accion btn-m-eliminar\" title=\"eliminar $id\" id=\"btn-m-$id\" id_mod=\"$this->id_mod\" nombre=\"$nom\" vars=\"eliminar,$id\"><i class=\"icn-trash\"></i><span></span></a></div></div></div>')";

		$this->fmt->class_modulo->script_cerrar_ventana($nuevo_sitio);

	}

	function modificar(){
		$sql="UPDATE sitio SET
						sitio_nombre='".$_POST['inputNombre']."',
						sitio_descripcion ='".$_POST['inputDescripcion']."',
						sitio_ruta_amigable ='".$_POST['inputRutaamigable']."',
						sitio_carpeta ='".$_POST['inputCarpeta']."',
						sitio_orden ='".$_POST['inputOrden']."',
						sitio_tipo='".$_POST['inputTipo']."'
						WHERE sitio_id='".$_POST['inputId']."'";
			//echo $sql;
		$this->fmt->query->consulta($sql);
		$id = $_POST['inputId'];

		$this->fmt->class_modulo->eliminar_fila($id,"sitio_roles","sitio_rol_sitio_id");
		$rol = $_POST['inputRol'];
		$ingresar1 ="sitio_rol_sitio_id, sitio_rol_rol_id";
		$valores1 = "'".$id."','".$rol."'";
		$sql1="insert into sitio_roles (".$ingresar1.") values (".$valores1.")";
		$this->fmt->query->consulta($sql1,__METHOD__);

		$this->fmt->class_modulo->eliminar_fila($id,"sitio_categorias","sitio_cat_sitio_id");
		$ingresar1 = "sitio_cat_sitio_id, sitio_cat_cat_id, sitio_cat_orden";
		$valor_cat=$_POST['inputCat'];
		$num_cat=count( $valor_cat );

		for ($i=0; $i<$num_cat;$i++){
			$valores1 = "'".$id."','".$valor_cat[$i]."','".$i."'";
			$sql1="insert into sitio_categorias (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1,__METHOD__);
		}

		$nuevo_sitio="$('.row-".$id." .items-inners label').html('".$_POST['inputNombre']."')";

		$this->fmt->class_modulo->script_cerrar_ventana($nuevo_sitio);

	}
}
