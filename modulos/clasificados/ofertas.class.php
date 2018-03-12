<?php
header("Content-Type: text/html;charset=utf-8");

class OFERTA{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function OFERTA($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones);
		$this->fmt->class_pagina->head_mod();
		$this->fmt->class_pagina->head_modulo_inner("Ofertas activas","","crear",$this->id_mod);
		$this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Oferta:Usuario:Tipo:Fecha:Acciones',"::::::col-acciones");
    $this->fmt->form->tbody_table_open();

    $consulta = "SELECT mod_ofr_id, mod_ofr_titulo,	 mod_ofr_usu_id,	mod_ofr_fecha,	mod_ofr_estado	 FROM mod_oferta";
  	$rs =$this->fmt->query->consulta($consulta);
  	$num=$this->fmt->query->num_registros($rs);
  	if($num>0){
  		for($i=0;$i<$num;$i++){
  			$row=$this->fmt->query->obt_fila($rs);
  			$row_id = $row["mod_ofr_id"];
  			$row_titulo = $row["mod_ofr_titulo"];
  			$row_usuario = $row["mod_ofr_usu_id"];
  			$row_fecha = $row["mod_ofr_fecha"];
  			$row_estado = $row["mod_ofr_estado"];

				echo "<tr class='row row-".$row_id."'>";
				echo '  <td class="col-id">'.$row_id.'</td>';
				echo '  <td class="col-name">'.$row_titulo.'</td>';
				echo '  <td class="">'.$row_usuario.'</td>';
				echo '  <td class="">'.$row_fecha.'</td>';
				echo '  <td class="">'.$row_estado.'</td>';
				echo '  <td class="col-acciones acciones">';
				$this->fmt->class_modulo->botones_tabla($row_id,$this->id_mod,$row_nombre);//
				echo '	</td>';
				echo '	</tr>';
			}
		}

    $this->fmt->form->tbody_table_close();
    $this->fmt->form->footer_table();
    $this->fmt->class_pagina->footer_mod();
    $this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","10",true);
		$this->fmt->class_modulo->script_accion_modulo();
	}
}