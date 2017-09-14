<?php
header("Content-Type: text/html;charset=utf-8");

class EMPRESAS{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;

	function EMPRESAS($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
	}

	function busqueda(){

    $this->fmt->class_pagina->crear_head_mod("icn-conf color-text-rojo","Empresas"); // $icon, $nom,$botones
		$clase_activa = "empresas";
		if ($this->id_estado!=''){
			$clase_activa = $this->id_estado;
		}
		?>
		<?php
		$this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","4","10",true);
		$this->fmt->class_modulo->script_accion_modulo();
  }

  function traer_relacion($id){
  	$relacion="";
  	$div="";
	  $sql="SELECT tip_emp_nombre FROM tipo_empresa, empresa_tipo where tip_emp_id=emp_tip_tip_id and emp_tip_emp_id=$id ORDER BY tip_emp_id asc";
			$rs =$this->fmt->query->consulta($sql);
			$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					list($fila_nombre)=$this->fmt->query->obt_fila($rs);
					$relacion.=$div.$fila_nombre;
					$div=" - ";
				}
			}
	return $relacion;
  }

  function form_nuevo_empresa(){
    //$botones .= $this->fmt->class_pagina->crear_btn("empresas.adm.php?tarea=busqueda&p=empresas&id_mod=".$this->id_mod,"btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $botones .= $this->fmt->class_pagina->crear_btn_m("volver","icn-chevron-left","volver","btn btn-link  btn-volver btn-menu-ajax",$this->id_mod,"busqueda");
    $this->fmt->class_pagina->crear_head_mod( "", "Nueva Empresa",'',$botones,'col-xs-6 col-xs-offset-4');
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" enctype="multipart/form-data" method="POST" id="form_nuevo">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre de la empresa:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->hidden_modulo($this->id_mod,"ingresar_empresa");
        $this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','','3','','');
        $this->fmt->form->input_form('Telefono piloto:','inputTelfPiloto','','','','','');
        ?>
        <div class="form-group">
			<label>Logo (560x400px):</label>
			<div class="panel panel-default" >
				<div class="panel-body">
				<?php
				$this->fmt->form->file_form_nuevo_croppie_thumb('Cargar Archivo (max 8MB)','','form_nuevo','form-file','','box-file-form','archivos/empresas',"350x350");
				?>
				</div>
			</div>
		</div>
        <?php
        $this->fmt->form->input_form('Razón social:','inputRazonSocial','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('NIT:','inputNit','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Dirección:','inputDireccion','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Coordenadas:','inputCoordenadas','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Rubro:','inputRubro','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('E-mail:','inputEmail','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Web:','inputWeb','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Nombre representante:','inputNombreRpr','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('E-mail representante:','inputEmailRpr','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Telefono representante:','inputTelefonoRpr','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

	    $sql="SELECT * from tipo_empresa where tip_emp_activar='1' order by tip_emp_id asc";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);

		if($num>0){
			for($i=0;$i<$num;$i++){
				list($fila_id,$fila_nombre,$fila_descripcion)=$this->fmt->query->obt_fila($rs);
				$label[$i]=$fila_nombre;
				$valor[$i]=$fila_id;
			}
		}

	    $this->fmt->form->check_form("Tipo:","InputTipo",$valor,$label);
	    $this->fmt->form->categoria_form('Categoria','inputCat',"0","","","");
        $this->fmt->form->botones_nuevo("form_nuevo");
        ?>
      </form>
    </div>
    <?php
    $this->fmt->class_modulo->script_form($this->ruta_modulo,"");
    $this->fmt->form->footer_page();
  }

  function form_nuevo(){
    //$botones .= $this->fmt->class_pagina->crear_btn("empresas.adm.php?tarea=busqueda&p=tipo&id_mod=".$this->id_mod,"btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $botones .= $this->fmt->class_pagina->crear_btn_m("volver","icn-chevron-left","volver","btn btn-link  btn-volver btn-menu-ajax",$this->id_mod,"busqueda,0,tipo");
    $this->fmt->class_pagina->crear_head_mod( "", "Nueva Tipo",'',$botones,'col-xs-6 col-xs-offset-4');
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" enctype="multipart/form-data" method="POST" id="form_nuevo">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->hidden_modulo($this->id_mod,"ingresar");
        $this->fmt->form->input_form('Nombre de la tipo:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','','3','','');
		$usuario = $this->fmt->sesion->get_variable('usu_id');
		$usuario_n = $this->fmt->sesion->get_variable('usu_nombre');
		$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');
	    $this->fmt->form->input_hidden_form("inputUsuario",$usuario);

        $this->fmt->form->botones_nuevo("form_nuevo");
        ?>
      </form>
    </div>
    <?php
    $this->fmt->class_modulo->script_form($this->ruta_modulo,"");
    $this->fmt->form->footer_page();
  }


  function form_editar_empresa(){
    $botones .= $this->fmt->class_pagina->crear_btn_m("volver","icn-chevron-left","volver","btn btn-link  btn-volver btn-menu-ajax",$this->id_mod,"busqueda");
    $this->fmt->class_pagina->crear_head_mod( "", "Editar Empresa",'',$botones,'col-xs-6 col-xs-offset-4');
		$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $this->id_item;
		$sql="SELECT emp_id,emp_nombre,emp_descripcion,emp_telefono,emp_logo,emp_razon_social,emp_nit,emp_direccion,emp_coordenadas,emp_rubro,emp_email,emp_web,emp_activar, emp_nombre_contacto, emp_telefono_contacto, emp_email_contacto from empresa	where emp_id='".$id."'";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					list($fila_id_emp,$fila_nombre,$fila_descripcion,$fila_telf,$fila_logo,$fila_razon_social,$fila_nit,$fila_direccion,$fila_coordenadas,$fila_rubro,$fila_email,$fila_web,$fila_activar,$fila_nomb_cont,$fila_tel_cont,$fila_mail_cont)=$this->fmt->query->obt_fila($rs);
				}
			}
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" enctype="multipart/form-data" method="POST" id="form_editar">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre de la empresa:','inputNombre','',$fila_nombre,'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->hidden_modulo($this->id_mod,"modificar_empresa");
				$this->fmt->form->input_hidden_form("inputId",$fila_id_emp);
				$this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$fila_descripcion,'','','3','','');
				$this->fmt->form->input_form('Telefono piloto:','inputTelfPiloto','',$fila_telf,'','','');

        ?>
        <div class="form-group">
			<label>Logo (560x400px):</label>
			<div class="panel panel-default" >
				<div class="panel-body">
					<?php
					$this->fmt->form->file_form_nuevo_croppie_thumb('Cargar Archivo (max 8MB)','','form_editar','form-file','','box-file-form','archivos/empresas','350x350',$fila_logo); //$nom,$ruta,$id_form,$class,$class_div,$id_div,$directorio_p,$sizethumb,$imagen
					?>

				</div>
			</div>
		</div>
        <?php
        $this->fmt->form->input_form('Razón social:','inputRazonSocial','',$fila_razon_social,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('NIT:','inputNit','',$fila_nit,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Dirección:','inputDireccion','',$fila_direccion,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Coordenadas:','inputCoordenadas','',$fila_coordenadas,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Rubro:','inputRubro','',$fila_rubro,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('E-mail:','inputEmail','',$fila_email,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Web:','inputWeb','',$fila_web,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_form('Nombre representante:','inputNombreRpr','',$fila_nomb_cont,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('E-mail representante:','inputEmailRpr','',$fila_mail_cont,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Telefono representante:','inputTelefonoRpr','',$fila_tel_cont,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$tipo_id = $this->fmt->categoria->traer_rel_cat_id($id,'empresa_tipo','emp_tip_tip_id','emp_tip_emp_id');


		$sql="SELECT * from tipo_empresa where tip_emp_activar='1' order by tip_emp_id asc";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);

		if($num>0){
			for($i=0;$i<$num;$i++){
				list($fila_id,$fila_nombre,$fila_descripcion)=$this->fmt->query->obt_fila($rs);
				$label[$i]=$fila_nombre;
				$valor[$i]=$fila_id;
			}
		}

	    $this->fmt->form->check_form("Tipo:","InputTipo",$valor,$label,$tipo_id);
	    $cats_id = $this->fmt->categoria->traer_rel_cat_id($id,'empresa_categoria','emp_cat_cat_id','emp_cat_emp_id');
	    $this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"","");
		$this->fmt->form->radio_activar_form($fila_activar);
		$this->fmt->form->botones_editar($id,$fila_nombre,'form_editar',$this->id_mod);
        ?>
      </div>
    </div>
    <?php
    $this->fmt->class_modulo->script_form("modulos/adm/empresas.adm.php","");
    $this->fmt->form->footer_page();
  }

  function form_editar(){
    $botones .= $this->fmt->class_pagina->crear_btn_m("volver","icn-chevron-left","volver","btn btn-link  btn-volver btn-menu-ajax",$this->id_mod,"busqueda,0,tipo");
    $this->fmt->class_pagina->crear_head_mod( "", "Editar Tipo",'',$botones,'col-xs-6 col-xs-offset-4');
		$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $this->id_item;
		$sql="SELECT * from tipo_empresa where tip_emp_id='".$id."'";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					list($fila_id,$fila_nombre,$fila_descripcion,$fila_activar,$fila_usuario)=$this->fmt->query->obt_fila($rs);
				}
			}
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" enctype="multipart/form-data" method="POST" id="form_editar">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->hidden_modulo($this->id_mod,"modificar");
        $this->fmt->form->input_form('Nombre del cargo:','inputNombre','',$fila_nombre,'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputId",$fila_id);
		$this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$fila_descripcion,'','','3','','');
		$usuario = $this->fmt->sesion->get_variable('usu_id');
		$usuario_n = $this->fmt->sesion->get_variable('usu_nombre');
		$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');
	    $this->fmt->form->input_hidden_form("inputUsuario",$usuario);
		$this->fmt->form->radio_activar_form($fila_activar);
		$this->fmt->form->botones_editar($id,$fila_nombre,'form_editar',$this->id_mod);

        ?>
      </div>
    </div>
    <?php
    $this->fmt->class_modulo->script_form("modulos/adm/empresas.adm.php","");
    $this->fmt->form->footer_page();
  }



  function ingresar_empresa(){
    if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
    $ingresar ="emp_nombre,
                emp_descripcion,
                emp_telefono,
                emp_logo,
                emp_razon_social,
                emp_nit,
                emp_direccion,
                emp_coordenadas,
                emp_rubro,
                emp_email,
                emp_web,
                emp_nombre_contacto,
                emp_telefono_contacto,
                emp_email_contacto,
                emp_activar";
    $valores_post  ="inputNombre,
									inputDescripcion,
									inputTelfPiloto,
									inputUrl,
									inputRazonSocial,
									inputNit,
									inputDireccion,
									inputCoordenadas,
									inputRubro,
									inputEmail,
									inputWeb,
									inputNombreRpr,
									inputTelefonoRpr,
									inputEmailRpr,
									inputActivar=".$activar;

    $this->fmt->class_modulo->ingresar_tabla('empresa',$ingresar,$valores_post);
		//$from,$filas,$valores_post
	$sql="select max(emp_id) as id from empresa";
	$rs= $this->fmt->query->consulta($sql);
	$fila = $this->fmt->query->obt_fila($rs);
	$id = $fila ["id"];

	$valor_cat=$_POST["InputTipo"];
	$num = count($valor_cat);
	$ingresar1 ="emp_tip_emp_id, emp_tip_tip_id";
	for($i=0;$i<$num;$i++){
		$valores1 = "'".$id."','".$valor_cat[$i]."'";
		$sql1="insert into empresa_tipo (".$ingresar1.") values (".$valores1.")";
		$this->fmt->query->consulta($sql1);
	}

	$valor_cat=$_POST["inputCat"];
	$num = count($valor_cat);
	$ingresar1 ="emp_cat_emp_id, emp_cat_cat_id";
	for($i=0;$i<$num;$i++){
		$valores1 = "'".$id."','".$valor_cat[$i]."'";
		$sql1="insert into empresa_categoria (".$ingresar1.") values (".$valores1.")";
		$this->fmt->query->consulta($sql1);
	}

    $this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
  }

  function ingresar(){
   if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
    $ingresar ="tip_emp_nombre,
				tip_emp_descripcion,
				tip_emp_usuario,
				tip_emp_activar";
    $valores_post  ="inputNombre,
					inputDescripcion,
					inputUsuario,inputActivar=".$activar;

    $this->fmt->class_modulo->ingresar_tabla('tipo_empresa',$ingresar,$valores_post);
		//$from,$filas,$valores_post
    $this->fmt->class_modulo->script_location($this->id_mod,"busqueda,0,tipo");
  }



	function modificar_empresa(){
		$filas='emp_id,
			  emp_nombre,
              emp_descripcion,
              emp_telefono,
              emp_logo,
              emp_razon_social,
              emp_nit,
              emp_direccion,
              emp_coordenadas,
              emp_rubro,
              emp_email,
              emp_web,
              emp_nombre_contacto,
			  emp_telefono_contacto,
			  emp_email_contacto,
              emp_activar';
			$valores_post='inputId,inputNombre,
										inputDescripcion,
										inputTelfPiloto,
										inputUrl,
										inputRazonSocial,
										inputNit,
										inputDireccion,
										inputCoordenadas,
										inputRubro,
										inputEmail,
										inputWeb,
										inputNombreRpr,
										inputTelefonoRpr,
										inputEmailRpr,
										inputActivar';
			$this->fmt->class_modulo->actualizar_tabla('empresa',$filas,$valores_post); //$from,$filas,$valores_post
			$id=$_POST["inputId"];
			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"empresa_tipo","emp_tip_emp_id");
			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"empresa_categoria","emp_cat_emp_id");
			$valor_cat=$_POST["InputTipo"];
			$num = count($valor_cat);
			$ingresar1 ="emp_tip_emp_id, emp_tip_tip_id";
			for($i=0;$i<$num;$i++){
				$valores1 = "'".$id."','".$valor_cat[$i]."'";
				$sql1="insert into empresa_tipo (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}

			$valor_cat=$_POST["inputCat"];
			$num = count($valor_cat);
			$ingresar1 ="emp_cat_emp_id, emp_cat_cat_id";
			for($i=0;$i<$num;$i++){
				$valores1 = "'".$id."','".$valor_cat[$i]."'";
				$sql1="insert into empresa_categoria (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}
		    $this->fmt->class_modulo->script_location($this->id_mod,"busqueda");


	}

	function modificar(){

			$filas='tip_emp_id,
					tip_emp_nombre,
					tip_emp_descripcion,
					tip_emp_usuario,
					tip_emp_activar';
			$valores_post='inputId,
					inputNombre,
					inputDescripcion,
					inputUsuario,
					inputActivar';
			$this->fmt->class_modulo->actualizar_tabla('tipo_empresa',$filas,$valores_post); //$from,$filas,$valores_post
			$this->fmt->class_modulo->script_location($this->id_mod,"busqueda,0,tipo");

	}


  function eliminar_empresa(){
      $this->fmt->class_modulo->eliminar_fila($this->id_item,"empresa","emp_id");
      $this->fmt->class_modulo->eliminar_fila($this->id_item,"empresa_tipo","emp_tip_emp_id");
	  $this->fmt->class_modulo->eliminar_fila($this->id_item,"empresa_categoria","emp_cat_emp_id");
      $this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
    }

  function activar_empresa(){
      $this->fmt->class_modulo->activar_get_id("empresa","emp_",$this->id_estado,$this->id_item);
      $this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
  }
  function eliminar(){
  	$this->fmt->class_modulo->eliminar_fila($this->id_item,"tipo_empresa","tip_emp_id");
    $this->fmt->class_modulo->script_location($this->id_mod,"busqueda,0,tipo");
  }

  function activar(){
      $this->fmt->class_modulo->activar_get_id("tipo_empresa","tip_emp_",$this->id_estado,$this->id_item);
      $this->fmt->class_modulo->script_location($this->id_mod,"busqueda,0,tipo");
  }


}
?>
