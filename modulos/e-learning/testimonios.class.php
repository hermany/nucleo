<?php
header("Content-Type: text/html;charset=utf-8");

class TESTIMONIO{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;
	var $curso;

	function TESTIMONIO($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
		require_once(_RUTA_NUCLEO."modulos/e-learning/cursos.class.php");
		$this->curso = new CURSO($this->fmt);
	}

	public function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones,"","","m-testimonios.css"); //$id_mod,$botones,$var,$div_clas,$css_nucleo
		$this->fmt->class_pagina->head_mod();
    $this->fmt->class_pagina->head_modulo_inner("Lista de Testimonios", $botones,"crear",$this->id_mod); // bd, id modulo, botones

    $this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Curso:Fecha:Activar:Acciones');
    $this->fmt->form->tbody_table_open();

  	$consulta = "SELECT * FROM mod_testimonio";
  	$rs =$this->fmt->query->consulta($consulta);
  	$num=$this->fmt->query->num_registros($rs);
  	if($num>0){
  		for($i=0;$i<$num;$i++){
  			$row=$this->fmt->query->obt_fila($rs);
  			$row_id = $row["mod_tes_id"];
  			$row_curso =  $this->curso->nombre_curso($row["mod_tes_cur_id"]);
  			$row_activar = $row["mod_tes_activar"];
  			$fecha  = $this->curso->fecha_curso($row["mod_tes_cur_id"]);
  			 
				echo "<tr class='row row-".$row_id."'>";
				echo '  <td class="col-id">'.$row_id.'</td>';
				echo '  <td class="col-name">'.$row_curso.'</td>';
				echo '  <td class="">'.$fecha.'	</td>';
				echo '  <td class="col-activar">';
				 $this->fmt->class_modulo->estado_publicacion($row_activar,$this->id_mod,"",$row_id);
				echo '	</td>';
				echo '  <td class="col-acciones acciones">';
				$this->fmt->class_modulo->botones_tabla($row_id,$this->id_mod,$row_curso);//
				echo '	</td>';
  		}
  	}
  	$this->fmt->query->liberar_consulta();
		
		$this->fmt->form->tbody_table_close();
    $this->fmt->form->footer_table();
		
		$this->fmt->class_pagina->footer_mod();
    $this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","20",true);
		$this->fmt->class_modulo->script_accion_modulo();
	}

	public function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Testimonio");
		$id_form="form-nuevo";
		$id = $this->id_item;

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-testimonio");

		$this->fmt->form->input_hidden_form("inputIdCurso");		
		$this->fmt->form->input_form("* Nombre curso:","inputNombreCurso","","","disabled");
		echo $this->curso->seleccionar_curso("inputIdCurso","inputNombreCurso"); //$id_valor="",$id_nombre=""
		$this->fmt->form->imagen_unica_form("inputImagen","","","form-normal","Imagen relacionada:");

 
		$this->fmt->form->input_form("Testimonio (1):","inputTestimonio0");
		$this->fmt->form->input_form("Nombre (1):","inputNombreTestimonio0");
		$this->fmt->form->input_form("Cargo (1):","inputCargoTestimonio0");
		echo "<div class='form-espacio'></div>";		
	 
		$this->fmt->form->input_form("Testimonio (2):","inputTestimonio1");
		$this->fmt->form->input_form("Nombre (2):","inputNombreTestimonio1");
		$this->fmt->form->input_form("Cargo (2):","inputCargoTestimonio1");
		echo "<div class='form-espacio'></div>";	
		
		$this->fmt->form->input_form("Testimonio (3):","inputTestimonio2");
		$this->fmt->form->input_form("Nombre (3):","inputNombreTestimonio2");
		$this->fmt->form->input_form("Cargo (3):","inputCargoTestimonio2","");

		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} //fin form_nuevo

	public function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Lugar","","");
		$id_form="form-editar";

		$id = $this->id_item;
		$consulta= "SELECT * FROM mod_testimonio WHERE mod_tes_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-cursos");

		$this->fmt->form->input_hidden_form("inputId",$id);
		$this->fmt->form->input_hidden_form("inputIdCurso",$row["mod_tes_cur_id"]);		
		$row_curso =  $this->curso->nombre_curso($row["mod_tes_cur_id"]);
		$this->fmt->form->input_form("* Nombre curso:","inputNombreCurso","",$row_curso,"","","");
		

		echo $this->curso->seleccionar_curso("inputIdCurso","inputNombreCurso"); //$id_valor="",$id_nombre=""

		$this->fmt->form->imagen_unica_form("inputImagen",$row["mod_tes_imagen"],"","form-normal","Imagen relacionada:");

		 
		$consultax = "SELECT mod_tes_com_id,mod_tes_com_comentario,mod_tes_com_nombre,mod_tes_com_cargo FROM mod_testimonio_comentarios  WHERE mod_tes_com_tes_id=$id";
		$rsx =$this->fmt->query->consulta($consultax);
		$numx=$this->fmt->query->num_registros($rsx);
		if($numx>0){
			for($i=0;$i<$numx;$i++){
				$rowx=$this->fmt->query->obt_fila($rsx);
				$com_id[$i] = $rowx["mod_tes_com_id"];
				$tes[$i] = $rowx["mod_tes_com_comentario"];
				$tes_nom[$i] = $rowx["mod_tes_com_nombre"];
				$tes_car[$i] = $rowx["mod_tes_com_cargo"];
			}
		}
		$this->fmt->query->liberar_consulta($rs);

		$this->fmt->form->input_hidden_form("inputIdCom0",$com_id[0]);
		$this->fmt->form->input_form("Testimonio (1):","inputTestimonio0","",$tes[0]);
		$this->fmt->form->input_form("Nombre (1):","inputNombreTestimonio0","",$tes_nom[0]);
		$this->fmt->form->input_form("Cargo (1):","inputCargoTestimonio0","",$tes_car[0]);
		echo "<div class='form-espacio'></div>";		
		$this->fmt->form->input_hidden_form("inputIdCom1",$com_id[1]);
		$this->fmt->form->input_form("Testimonio (2):","inputTestimonio1","",$tes[1]);
		$this->fmt->form->input_form("Nombre (2):","inputNombreTestimonio1","",$tes_nom[1]);
		$this->fmt->form->input_form("Cargo (2):","inputCargoTestimonio1","",$tes_car[1]);
		echo "<div class='form-espacio'></div>";	
		$this->fmt->form->input_hidden_form("inputIdCom2",$com_id[2]);
		$this->fmt->form->input_form("Testimonio (3):","inputTestimonio2","",$tes[2]);
		$this->fmt->form->input_form("Nombre (3):","inputNombreTestimonio2","",$tes_nom[2]);
		$this->fmt->form->input_form("Cargo (3):","inputCargoTestimonio2","",$tes_car[2]);


		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} // fin form_editar


	public function ingresar(){
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}

		$ingresar ="mod_tes_imagen,mod_tes_cur_id,mod_tes_activar";

		$valores  ="'".$_POST['inputImagen']."','".
					$_POST['inputIdCurso']."','".
					$activar."'";
		 $sql="insert into mod_testimonio (".$ingresar.") values (".$valores.")";
		 
		$this->fmt->query->consulta($sql);

		$sql="select max(mod_tes_id) as id from mod_testimonio";
		$rs= $this->fmt->query->consulta($sql);
		$fila = $this->fmt->query->obt_fila($rs);
		$id = $fila ["id"];
 		

 		$ingresar ="mod_tes_com_comentario, mod_tes_com_nombre, mod_tes_com_cargo, mod_tes_com_tes_id, mod_tes_com_activar";
		 
		if (!empty($_POST['inputTestimonio0'])){
			$valores = "'".$_POST['inputTestimonio0']."','".$_POST['inputNombreTestimonio0']."','".$_POST['inputCargoTestimonio0']."','".$id."','".$activar."'";
			$sql="insert into mod_testimonio_comentarios (".$ingresar.") values (".$valores.")";
			$this->fmt->query->consulta($sql);
		}

		if (!empty($_POST['inputTestimonio1'])){
			$valores = "'".$_POST['inputTestimonio1']."','".$_POST['inputNombreTestimonio1']."','".$_POST['inputCargoTestimonio1']."','".$id."','".$activar."'";
			$sql="insert into mod_testimonio_comentarios (".$ingresar.") values (".$valores.")";
			$this->fmt->query->consulta($sql);
		}

		if (!empty($_POST['inputTestimonio2'])){
			$valores = "'".$_POST['inputTestimonio2']."','".$_POST['inputNombreTestimonio2']."','".$_POST['inputCargoTestimonio2']."','".$id."','".$activar."'";
			$sql="insert into mod_testimonio_comentarios (".$ingresar.") values (".$valores.")";
			$this->fmt->query->consulta($sql);
		}

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin ingresar

	function modificar(){
 

		$sql="UPDATE mod_testimonio SET
						mod_tes_cur_id='".$_POST['inputIdCurso']."',
						mod_tes_imagen='".$_POST['inputImagen']."'
						WHERE mod_tes_id='".$_POST['inputId']."'";
			//echo $sql;
			$this->fmt->query->consulta($sql);

			 
			$ingresar2 ="mod_tes_com_tes_id,mod_tes_com_com_id,mod_tes_com_orden";

			$valor_cat2[0]= $_POST['inputIdCom0'];
			$valor_cat2[1]= $_POST['inputIdCom1'];
			$valor_cat2[2]= $_POST['inputIdCom2'];

			$num2=3;
			for ($i=0; $i<$num2;$i++){
				if (!empty($_POST['inputTestimonio'.$i])){
					$sql3="UPDATE mod_testimonio_comentarios SET
							mod_tes_com_comentario='".$_POST['inputTestimonio'.$i]."',
							mod_tes_com_nombre='".$_POST['inputNombreTestimonio'.$i]."',
							mod_tes_com_cargo='".$_POST['inputCargoTestimonio'.$i]."'
							WHERE mod_tes_com_id='".$_POST['inputIdCom'.$i]."'";
					$this->fmt->query->consulta($sql3);
				}else{
					$this->fmt->class_modulo->eliminar_fila($_POST['inputIdCom'.$i],"mod_testimonio_comentarios","mod_tes_com_id");
					$up_sqr6 = "ALTER TABLE mod_testimonio_comentarios AUTO_INCREMENT=1";
					$this->fmt->query->consulta($up_sqr6,__METHOD__);
				}
			}
			 

			$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} //modificar
}