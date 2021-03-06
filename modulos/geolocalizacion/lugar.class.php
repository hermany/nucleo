<?php
header("Content-Type: text/html;charset=utf-8");

class LUGAR{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function LUGAR($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){

		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones);
		$this->fmt->class_pagina->head_mod();

		$this->fmt->class_pagina->head_modulo_inner("Lista de Listas apps", $botones,"crear",$this->id_mod); // bd, id modulo, botones

    $this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Nombre:Estado:Activar:Acciones');
    $this->fmt->form->tbody_table_open();

  	$consulta = "SELECT mod_lug_id, mod_lug_nombre, mod_lug_direccion, mod_lug_telefono, mod_lug_coordenada_principal, mod_lug_coordenadas, mod_lug_estado, mod_lug_activar  FROM mod_lugar";
  	$rs =$this->fmt->query->consulta($consulta);
  	$num=$this->fmt->query->num_registros($rs);
  	if($num>0){
  		for($i=0;$i<$num;$i++){
  			$row=$this->fmt->query->obt_fila($rs);
  			$row_id = $row["mod_lug_id"];
  			$row_nombre = $row["mod_lug_nombre"];
  			$row_estado = $row["mod_lug_estado"];
  			$row_activar = $row["mod_lug_activar"];


				echo "<tr class='row row-".$row_id."'>";
				echo '  <td class="col-id">'.$row_id.'</td>';
				echo '  <td class="col-name">'.$row_nombre.'</td>';
				echo '  <td class="col-estado"></td>';
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
	} //fin buscqueda

	function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Lugar","","");
		$id_form="form-nuevo";
		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-lugares");

		$this->fmt->form->input_form("* Nombre:","inputNombre","","","input-lg","","","",""); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros
		$this->fmt->form->input_form("Tags:","inputTags","","");
		$this->fmt->form->input_form("Dirección:","inputDireccion","","");
		$this->fmt->form->input_form("Teléfonos:","inputTelefono","","");
		$this->fmt->form->textarea_form('Información:','inputInfo','','','','3','');
		$this->fmt->form->imagen_unica_form("inputImagen","","","form-normal","Imagen relacionada:");
		$this->fmt->form->input_form("Coordenada Principal:","inputCoordPrincipal","","");
		$this->fmt->form->input_form("Coordenadas:","inputCoordenadas","","");
		$valor='<p><i class="icon icon-puntero icon-mapa-lpz"></i><a href="" class="ads" style="background:url( [{imagen}] )"></a></p><div class="title">[{nombre}]</div><div class="inner-icons"><a class="disabled" href="#"><i class="icon icon-ra"></i><span>Realidad Virtual</span></a><a class="" href="#"><i class="icon icon-visita-guiada"></i><span>Visita Guiada</span></a><a class="" href="#"><i class="icon icon-preguntados"></i><span>Preguntados</span></a><a class="" href="#"><i class="icon icon-zafari"></i><span>Zafari Fotográfico</span></a><a class="" href="#"><i class="icon icon-info-x"></i><span>Información</span></a></div><br><p></p>';
		$this->fmt->form->textarea_form('Content:','inputContenido','',$valor,'','','12');
		$this->fmt->form->input_form("Icono Home:","inputIcono","","");
		$this->fmt->form->input_form("Icono Puntero:","inputIcon","","");
		$this->fmt->form->input_form("Usuario:","inputUsuario","","");
		$this->fmt->form->input_form("Billetera:","inputBilletera","","");
		$this->fmt->form->input_form("Estado:","inputEstado","","1");
		$this->fmt->form->categoria_form('Categoria','inputCat',"0","","",""); //

		// $this->fmt->form->arbol_editable_nodo('mod_lista','mod_list_','0',$this->id_mod);

		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputInfo");
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} //fin form_nuevo

	function ingresar(){
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
		$ingresar ="mod_lug_nombre, mod_lug_tags,mod_lug_direccion, mod_lug_telefono, mod_lug_info, mod_lug_imagen, mod_lug_coordenada_principal, mod_lug_coordenadas, mod_lug_icono, mod_lug_icon, mod_lug_contenido, mod_lug_usuario,mod_lug_bill_id, mod_lug_estado, mod_lug_activar";

		$valores  ="'".$_POST['inputNombre']."','".
					$_POST['inputTags']."','".
					$_POST['inputDireccion']."','".
					$_POST['inputTelefono']."','".
					$_POST['inputInfo']."','".
					$_POST['inputImagen']."','".
					$_POST['inputCoordPrincipal']."','".
					$_POST['inputCoordenadas']."','".
					$_POST['inputIcono']."','".
					$_POST['inputIcon']."','".
					$_POST['inputContenido']."','".
					$_POST['inputUsuario']."','".
					$_POST['inputBilletera']."','".
					$_POST['inputEstado']."','".
					$activar."'";
		$sql="insert into mod_lugar (".$ingresar.") values (".$valores.")";
		$this->fmt->query->consulta($sql);

		$sql="select max(mod_lug_id) as id from mod_lugar";
		$rs= $this->fmt->query->consulta($sql);
		$fila = $this->fmt->query->obt_fila($rs);
		$id = $fila ["id"];

		$ingresar1 ="mod_lug_cat_lug_id, mod_lug_cat_cat_id, mod_lug_cat_orden";
		$valor_cat= $_POST['inputCat'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$id."','".$valor_cat[$i]."','".$i."'";
			$sql1="insert into mod_lugar_categorias (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin ingresar

	function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Lugar","","");
		$id_form="form-editar";

		$id = $this->id_item;
		$consulta= "SELECT * FROM mod_lugar WHERE mod_lug_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-lugares");
		
		// $this->fmt->form->hidden_modulo($this->id_mod,"modificar");
		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","",$row["mod_lug_nombre"],"input-lg","","");
		$this->fmt->form->input_hidden_form("inputId",$id);

		$this->fmt->form->input_form("Tags:","inputTags","",$row["mod_lug_tags"]);
		$this->fmt->form->input_form("Dirección:","inputDireccion","",$row["mod_lug_direccion"]);
		$this->fmt->form->input_form("Teléfonos:","inputTelefono","",$row["mod_lug_telefono"]);
		$this->fmt->form->textarea_form('Información:','inputInfo','',$row["mod_lug_info"],'','','');
		// echo "imagen:".$row['mod_lug_imagen'];
		$this->fmt->form->imagen_unica_form("inputImagen",$row['mod_lug_imagen'],"","form-normal","Imagen relacionada:");
		$this->fmt->form->input_form("Coordenada Principal:","inputCoordPrincipal","",$row['mod_lug_coordenada_principal']);
		$this->fmt->form->input_form("Coordenadas:","inputCoordenadas","",$row['mod_lug_coordenadas']);
		$this->fmt->form->textarea_form('Content:','inputContenido','',$row["mod_lug_contenido"],'','','12'); //$label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje
		$this->fmt->form->input_form("Icono Home:","inputIcono","",$row["mod_lug_icono"]);
		$this->fmt->form->input_form("Icono Puntero:","inputIcon","",$row["mod_lug_icon"]);
		$this->fmt->form->input_form("Usuario:","inputUsuario","",$row["mod_lug_usuario"]);
		$this->fmt->form->input_form("Billetera:","inputBilletera","",$row["mod_lug_bill_id"]);

		$this->fmt->form->input_form("Estado:","inputEstado","",$row['mod_lug_estado']);

		$cats_id = $this->fmt->categoria->traer_rel_cat_id($id,'mod_lugar_categorias','mod_lug_cat_cat_id','mod_lug_cat_lug_id'); //$fila_id,$from,$prefijo_cat,$prefijo_rel
		$this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"",""); //

		
		$this->fmt->form->nodo_form('{
																	"label":"Listas:",
																	"id":"inputList",
																	"id_raiz":"0",
																	"valores":"'.$id.',mod_lugar_listas,mod_lug_list_list_id,mod_lug_list_lug_id",
																	"from":"mod_lista",
																	"prefijo":"mod_list_"
																}');

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputInfo");
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} // fin form_editar

	function modificar(){

		$sql="UPDATE mod_lugar SET
						mod_lug_nombre='".$_POST['inputNombre']."',
						mod_lug_tags ='".$_POST['inputTags']."',
						mod_lug_direccion ='".$_POST['inputDireccion']."',
						mod_lug_telefono ='".$_POST['inputTelefono']."',
						mod_lug_info ='".$_POST['inputInfo']."',
						mod_lug_imagen ='".$_POST['inputImagen']."',
						mod_lug_coordenada_principal='".$_POST['inputCoordPrincipal']."',
						mod_lug_coordenadas='".$_POST['inputCoordenadas']."',
						mod_lug_contenido='".$_POST['inputContenido']."',
						mod_lug_usuario='".$_POST['inputUsuario']."',
						mod_lug_bill_id='".$_POST['inputBilletera']."',
						mod_lug_icono='".$_POST['inputIcono']."',
						mod_lug_icon='".$_POST['inputIcon']."',
						mod_lug_estado='".$_POST['inputEstado']."'
						WHERE mod_lug_id='".$_POST['inputId']."'";
			//echo $sql;
			$this->fmt->query->consulta($sql);

			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"mod_lugar_categorias","mod_lug_cat_lug_id");
			$ingresar2 ="mod_lug_cat_lug_id,mod_lug_cat_cat_id,mod_lug_cat_orden";
			$valor_cat2= $_POST['inputCat'];
			$num2=count( $valor_cat2 );
			for ($i=0; $i<$num2;$i++){
				$valores2 = "'".$_POST['inputId']."','".$valor_cat2[$i]."','".$i."'";
				$sql2="insert into mod_lugar_categorias (".$ingresar2.") values (".$valores2.")";
				$this->fmt->query->consulta($sql2);
			}

			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"mod_lugar_listas","mod_lug_list_lug_id");
			$ingresar1 ="mod_lug_list_lug_id, mod_lug_list_list_id, mod_lug_list_orden";
			$valor_cat= $_POST['inputList'];
			$num=count( $valor_cat );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."','".$i."'";
				$sql1="insert into mod_lugar_listas (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}

			$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}

} //fin clase