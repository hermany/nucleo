<?php
header("Content-Type: text/html;charset=utf-8");

class CUENTA_EMPRESA{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;
	var $empresa;

	function CUENTA_EMPRESA($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
		require_once(_RUTA_NUCLEO."modulos/adm/empresas.class.php");
		$this->empresa = new EMPRESAS($this->fmt);
	}

	public function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones,"","",""); //$id_mod,$botones,$var,$div_clas,$css_nucleo
		$this->fmt->class_pagina->head_mod();
    $this->fmt->class_pagina->head_modulo_inner("Lista de Cuentas de empresas", $botones,"crear",$this->id_mod); // bd, id modulo, botones

    $this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Empresa:Usuario:Codigo:Registro:Estado:Acciones');
    $this->fmt->form->tbody_table_open();

  	$consulta = "SELECT * FROM mod_cuenta_empresa";
  	$rs =$this->fmt->query->consulta($consulta);
  	$num=$this->fmt->query->num_registros($rs);
  	if($num>0){
  		for($i=0;$i<$num;$i++){
  			$row=$this->fmt->query->obt_fila($rs);
  			$row_id = $row["mod_cnt_id"];
  			$row_usu = $this->fmt->usuario->nombre_apellidos($row["mod_cnt_usu_id"]);
  			$datos  = $this->empresa->datos_empresa($row["mod_cnt_emp_id"]);

  			 
				echo "<tr class='row row-".$row_id."'>";
				echo '  <td class="col-id">'.$row_id.'</td>';
				echo '  <td class="col-name">'.$datos["emp_nombre"].'</td>';
				echo '  <td class="col-usuario">'.$row_usu.'</td>';
				echo '  <td class="col-codigo">'.$row["mod_cnt_codigo"].'</td>';
				echo '  <td class="">'.$row["mod_cnt_fecha_registro"].'	</td>';
				echo '  <td class="col-activar">';
				echo $row["mod_cnt_estado"];
				echo '	</td>';
				echo '  <td class="col-acciones acciones">';
				$this->fmt->class_modulo->botones_tabla($row_id,$this->id_mod,$datos["emp_nombre"]);//
				echo '	</td>';
				echo '</tr>';
  		}
  	}
  	$this->fmt->query->liberar_consulta();
		
		$this->fmt->form->tbody_table_close();
    $this->fmt->form->footer_table();
		
		$this->fmt->class_pagina->footer_mod();
    $this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","20",true);
		$this->fmt->class_modulo->script_accion_modulo();
	} // fin busqueda
} // fin class
?>