<?php
header("Content-Type: text/html;charset=utf-8");

class APP{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo ="modulos/apps/apps.adm.php";
	var $nombre_modulo ="apps.adm.php";
	var $nombre_tabla ="aplicaciones";
	var $prefijo_tabla ="app_";

	function APP($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
	}

	function dashboard_modulo(){
		$this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
		$this->fmt->class_pagina->head_mod();
		$this->fmt->class_pagina->head_modulo_inner("Aplicaciones","","crear",$this->id_mod);

		$this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Nombre:Activar:Acciones',":::col-acciones");
    $this->fmt->form->tbody_table_open("row-sorteable");

    $consulta = "SELECT  app_id,app_nombre,app_icono, app_color, app_activar  FROM aplicacion ORDER BY app_orden";
  	$rs =$this->fmt->query->consulta($consulta);
  	$num=$this->fmt->query->num_registros($rs);
  	if($num>0){
  		for($i=0;$i<$num;$i++){
  			$row=$this->fmt->query->obt_fila($rs);
  			$row_id = $row["app_id"];
  			$row_nombre = $row["app_nombre"];
  			$row_activar = $row["app_activar"];
  			$row_icono = $row["app_icono"];
  			$row_color = $row["app_color"];

				echo "<tr class='row row-list row-".$row_id."'>";
				echo '  <td class="col-id">'.$row_id.'</td>';
				echo '  <td class="col-name"><i class="'.$row_icono.'" style="color:'.$row_color.'"></i> '.$row_nombre.'</td>';
				echo '  <td class="col-activar">';
				$this->fmt->class_modulo->estado_publicacion($row_activar,$this->id_mod,"",$row_id);
				echo '	</td>';
				echo '  <td class="col-acciones acciones">';
				$this->fmt->class_modulo->botones_tabla($row_id,$this->id_mod,$row_nombre);//
				echo '	</td>';
  		}
  	}
  	$this->fmt->query->liberar_consulta();

    $this->fmt->form->tbody_table_close();
    $this->fmt->form->footer_table();

    $this->fmt->class_pagina->footer_mod();
    $this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","10",true);
		$this->fmt->class_modulo->script_accion_modulo();
  }

  function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar App","","");
		$id_form="form-editar";

		$id = $this->id_item;
		$consulta= "SELECT * FROM aplicacion WHERE app_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-apps");
		
		$this->fmt->form->hidden_modulo($this->id_mod,"modificar");
		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","",$row["app_nombre"],"","","");
		$this->fmt->form->input_hidden_form("inputId",$id);
		$this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$row["app_descripcion"],'','','');

		$this->fmt->form->input_form("Ruta amigable:","inputRutaAmigable","",$row["app_ruta_amigable"]);
		$this->fmt->form->input_form("Nav url:","inputNavUrl","",$row["app_nav_url"]);
		$this->fmt->form->input_form("url:","inputUrl","",$row["app_url"]);
		
		$this->fmt->form->input_form("Icono:","inputIcono","",$row["app_icono"]);
		$this->fmt->form->input_form("color:","inputColor","",$row["app_color"]);

		$this->fmt->form->input_form("Orden:","inpuOrden","",$row['app_orden']);

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} // fin form_editar

function modificar(){

		$sql="UPDATE aplicacion SET
						app_nombre='".$_POST['inputNombre']."',
						app_descripcion ='".$_POST['inputDescripcion']."',
						app_ruta_amigable ='".$_POST['inputRutaAmigable']."',
						app_nav_url ='".$_POST['inputNavUrl']."',
						app_url ='".$_POST['inputUrl']."',
						app_icono='".$_POST['inputIcono']."',
						app_color='".$_POST['inputColor']."',
						app_orden='".$_POST['inputOrden']."'
						WHERE app_id='".$_POST['inputId']."'";
			//echo $sql;
			$this->fmt->query->consulta($sql);

			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"mod_lugar_categorias","app_cat_lug_id");
			$ingresar1 ="app_cat_lug_id, app_cat_cat_id, app_cat_orden";
			$valor_cat= $_POST['inputCat'];
			$num=count( $valor_cat );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."','".$i."'";
				$sql1="insert into mod_lugar_categorias (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}

			$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}


}
