<?php
header("Content-Type: text/html;charset=utf-8");
 
class INSTRUCTOR{
	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;
	var $curso;

	public function INSTRUCTOR($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	public function busqueda(){
		require_once(_RUTA_NUCLEO."modulos/e-learning/cursos.class.php");
		$this->curso = new CURSO($this->fmt);

		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones);
		$this->fmt->class_pagina->head_mod();
    $this->fmt->class_pagina->head_modulo_inner("Lista de instructores", $botones,"crear",$this->id_mod); // bd, id modulo, botones

    $this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Nombre Instructor:Activar:Acciones');
    $this->fmt->form->tbody_table_open();

  	$consulta = "SELECT * FROM mod_instructor";

  	$rs =$this->fmt->query->consulta($consulta);
  	$num=$this->fmt->query->num_registros($rs);
  	if($num>0){
  		for($i=0;$i<$num;$i++){
  			$row=$this->fmt->query->obt_fila($rs);
  			$row_id = $row["mod_ins_id"];
  			$row_nombre =  $row["mod_ins_nombre"];
  			$row_activar = $row["mod_ins_activar"];

				echo "<tr class='row row-".$row_id."'>";
				echo '  <td class="col-id">'.$row_id.'</td>';
				echo '  <td class="col-name"><span class="img" style="background:url('.$row_imagen.') no-repeat center center"></span><span class="text">'.$row_nombre.'</span></td>';
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
    $this->fmt->class_pagina->footer_mod();
		$this->fmt->class_modulo->script_accion_modulo();
	}

	public function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Instructor","","");
		$id_form="form-nuevo";
		$id = $this->id_item;

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-cursos");

		$this->fmt->form->input_form("* Nombre del Instructor:","inputNombre"); 
		$this->fmt->form->ruta_amigable_form("inputNombre","","","inputRutaamigable","","","1");

		$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');
		$this->fmt->form->input_form_date('{
				"label":"Fecha Nacimiento:",
				"id":"inputFecha",
				"format":"dd-mm-yyyy",
				"fecha":"'.$fecha.'"}');

		$this->fmt->form->textarea_form('Detalles:','inputDetalles');
		$this->fmt->form->imagen_unica_form("inputImagen","","","form-normal","Imagén:");

		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputDetalles","300");
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	public function ingresar(){
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}

		$ingresar ="mod_ins_nombre,mod_ins_ruta_amigable,mod_ins_detalles,mod_ins_imagen,mod_ins_fecha_nacimiento,mod_ins_activar";

		$valores  ="'".$_POST['inputNombre']."','".
					$_POST['inputRutaamigable']."','".
					$_POST['inputDetalles']."','".
					$_POST['inputImagen']."','".
					$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputFecha'])."','".
					$activar."'";

		 $sql="insert into mod_instructor (".$ingresar.") values (".$valores.")";
		 
		$this->fmt->query->consulta($sql);
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");	
	}

	public function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Instructor","","");
		$id_form="form-editar";

		$id = $this->id_item;
		$consulta= "SELECT * FROM mod_instructor WHERE mod_ins_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-instructor");
		
		$this->fmt->form->input_form("* Nombre:","inputNombre","",$row["mod_ins_nombre"],"","","");
		$this->fmt->form->ruta_amigable_form("inputNombre","",$row['mod_ins_ruta_amigable'],"inputRutaamigable","","","1"); 

		$this->fmt->form->input_hidden_form("inputId",$id);

		$this->fmt->form->input_form_date('{
				"label":"Fecha Nacimiento:",
				"id":"inputFecha",
				"format":"dd-mm-yyyy",
				"fecha":"'.$row['mod_ins_fecha_nacimiento'].'"}');

		$this->fmt->form->textarea_form('Detalles:','inputDetalles','',$row["mod_ins_detalles"]);

		$this->fmt->form->imagen_unica_form("inputImagen",$row["mod_ins_imagen"],"","form-normal","Imagen relacionada:");

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputDetalles","300");
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	function modificar(){

		$sql="UPDATE mod_instructor SET
						mod_ins_nombre ='".$_POST['inputNombre']."',
						mod_ins_ruta_amigable ='".$_POST['inputRutaamigable']."',
						mod_ins_detalles ='".$_POST['inputDetalles']."',
						mod_ins_fecha_nacimiento ='".$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputFecha'])."',
						mod_ins_imagen='".$_POST['inputImagen']."'
						WHERE mod_ins_id='".$_POST['inputId']."'";
			//echo $sql;
			$this->fmt->query->consulta($sql);

			$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} //modificar

} // Fin class
?>