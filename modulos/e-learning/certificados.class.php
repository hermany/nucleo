<?php
header("Content-Type: text/html;charset=utf-8");
 
class CERTIFICADO{
	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	public function CERTIFICADO($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	public function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones);
		$this->fmt->class_pagina->head_mod();
    $this->fmt->class_pagina->head_modulo_inner("Lista de Certificados", $botones,"crear",$this->id_mod); // bd, id modulo, botones

    $this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Nombre:Activar:Acciones');
    $this->fmt->form->tbody_table_open();

  	$consulta = "SELECT * FROM curso_certificado";

  	$rs =$this->fmt->query->consulta($consulta);
  	$num=$this->fmt->query->num_registros($rs);
  	if($num>0){
  		for($i=0;$i<$num;$i++){
  			$row=$this->fmt->query->obt_fila($rs);
  			$row_id = $row["cur_cer_id"];
  			$row_nombre = $row["cur_cer_nombre"];
  			$row_imagen = _RUTA_IMAGES.$this->fmt->archivos->url_add($row["cur_cer_imagen"],'-thumb');
  			$row_activar = $row["cur_cer_activar"];

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
		$this->fmt->class_pagina->crear_head_form("Nuevo Certificado","","");
		$id_form="form-nuevo";
		$id = $this->id_item;

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-cursos");

		$this->fmt->form->input_form("* Nombre del Certificado:","inputNombre"); //
		$this->fmt->form->textarea_form('Detalles:','inputDetalles');
		$this->fmt->form->imagen_unica_form("inputImagen","","","form-normal","Imagen de Certificación:");

		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
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

		$ingresar ="cur_cer_nombre,cur_cer_imagen,cur_cer_detalles,cur_cer_activar";

		$valores  ="'".$_POST['inputNombre']."','".
					$_POST['inputImagen']."','".
					$_POST['inputDetalles']."','".
					$activar."'";

		 $sql="insert into curso_certificado (".$ingresar.") values (".$valores.")";
		 
		$this->fmt->query->consulta($sql);
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");	
	}

	public function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Certificado","","");
		$id_form="form-editar";

		$id = $this->id_item;
		$consulta= "SELECT * FROM curso_certificado WHERE cur_cer_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-certificado");
		
		$this->fmt->form->input_form("* Nombre:","inputNombre","",$row["cur_cer_nombre"],"","","");
		$this->fmt->form->input_hidden_form("inputId",$id);

		$this->fmt->form->textarea_form('Detalles:','inputDetalles','',$row["cur_cer_detalles"]);

		$this->fmt->form->imagen_unica_form("inputImagen",$row["cur_cer_imagen"],"","form-normal","Imagen relacionada:");

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	function modificar(){

		$sql="UPDATE curso_certificado SET
						cur_cer_nombre ='".$_POST['inputNombre']."',
						cur_cer_detalles ='".$_POST['inputDetalles']."',
						cur_cer_imagen='".$_POST['inputImagen']."'
						WHERE cur_cer_id='".$_POST['inputId']."'";
			//echo $sql;
			$this->fmt->query->consulta($sql);

			$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} //modificar

} // Fin class
?>