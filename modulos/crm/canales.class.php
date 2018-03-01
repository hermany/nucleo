<?php
header("Content-Type: text/html;charset=utf-8");

class CANALES{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function CANALES($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
		echo '<div class="list-items container">';

			$consulta = "SELECT canal_id,canal_nombre FROM canal  ORDER BY canal_orden ASC";
			$rs =$this->fmt->query->consulta($consulta);
			$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					$row=$this->fmt->query->obt_fila($rs);
					$fila_id = $row["canal_id"];
					$fila_nombre = $row["canal_nombre"];
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
			echo "<a class='item item-nuevo btn-accion btn-menu-ajax' vars='form_nuevo' id_mod='".$this->id_mod."'><span><i class='icn icn-plus'></i>Nuevo Canal<span></a>";
			$this->fmt->query->liberar_consulta();
		echo '</div>';
		$this->fmt->class_pagina->footer_body_modulo_inner();
		$this->fmt->class_modulo->script_accion_modulo();
	}//fin busqueda

	function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Canal","","");
		$id_form="form-editar";

		$id = $this->id_item;
		$consulta= "SELECT * FROM canal WHERE canal_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-canales");

		
		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","",$row["canal_nombre"],"input-lg","","");
		$this->fmt->form->input_hidden_form("inputId",$id);
		$this->fmt->form->textarea_form('Descripcion:','inputDescripcion','',$row["canal_descripcion"],'','','3');
		$valor="";
		$consulta= "SELECT canal_usu_usu_id FROM canal_usuarios WHERE canal_usu_canal_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					$row=$this->fmt->query->obt_fila($rs);

					if ($i<$num-1){
						$mar=",";
					}else{
						$mar="";
					}
					$valor .= $row["canal_usu_usu_id"].$mar;
				}
			}
		$this->fmt->query->liberar_consulta($rs);


		$this->fmt->form->input_form('Usuarios Asignados:','inputAsignados','',$valor,'','','');
		//$this->fmt->usuario->agregar_usuarios_input('inputAutor','mod_columnista','simple');


		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
 
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} //fin form-editar

	function modificar(){

		$sql="UPDATE canal SET
						canal_nombre='".$_POST['inputNombre']."',
						canal_descripcion ='".$_POST['inputDescripcion']."'
						WHERE canal_id='".$_POST['inputId']."'";
			//echo $sql;
			$this->fmt->query->consulta($sql);
			// exit(0);

			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"canal_usuarios","canal_usu_canal_id");

			$ingresar1 ="canal_usu_canal_id,canal_usu_usu_id,canal_usu_rol";
			$valor_as= explode(",",$_POST['inputAsignados']);
	
			$num=count( $valor_as );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$_POST['inputId']."','".$valor_as[$i]."','4'";
				$sql1="insert into canal_usuarios (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}

			$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}
}
?>