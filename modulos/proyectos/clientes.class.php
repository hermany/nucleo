<?php
header("Content-Type: text/html;charset=utf-8");

class CLIENTES_PROY{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function CLIENTES_PROY($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){
    $this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
		$this->fmt->class_pagina->head_mod();
		$botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nuevo Cliente","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
    $this->fmt->class_pagina->head_modulo_inner("Lista de Clientes de proyectos y operaciones", $botones); // bd, id modulo, botones
		echo '<link rel="stylesheet" href="'._RUTA_WEB_NUCLEO.'css/clientes-proyectos.css?reload" rel="stylesheet" type="text/css">';
    echo "<div class='body-modulo-inner'>";
    $consulta = "SELECT  mod_cli_proy_id,mod_cli_proy_nombre,mod_cli_proy_codigo,mod_cli_proy_logo, mod_cli_proy_etiqueta FROM mod_cliente_proyectos ORDER BY mod_cli_proy_etiqueta asc";
    $rs =$this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
    if($num>0){
      for($i=0;$i<$num;$i++){
        list($fila_id,$nombre,$codigo,$img,$etiqueta)=$this->fmt->query->obt_fila($rs);
        if (empty($img)){
          $imgx = _RUTA_WEB_NUCLEO."images/cliente-proy.png";
        }else{
          $imgx = _RUTA_IMAGES.$img;
        }
        ?>
        <div class="box-cliente box-cliente-<?php echo $codigo; ?>" >
          <div class="logo btn-cliente" style="background:url(<?php echo $imgx; ?>)no-repeat center center" idCliente="<?php echo $fila_id; ?>" ></div>
          <div class="nombre btn-cliente" idCliente="<?php echo $fila_id; ?>" ><?php echo $codigo." - ".$nombre; ?></div>
          <icon class="etiqueta etiqueta-<?php echo $etiqueta; ?>" title="Cambiar etiqueta"></icon>
          <?php
          echo $this->fmt->class_pagina->crear_btn_m("Editar","icn-pencil","editar ".$fila_id,"btn btn-full btn-accion btn-editar btn-menu-ajax  btn-small",$this->id_mod,"form_editar,".$fila_id);
          echo $this->fmt->class_pagina->crear_btn_m("","icn-trash","eliminar ".$fila_id,"btn btn-accion btn-full btn-small btn-m-eliminar",$this->id_mod,"eliminar,".$fila_id,"",$nombre);
          ?>
        </div>
        <?php
      }
    }
    echo "</div>";
		echo "<div class='footer-modulo'>";
		echo "</div>";
		$this->fmt->class_modulo->script_accion_modulo();
		$this->fmt->class_pagina->footer_mod();
  }

  function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Cliente","","");
		$id_form="form-nuevo";
		$this->fmt->class_pagina->head_form_mod();
		//$this->fmt->form->finder("",$this->id_mod,"","multiple-accion","");

		$this->fmt->class_pagina->form_ini_mod($id_form,"form-nuevo-cliente");
		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","",$fila["cat_nombre"],"input-lg","","");
		$this->fmt->form->input_form("Codigo:","inputCodigo","> a 3 digitos en mayuscula","");
		$this->fmt->form->input_form("Detalles:","inputDetalle","A que se dedica, en que rubro trabaja...","");
		$this->fmt->form->imagen_unica_form("inputImagen","",$titulo="Imagen principal","","Logo:");
    $this->fmt->form->input_form("Dirección:","inputDireccion","","");
    $this->fmt->form->input_form("Mapa:","inputMapa","","");
    $this->fmt->form->input_form("Ciudad:","inputCiudad","","");
    $this->fmt->form->input_form("Pais:","inputPais","","");
    $this->fmt->form->input_form("Teléfono piloto:","inputTelefono","","");
    $this->fmt->form->input_form("Razón Social/Nombre:","inputRazonSocial","","");
    $this->fmt->form->input_form("Nit/C.I:","inputNit","","");
		$this->fmt->form->agenda_form("inputAgenda",$this->id_mod,"","Interesados:","mod_agenda_cliente_proyectos","mod_agd_proy_","mod_agd_"); //$id,$id_mod,$class_div,$label_form="",$from,$prefijo,$prefijo_mod
		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

}
