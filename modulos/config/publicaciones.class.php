<?php
header("Content-Type: text/html;charset=utf-8");

class PUBLICACIONES{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;
	var $nombre_modulo ="publicaciones.adm.php";
	var $nombre_tabla ="publicacion";
	var $prefijo_tabla ="pub_";

	function PUBLICACIONES($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

  function busqueda(){
		$botones .= $this->fmt->class_modulo->botones_hijos_modulos($this->id_mod);
		//$botones .= $this->fmt->class_pagina->crear_btn_m("Nueva Publicación","icn-plus","nueva publicacion","btn btn-primary btn-menu-ajax ",$this->id_mod,"form_nuevo");
		$this->fmt->class_pagina->crear_head( $this->id_mod, $botones,"");
		?>
		<div class="body-modulo">
			<div class="container">
				<?php
				$botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Crear","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
				$this->fmt->class_pagina->head_modulo_inner("Publicaciones activas", $botones); // bd, id
				?>
				<div class="table-responsive">
					<table class="table table-hover" id="table_id">
					  <thead>
					    <tr>
						  <th class="col-id">Id</th>
					      <th>Nombre Publicación</th>
					      <th>Ruta</th>
					      <th>Publicación</th>
					      <th class="col-xl-offset-2">Acciones</th>
					    </tr>
					  </thead>
					  <tbody>
							<?php
								$sql="select pub_id, pub_nombre, pub_archivo, pub_activar from publicacion	ORDER BY pub_id desc";
								$rs =$this->fmt->query->consulta($sql);
								$num=$this->fmt->query->num_registros($rs);
								if($num>0){
							  for($i=0;$i<$num;$i++){
							    $row=$this->fmt->query->obt_fila($rs);
									$fila_id= $row["pub_id"];
									$fila_nombre= $row["pub_nombre"];
									$fila_url= $row["pub_archivo"];
									$fila_activar= $row["pub_activar"];
							  ?>
							  <tr>
								  <td class=""><?php echo $fila_id; ?></td>
							    <td class="col-nombre"><?php echo $fila_nombre; ?></td>
							    <td class=""><?php echo $fila_url; ?></td>
							    <td class="estado">
							      <?php
							        $this->fmt->class_modulo->estado_publicacion($fila_activar, $this->id_mod,"",$fila_id,"");  //$estado,$id_mod,$disabled,$id,$activar
										?>
							    </td>
							    <td class="col-xl-offset-2 acciones">

							    <?php
									echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar:".$fila_id,"btn btn-accion btn-editar btn-menu-ajax ".$aux,$this->id_mod,"form_editar,".$fila_id); //$nom,$icon,$title,$clase,$id_mod,$vars

									echo $this->fmt->class_pagina->crear_btn_m("","icn-trash","eliminar:".$fila_id,"btn btn-accion btn-eliminar ".$aux,$this->id_mod,"eliminar,".$fila_id,"nombre=".$fila_nombre); //$nom,$icon,$title,$clase,$id_mod,$vars,$attr
									?>
										<!-- <a  title="eliminar <?php echo $fila_id; ?>" type="button" idEliminar="<?php echo $fila_id; ?>" nombreEliminar="<?php echo $fila_nombre; ?>" class="btn btn-eliminar btn-accion <?php echo $aux; ?>"><i class="icn-trash"></i></a> -->
							    </td>
							  </tr>
							  <?php
							   } // Fin for query1
							  }// Fin if query1
							?>
					  </tbody>
					</table>
				</div>
			</div>
		</div>
		<?php
		$this->fmt->class_modulo->script_accion_modulo();
		$this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","4","25",true);
  }

	function form_nuevo(){
		// $botones = $this->fmt->class_pagina->crear_btn("publicaciones.adm.php?tarea=busqueda","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre

		$botones = $this->fmt->class_pagina->crear_btn_m("volver","icn-chevron-left","volver","btn btn-link btn-volver btn-menu-ajax ",$this->id_mod,"busqueda");

		$this->fmt->class_pagina->crear_head_form("Nueva Publicación");
		$id_form="form-nuevo";

		 //$nombre,$botones_left, $botones_right, $class_modo,$id_mod,$vars
		?>
		<div class="body-modulo col-xs-6 col-xs-offset-3">
			<form class="form form-modulo"  method="POST" id="<?php echo $id_form?>">
				<div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->

				<div class="form-group">
					<label>Nombre publicación:</label>
					<input class="form-control input-lg"  id="inputNombre" name="inputNombre" placeholder=" " value="" type="text" autofocus />

				</div>
				<div class="form-group form-descripcion">
					<label>Descripción:</label>
					<textarea class="form-control" rows="2" id="inputDescripcion" name="inputDescripcion" placeholder=""></textarea>
				</div>
				<div class="form-group">
					<label>Ruta Archivo:</label>
					<input class="form-control" id="inputArchivo" name="inputArchivo" placeholder="" value=""/>
				</div>
				<div class="form-group">
					<label>Ruta Archivo Config:</label>
					<input class="form-control" id="inputArchivoConfig" name="inputArchivoConfig" placeholder="" value=""/>
				</div>
				<div class="form-group">
					<label>Imagen:</label>
					<input class="form-control" id="inputImagen" name="inputImagen" placeholder="" value=""/>
				</div>
				<div class="form-group">
					<label>Titulo:</label>
					<input class="form-control" id="inputTitulo" name="inputTitulo" placeholder="" value=""/>
				</div>
				<div class="form-group">
					<label>Tipo:<?php //echo $fila_tipo; ?></label>

						<select class="form-control form-select" name="inputTipo" id="inputTipo">
						<?php  echo $this->opciones_tipo();  ?></select>
				</div>

				<div class="form-group">
					<label>Ruta Css:</label>
					<input class="form-control" id="inputUrlCss" name="inputUrlCss" placeholder="" value=""/>
				</div>
				<div class="form-group">
					<label>Clase:</label>
					<input class="form-control" id="inputClase" name="inputClase"  placeholder="" value="" />
				</div>
				<div class="form-group">
					<label>Id Item:</label>
					<input class="form-control" id="inputItem" name="inputItem"  placeholder="" value=""/>
				</div>
				<div class="form-group">
					<label>Número/Items:</label>
					<input class="form-control" id="inputNumero" name="inputNumero"  placeholder="" value=""/>
				</div>

				<div class="form-group">
					<label>Id categoria:</label>
					<input class="form-control" id="inputCat" name="inputCat"  placeholder="" value=""/>
				</div>
				<?php

					$this->fmt->form->btn_nuevo($id_form,"",$this->id_mod,"ingresar"); //$id_form,$id_mod,$tarea
				?>
					 <!-- <button type="submit" class="btn btn-info  btn-actualizar hvr-fade btn-lg color-bg-celecte-c btn-lg" name="btn-accion" id="btn-activar" value="actualizar"><i class="icn-sync" ></i> Actualizar</button> -->
				</div>
			</form>
		</div>
		<?php
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	function form_editar(){

		$sql="select * from publicacion	where pub_id='".$this->id_item."'";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					$row=$this->fmt->query->obt_fila($rs);
					$fila_id= $row["pub_id"];
					$fila_nombre= $row["pub_nombre"];
					$fila_descripcion= $row["pub_descripcion"];
					$fila_imagen= $row["pub_imagen"];
					$fila_titulo= $row["pub_titulo"];
					$fila_tipo= $row["pub_tipo"];
					$fila_archivo= $row["pub_archivo"];
					$fila_archivo_config= $row["pub_archivo_config"];
					$fila_css= $row["pub_css"];
					$fila_clase= $row["pub_clase"];
					$fila_item= $row["pub_id_item"];
					$fila_numero= $row["pub_numero"];
					$fila_cat= $row["pub_id_cat"];
					$fila_activar= $row["pub_activar"];
				}
			}

		// $botones = $this->fmt->class_pagina->crear_btn_m("volver","icn-chevron-left","volver","btn btn-link btn-volver btn-menu-ajax ",$this->id_mod,"busqueda");

		//$this->fmt->class_pagina->crear_head_form("Editar Publicación", $botones,"","",$this->id_mod,"form_editar,".$fila_id);
		$this->fmt->class_pagina->crear_head_form("Editar Publicación");
		$id_form="form-editar";

		 //$nombre,$botones_left, $botones_right, $class_modo,$id_mod,$vars
		?>
		<div class="body-modulo col-xs-6 col-xs-offset-3">
			<form class="form form-modulo"  method="POST" id="<?php echo $id_form?>">
				<div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->

				<div class="form-group">
					<label>Nombre publicación:</label>
					<input class="form-control input-lg"  id="inputNombre" name="inputNombre" placeholder=" " value="<?php echo $fila_nombre; ?>" type="text" autofocus />
					<input id="inputId" name="inputId" type="hidden" value="<?php echo $fila_id; ?>" />
				</div>
				<div class="form-group form-descripcion">
					<label>Descripción:</label>
					<textarea class="form-control" rows="2" id="inputDescripcion" name="inputDescripcion" placeholder=""><?php echo $fila_descripcion; ?></textarea>
				</div>
				<div class="form-group">
					<label>Ruta Archivo:</label>
					<input class="form-control" id="inputArchivo" name="inputArchivo" placeholder="" value="<?php echo $fila_archivo; ?>"/>
				</div>
				<div class="form-group">
					<label>Ruta Archivo Config:</label>
					<input class="form-control" id="inputArchivoConfig" name="inputArchivoConfig" placeholder="" value="<?php echo $fila_archivo_config; ?>"/>
				</div>
				<div class="form-group">
					<label>Imagen:</label>
					<input class="form-control" id="inputImagen" name="inputImagen" placeholder="" value="<?php echo $fila_imagen; ?>"/>
				</div>
				<div class="form-group">
					<label>Titulo:</label>
					<input class="form-control" id="inputTitulo" name="inputTitulo" placeholder="" value="<?php echo $fila_titulo; ?>"/>
				</div>
				<div class="form-group">
					<label>Tipo:<?php //echo $fila_tipo; ?></label>
					<!-- <input class="form-control" id="inputTipo" name="inputTipo" placeholder="" value="<?php echo $fila_tipo; ?>" /> -->
						<select class="form-control form-select" name="inputTipo" id="inputTipo">
						<?php  echo $this->opciones_tipo($fila_tipo);  ?></select>
				</div>

				<div class="form-group">
					<label>Ruta Css:</label>
					<input class="form-control" id="inputUrlCss" name="inputUrlCss" placeholder="" value="<?php echo $fila_css; ?>"/>
				</div>
				<div class="form-group">
					<label>Clase:</label>
					<input class="form-control" id="inputClase" name="inputClase"  placeholder="" value="<?php echo $fila_clase; ?>" />
				</div>
				<div class="form-group">
					<label>Id Item:</label>
					<input class="form-control" id="inputItem" name="inputItem"  placeholder="" value="<?php echo $fila_item; ?>"/>
				</div>
				<div class="form-group">
					<label>Número/Items:</label>
					<input class="form-control" id="inputNumero" name="inputNumero"  placeholder="" value="<?php echo $fila_numero; ?>"/>
				</div>

				<!-- <div class="form-group">
					<label>Id categoria:</label>
					<input class="form-control" id="inputCat" name="inputCat"  placeholder="" value="<?php echo $fila_cat; ?>"/>
				</div> -->
				<?php
				$this->fmt->form->select_form_cat_id("Categoría:","inputCat",$fila_cat); //$label,$id,$id_item,$div_class

					$this->fmt->form->radio_activar_form($fila_activar);
					$this->fmt->form->btn_actualizar("form-editar",$this->id_mod,"modificar"); //$id_form,$id_mod,$tarea
				?>
					 <!-- <button type="submit" class="btn btn-info  btn-actualizar hvr-fade btn-lg color-bg-celecte-c btn-lg" name="btn-accion" id="btn-activar" value="actualizar"><i class="icn-sync" ></i> Actualizar</button> -->
				</div>
			</form>
		</div>
		<?php
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	function activar(){
		$this->fmt->class_modulo->activar_fila($this->nombre_tabla,$this->prefijo_tabla,$this->id_estado,$this->id_item);//$from,$prefijo,$estado,$id
		$this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
	}


	function ingresar(){

		if ($_POST["estado-mod"]=="activar"){ $activar=1; }else{ $activar=0;}

		$ingresar ="pub_nombre, pub_descripcion, pub_imagen, pub_titulo, pub_tipo, pub_archivo,pub_archivo_config, pub_css, pub_clase, pub_id_item, pub_numero, pub_id_cat, pub_activar";
		$valores  ="'".$_POST['inputNombre']."','".
									 $_POST['inputDescripcion']."','".
									 $_POST['inputImagen']."','".
									 $_POST['inputTitulo']."','".
									 $_POST['inputTipo']."','".
									 $_POST['inputArchivo']."','".
									 $_POST['inputArchivoConfig']."','".
									 $_POST['inputUrlCss']."','".
									 $_POST['inputClase']."','".
									 $_POST['inputItem']."','".
									 $_POST['inputNumero']."','".
									 $_POST['inputCat']."','".
									 $activar."'";

		$sql="insert into publicacion (".$ingresar.") values (".$valores.")";

		$this->fmt->query->consulta($sql);

	$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin funcion ingresar

	function modificar(){
		if ($_POST["estado-mod"]=="eliminar"){
		}else{
		 $sql="UPDATE publicacion SET
						pub_nombre='".$_POST['inputNombre']."',
						pub_descripcion='".$_POST['inputDescripcion']."',
						pub_imagen ='".$_POST['inputImagen']."',
						pub_titulo='".$_POST['inputTitulo']."',
						pub_tipo='".$_POST['inputTipo']."',
						pub_archivo='".$_POST['inputArchivo']."',
						pub_archivo_config='".$_POST['inputArchivoConfig']."',
						pub_css='".$_POST['inputUrlCss']."',
						pub_clase='".$_POST['inputClase']."',
						pub_id_item='".$_POST['inputItem']."',
						pub_numero='".$_POST['inputNumero']."',
						pub_id_cat='".$_POST['inputCat']."',
						pub_activar='".$_POST['inputActivar']."'
	          WHERE pub_id='".$_POST['inputId']."'";

			$this->fmt->query->consulta($sql);
		}
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}

	function eliminar(){
		$this->fmt->class_modulo->eliminar_fila($this->id_item,$this->nombre_tabla,$this->prefijo_tabla."id"); //$id,$from,$fila,$imprimir=0
		$this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
	}

	function tipo_publicacion($mod_tipo){

		switch ($mod_tipo) {
			case '0':
				$mod_tipo="0: Modulo Nucleo";
				break;
			case '1':
				$mod_tipo="1: Modulo de sitio";
				break;
			case '2':
				$mod_tipo="2: JSON / AJAX / Web Service";
				break;
			default:
				$mod_tipo="no definido";
				break;
		}
		return $mod_tipo;
	}

	function opciones_tipo($fila_tipo){
		$tipos = Array();
		for ($i = 0; $i <= 3; $i++) {
			$tipos [$i]= $this->tipo_publicacion($i);
		}

		for ($i = 0; $i <= 3; $i++) {
			$sel="";
			if ($fila_tipo==$i){
					$sel="selected";
			}
			$aux .='<option value="'.$i.'" '.$sel.' >'.$tipos[$i].'</option>';
		}
		return $aux;
	}


}
?>
