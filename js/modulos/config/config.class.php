<?php
header("Content-Type: text/html;charset=utf-8");

class CONFIG{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $nombre_modulo ="config.adm.php";
	var $nombre_tabla ="configuracion";
	var $prefijo_tabla ="conf_";


	function CONFIG($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function form_editar(){
		$botones = $this->fmt->class_modulo->botones_hijos_modulos($this->id_mod);
		$this->fmt->class_pagina->crear_head( $this->id_mod, $botones,"");

		$sql="SELECT * FROM ".$this->nombre_tabla;
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
			for($i=0;$i<$num;$i++){
				$row=$this->fmt->query->obt_fila($rs);
				$fila_nombre_sitio=$row["conf_nombre_sitio"];
				$fila_imagen=$row["conf_imagen"];
				$fila_favicon=$row["conf_favicon"];
				$fila_script_head=$row["conf_script_head"];
				$fila_script_footer=$row["conf_script_footer"];
				$fila_ruta_analitica=$row["conf_meta"];
			}
		}
		?>
		<div class="body-modulo container-fluid">
			<div class="container">
				<form class="form form-modulo form-vertical" enctype="multipart/form-data" method="POST" id="form-editar">
					<div class="modulo-inner">
						<div class="box-md-6 box-md-right">
						<?php
							$this->fmt->form->input_form('Nombre del sitio:','inputNombre','',$fila_nombre_sitio,'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
							$this->fmt->form->input_form('Imagen:','inputImagen','',$fila_imagen,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
							$this->fmt->form->input_form('Favicon:','inputIcono','',$fila_favicon,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
							$this->fmt->form->input_form('Meta:','inputMeta','',$fila_ruta_analitica,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

						?>
						</div>
						<div class="box-md-6 box-md-left">
						<?php
							$this->fmt->form->textarea_form('Script head:','inputHead','',$fila_script_head,'','','5','','');
							$this->fmt->form->textarea_form('script footer:','inputFooter','',$fila_script_footer,'','','5','','');

							// $this->fmt->form->hidden_modulo($this->id_mod,"modificar_empresa");
							$this->fmt->form->btn_actualizar("form-editar",$this->id_mod,"modificar","box-botones"); //$id_form,$id_mod,$tarea
						?>
							<!-- <div class="form-group form-botones clear-both">
								<button type="submit" class="btn-accion-form btn-form btn btn-info  btn-actualizar hvr-fade btn-lg color-bg-celecte-c btn-lg" form="form_editar" name="btn-accion" id="btn-activar" value="actualizar"><i class="icn-sync" ></i> Actualizar</button>
							</div> -->
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php
		//$this->fmt->class_modulo->script_form($this->ruta_modulo,$this->id_mod);
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	function modificar(){

			$sql="UPDATE configuracion SET
									conf_nombre_sitio='".$_POST['inputNombre']."',
									conf_imagen ='".$_POST['inputImagen']."',
									conf_favicon ='".$_POST['inputIcono']."',
									conf_script_head ='".$_POST['inputHead']."',
									conf_script_footer ='".$_POST['inputFooter']."',
									conf_meta ='".$_POST['inputMeta']."'";
			$this->fmt->query->consulta($sql);
			$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");

	}

}
?>
