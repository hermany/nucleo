<?php
header("Content-Type: text/html;charset=utf-8");

class GRUPOS{

	var $fmt;
	var $id_mod;

	function GRUPOS($fmt){
		$this->fmt = $fmt;
		$this->fmt->get->validar_get($_GET['id_mod']);
		$this->id_mod=$_GET['id_mod'];
	}

	function busqueda(){
		$botones .= $this->fmt->class_pagina->crear_btn("usuarios.adm.php?tarea=busqueda&id_mod=$this->id_mod","btn btn-link","icn-users","Usuarios");

		$botones .= $this->fmt->class_pagina->crear_btn("roles.adm.php","btn btn-link","icn-credential","Roles");

		$botones .= $this->fmt->class_pagina->crear_btn("grupos.adm.php?tarea=form_nuevo&id_mod=$this->id_mod","btn btn-primary","icn-plus","Nuevo Grupo");

		$this->fmt->class_pagina->crear_head_mod( "icn-list color-text-naranja-a","Grupos de usuarios", $botones); // bd, id modulo, botones

	    ?>
	    <div class="body-modulo">
	      <div class="table-responsive">
	        <table class="table table-hover">
	          <thead>
	            <tr>
	              <th>Nombre Grupo</th>
	              <th>Detalle</th>
	              <th>Estado</th>
	              <th class="col-xl-offset-2">Acciones</th>
	            </tr>
	          </thead>
	          <tbody>
	            <?php
	              $sql="select grupo_id,grupo_nombre, grupo_detalle, grupo_activar from grupos ORDER BY grupo_id desc";
	              $rs =$this->fmt->query->consulta($sql);
	              $num=$this->fmt->query->num_registros($rs);
	              if($num>0){
	              for($i=0;$i<$num;$i++){
	                list($fila_id,$fila_nombre,$fila_detalle,$fila_activar)=$this->fmt->query->obt_fila($rs);
	            ?>
	              <tr>
	                <td class=""><?php echo $fila_nombre; ?></td>
	                <td class=""><?php echo $fila_detalle; ?></td>
	                <td class="">
	                <?php
	                      $this->fmt->class_modulo->estado_publicacion($fila_activar,"modulos/usuarios/grupos.adm.php", $this->id_mod,$aux,$fila_id );
	                ?>
	                </td>
	                <td class="col-xl-offset-2 accione">
	                  <a  id="btn-editar-modulo" class="btn btn-accion btn-editar <?php echo $aux; ?>" href="grupos.adm.php?tarea=form_editar&id=<?php echo $fila_id; ?>" title="Editar <?php echo $fila_id; ?>" ><i class="icn-pencil"></i></a>
	                  <a  title="eliminar <?php echo $fila_id; ?>" type="button" idEliminar="<?php echo $fila_id; ?>" nombreEliminar="<?php echo $fila_nombre; ?>" class="btn btn-eliminar btn-accion <?php echo $aux; ?>"><i class="icn-trash"></i></a>
	                </td>
	              </tr>
	            <?php
	                }
	              }
	            ?>
	          </tbody>
	        </table>
	      </div>
	    </div>
	    <?php
	    $this->fmt->class_modulo->script_form("modulos/usuarios/grupos.adm.php",$this->id_mod);
	}

	function form_nuevo(){
		$this->fmt->form->head_nuevo('
		Nuevo Grupo','grupos',$this->id_mod,'','form_nuevo','head-grupos'); //$nom,$archivo,$id_mod,$botones,$id_form,$class
		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","","","input-lg","","");
		$this->fmt->form->textarea_form('Detalle:','inputDetalle','','','','','3','','');
		$this->fmt->form->textarea_form('Funciones:','inputFunciones','','','','','3','','');
		$this->fmt->form->botones_nuevo();
		$this->fmt->class_modulo->script_form("modulos/usuarios/grupos.adm.php",$this->id_mod);
		$this->fmt->form->footer_page();
	}


	function form_editar(){
    $this->fmt->form->head_editar('Grupo','grupos','',''); //$nom,$archivo,$id_mod,$botones
    $this->fmt->get->validar_get ( $_GET['id'] );
	$id = $_GET['id'];
    $consulta ="SELECT * FROM grupos WHERE grupo_id='$id'";
    $rs = $this->fmt->query->consulta($consulta);
    $fila=  $this->fmt->query->obt_fila($rs);
    $this->fmt->form->input_form('Nombre','inputNombre','', $fila['grupo_nombre'],'input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
    $this->fmt->form->input_hidden_form('inputId',$fila['grupo_id']);
    $this->fmt->form->textarea_form('Detalles:','inputDetalles','', $fila['grupo_detalles'],'','','3',''); //$label,$id,$placeholder,$valor,$class,$rows,$mensajes
    $this->fmt->form->textarea_form('Funciones','inputFunciones','', $fila['grupo_funciones'],'','','3',''); //$label,$id,$placeholder,$valor,$class,$rows,$mensajes

    $this->fmt->form->radio_activar_form($fila['grupo_activar']);
    $this->fmt->form->botones_editar($fila['grupo_id'],$fila['grupo_nombre'],'Grupo');//$fila_id,$fila_nombre,$nombre

    $this->fmt->class_modulo->script_form("modulos/usuarios/grupos.adm.php",$this->id_mod);
    $this->fmt->form->footer_page();
  }


	function ingresar(){

		if ($_POST["btn-accion"]=="activar"){
			$activar=1;
		}
		if ($_POST["btn-accion"]=="guardar"){
			$activar=0;
		}

		$ingresar ="grupo_nombre,grupo_detalle,grupo_funciones,grupo_activar";
		$valores  ="'".$_POST['inputNombre']."','".
						$_POST['inputDetalle']."','".
						$_POST['inputFunciones']."','".
						$activar."'";

		$sql="insert into grupos (".$ingresar.") values (".$valores.")";

		$this->fmt->query->consulta($sql);

		header("location: grupos.adm.php?id_mod=".$this->id_mod);
	} // fin funcion ingresar


	function eliminar(){
  		$this->fmt->class_modulo->eliminar_get_id("grupos","grupo_");
  		header("location: grupos.adm.php");
  	}

  	function activar(){
	    $this->fmt->class_modulo->activar_get_id("grupos","grupo_");
	    header("location: grupos.adm.php");
  	}


}
