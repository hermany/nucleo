<?php
header("Content-Type: text/html;charset=utf-8");

class ROLES{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function ROLES($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

  function busqueda(){
		$botones = $this->fmt->class_modulo->botones_hijos_modulos($this->id_mod);
		$this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
    $this->fmt->class_pagina->head_mod();
    $botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nuevo pedido","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
    $this->fmt->class_pagina->head_modulo_inner("Lista de Roles", $botones); // bd, id modulo, botones
    $this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Nombre Rol:Padre:Estado:Acciones');
    $this->fmt->form->tbody_table_open();
		$consulta = "SELECT rol_id,rol_nombre,rol_id_padre,rol_activar FROM rol";
		$rs =$this->fmt->query->consulta($consulta,__METHOD__);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
			for($i=0;$i<$num;$i++){
				$row=$this->fmt->query->obt_fila($rs);
				$fila_id=$row["rol_id"];
				$nombre=$row["rol_nombre"];
				$rol_id_padre=$row["rol_id_padre"];
				$estado=$row["rol_activar"];
				echo "<tr class='row row-".$fila_id."'>";
				echo "  <td class='col-id'>$fila_id</td>";
				echo "  <td class=''>$nombre</td>";
				echo "  <td class=''>".$this->fmt->usuario->nombre_rol($rol_id_padre)."</td>";
				echo "  <td class=''>";
				$this->fmt->class_modulo->estado_publicacion($estado,$this->id_mod,"",$fila_id);
				echo "	</td>";
				echo "  <td class='col-acciones acciones'>";
				$this->fmt->form->botones_acciones_form("editar,eliminar",$this->id_mod,$fila_id,$nombre);
				echo "	</td>";
				echo "</tr>";
			}
		}
		$this->fmt->form->tbody_table_close();
		$this->fmt->form->footer_table();

		$this->fmt->class_modulo->script_accion_modulo();
		$this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","25",true);
		$this->fmt->class_pagina->footer_mod();
  }

  function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Rol");
		$id_form="form-nuevo";

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form);

		$this->fmt->form->input_form('Nombre:','inputNombre','',"",'input-lg','','');

		$this->fmt->form->textarea_form('Ref. Funciones:','inputFunciones','','','','','3',''); //$label,$id,$placeholder,$valor,$class,$rows,$mensaje
		$this->fmt->form->select_form('Id padre:','inputPadre','rol_','rol','');
		$this->fmt->form->textarea_form('Ref. Permisos','inputPermisos','', '','','','3',''); //$label,$id,$placeholder,$valor,$class,$rows,$mensaje

		$this->fmt->form->categoria_form("Accesos a categoría:","inputCat","0","","","rol-cat"); //$label,$id,$cat_raiz,$cat_valor,$class,$class_div

		$this->fmt->form->select_form("Redireccion:","inputRedireccion","sitio_","sitio","");

		$this->fmt->class_modulo->sistemas_modulos_select("Accesos a Sistemas y modulos","inputMod","","rol-cat box-sm"); //$label,$id,$class_div,$ids_sis,$isd_mod
		//$this->fmt->class_modulo->grupos_select("Definición de grupos","inputGrupos",""); //$label,$id,$class_div

		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");

		$this->fmt->class_pagina->form_fin_mod($id_form);
		$this->fmt->class_pagina->footer_form_mod();
		$this->fmt->class_modulo->modal_script($this->id_mod);

  }

  function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Rol");
		$id_form="form-editar";
		$id = $this->id_item;
    $consulta ="SELECT * FROM rol WHERE rol_id='$id'";
    $rs = $this->fmt->query->consulta($consulta);
    $fila=  $this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form);

    $this->fmt->form->input_form('Nombre:','inputNombre','',$fila['rol_nombre'],'input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
    $this->fmt->form->input_hidden_form('inputId',$fila['rol_id']);
    $this->fmt->form->textarea_form('Ref. Funciones:','inputFunciones','', $fila['rol_funciones'],'','','3',''); //$label,$id,$placeholder,$valor,$class,$rows,$mensaje
    $this->fmt->form->select_form('Id padre:','inputPadre','rol_','rol',$fila['rol_id_padre']);
    $this->fmt->form->textarea_form('Ref. Permisos','inputPermisos','', $fila['rol_permisos'],'','','3',''); //$label,$id,$placeholder,$valor,$class,$rows,$mensaje
		$cats_id = $this->fmt->categoria->traer_rel_cat_id($id,'rol_categorias','rol_cat_cat_id','rol_cat_rol_id'); //$fila_id,$from,$prefijo_cat,$prefijo_rel
		$this->fmt->form->categoria_form('Accesos a categoría:','inputCat',"0", $cats_id,"","rol-cat"); //$label,$id,$cat_raiz,$cat_valor,$class,$class_div

	$this->fmt->form->select_form("Redireccion","inputRedireccion","sitio_","sitio",$fila["rol_redireccion"]);


		//$ids_sis = $this->fmt->class_modulo->traer_sistemas_roles($id);
		//$ids_mod = $this->fmt->class_modulo->traer_modulos_roles($id);
		//$ids_per = $this->fmt->class_modulo->traer_modulos_roles_permisos($id);


    $this->fmt->class_modulo->sistemas_modulos_select("Accesos a Sistemas y modulos","inputMod",$id,"rol-cat box-sm"); //$label,$id,$class_div,$ids_sis,$isd_mod
    //$this->fmt->class_modulo->grupos_select("Definición de grupos","inputGrupos",""); //$label,$id,$class_div

		$this->fmt->form->vista_item($fila['rol_activar'],".box-botones-form");
    $this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");

		$this->fmt->class_pagina->form_fin_mod($id_form);
		$this->fmt->class_pagina->footer_form_mod();
		$this->fmt->class_modulo->modal_script($this->id_mod);
  }

  function nombre_rol($rol){
    $sql = "SELECT rol_nombre FROM roles WHERE rol_id=$rol";
    $rs =$this->fmt->query->consulta($sql,__METHOD__);
    $fila =$this->fmt->query->obt_fila($rs);
    return $fila['rol_nombre'];
  }

  function traer_opciones($id){
    $consulta ="SELECT rol_id, rol_nombre FROM roles";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    echo "<option class='' value='0'>Raiz (0)</option>";
    if($num>0){
      for($i=0;$i<$num;$i++){
        list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
        if ($fila_id==$id){  $aux="selected";  $aux1="disabled"; }else{ $aux1=""; $aux=""; }
        echo "<option class='' value='$fila_id' $aux $aux1 > ".$fila_nombre;
        echo "</option>";
      }
    }
  }

  function ingresar(){

		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}

		$ingresar ="rol_nombre,rol_funciones, rol_id_padre, rol_permisos, rol_redireccion, rol_activar";
		$valores  ="'".$_POST['inputNombre']."','".
									 $_POST['inputFunciones']."','".
									 $_POST['inputPadre']."','".
									 $_POST['inputPermisos']."','".
									 $_POST['inputRedireccion']."','".
									 $activar."'";

		$sql="insert into rol (".$ingresar.") values (".$valores.")";

		$this->fmt->query->consulta($sql,__METHOD__);

		$sql="select max(rol_id) as id from rol";
		$rs= $this->fmt->query->consulta($sql,__METHOD__);
		$fila = $this->fmt->query->obt_fila($rs);
		$id = $fila ["id"];


		$ingresar2 = "sitio_rol_sitio_id,sitio_rol_rol_id";
		$valores2 = "'".$_POST['inputRedireccion']."','".$id."'";
		$sql2="insert into sitio_roles (".$ingresar2.") values (".$valores2.")";
		$this->fmt->query->consulta($sql2,__METHOD__);

		

		//var_dump( $_POST['inputCat']);
		$ingresar1 ="rol_cat_rol_id, rol_cat_cat_id";
		$valor_cat= $_POST['inputCat'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$id."','".$valor_cat[$i]."'";
			$sql1="insert into rol_categorias (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1,__METHOD__);
		}

		$ingresar1 ="sis_rol_rol_id, sis_rol_sis_id";
		$valor_sis= $_POST['inputSis'];
		$num=count( $valor_sis );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$id."','".$valor_sis[$i]."'";
			$sql1="insert into sistema_roles (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1,__METHOD__);
		}

		$ingresar1 ="mod_rol_rol_id,mod_rol_mod_id,mod_rol_permisos";
		$valor_mod= $_POST['inputMod'];
		$num=count( $valor_mod );
		for ($i=0; $i<$num;$i++){
			$valor_v= $_POST['input_v'.$valor_mod[$i]];
			$valor_p= $_POST['input_p'.$valor_mod[$i]];
			$valor_a= $_POST['input_a'.$valor_mod[$i]];
			$valor_e= $_POST['input_e'.$valor_mod[$i]];
			$valor_t= $_POST['input_t'.$valor_mod[$i]];
			$valores1 = "'".$id."','".$valor_mod[$i]."','".$valor_v.",".$valor_p.",".$valor_a.",".$valor_e.",".$valor_t."'";
			$sql1="insert into modulo_roles (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1,__METHOD__);
		}

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin funcion ingresar

	function modificar(){

			$id=$_POST['inputId'];
			$sql="UPDATE rol SET
						rol_nombre='".$_POST['inputNombre']."',
						rol_funciones='".$_POST['inputFunciones']."',
						rol_id_padre ='".$_POST['inputPadre']."',
						rol_permisos='".$_POST['inputPermisos']."',
						rol_redireccion='".$_POST['inputRedireccion']."',
						rol_activar='".$_POST['inputActivar']."'
	          WHERE rol_id='".$id."'";

			$this->fmt->query->consulta($sql,__METHOD__);

			// $sql1="DELETE FROM roles_rel WHERE rol_rel_rol_id='".$id."'";
			// $this->fmt->query->consulta($sql1,__METHOD__);
			//
			// $up_sqr7 = "ALTER TABLE roles_rel AUTO_INCREMENT=1";
			// $this->fmt->query->consulta($up_sqr7,__METHOD__);

			$sql2="DELETE FROM sitio_roles WHERE sitio_rol_rol_id='".$id."'";
			$this->fmt->query->consulta($sql2,__METHOD__);

			$ingresar2 = "sitio_rol_sitio_id,sitio_rol_rol_id";
			$valores2 = "'".$_POST['inputRedireccion']."','".$id."'";
			$sql2="insert into sitio_roles (".$ingresar2.") values (".$valores2.")";
			$this->fmt->query->consulta($sql2,__METHOD__);

	 


			$sql1="DELETE FROM rol_categorias WHERE rol_cat_rol_id='".$id."'";
			$this->fmt->query->consulta($sql1,__METHOD__);

			$ingresar1 ="rol_cat_rol_id, rol_cat_cat_id";
			$valor_cat= $_POST['inputCat'];
			$num=count( $valor_cat );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$id."','".$valor_cat[$i]."'";
				$sql1="insert into rol_categorias (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1,__METHOD__);
			}

			$sql1="DELETE FROM sistema_roles WHERE sis_rol_rol_id='".$id."'";
			$this->fmt->query->consulta($sql1,__METHOD__);
			$ingresar1 ="sis_rol_rol_id, sis_rol_sis_id";
			$valor_sis= $_POST['inputSis'];
			$num=count( $valor_sis );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$id."','".$valor_sis[$i]."'";
				$sql1="insert into sistema_roles (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1,__METHOD__);
			}

			$sql1="DELETE FROM modulo_roles WHERE mod_rol_rol_id='".$id."'";
			$this->fmt->query->consulta($sql1,__METHOD__);
			$ingresar1 ="mod_rol_rol_id,mod_rol_mod_id,mod_rol_permisos";
			$valor_mod= $_POST['inputMod'];
			$num=count( $valor_mod );
			for ($i=0; $i<$num;$i++){
				$valor_v= $_POST['input_v'.$valor_mod[$i]];
				$valor_p= $_POST['input_p'.$valor_mod[$i]];
				$valor_a= $_POST['input_a'.$valor_mod[$i]];
				$valor_e= $_POST['input_e'.$valor_mod[$i]];
				$valor_t= $_POST['input_t'.$valor_mod[$i]];
				$valores1 = "'".$id."','".$valor_mod[$i]."','".$valor_v.",".$valor_p.",".$valor_a.",".$valor_e.",".$valor_t."'";
				$sql1="insert into modulo_roles (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1,__METHOD__);
			}

			$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}

	function eliminar(){
		$this->fmt->get->validar_get ( $_GET['id'] );
		$id= $_GET['id'];
		$sql="DELETE FROM roles WHERE rol_id='".$id."'";
		$this->fmt->query->consulta($sql,__METHOD__);
		$up_sqr6 = "ALTER TABLE roles AUTO_INCREMENT=1";
		$this->fmt->query->consulta($up_sqr6,__METHOD__);

		$sql1="DELETE FROM roles_rel WHERE rol_rel_rol_id='".$id."'";
		$this->fmt->query->consulta($sql1,__METHOD__);
		$up_sqr7 = "ALTER TABLE roles_rel AUTO_INCREMENT=1";
		$this->fmt->query->consulta($up_sqr7);

		header("location: roles.adm.php?id_mod=".$this->id_mod);
	}

	function activar(){
		$this->fmt->get->validar_get ( $_GET['estado'] );
		$this->fmt->get->validar_get ( $_GET['id'] );
		$sql="update roles set
				rol_activar='".$_GET['estado']."' where rol_id='".$_GET['id']."'";
		$this->fmt->query->consulta($sql,__METHOD__);
		header("location: roles.adm.php?id_mod=".$this->id_mod);
	}

}
