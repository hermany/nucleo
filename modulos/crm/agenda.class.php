<?php
header("Content-Type: text/html;charset=utf-8");

class AGENDA{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function AGENDA($fmt,$id_mod=0,$id_item=0,$id_estado=0){
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
    $this->fmt->class_pagina->head_modulo_inner("Agenda de personas", $botones); // bd, id modulo, botones
    //echo '<link rel="stylesheet" href="'._RUTA_WEB_NUCLEO.'css/agenda.css?reload" rel="stylesheet" type="text/css">';
    $this->fmt->form->head_table('table_id');
    $this->fmt->form->thead_table('Nombre:Email:Telefono/Celulares:Etiquetas:Acciones');
    $this->fmt->form->tbody_table_open();
    $sql="SELECT * FROM mod_agenda ORDER BY mod_agd_id desc";

    $rs =$this->fmt->query->consulta($sql);
    $num=$this->fmt->query->num_registros($rs);
    if($num>0){
      for($i=0;$i<$num;$i++){
				$fila=$this->fmt->query->obt_fila($rs);
				if (empty($fila["mod_agd_foto"])){
				  $img = _RUTA_WEB_NUCLEO."images/user/user-mini.png";
				}else{
				  $img = _RUTA_IMAGES.$fila["mod_agd_foto"];
				}
				echo "<tr class='row row-".$fila["mod_agd_id"]."'>";
				echo '<td class=""><div class="foto foto-perfil" style="background:url('.$img.')no-repeat center center"></div><strong class="nombre-perfil">'.$fila["mod_agd_nombre"].'</strong></td>';
				if ($fila["mod_agd_email_personal"]){
					$email=$fila["mod_agd_email_personal"]."</br>".$fila["mod_agd_email_trabajo"];
				}else{
					$email=$fila["mod_agd_email_trabajo"];
				}
				echo '<td class="">'.$email.'</td>';
				$tefl ="";
				if ($fila["mod_agd_telefono"]){
					$tefl .= $fila["mod_agd_telefono"]."</br>";
				}
				if ($fila["mod_agd_telefono_trabajo"]){
					$tefl .= $fila["mod_agd_telefono_trabajo"]."</br>";
				}
				if ($fila["mod_agd_celular"]){
					$tefl .= $fila["mod_agd_celular"]."</br>";
				}
				if ($fila["mod_agd_celular_trabajo"]){
					$tefl .= $fila["mod_agd_celular_trabajo"];
				}
				echo '<td class="">'.$tefl.'</td>';

				echo '<td class="">'.$this->fmt->class_modulo->estructurar_hashtags($fila["mod_agd_tags"]).'</td>';
				echo '<td class="">';
				$fila_id=$fila["mod_agd_id"];
				$nombre=$fila["mod_agd_nombre"];
				echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar ".$fila_id,"btn btn-accion btn-editar btn-menu-ajax",$this->id_mod,"form_editar,".$fila_id);
				echo $this->fmt->class_pagina->crear_btn_m("","icn-trash","eliminar ".$fila_id,"btn btn-accion btn-m-eliminar",$this->id_mod,"eliminar,".$fila_id,"",$nombre);
				echo '</td>';
				echo "</tr>";
      }
    }
    $this->fmt->form->tbody_table_close();
    $this->fmt->form->footer_table();
    $this->fmt->class_pagina->footer_mod();
    $this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","1","10",true);
		$this->fmt->class_modulo->script_accion_modulo();
  }

  function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nueva Persona","","");
		$id_form="form-nuevo";
		$this->fmt->class_pagina->head_form_mod();
		//$this->fmt->form->finder("",$this->id_mod,"","multiple-accion","");

		$this->fmt->class_pagina->form_ini_mod($id_form,"form-nueva-persona");
		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","","","input-lg","","");
		$this->fmt->form->input_form("Empresa:","inputEmpresa","","");
		$this->fmt->form->input_form("Cargo:","inputCargo","","");
		$this->fmt->form->input_form("Etiquetas:","inputEtiquetas","","");
		$this->fmt->form->imagen_unica_form("inputFoto","",$titulo="Imagen principal","","Foto:");
    $this->fmt->form->input_form("Email personal:","inputEmail","","");
    $this->fmt->form->input_form("Email Trabajo:","inputEmailTrabajo","","");
    $this->fmt->form->input_form("Teléfono:","inputTelefono","xxx x xxx-xxxx","");
    $this->fmt->form->input_form("Teléfono trabajo:","inputTelefonoTrabajo","xxx x xxx-xxxx","");
    $this->fmt->form->input_form("Celular Personal:","inputCelular","xxx xxx-xxxx","");
    $this->fmt->form->input_form("Celular Trabajo:","inputCelularTrabajo","xxx xxx-xxxx","");
    $fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');
    $this->fmt->form->input_date_form('Fecha de Nacimiento:','inputFecha','',$fecha,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

    $this->fmt->form->textarea_form("Notas:","inputNotas","","");

		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

  function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Nueva Persona","","");
		$id_form="form-editar";
		$this->fmt->class_pagina->head_form_mod();

		$sql="SELECT * from mod_agenda where mod_agd_id='".$this->id_item."'";
		$rs=$this->fmt->query->consulta($sql,__METHOD__);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
			for($i=0;$i<$num;$i++){
				list($fila_id,$fila_nombre,$fila_foto,$fila_tags,$fila_empresa,$fila_cargo,$fila_email_personal,$fila_email_trabajo,$fila_telefono,$fila_telefono_trabajo,$fila_celular,$fila_celular_trabajo,$fila_fecha_nacimiento,$fila_notas,$fila_activar)=$this->fmt->query->obt_fila($rs);
			}
		}

		$this->fmt->class_pagina->form_ini_mod($id_form,"form-editar-persona");
		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","",$fila_nombre,"input-lg","","");
		$this->fmt->form->input_form("Empresa:","inputEmpresa","",$fila_empresa,"");
		$this->fmt->form->input_form("Cargo:","inputCargo","",$fila_cargo,"");
		$this->fmt->form->input_form("Etiquetas:","inputEtiquetas","",$fila_tags,"");
		$this->fmt->form->imagen_unica_form("inputFoto",$fila_foto,$titulo="Imagen principal","","Foto:");
    $this->fmt->form->input_form("Email personal:","inputEmail","",$fila_email_personal,"");
    $this->fmt->form->input_form("Email Trabajo:","inputEmailTrabajo","",$fila_email_trabajo,"");
    $this->fmt->form->input_form("Teléfono:","inputTelefono","",$fila_telefono);
    $this->fmt->form->input_form("Teléfono trabajo:","inputTelefonoTrabajo","",$fila_telefono_trabajo,"");
    $this->fmt->form->input_form("Celular Personal:","inputCelular","",$fila_celular);
    $this->fmt->form->input_form("Celular Trabajo:","inputCelularTrabajo","",$fila_celular_trabajo);
		if ($fila_fecha!="0000-00-00"){
			$fecha = $fila_fecha;
		}else{
			$fecha="";
		}
    $this->fmt->form->input_date_form('Fecha de Nacimiento:','inputFecha','',$fecha,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

    $this->fmt->form->textarea_form("Notas:","inputNotas","",$fila_notas);
		$this->fmt->form->vista_item($fila_activar,".box-botones-form");

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar"); //$id_form,$id_mod,$tarea
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	function ingresar(){
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}

		$ingresar ="mod_agd_nombre,
                mod_agd_foto,
                mod_agd_tags,
                mod_agd_empresa,
                mod_agd_cargo,
                mod_agd_email_personal,
                mod_agd_email_trabajo,
                mod_agd_telefono,
                mod_agd_telefono_trabajo,
                mod_agd_celular,
                mod_agd_celular_trabajo,
								mod_agd_fecha_nacimiento,
								mod_agd_notas,
                mod_agd_activar";

		$valores  ="'".$_POST['inputNombre']."','".
					$_POST['inputFoto']."','".
					$_POST['inputTags']."','".
					$_POST['inputEmpresa']."','".
					$_POST['inputCargo']."','".
					$_POST['inputEmail']."','".
					$_POST['inputEmailTrabajo']."','".
					$_POST['inputTelefono']."','".
					$_POST['inputTelefonoTrabajo']."','".
					$_POST['inputCelular']."','".
					$_POST['inputCelularTrabajo']."','".
					$_POST['inputFecha']."','".
					$_POST['inputNotas']."','".
					$activar."'";

		$sql="insert into mod_agenda (".$ingresar.") values (".$valores.")";
		$this->fmt->query->consulta($sql);
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}
}
