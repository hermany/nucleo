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

		$this->fmt->class_pagina->head_mod();
		$this->fmt->class_pagina->head_modulo_inner("Lista de Sitios", $botones,"crear"); // bd, id modulo, botones
		$this->fmt->class_pagina->head_body_modulo_inner("list-items");

			$consulta = "SELECT sitio_id, sitio_nombre FROM sitio  ORDER BY sitio_orden ASC LIMIT 0,5";
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
									$this->fmt->class_modulo->botones_tabla($fila_id, $this->id_mod,$fila_nombre);//$id_item,$id_mod,$nombre_item
								?>
							</div>
						</div>
					</div>
					<?php
				}
			}
			$this->fmt->query->liberar_consulta();

		$this->fmt->class_pagina->footer_body_modulo_inner();
		$this->fmt->class_pagina->footer_mod("end_mod");
		$this->fmt->class_modulo->script_accion_modulo();
	}
}