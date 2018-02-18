<?php
header("Content-Type: text/html;charset=utf-8");

class PLANTILLAS{

	var $fmt;
	var $id_mod;
	var $ruta_modulo;
	var $nombre_modulo;
	var $nombre_tabla;
	var $prefijo_tabla;

	function PLANTILLAS($fmt){
		$this->fmt = $fmt;
		$this->ruta_modulo="modulos/config/plantillas.adm.php";
		$this->nombre_modulo="plantillas.adm.php";
		$this->nombre_tabla="plantilla";
		$this->prefijo_tabla="pla_";
	}
	
	function busqueda(){
		$botones = $this->fmt->class_pagina->crear_btn("config.adm.php?id_mod=6","btn btn-link","icn-conf","Configuración Site");
		
		$botones .= $this->fmt->class_pagina->crear_btn($this->nombre_modulo."?tarea=form_nuevo","btn btn-primary","icn-plus","Nueva Plantilla");
		
	    $this->fmt->class_pagina->crear_head_mod("icn-level-page","Plantillas",$botones ); // $icon, $nom,$botones
	    ?>
	    <div class="body-modulo">
			<?php
			$this->fmt->form->head_table('table_div');
			$this->fmt->form->thead_table('ID:Nombre plantilla:Estado:Acciones','td-id:::td-acciones');
			$this->fmt->form->tbody_table_open();
			$sql="SELECT pla_id,pla_nombre,pla_activar  FROM plantilla ORDER BY pla_id asc";
			$rs =$this->fmt->query->consulta($sql);
			$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					list($fila_id,$fila_nombre,$fila_activar)=$this->fmt->query->obt_fila($rs);
					echo "<tr>";
					echo '<td class="">'.$fila_id.'</td>';
					echo '<td class=""><strong>'.$fila_nombre.'</strong></td>';
					
					echo '<td class="">';
					$this->fmt->class_modulo->estado_activar($fila_activar,$this->ruta_modulo."?tarea=activar","","",$fila_id);
					echo '</td>';
					
					echo '<td class="">';
					$url_editar= $this->nombre_modulo."?tarea=form_editar&id=".$fila_id;
					$this->fmt->form->botones_acciones("btn-editar-modulo","btn btn-accion btn-editar",$url_editar,"Editar","icn-pencil","form_editar",'editar',$fila_id); //$id,$class,$href,$title,$icono,$tarea,$nom,$ide
					$this->fmt->form->botones_acciones("btn-eliminar","btn btn-eliminar btn-accion","","Eliminar -".$fila_id,"icn-trash","eliminar",$fila_nombre,$fila_id);
					//$id,$class,$href,$title,$icono,$tarea,$nom,$ide
					echo '</td>';
					echo "</tr>";
				}
			}
			$this->fmt->form->tbody_table_close();
			$this->fmt->form->footer_table();
			?>
		</div>
		<script>
		$('#table_div').DataTable({
			"language": {
            "url": "<?php echo _RUTA_WEB; ?>js/spanish_datatable.json"
            },
            "pageLength": 25,
            "order": [[ 0, 'asc' ]]
		});
		</script>
		<?php
		$this->fmt->class_modulo->script_form($this->ruta_modulo,"");
	    echo $this->fmt->form->footer_page();
	}
	
	function form_nuevo(){
		$botones .= $this->fmt->class_pagina->crear_btn($this->nombre_modulo."?tarea=busqueda","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
		$this->fmt->class_pagina->crear_head_mod( "", "Nueva Plantilla",'',$botones,'col-xs-6 col-xs-offset-4');
		?>
	    <div class="body-modulo col-xs-6 col-xs-offset-3">
	      <form class="form form-modulo" action="<?php echo $this->nombre_modulo; ?>?tarea=ingresar"  enctype="multipart/form-data" method="POST" id="form_nuevo">
	        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
	        <?php
	        $this->fmt->form->input_form('Nombre de la plantilla:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

	        //$this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','','3','','');
	        $this->fmt->form->input_form('Ruta Amigable:','inputRutaamigable','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->input_form('Favicon:','inputIcono','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->input_form('Imagen:','inputImagen','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->textarea_form('Metas:','inputMeta','','','','','3','','');
	        $this->fmt->form->input_form('Css:','inputCss','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->input_form('Class:','inputClase','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->textarea_form('Codigos:','inputCodigos','','','','','3','','');
	        $this->fmt->form->input_form('Tipo:','inputTipo','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->input_form('Movil:','inputMovil','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->input_form('Onload:','inputOnload','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
			$this->fmt->form->radio_form("Contenedores asociados","inputContenedor","cont_","contenedor","","","",""); //$label,$id,$prefijo,$from,$id_select,$id_disabled,$class_div,$class
			
	       	$this->fmt->form->botones_nuevo();
	        ?>
	      </form>
		    </div>
		<?php
	    $this->fmt->class_modulo->script_form($this->ruta_modulo,"");
	    $this->fmt->form->footer_page();

	}
	
	function form_editar(){
		$botones .= $this->fmt->class_pagina->crear_btn($this->nombre_modulo."?tarea=busqueda","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
		$this->fmt->class_pagina->crear_head_mod( "", "Editar plantilla",'',$botones,'col-xs-6 col-xs-offset-4');
		$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $_GET['id'];
		
		$sql="SELECT * FROM ".$this->nombre_tabla." where ".$this->prefijo_tabla."id='".$id."'";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
			for($i=0;$i<$num;$i++){
				list($fila_id,$fila_nombre,$fila_ruta_amigable,$fila_icono,$fila_imagen,$fila_meta,$fila_css,$fila_clase,$fila_codigos,$fila_tipo,$fila_movil,$fila_onload,$fila_activar)=$this->fmt->query->obt_fila($rs);
			}
		}
		?>
	    <div class="body-modulo col-xs-6 col-xs-offset-3">
	      <form class="form form-modulo" action="<?php echo $this->nombre_modulo; ?>?tarea=modificar"  enctype="multipart/form-data" method="POST" id="form_editar">
	        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
	        <?php
	        $this->fmt->form->input_form('Nombre de la plantilla:','inputNombre','',$fila_nombre,'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
			$this->fmt->form->input_hidden_form("inputId",$fila_id);	  	       
			
			$this->fmt->form->input_form('Ruta Amigable:','inputRutaamigable','',$fila_ruta_amigable,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->input_form('Favicon:','inputIcono','',$fila_icono,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->input_form('Imagen:','inputImagen','',$fila_imagen,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->textarea_form('Metas:','inputMeta','',$fila_meta,'','','3','','');
	        $this->fmt->form->input_form('Css:','inputCss','',$fila_css,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->input_form('Class:','inputClase','',$fila_clase,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->textarea_form('Codigos:','inputCodigos','',$fila_codigos,'','','3','','');
	        $this->fmt->form->input_form('Tipo:','inputTipo','',$fila_tipo,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->input_form('Movil:','inputMovil','',$fila_movil,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->input_form('Onload:','inputOnload','',$fila_onload,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        
	        $sql="SELECT contenedor_cont_id FROM  contenedor_plantilla where  plantilla_pla_id='".$fila_id."'";
			$rs=$this->fmt->query->consulta($sql);
			$fila = $this->fmt->query->obt_fila($rs);
			$idc= $fila["contenedor_cont_id"];
		
	        $this->fmt->form->radio_form("Contenedores asociados","inputContenedor","cont_","contenedor",$idc,"","",""); //$label,$id,$prefijo,$from,$id_select,$id_disabled,$class_div,$class

	        
			$this->fmt->form->radio_activar_form($fila_activar);
	       	$this->fmt->form->botones_editar($fila_id,$fila_nombre,'plantilla','eliminar'); //$fila_id,$fila_nombre,$nombre,$tarea_eliminar
	        ?>
	      </form>
		    </div>
		<?php
	    $this->fmt->class_modulo->script_form($this->ruta_modulo,"");
	    $this->fmt->form->footer_page();

	}
	
	function ingresar(){
	    if ($_POST["btn-accion"]=="activar"){
	      $activar=1;
	    }
	    if ($_POST["btn-accion"]=="guardar"){
	      $activar=0;
	    }
	    $ingresar ="pla_nombre,
					pla_ruta_amigable,
					pla_icono,
					pla_imagen,
					pla_meta,
					pla_css,
					pla_clase,
					pla_codigos,
					pla_tipo,
					pla_movil,
					pla_onload,
					pla_activar";
					
	    $valores_post  ="inputNombre,
						inputRutaamigable,
						inputIcono,
						inputImagen,
						inputMeta,
						inputCss,
						inputClase,
						inputCodigos,
						inputTipo,
						inputMovil,
						inputOnload,
						inputActivar=".$activar;
	
	    $this->fmt->class_modulo->ingresar_tabla('plantilla',$ingresar,$valores_post);
			//$from,$filas,$valores_post
			
		$sql="select max(pla_id) as id from plantilla";
		$rs= $this->fmt->query->consulta($sql);
		$fila = $this->fmt->query->obt_fila($rs);
	  	$id = $fila ["id"];
	  	
		$ingresar1 ="contenedor_cont_id, plantilla_pla_id";
		$valores1= "'".$_POST['inputContenedor']."','".$id."'";
		$sql1="insert into contenedor_plantilla (".$ingresar1.") values (".$valores1.")";
		$this->fmt->query->consulta($sql1);
		
	    header("location: ".$this->nombre_modulo."?tarea=busqueda");
	}
	
	function modificar(){
		if ($_POST["btn-accion"]=="eliminar"){}
		if ($_POST["btn-accion"]=="actualizar"){

			$filas='pla_id,
					pla_nombre,
					pla_ruta_amigable,
					pla_icono,
					pla_imagen,
					pla_meta,
					pla_css,
					pla_clase,
					pla_codigos,
					pla_tipo,
					pla_movil,
					pla_onload,
					pla_activar';
					
			$valores_post='inputId,inputNombre,
						inputRutaamigable,
						inputIcono,
						inputImagen,
						inputMeta,
						inputCss,
						inputClase,
						inputCodigos,
						inputTipo,
						inputMovil,
						inputOnload,
						inputActivar';
						
			$this->fmt->class_modulo->actualizar_tabla($this->nombre_tabla,$filas,$valores_post); //$from,$filas,$valores_post
			header("location: ".$this->nombre_modulo."?tarea=busqueda");
		}
	}

	
	function activar(){
		$this->fmt->class_modulo->activar_get_id($this->nombre_tabla,$this->prefijo_tabla);
		header("location: ".$this->nombre_modulo."?tarea=busqueda");
	}
	
	function eliminar(){
		$this->fmt->class_modulo->eliminar_get_id("contenedor_plantilla","plantilla_pla_");
		$this->fmt->class_modulo->eliminar_get_id($this->nombre_tabla,$this->prefijo_tabla);
		
		header("location: ".$this->nombre_modulo."?tarea=busqueda");
    }

	
}