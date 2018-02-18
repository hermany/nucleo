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
    $consulta = "SELECT  mod_cli_proy_id,mod_cli_proy_nombre,mod_cli_proy_codigo,mod_cli_proy_logo, mod_cli_proy_etiqueta FROM mod_cliente_proyectos ORDER BY mod_cli_proy_etiqueta,mod_cli_proy_id asc";
    $rs =$this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
    if($num>0){
      for($i=0;$i<$num;$i++){
        $row=$this->fmt->query->obt_fila($rs);
        // list($fila_id,$nombre,$codigo,$img,$etiqueta)=$this->fmt->query->obt_fila($rs);
        $row_id = $row["mod_cli_proy_id"];
        $row_nombre = $row["mod_cli_proy_nombre"];
        $row_logo = $row["mod_cli_proy_logo"];
        $row_codigo = $row["mod_cli_proy_codigo"];
        $row_etiqueta = $row["mod_cli_proy_etiqueta"];


        if (empty($row_logo)){
          $logo = _RUTA_WEB_NUCLEO."images/cliente-proy.png";
        }else{
          $logo = _RUTA_IMAGES.$row_logo;
        }
        ?>
        <div class="box-cliente box-cliente-<?php echo $row_codigo; ?> row-<?php echo $row_id; ?>" >
          <div class="logo btn-cliente" style="background:url(<?php echo $logo; ?>)no-repeat center center" idCliente="<?php echo $row_id; ?>" ></div>
          <div class="nombre btn-cliente" idCliente="<?php echo $row_id; ?>" ><?php echo $row_codigo." - ".$row_nombre; ?></div>
          <icon class="etiqueta etiqueta-<?php echo $row_etiqueta; ?>" title="Cambiar etiqueta"></icon>
          <?php
          echo $this->fmt->class_pagina->crear_btn_m("Editar","icn-pencil","editar ".$row_id,"btn btn-full btn-accion btn-editar btn-menu-ajax  btn-small",$this->id_mod,"form_editar,".$row_id);
          echo $this->fmt->class_pagina->crear_btn_m("","icn-trash","eliminar ".$row_id,"btn btn-accion btn-full btn-small btn-m-eliminar",$this->id_mod,"eliminar,".$row_id,"",$row_nombre);
          ?>
        </div>
        <?php
      }
    }
    echo "</div>";
    echo "<div class='footer-modulo'>";
    $this->fmt->class_pagina->footer_mod();
    $this->fmt->class_modulo->script_accion_modulo();
    
  }

  function form_nuevo(){
    $this->fmt->class_pagina->crear_head_form("Nuevo Cliente","","");
    $this->fmt->class_pagina->head_form_mod();
    $this->fmt->class_pagina->form_ini_mod("form_nuevo","form-lugares");

    $this->fmt->form->input_form("* Nombre:","inputNombre","","","input-lg","","");

    $this->fmt->class_pagina->form_fin_mod();
    $this->fmt->class_pagina->footer_form_mod();
  }

  function form_editar(){

  }

  function ingresar(){

  }

  function modificar(){

  }

}
