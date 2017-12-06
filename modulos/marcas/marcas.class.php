<?php
header("Content-Type: text/html;charset=utf-8");

class MARCA{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function MARCA($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
		?>
    <div class="body-modulo">
		  <div class="container">
				<?php
					$botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nueva noticia","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
					$this->fmt->class_pagina->head_modulo_inner("Lista de Sucursales", $botones);
				?>
				<div class="table-responsive">
		      <table class="table table-hover" id="table_id">
		        <thead>
		          <tr>
		            <th style="width:10%" >Logo</th>
		            <th>Nombre de la marca</th>
		            <th>Categoria/s</th>
		            <th class="estado">Publicación</th>
		            <th class="col-xl-offset-2 acciones">Acciones</th>
		          </tr>
		        </thead>
		        <tbody>
		          <?php
		            $sql="select mod_mar_id, mod_mar_nombre,mod_mar_logo,mod_mar_imagen,   mod_mar_activar from mod_marcas ORDER BY mod_mar_id desc";
		            $rs =$this->fmt->query->consulta($sql);
		            $num=$this->fmt->query->num_registros($rs);
		            if($num>0){
		            for($i=0;$i<$num;$i++){
		              $row=$this->fmt->query->obt_fila($rs);
									$fila_id = $row["mod_mar_id"];
									$fila_nombre =$row["mod_mar_nombre"];
									$fila_imagen= $row["mod_mar_imagen"];
									$fila_logo= $row["mod_mar_logo"];
									$fila_activar =$row["mod_mar_activar"];
									//if (empty($fila_dominio)){ $aux=_RUTA_WEB; } else { $aux = $this->fmt->categoria->traer_dominio_cat_id($fila_dominio); }
									$aux= _RUTA_IMAGES;
									$img=$this->fmt->archivos->convertir_url_mini( $fila_logo );
		            ?>
		            <tr class='row row-<?php echo $fila_id; ?>'>
		              <td><img class="img-responsive" width="60px" src="<?php echo $aux.$img; ?>" alt="" /></td>
		              <td> <?php echo $fila_nombre; ?> </td>
		              <td><?php	$this->traer_rel_cat_nombres($fila_id); ?> </td>
		              <td><?php
		              	$this->fmt->class_modulo->estado_publicacion($fila_activar,$this->id_mod,"", $fila_id );
		               ?></td>
		              <td>
						  <?php
							  echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar".$fila_id."-".$fila_url,"btn btn-accion btn-editar btn-menu-ajax ",$this->id_mod,"form_editar,".$fila_id);
						  ?>


		                <a  title="eliminar <?php echo $fila_id; ?>" type="button" nombre="<?php echo $fila_nombre; ?>" id="btn-m<?php echo $this->id_mod; ?>" id_mod="<?php echo $this->id_mod; ?>" vars="eliminar,<?php echo $fila_id; ?>" class="btn btn-fila-eliminar btn-accion "><i class="icn-trash"></i></a>


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
  	//$this->fmt->class_modulo->script_form("modulos/productos/marcas.adm.php",$this->id_mod);
		$this->fmt->class_modulo->script_accion_modulo();
		$this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","25",true);
  }
  function traer_rel_cat_nombres($fila_id){
	  $consulta = "SELECT cat_id, cat_nombre FROM categoria, mod_marcas_categorias WHERE mod_mar_mar_id='".$fila_id."' and cat_id = mod_mar_cat_id";
		$rs = $this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);
		if ($num>0){
			for ($i=0;$i<$num;$i++){
				$row=$this->fmt->query->obt_fila($rs);
				$fila_id=$row["cat_id"];
				$fila_nombre=$row["cat_nombre"];
				echo "- ".$fila_nombre." <br/>";
			}
		}
  }
  function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nueva Marca","","");

		$id_form="form-nuevo";
		// $this->fmt->form->finder("inputImagen",$this->id_mod,"","individual","imagenes");

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"");

		  $this->fmt->form->hidden_modulo($this->id_mod,"modificar");
		  $this->fmt->form->input_form('Nombre de la marca:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

	    $this->fmt->form->textarea_form('Detalles:','inputDetalles','','','','','3','','');
	    $this->fmt->form->input_form('Ruta amigable:','inputRutaAmigable','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
			
			$this->fmt->form->imagen_unica_form("inputLogo","","","form-row","Logotipo:");  //$id,$valor,$titulo="Imagen principal",$class_div,$label_form=""
			$this->fmt->form->imagen_unica_form("inputImagen","","","form-row","Imagen relacionada:");

	    $usuario = $this->fmt->sesion->get_variable('usu_id');
			$usuario_n = $this->fmt->sesion->get_variable('usu_nombre');

			$this->fmt->form->categoria_form('Categoria','inputCat',"0","","",""); //$label,$id,$cat_raiz,$cat_valor,$class,$class_div
	    $this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');
		  $this->fmt->form->input_hidden_form("inputUsuario",$usuario);
			$this->fmt->form->input_form('Orden:','inputOrden','','0','','','');

			$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");

		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		 
		?>
		<script>
			$(document).ready(function () {
					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
					$("#inputNombre").keyup(function () {
							var value = $(this).val();
							//$("#inputNombreAmigable").val();
							$.ajax({
									url: ruta,
									type: "POST",
									data: { inputRuta:value, ajax:"ajax-ruta-amigable" },
									success: function(datos){
										$("#inputRutaAmigable").val(datos);
									}
							});
					});
			});
		</script>
		<?php
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
  }

  function ingresar($modo){
  	if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
		$ingresar ="mod_mar_nombre,
	                mod_mar_ruta_amigable,
	                mod_mar_logo,
	                mod_mar_imagen,
	                mod_mar_usuario,
	                mod_mar_detalle,
	                mod_mar_id_dominio,
	                mod_mar_activar";
		$valores  ="'".$_POST['inputNombre']."','".
					$_POST['inputRutaAmigable']."','".
					$_POST['inputLogo']."','".
					$_POST['inputImagen']."','".
					$_POST['inputUsuario']."','".
					$_POST['inputDetalles']."','".
					$_POST['inputDominio']."','".
					$activar."'";
		$sql="insert into mod_marcas (".$ingresar.") values (".$valores.")";
		$this->fmt->query->consulta($sql);

		$sql="select max(mod_mar_id) as id from mod_marcas";
		$rs= $this->fmt->query->consulta($sql);
		$fila = $this->fmt->query->obt_fila($rs);
		$id = $fila ["id"];
		$ingresar1 ="mod_mar_mar_id, mod_mar_cat_id, mod_mar_cat_orden";
		$valor_cat= $_POST['inputCat'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$id."','".$valor_cat[$i]."','".$_POST["inputOrden"]."'";
			$sql1="insert into mod_marcas_categorias (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}
		if (empty($modo)){
			$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
		}else if ($modo=="modal"){
			echo $this->fmt->mensaje->documento_subido();
			echo "<div class='otro-nuevo'><i class='icn-plus'></i> <a href='marcas.adm.php?tarea=form_nuevo' > Agregar otra nueva marca. </a></div>";
		}

  }

  function traer_marcas($id_marca,$div_class,$div_item){
		$consulta = "SELECT mod_mar_id, mod_mar_nombre,mod_mar_logo,mod_mar_ruta_amigable from mod_marcas WHERE mod_mar_activar=1 ORDER BY mod_mar_orden desc";
		$rs =$this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);
		$aux="<ul class='list-marcas $div_class' id='$id_marca'>";
		if($num>0){
			for($i=0;$i<$num;$i++){
				$row=$this->fmt->query->obt_fila($rs);
				$row_id = $row["mod_mar_id"];
				$row_nombre = $row["mod_mar_nombre"];
				$row_logo = _RUTA_IMAGES.$this->fmt->archivos->convertir_url_thumb($row["mod_mar_logo"]);
				$row_ra = $row["mod_mar_ruta_amigable"];
				$aux .="<li class='item-marca $div_item item-marca-$i' id='item-marca-$row_id'><a href='"._RUTA_WEB.$row_ra."' style='background:url($row_logo) no-repeat center center'><span class='item-nombre'>$row_nombre</span></a></li>";
			}
		}
		$aux .= "</ul>";
		$this->fmt->query->liberar_consulta();
		return $aux;
  }

  function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Marca","","");
	  $id = $this->id_item;
		$id_form="form-editar";
	  $consulta= "SELECT * FROM mod_marcas WHERE mod_mar_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $fila=$this->fmt->query->obt_fila($rs);

		//$this->fmt->form->finder("inputImagen",$this->id_mod,$fila["cat_imagen"],"individual","imagenes");

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"");

	  $this->fmt->form->hidden_modulo($this->id_mod,"modificar");
	  $this->fmt->form->input_form('Nombre de la marca:','inputNombre','',$fila['mod_mar_nombre'],'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputId",$id);
    $this->fmt->form->textarea_form('Detalles:','inputDetalles','',$fila['mod_mar_detalle'],'','','3','','');
    $this->fmt->form->input_form('Ruta amigable:','inputRutaAmigable','',$fila['mod_mar_ruta_amigable'],'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		// if ($fila["cat_imagen"]){ $text="Actualizar"; $aux=_RUTA_WEB; }else{ $text="Cargar archivo"; $aux=""; }
		// $this->fmt->form->imagen_form("Imagen:",$text,"inputImagen",$fila["mod_mar_id"],$aux.$fila["mod_mar_imagen"]);

		$this->fmt->form->imagen_unica_form("inputLogo",$fila["mod_mar_logo"],"","form-row","Logotipo:");  //$id,$valor,$titulo="Imagen principal",$class_div,$label_form=""
		$this->fmt->form->imagen_unica_form("inputImagen",$fila["mod_mar_imagen"],"","form-row","Imagen relacionada:");

    $usuario = $this->fmt->sesion->get_variable('usu_id');
		$usuario_n = $this->fmt->sesion->get_variable('usu_nombre');
		$cats_id = $this->fmt->categoria->traer_rel_cat_id($id,'mod_marcas_categorias','mod_mar_cat_id','mod_mar_mar_id'); //$fila_id,$from,$prefijo_cat,$prefijo_rel
		$this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"",""); //$label,$id,$cat_raiz,$cat_valor,$class,$class_div
    $this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');
	  $this->fmt->form->input_hidden_form("inputUsuario",$usuario);
		$this->fmt->form->input_form('Orden:','inputOrden','','0','','','');
		$this->fmt->form->radio_activar_form($fila['mod_suc_activar']);
		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");

		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
    $this->fmt->class_modulo->modal_editor_texto("inputDetalles");
		$this->fmt->class_modulo->modal_script($this->id_mod);
		$this->fmt->finder->finder_window();
		?>
		<script>
			$(document).ready(function () {
					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
					$("#inputNombre").keyup(function () {
							var value = $(this).val();
							//$("#inputNombreAmigable").val();
							$.ajax({
									url: ruta,
									type: "POST",
									data: { inputRuta:value, ajax:"ajax-ruta-amigable" },
									success: function(datos){
										$("#inputRutaAmigable").val(datos);
									}
							});
					});
			});
		</script>
		<?php
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
  }

  function modificar(){

	   	$imagen="";
	  	// if($_FILES["inputArchivos"]["name"]!=""){
		  // 	$imagen = "mod_mar_imagen ='".$_POST['inputImagen']."',mod_mar_id_dominio='".$_POST['inputDominio']."',";
	  	// }

		$sql="UPDATE mod_marcas SET
						mod_mar_nombre='".$_POST['inputNombre']."',
						mod_mar_ruta_amigable ='".$_POST['inputRutaAmigable']."',
						mod_mar_logo ='".$_POST['inputLogo']."',
						mod_mar_imagen ='".$_POST['inputImagen']."',
						mod_mar_usuario='".$_POST['inputUsuario']."',
						mod_mar_detalle='".$_POST['inputDetalles']."'


						WHERE mod_mar_id='".$_POST['inputId']."'";
			//echo $sql;
			$this->fmt->query->consulta($sql);

			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"mod_marcas_categorias","mod_mar_mar_id");  //$valor,$from,$fila

			$ingresar1 ="mod_mar_mar_id, mod_mar_cat_id, mod_mar_cat_orden";
			$valor_cat= $_POST['inputCat'];
			$num=count( $valor_cat );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."','".$_POST["inputOrden"]."'";
				$sql1="insert into mod_marcas_categorias (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}


		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }
  function activar(){
	  $this->fmt->class_modulo->activar_get_id("mod_marcas","mod_mar_",$this->id_estado,$this->id_item);
	  $this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
  }

  function eliminar(){
  		$this->fmt->class_modulo->eliminar_get_id("mod_productos_categorias","mod_mar_mar_",$this->id_item);
  		$this->fmt->class_modulo->eliminar_get_id("mod_marcas","mod_mar_",$this->id_item);
  		$this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
  }
}
