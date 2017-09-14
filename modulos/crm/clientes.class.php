<?php
header("Content-Type: text/html;charset=utf-8");

class CLIENTES{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function CLIENTES($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
		$this->fmt->class_pagina->head_mod();
    $botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nueva zona","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
    $this->fmt->class_pagina->head_modulo_inner("Lista de Tipo de pedidos", $botones); // bd, id modulo, botones
    $this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Nombre:Estado:Acciones');
    $this->fmt->form->tbody_table_open();
    $consulta = "SELECT mod_cli_id,mod_cli_nombre,mod_cli_telefono,mod_cli_estado FROM mod_clientes ORDER BY mod_cli_id desc";
    $rs =$this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
    if($num>0){
      for($i=0;$i<$num;$i++){
        list($fila_id,$nombre,$telefono,$estado)=$this->fmt->query->obt_fila($rs);
        echo "<tr class='row row-".$fila_id."'>";
        echo '  <td class="col-id">'.$fila_id.'</td>';
        echo '  <td class="col-name">'.$nombre.'<span class="text-ref">'.$telefono.'</span></td>';
        echo '  <td class="">';
				echo $this->nombre_estado_cliente($estado);
				echo '	</td>';
        echo '  <td class="col-acciones acciones">';
        echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar ".$fila_id,"btn btn-accion btn-m-editar ",$this->id_mod,"form_editar,".$fila_id);
        echo $this->fmt->class_pagina->crear_btn_m("","icn-trash","eliminar ".$fila_id,"btn btn-accion btn-fila-eliminar",$this->id_mod,"eliminar,".$fila_id,"",$nombre);
        echo '  </td>';
        echo "</tr>";
      }
    }
		$this->fmt->query->liberar_consulta($rs);
    $this->fmt->form->tbody_table_close();
    $this->fmt->form->footer_table();
    $this->fmt->class_modulo->script_accion_modulo();
		$this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","25",true);
    $this->fmt->class_pagina->footer_mod();
	}

	function nombre_estado_cliente($estado){
		$consulta = "SELECT mod_cli_est_nombre FROM mod_clientes_estados WHERE mod_cli_est_id=$estado";
    $rs =$this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
		$fila = $this->fmt->query->obt_fila($rs);
		return  $fila["mod_cli_est_nombre"];
		$this->fmt->query->liberar_consulta($rs);
	}
	function nombre_cliente($id){
		$consulta = "SELECT mod_cli_nombre FROM mod_clientes WHERE mod_cli_id=$id";
    $rs =$this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
		$fila = $this->fmt->query->obt_fila($rs);
		return  $fila["mod_cli_nombre"];
		$this->fmt->query->liberar_consulta($rs);
	}
}
