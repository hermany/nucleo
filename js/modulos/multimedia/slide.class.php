<?php
header("Content-Type: text/html;charset=utf-8");

class SLIDE{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function SLIDE($fmt,$id_mod=0,$id_item=0,$id_estado=0){
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
    $this->fmt->class_pagina->head_modulo_inner("Slides multimedia", $botones); // bd, id modulo, botones
    //echo '<link rel="stylesheet" href="'._RUTA_WEB_NUCLEO.'css/agenda.css?reload" rel="stylesheet" type="text/css">';
    $this->fmt->form->head_table('table_id');
    $this->fmt->form->thead_table('#:Slide:Publicación:Categoria:Estado:Acciones','col-id');
    $this->fmt->form->tbody_table_open();
    $sql="SELECT sli_id,sli_nombre,sli_activar FROM slide ORDER BY sli_id desc";

    $rs =$this->fmt->query->consulta($sql);
    $num=$this->fmt->query->num_registros($rs);
    if($num>0){
      for($i=0;$i<$num;$i++){
        $fila=$this->fmt->query->obt_fila($rs);
      }
    }
    $this->fmt->form->tbody_table_close();
    $this->fmt->form->footer_table();
    $this->fmt->class_pagina->footer_mod();
    $this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","1","10",true);
    $this->fmt->class_modulo->script_accion_modulo();
  }

  function form_nuevo(){
    $this->fmt->class_pagina->crear_head_form("Nuevo Slide","","");
		$id_form="form-nuevo";
		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-nueva-persona");
    ?>
      <div class="group-tabs">
        <div class="tab">
          <span class="group group-fluid">
            <a class="category active" id="tab-web" idtab="web">Web</a>
            <a class="category" id="tab-mobile" idtab="mobile">Mobile</a>
            <a class="btn btn-success btn-small pull-right"><i class="icn icn-plus"></i>Agregar</a>
          </span>
        </div>
        <div class="slide-body">
          <div class="tbody tab-web on" id="content-web">
          </div>
          <div class="tbody tab-mobile" id="content-mobile">
          </div>
        </div>
      </div>
    <?php
    $this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
  }
}
