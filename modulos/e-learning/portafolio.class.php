<?php
header("Content-Type: text/html;charset=utf-8");

class PORTAFOLIOCURSOS{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;
	var $curso;

	function PORTAFOLIOCURSOS($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
		require_once(_RUTA_NUCLEO."modulos/e-learning/cursos.class.php");
		$this->curso = new CURSO($this->fmt);
	}

	public function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones,"","","m-portafolio.css"); //$id_mod,$botones,$var,$div_clas,$css_nucleo
		$this->fmt->class_pagina->head_mod();
    $this->fmt->class_pagina->head_modulo_inner("Lista Curso de Portafólio", $botones,"crear",$this->id_mod); // bd, id modulo, botones

    $this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Curso:Activar:Acciones');
    $this->fmt->form->tbody_table_open();

	   

  	$consulta = "SELECT * FROM mod_portafolio";
  	$rs =$this->fmt->query->consulta($consulta);
  	$num=$this->fmt->query->num_registros($rs);
  	if($num>0){
  		for($i=0;$i<$num;$i++){
  			$row=$this->fmt->query->obt_fila($rs);
  			$row_id = $row["mod_por_id"];
  			$row_curso =  $row["mod_por_nombre"];
  			$row_activar = $row["mod_por_activar"];

  			// $nom = $this->fmt->get->convertir_url_amigable($row_curso);
  			
  			// $sql="UPDATE mod_portafolio SET
	   	// 					mod_por_ruta_amigable='".$nom."'
	   	// 					WHERE mod_por_id ='$row_id'";
	   	// 	$this->fmt->query->consulta($sql);
  			 
				echo "<tr class='row row-".$row_id."'>";
				echo '  <td class="col-id">'.$row_id.'</td>';
				echo '  <td class="col-name">'.$row_curso.'</td>';
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
		$this->fmt->class_pagina->crear_head_form("Nuevo Curso Portafolio","","");
		$id_form="form-nuevo";
		$id = $this->id_item;

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-cursos");

		$this->fmt->form->input_form("* Nombre del curso:","inputNombre","","","","","","",""); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros
		$this->fmt->form->ruta_amigable_form("inputNombre","Ruta Amigable:","","inputRutaamigable","","","1");
		$this->fmt->form->input_form("Tags:","inputTags","","");
		$this->fmt->form->input_form("Certificación:","inputCertificacion","","");

		$this->fmt->form->imagen_unica_form("inputImagen","","","form-normal","Imagen relacionada:");
		
		 
		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Curso Portafolio","","");
		$id_form="form-editar";

		$id = $this->id_item;
		$consulta= "SELECT * FROM mod_portafolio WHERE mod_por_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-cursos");
		
		// $this->fmt->form->hidden_modulo($this->id_mod,"modificar");
		$this->fmt->form->input_form("* Nombre:","inputNombre","",$row["mod_por_nombre"],"","","");
		$this->fmt->form->input_hidden_form("inputId",$id);

		$this->fmt->form->ruta_amigable_form("inputNombre","",$row["mod_por_ruta_amigable"],"inputRutaamigable","","","1");
		$this->fmt->form->input_form("tags:","inputTags","",$row["mod_por_tags"]);

		$this->fmt->form->input_form("Certificación:","inputCertificacion","",$row["mod_por_certificacion"]);

		$this->fmt->form->imagen_unica_form("inputImagen",$row["mod_por_imagen"],"","form-normal","Imagen relacionada:");

		$consulta = "SELECT cat_id, cat_nombre FROM categoria  WHERE cat_id_padre='3'  ORDER BY cat_id ASC";
		$rs =$this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
			for($i=0;$i<$num;$i++){
				$row=$this->fmt->query->obt_fila($rs);
				$options[$i] = $row["cat_nombre"] ;
				$valores[$i] = $row["cat_id"];
			}
		}
		$this->fmt->query->liberar_consulta();

		$this->fmt->form->select_form_simple("Areá","inputArea",$options,$valores,"",$row["mod_por_area"]);

		$nom_curso = $this->curso->nombre_curso($row["mod_por_cur_id"]);

		$this->fmt->form->input_form("Curso:","inputCurso","",$nom_curso,"disabled");
		$this->fmt->form->input_hidden_form("inputIdCurso",$row["mod_por_cur_id"]);

		?>
			<div class="box-cursos">
				<div class="buscador">
					<i class="icn icn-search"></i>
					<input type="text" id='inputBuscarCursos' placeholder="Buscar Curso">
				</div>
				<div class="list-cursos">
					<label>Ultimos 10 cursos creados</label>
					<?php 
							$consulta = "SELECT cur_id, cur_nombre FROM curso  WHERE cur_activar='1'  ORDER BY cur_id  DESC LIMIT 0,10";
							$rs =$this->fmt->query->consulta($consulta);
							$num=$this->fmt->query->num_registros($rs);
							if($num>0){
								for($i=0;$i<$num;$i++){
									$row=$this->fmt->query->obt_fila($rs);
									echo '<div class="item item-curso" ><span>'.$row["cur_nombre"].'</span><a class="btn btn-full btn-seleccionar" nom="'.$row["cur_nombre"].'" cur="'.$row["cur_id"].'">Seleccionar</a></div>';
								}
							}
							$this->fmt->query->liberar_consulta();
					?>
				</div>
				<script type="text/javascript">
					$(document).ready(function() {
						$('.btn-seleccionar').click(function(event) {
							var cur = $(this).attr('cur');
							var nom = $(this).attr('nom');

							$('#inputCurso').val(nom);
							$('#inputIdCurso').val(cur);
						});
						$('#inputBuscarCursos').keyup(function () {
							var ruta_ajax = "ajax-portafolio-buscar-curso";
		          var variables = $(this).val();
		          var datos = {ajax:ruta_ajax, inputIdMod:"" , inputVars : variables };
		          var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";   
		          $.ajax({ 
		            url:ruta,
		            type:"post",  
		            async: true,   
		            data:datos, 
		            beforeSend: function () {},
		            success:function(msg){  
		               console.log(msg);
		               $(".list-cursos").html(msg);
		            }
		        	});   
						});
					});
				</script>
			</div>
		<?

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputImportante","200");
		$this->fmt->class_modulo->modal_editor_texto("inputObjetivo","200");
		$this->fmt->class_modulo->modal_editor_texto("inputCerfigicacion","200");
		$this->fmt->class_modulo->modal_editor_texto("inputContenido","480");
		$this->fmt->class_modulo->modal_editor_texto("inputInstructor","480");
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} // fin form_editar
}
?>