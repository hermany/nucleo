<?php
header("Content-Type: text/html;charset=utf-8");

class CALENDARIO{
	var $fmt;
	var $id_mod;
	function CALENDARIO($fmt){
		$this->fmt = $fmt;
		$this->fmt->get->validar_get($_GET['id_mod']);
		$this->id_mod=$_GET['id_mod'];
	}
/************** Busqueda ***************/

	function busqueda(){
		$botones .= $this->fmt->class_pagina->crear_btn("calendario.adm.php?tarea=list_papelera&id_mod=$this->id_mod","btn btn-link","icn-trash","Papelera");

		$botones .= $this->fmt->class_pagina->crear_btn("calendario.adm.php?tarea=form_nuevo&id_mod=$this->id_mod","btn btn-primary","icn-plus","Nuevo Registro");
		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones);

		$this->fmt->form->head_table('table_id');
		$this->fmt->form->thead_table('#:Color:Nombre:Grupo de Usuario:Estado:Acciones');
		$this->fmt->form->tbody_table_open();

	    $sql="select cal_id, 	cal_nombre, cal_color, cal_grupo_usuario, cal_activar from calendario where cal_papelera=0 ORDER BY cal_id desc";
		$rs =$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
			for($i=0;$i<$num;$i++){
				$fila=$this->fmt->query->obt_fila($rs);

				?>
			  	<tr>
			  		<th class=""><?php echo $fila["cal_id"]; ?></th>
						<td class=""><div class="color-cuadrado" style="background-color: <?php echo $fila["cal_color"]; ?>;">
						</div></td>
			  		<td class=""><strong><?php echo $fila["cal_nombre"];?></strong></td>
			     	<td class=""><?php echo $this->fmt->usuario->nombre_grupo_usuario($fila["cal_grupo_usuario"]); ?></td>
			     	<td class="">
			      	<?php
			      	echo $this->fmt->class_modulo->estado_publicacion($fila["cal_activar"],"modulos/calendario/calendario.adm.php", $this->id_mod,$aux,$fila["cal_id"] );
			      	?>
						</td>
			      <td class="td-user col-xl-offset-2 acciones">
				     <?php
					 $url_editar= "calendario.adm.php?tarea=form_editar&id=".$fila["cal_id"]."&id_mod=".$this->id_mod;
				$this->fmt->form->botones_acciones("btn-editar-modulo","btn btn-accion btn-editar",$url_editar,"Editar","icn-pencil","editar",'editar',$fila["cal_id"]); //$id,$class,$href,$title,$icono,$tarea,$nom,$ide

				$this->fmt->form->botones_acciones("btn-eliminar","btn btn-eliminar btn-accion","","Eliminar -".$fila["cal_nombre"],"icn-trash","eliminar",$fila["cal_nombre"],$fila["cal_id"]);
				     ?>
			      </td>
			  	</tr>
			  	<?php
			  		}
			  	}
		$this->fmt->form->tbody_table_close();
		$this->fmt->form->footer_table();
		$this->fmt->class_modulo->script_form("modulos/calendario/calendario.adm.php",$this->id_mod,"desc");
		$this->fmt->form->footer_page();

	} // Fin busqueda
/************** Lista Papelera **********************/
	function list_papelera(){
		$botones .= $this->fmt->class_pagina->crear_btn("calendario.adm.php?tarea=busqueda&id_mod=$this->id_mod","btn btn-link","icn-chevron-left","Volver");
		$botones .= '<a id="restaurar_all" link="calendario.adm.php?tarea=restaurar&id_mod='.$this->id_mod.'" class="btn btn-link"><i class="icn-sync"></i>Restaurar Seleccionado</a>';

		$botones .= '<a id="vaciar_all" link="calendario.adm.php?tarea=vaciar&id_mod='.$this->id_mod.'" class="btn btn-link"><i class="icn-trash"></i>Vaciar Seleccionado</a>';

		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones); // bd, id modulo, botones
		$this->fmt->class_modulo->script_form("modulos/calendario/calendario.adm.php",$this->id_mod,"asc","1","25",true);
		?>
	<form action="" id="form_papelera" method="post">
		<?php
		$this->fmt->form->head_table('table_id');
		$this->fmt->form->thead_table('<input type="checkbox" id="select_all">:Color:Nombre:Grupo de Usuario:Acciones');
		$this->fmt->form->tbody_table_open();

	    $sql="select cal_id, 	cal_nombre, cal_color, cal_grupo_usuario, cal_activar from calendario where cal_papelera=1 ORDER BY cal_id desc";
		$rs =$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
			for($i=0;$i<$num;$i++){
				$fila=$this->fmt->query->obt_fila($rs);
				$url= "calendario.adm.php?tarea=restaurar_id&id=".$fila["cal_id"]."&id_mod=".$this->id_mod;
				?>
			  	<tr>
			  		<th class=""><input type="checkbox" name="InputId[]" id="InputId<?php echo $fila["cal_id"]; ?>" value="<?php echo $fila["cal_id"]; ?>"></th>
						<td class=""><div class="color-cuadrado" style="background-color: <?php echo $fila["cal_color"]; ?>;">
						</div></td>
			  		<td class=""><strong><?php echo $fila["cal_nombre"];?></strong></td>
			     	<td class=""><?php echo $this->fmt->usuario->nombre_grupo_usuario($fila["cal_grupo_usuario"]); ?></td>
			      <td class="td-user col-xl-offset-2 acciones">
							<a  id="btn-restaurar-modulo" class="btn btn-accion btn-restaurar" href="<?php echo $url; ?>" title="Editar <?php echo $fila["cal_id"]; ?>" ><i class="icn-sync"></i></a>
 						<a  title="Eliminar <?php echo $fila["cal_nombre"]; ?>" type="button" idEliminar="<?php echo $fila["cal_id"]; ?>" nombreEliminar="<?php echo $fila["cal_nombre"]; ?>" tarea="vaciar_id" class="btn btn-eliminar btn-accion"><i class="icn-trash"></i></a>
			      </td>
			  	</tr>
			  	<?php
			  		}
			  	}
		$this->fmt->form->tbody_table_close();
		$this->fmt->form->footer_table();
		?>
	</form>
		<?php
		$this->fmt->form->footer_page();
	}
/************** Formulario form_nuevo ***************/

	function form_nuevo(){
		$this->fmt->form->head_nuevo('
		Nuevo Calendario','calendario',$this->id_mod,'','form_nuevo','col-xs-offset-2 head-calendario'); //$nom,$archivo,$id_mod,$botones,$id_form,$class
		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","","","input-lg","","");
		$this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','textarea-descripcion','',''); //label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje
		$this->fmt->form->input_color('Color:','inputColor','',"#ff0000",'');
		$this->fmt->form->select_form('Grupo de usuarios:',"inputGrupo","grupo_","grupos",'','','');
		$this->fmt->form->botones_nuevo();
		$this->fmt->class_modulo->script_form("modulos/calendario/calendario.adm.php",$this->id_mod);
		$this->fmt->form->footer_page();
	} //Fin function form modificar


	function ingresar(){
		if ($_POST["btn-accion"]=="activar"){
			$activar=1;
		}
		if ($_POST["btn-accion"]=="guardar"){
			$activar=0;
		}
		if($_POST["inputNombre"]!=""){
			$ingresar ="cal_nombre, cal_descripcion, cal_color, cal_grupo_usuario, cal_activar";
			$valores  ="'".$_POST['inputNombre']."','".
						$_POST['inputDescripcion']."','".
						$_POST['inputColor']."','".
						$_POST['inputGrupo']."','".
						$activar."'";
			$sql="insert into calendario (".$ingresar.") values (".$valores.")";
			$this->fmt->query->consulta($sql);

		}
		$url="calendario.adm.php?tarea=busqueda&id_mod=".$this->id_mod;
    $this->fmt->class_modulo->script_location($url);

	}
	function form_editar(){
		$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $_GET['id'];
		$consulta= "SELECT * FROM calendario WHERE cal_id='".$id."'";
		$rs =$this->fmt->query->consulta($consulta);
		$fila=$this->fmt->query->obt_fila($rs);
		$this->fmt->form->head_editar('Editar Calendario','calendario',$this->id_mod,'','form_editar');
		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","",$fila["cal_nombre"],"input-lg","","");
		$this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$fila["cal_descripcion"],'','textarea-descripcion','',''); //label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje
		$this->fmt->form->input_color('Color:','inputColor','',$fila["cal_color"],'');
		$this->fmt->form->select_form('Grupo de usuarios:',"inputGrupo","grupo_","grupos",$fila["cal_grupo_usuario"],'','');
		$this->fmt->form->input_hidden_form("inputId",$id);
		$this->fmt->form->radio_activar_form($fila['cal_activar']);
		$this->fmt->form->botones_editar($id,$fila['cal_nombre'],'Calendario');
		$this->fmt->class_modulo->script_form("modulos/calendario/calendario.adm.php",$this->id_mod);
		$this->fmt->form->footer_page();
	}
	function modificar(){
		if ($_POST["btn-accion"]=="eliminar"){}
		if ($_POST["btn-accion"]=="actualizar"){
			if($_POST["inputNombre"]!=""){
				$sql="UPDATE calendario SET
							cal_nombre='".$_POST['inputNombre']."',
							cal_descripcion ='".$_POST['inputDescripcion']."',
							cal_color ='".$_POST['inputColor']."',
							cal_grupo_usuario='".$_POST['inputGrupo']."',
							cal_activar='".$_POST['inputActivar']."'
							WHERE cal_id='".$_POST['inputId']."'";

				$this->fmt->query->consulta($sql);
			}
		}
		$url="calendario.adm.php?tarea=busqueda&id_mod=".$this->id_mod;
    $this->fmt->class_modulo->script_location($url);
	}
	function eliminar(){
		$sql="UPDATE calendario SET
					cal_papelera='1'
					WHERE cal_id='".$_GET['id']."'";

		$this->fmt->query->consulta($sql);

			$url="calendario.adm.php?tarea=busqueda&id_mod=".$this->id_mod;
	    $this->fmt->class_modulo->script_location($url);
  }
	function restaurar_id(){
		$this->fmt->get->validar_get ( $_GET['id'] );
	$id = $_GET['id'];
	$sql ="UPDATE calendario SET cal_papelera='0' where cal_id='".$id."'";
		$this->fmt->query->consulta($sql);
		$url="calendario.adm.php?tarea=list_papelera&id_mod=".$this->id_mod;
	$this->fmt->class_modulo->script_location($url);
	}

	function restaurar(){
		$ids = $_POST["InputId"];
		$num = count($ids);
		for($i=0;$i<$num;$i++){
		$sql ="UPDATE calendario SET cal_papelera='0' where cal_id='".$ids[$i]."'";
		$this->fmt->query->consulta($sql);
		}
		$url="calendario.adm.php?tarea=list_papelera&id_mod=".$this->id_mod;
	$this->fmt->class_modulo->script_location($url);
	}

	function vaciar(){
		$ids = $_POST["InputId"];
		$num = count($ids);
		for($i=0;$i<$num;$i++){
		$this->fmt->class_modulo->eliminar_fila($ids[$i],"calendario","cal_id");
		}
		$url="calendario.adm.php?tarea=list_papelera&id_mod=".$this->id_mod;
	$this->fmt->class_modulo->script_location($url);
	}

	function vaciar_id(){
		$this->fmt->get->validar_get ( $_GET['id'] );
	$id = $_GET['id'];
		$this->fmt->class_modulo->eliminar_get_id("calendario","cal_");
		$url="calendario.adm.php?tarea=list_papelera&id_mod=".$this->id_mod;
	$this->fmt->class_modulo->script_location($url);
	}

  function activar(){
	  $this->fmt->class_modulo->activar_get_id("calendario","cal_");
		$url="calendario.adm.php?tarea=busqueda&id_mod=".$this->id_mod;
	  $this->fmt->class_modulo->script_location($url);
  }
}

?>
