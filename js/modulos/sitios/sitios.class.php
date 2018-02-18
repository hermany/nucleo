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

			$consulta = "SELECT sitio_id, sitio_nombre FROM sitio  ORDER BY sitio_orden ASC";
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
		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","","","input-lg","","");
		$this->fmt->form->ruta_amigable_form("inputNombre","","","inputRutaamigable","","","1");
		$this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','3','');
		$this->fmt->form->input_form("Icono:","inputIcono");
		$this->fmt->form->input_form("Url:","inputUrl");
		$this->fmt->form->select_target("inputDestino"); //id,$target,$class,$class_option
		$this->fmt->form->select_form_num("Tipo:","inputTipo","Normal,Logeado,Site"); //$label,$id,$valores,$select,$class_div,$class_select
		$this->fmt->form->input_form("Carpeta:","inputCarpeta");
		$this->fmt->form->roles_usuarios_checkbox("Roles:","inputRol"); //label,$id,$rol,$class_div,$class_selec
		$this->fmt->form->categoria_form('Categoria','inputCat',"0","","",""); //

		$this->fmt->form->botones_nuevo("form_nuevo",$this->id_mod,"","ingresar");

		$this->fmt->class_pagina->fin_form();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}// fin form_nuevo	

	function form_editar(){

	}// fin form_editar

	function ingresar(){
		if ($_POST["estado-mod"]=="activar"){ $activar=1; }else{ $activar=0;}

		$ingresar = "sitio_nombre, sitio_descripcion, sitio_ruta_amigable, sitio_icono, sitio_url, sitio_target, sitio_tipo, sitio_carpeta, sitio_orden, sitio_activar";
		$valores  = "'".$_POST['inputNombre']."','".
										$_POST['inputDescripcion']."','".
										$_POST['inputRutaamigable']."','".
										$_POST['inputIcono']."','".
										$_POST['inputUrl']."','".
										$_POST['inputTarget']."','".
										$_POST['inputTipo']."','".
										$_POST['inputCarpeta']."','0','".
										$activar."'";

		$sql="insert into sitio (".$ingresar.") values (".$valores.")";
		$this->fmt->query->consulta($sql,__METHOD__);

		$sql="select max(sitio_id) as id from sitio";
		$rs= $this->fmt->query->consulta($sql,__METHOD__);
		$fila = $this->fmt->query->obt_fila($rs);
		$id = $fila ["id"];

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

		$this->fmt->class_modulo->redireccionar($this->ruta_modulo,"1");

	}
}
