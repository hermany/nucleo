<?php
header("Content-Type: text/html;charset=utf-8");

class SUCURSALES{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function SUCURSALES($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){

    $this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
    ?>
    <div class="body-modulo container-fluid">
			<div class="container">
				<?php
				$botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nueva noticia","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars

				$this->fmt->class_pagina->head_modulo_inner("Lista de Sucursales", $botones); // bd, id modulo, botones
        $this->fmt->form->head_table("table_id");
        $this->fmt->form->thead_table('Id:Nombre y Dirección:Teléfono:Estado:Acciones');
        $this->fmt->form->tbody_table_open();
        $consulta = "SELECT mod_suc_id,mod_suc_nombre,mod_suc_direccion,mod_suc_telefono,mod_suc_activar FROM mod_sucursales ORDER BY mod_suc_id desc";
        $rs =$this->fmt->query->consulta($consulta);
        $num=$this->fmt->query->num_registros($rs);
        if($num>0){
          for($i=0;$i<$num;$i++){
            list($fila_id,$nombre,$direccion,$telefono,$activar)=$this->fmt->query->obt_fila($rs);
            echo "<tr class='row row-".$fila_id."'>";
            echo '  <td class="col-id">'.$fila_id.'</td>';
            echo '  <td class="col-name"><strong>'.$nombre.'</strong><span class="text-ref">'.$direccion.'</span></td>';
            echo '  <td class="">'.$telefono.'</td>';
            echo '  <td class="">';
             $this->fmt->class_modulo->estado_publicacion($activar,$this->id_mod,"",$fila_id);
            echo '  </td>';
            echo '  <td class="">';
            echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar ".$fila_id,"btn btn-accion btn-m-editar ",$this->id_mod,"form_editar,".$fila_id);
            echo $this->fmt->class_pagina->crear_btn_m("","icn-trash","eliminar ".$fila_id,"btn btn-accion btn-fila-eliminar",$this->id_mod,"eliminar,".$fila_id,"",$nombre);
            echo '  </td>';
            echo "</tr>";
          }
        }
        $this->fmt->form->tbody_table_close();
        $this->fmt->form->footer_table();
				?>
      </div>
    </div>
    <?php
    $this->fmt->class_modulo->script_accion_modulo();
		$this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","25",true);
  }

	function nombre_sucursal($id){
    $consulta = "SELECT mod_suc_nombre FROM mod_sucursales WHERE mod_suc_id=$id";
    $rs =$this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
    $fila = $this->fmt->query->obt_fila($rs);
    if ($fila["mod_suc_nombre"]){
      return  $fila["mod_suc_nombre"];
    }else{
      return  "sin nombre";
    }
    $this->fmt->query->liberar_consulta($rs);
  }

  function form_editar(){
    $this->fmt->class_pagina->crear_head_form("Editar Sucursal","","");
		$id_form="form-editar";
    $id = $this->id_item;

    $consulta= "SELECT * FROM mod_sucursales WHERE mod_suc_id='".$id."'";
		$rs =$this->fmt->query->consulta($consulta);
		$fila=$this->fmt->query->obt_fila($rs);

    $this->fmt->class_pagina->head_form_mod();
    $this->fmt->class_pagina->form_ini_mod($id_form,"");
    // inicio de formulario

      $this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre  Sucursal:","inputNombre","",$fila['mod_suc_nombre'],"input-lg","","");
      $this->fmt->form->ruta_amigable_form("inputNombre",_RUTA_WEB,$fila['mod_suc_ruta_amigable'],"inputRutaamigable"); //$id,$ruta,$valor,$form
      $this->fmt->form->input_form('Dirección:','inputDireccion','',$fila['mod_suc_direccion']);
      $this->fmt->form->input_form('Teléfono:','inputTelefono','',$fila['mod_suc_telefono']);
      $this->fmt->form->input_form('Coordenadas:','inputCoordenadas','',$fila['mod_suc_coordenadas']);
      $this->fmt->form->input_form('Email:','inputEmail','',$fila['mod_suc_email']);
      $this->fmt->form->input_form('Orden:','inputEmail','',$fila['mod_suc_orden']);
      $this->fmt->form->input_hidden_form("inputId",$id);
      $this->fmt->form->radio_activar_form($fila['mod_suc_activar']);
      $this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");

    // fin de formulario
    $this->fmt->class_pagina->form_fin_mod();
    $this->fmt->class_pagina->footer_form_mod();
    $this->fmt->class_modulo->modal_script($this->id_mod);
  }
  function modificar(){
      $sql="UPDATE mod_sucursales SET
      mod_suc_nombre='".$_POST['inputNombre']."',
      mod_suc_ruta_amigable ='".$_POST['inputRutaamigable']."',
      mod_suc_direccion='".$_POST['inputDireccion']."',
      mod_suc_telefono='".$_POST['inputTelefono']."',
      mod_suc_coordenadas='".$_POST['inputCoordenadas']."',
      mod_suc_orden='".$_POST['inputOrden']."',
      mod_suc_activar='".$_POST['inputActivar']."'
      WHERE mod_suc_id='".$_POST['inputId']."'";

    $this->fmt->query->consulta($sql);

    $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }
  function form_nuevo(){
    $this->fmt->class_pagina->crear_head_form("Nueva Sucursal","","");
		$id_form="form-nuevo";

    $this->fmt->class_pagina->head_form_mod();
    $this->fmt->class_pagina->form_ini_mod($id_form,"");
    // inicio de formulario

      $this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre  Sucursal:","inputNombre","","","input-lg","","");
      $this->fmt->form->ruta_amigable_form("inputNombre",_RUTA_WEB,"","inputRutaamigable"); //$id,$ruta,$valor,$form
      $this->fmt->form->input_form('Dirección:','inputDireccion','','');
      $this->fmt->form->input_form('Teléfono:','inputTelefono','','');
      $this->fmt->form->input_form('Coordenadas:','inputCoordenadas','','');
      $this->fmt->form->input_form('Email:','inputEmail','',"");
      $this->fmt->form->input_form('Orden:','inputOrden','',"");
      $this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");

    // fin de formulario
    $this->fmt->class_pagina->form_fin_mod();
    $this->fmt->class_pagina->footer_form_mod();
    $this->fmt->class_modulo->modal_script($this->id_mod);
  }
  function ingresar(){
    $activar=0; if ($_POST["estado-mod"]=="activar"){ $activar=1;}

    $ingresar ="mod_suc_nombre,mod_suc_ruta_amigable,mod_suc_direccion,mod_suc_telefono,mod_suc_coordenadas,mod_suc_email,mod_suc_orden,mod_suc_activar";
    $valores  ="'".$_POST['inputNombre']."','".
                   $_POST['inputRutaamigable']."','".
                   $_POST['inputDireccion']."','".
                   $_POST['inputTelefono']."','".
                   $_POST['inputCoordenadas']."','".
                   $_POST['inputEmail']."','".
                   $_POST['inputOrden']."','".
                   $activar."'";

    $sql="insert into mod_sucursales (".$ingresar.") values (".$valores.")";
    $this->fmt->query->consulta($sql);
    $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }
}
