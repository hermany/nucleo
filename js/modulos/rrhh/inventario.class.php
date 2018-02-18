<?php
header("Content-Type: text/html;charset=utf-8");

class INVENTARIO{

	var $fmt;
	var $id_mod;
	var $ruta_modulo;
	var $nombre_modulo;

	function INVENTARIO($fmt){
		$this->fmt = $fmt;
		$this->fmt->get->validar_get($_GET['id_mod']);
		$this->id_mod=$_GET['id_mod'];
	}

	function busqueda(){
		$id_cat=0;
	if(isset($_GET["cat_id"]))
		//$id_cat=$_GET["cat_id"];

   $this->fmt->class_pagina->crear_head( $this->id_mod, ""); // bd, id modulo, botones

    $this->fmt->class_modulo->script_form("modulos/rrhh/inventario.adm.php",$this->id_mod,"asc","0","25",true);

    $id_rol = $this->fmt->sesion->get_variable("usu_rol");

	if (isset($_GET["p"])){
			$clase_activa = $_GET["p"];
		}else{
			$clase_activa = "categoria";
		}
    ?>
    <ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="<?php if ($clase_activa=="categoria"){ echo "active"; }?>"><a href="#categoria" aria-controls="categoria" role="tab" data-toggle="tab"><i class="icn-category-o color-text-azul-b"></i> Categorias</a></li>
		<li role="presentation" class="<?php if ($clase_activa=="cargos"){ echo "active"; }?>"><a href="#items" aria-controls="items" role="tab" data-toggle="tab"><i class="icn-credential-o color-text-naranja"></i> Items</a></li>
	</ul>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane <?php if ($clase_activa=="categoria"){ echo "active"; }?>" id="categoria">
			<label><h4>Categoria</h4></label>
			<?php
			$this->fmt->categoria->arbol_editable_mod('categoria_almacen','cat_alm_','cat_alm_id_padre=0','modulos/rrhh/inventario.adm.php?tarea=form_nuevo_cat&cat_id=0&id_mod='.$this->id_mod,'box-categoria');
			?>
			<script>
				$(".btn-activar-i").click(function(e){
					var cat = $( this ).attr("cat");
				    var estado = $( this ).attr("estado");
				    url="inventario.adm.php?tarea=activar_cat&id="+cat+"&estado="+estado+"&id_mod=<?php echo $this->id_mod; ?>";
				    window.location=(url);
				  });
				  $(".btn-editar-i").click(function(e){
				     var cat = $( this ).attr("cat");
				     url="inventario.adm.php?tarea=form_editar_cat&id="+cat+"&id_mod=<?php echo $this->id_mod; ?>";
				        //alert(url);
				      window.location=(url);
				  });
				  $(".btn-nuevo-i").click(function(e){
					var cat = $( this ).attr("cat");
					url="inventario.adm.php?tarea=form_nuevo_cat&cat_id="+cat+"&id_mod=<?php echo $this->id_mod; ?>";
					//alert(url);
					window.location=(url);
					});
			</script>
		</div>
		<div role="tabpanel" class="tab-pane <?php if ($clase_activa=="items"){ echo "active"; }?>" id="items">
			<label><h4>Item</h4></label>
	          <a href="inventario.adm.php?tarea=form_nuevo&id_mod=<?php echo $this->id_mod; ?>" class='btn btn-primary pull-right'><i class="icn-plus"></i> Nuevo Item</a>
			   <div class="table-responsive">
				   <table class="table table-hover" id="table_id">
					   <thead>
						   <tr>
							   <th style="width:10%" >#</th>
							   <th>Nombre del Item</th>
							   <th>Categoria</th>
					            <th class="estado">Publicación</th>
					            <th class="col-xl-offset-2 acciones">Acciones</th>
					        </tr>
					   </thead>
					   <tbody>
          <?php
          	if($id_rol==1)
            	$sql="select alm_id, alm_nombre, alm_activar, cat_alm_nombre from almacen, categoria_almacen where alm_id_categoria=cat_alm_id ORDER BY alm_id desc";
            else{


            }

            $rs =$this->fmt->query->consulta($sql);
            $num=$this->fmt->query->num_registros($rs);
            if($num>0){
	            for($i=0;$i<$num;$i++){
	              list($fila_id,$fila_nombre,$fila_activar,$fila_categoria)=$this->fmt->query->obt_fila($rs);
					$url ="inventario.adm.php?tarea=form_editar&id=".$fila_id."&id_mod=".$this->id_mod;
            ?>
				            <tr>
				              <td><?php echo $fila_id; ?></td>
				              <td><strong><a href="<?php echo $url; ?>" ><?php echo $fila_nombre; ?></a></strong></td>
				              <td><?php echo $fila_categoria; ?></td>
				              <td><?php $this->fmt->class_modulo->estado_activar($fila_activar,"modulos/rrhh/inventario.adm.php?tarea=activar&id_mod=$this->id_mod", "","", $fila_id ); ?></td>
				              <td>

				                <a  id="btn-editar-modulo" class="btn btn-accion btn-editar" href="<?php echo $url; ?>" title="Editar <?php echo $fila_id."-".$fila_nombre; ?>" ><i class="icn-pencil"></i></a>
				                <?php
					                $this->fmt->form->botones_acciones("btn-eliminar","btn btn-eliminar btn-accion","","Eliminar -".$fila_id,"icn-trash","eliminar_item&id_mod=$this->id_mod",$fila_nombre,$fila_id);
				                ?>

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
		//$this->fmt->class_modulo->script_form($this->ruta_modulo,"");

  }

	function busqueda_seleccion($modo,$valor){
		$this->fmt->form->head_modal('Busqueda Inventario',$modo);  //$nom,$archivo,$id_mod,$botones,$id_form,$class,$modo)
		$this->fmt->form->head_table('table_id_modal_aux');
		$this->fmt->form->thead_table('Nombre:Categoria:Acciones');
		$this->fmt->form->tbody_table_open();

			$sql="select alm_id, alm_nombre, cat_alm_nombre from almacen, categoria_almacen where alm_id_categoria=cat_alm_id and alm_activar=1 ORDER BY alm_id desc";
		$rs =$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
			for($i=0;$i<$num;$i++){

				list($fila_id,$fila_nombre,$fila_cat_nombre)=$this->fmt->query->obt_fila($rs);
			$class_a ='';
			$class_do ='';
				if (!empty($valor)){
				$num_v = count($valor);

				for ($j=0; $j<$num_v;$j++){
					if ( $fila_id ==$valor[$j]){
						$class_a ="on";
						$class_do ="on";
					}
				}
			}



				//var_dump($fila);
					echo "<tr>";
				echo '<td class="fila-url"><strong>'.$fila_nombre.'</strong></td>';
				echo '<td class="fila-url"><strong>'.$fila_cat_nombre.'</strong></td>';
				echo "<td class='acciones' id='dp-".$fila_id."'><a class='btn btn-agregar-ped ".$class_a."' value='".$fila_id."' id='bp-".$fila_id."' nombre='".$fila_nombre."' ><i class='icn-plus'></i> Agregar</a> <span class='agregado btp-".$fila_id." ".$class_do."'>Agregado</span></td>";
				echo "</tr>";
				}
		}

		$this->fmt->form->tbody_table_close();
		$this->fmt->form->footer_table();
		$this->fmt->form->footer_page($modo);
	}

  function form_nuevo_cat(){
  	$id_cat=$_GET["cat_id"];
    $botones .= $this->fmt->class_pagina->crear_btn("inventario.adm.php?tarea=busqueda&id_mod=$this->id_mod&cat_id=$id_cat","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $this->fmt->class_pagina->crear_head_mod( "", "Nueva Categoria",'',$botones,'col-xs-6 col-xs-offset-4');
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" action="inventario.adm.php?tarea=ingresar_cat"  enctype="multipart/form-data" method="POST" id="form_nuevo">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_hidden_form("inputIdPadre",$id_cat);
        $this->fmt->form->input_hidden_form("inputMod",$this->id_mod);
        $this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','','3','','');
		$usuario = $this->fmt->sesion->get_variable('usu_id');
		$usuario_n =  $this->fmt->usuario->nombre_usuario( $usuario );
		$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
        $this->fmt->form->botones_nuevo();
        ?>
      </form>
    </div>
    <?php
    $this->fmt->class_modulo->script_form($this->ruta_modulo,"");
    $this->fmt->form->footer_page();
  }

  function form_nuevo(){
    $botones .= $this->fmt->class_pagina->crear_btn("inventario.adm.php?tarea=busqueda&id_mod=$this->id_mod&p=items","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $this->fmt->class_pagina->crear_head_mod( "", "Nuevo Item",'',$botones,'col-xs-6 col-xs-offset-4');
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" action="inventario.adm.php?tarea=ingresar"  enctype="multipart/form-data" method="POST" id="form_nuevo">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_hidden_form("inputMod",$this->id_mod);
        $this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','','3','','');
		?>
		<div class="form-group">
			<label>Imagen (560x400px):</label>
			<div class="panel panel-default" >
				<div class="panel-body">
					<?php
					$this->fmt->form->file_form_nuevo_croppie_thumb('Cargar Archivo (max 8MB)','','form_nuevo','form-file','','box-file-form','archivos/productos','350x350'); //$nom,$ruta,$id_form,$class,$class_div,$id_div,$directorio_p,$sizethumb,$imagen
					?>

				</div>
			</div>
		</div>
		<?php
       	$this->fmt->form->input_form('Cantidad Minima:','inputCantMin','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
       	$this->fmt->form->input_form('Costo Unitario:','inputCostoUnit','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

       	$this->fmt->form->select_form('Categoria:','inputCategoria','cat_alm_','categoria_almacen'); //$label,$id,$prefijo,$from,$id_select,$id_disabled

	   	$usuario = $this->fmt->sesion->get_variable('usu_id');
		$usuario_n =  $this->fmt->usuario->nombre_usuario( $usuario );
		$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputUsuario",$usuario);

        $this->fmt->form->botones_nuevo();
        ?>
      </form>
    </div>
    <?php
    $this->fmt->class_modulo->script_form($this->ruta_modulo,"");
    $this->fmt->form->footer_page();
  }



  function form_editar(){
    $botones .= $this->fmt->class_pagina->crear_btn("inventario.adm.php?tarea=busqueda&id_mod=$this->id_mod&p=items","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $this->fmt->class_pagina->crear_head_mod( "", "Editar Item",'',$botones,'col-xs-6 col-xs-offset-4');
		$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $_GET['id'];
		$sql="SELECT * from almacen where alm_id='".$id."'";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					list($fila_id,$fila_nombre,$fila_descripcion,$fila_cant_min,$fila_images,$fila_costo,$fila_activar,$fila_usuario,$fila_categoria)=$this->fmt->query->obt_fila($rs);
				}
			}
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" action="inventario.adm.php?tarea=modificar"  enctype="multipart/form-data" method="POST" id="form_editar">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre:','inputNombre','',$fila_nombre,'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputId",$fila_id);
		$this->fmt->form->input_hidden_form("inputMod",$this->id_mod);
		$this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$fila_descripcion,'','','3','','');
				?>
		<div class="form-group">
			<label>Imagen (560x400px):</label>
			<div class="panel panel-default" >
				<div class="panel-body">
					<?php
					$this->fmt->form->file_form_nuevo_croppie_thumb('Cargar Archivo (max 8MB)','','form_editar','form-file','','box-file-form','archivos/productos','350x350',$fila_images); //$nom,$ruta,$id_form,$class,$class_div,$id_div,$directorio_p,$sizethumb,$imagen
					?>

				</div>
			</div>
		</div>
		<?php
       	$this->fmt->form->input_form('Cantidad Minima:','inputCantMin','',$fila_cant_min,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
       	$this->fmt->form->input_form('Costo Unitario:','inputCostoUnit','',$fila_costo,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

       	$this->fmt->form->select_form('Categoria:','inputCategoria','cat_alm_','categoria_almacen', $fila_categoria); //$label,$id,$prefijo,$from,$id_select,$id_disabled

	   	$usuario = $this->fmt->sesion->get_variable('usu_id');
		$usuario_n =  $this->fmt->usuario->nombre_usuario( $usuario );
		$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'',$fila_costo,'');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputUsuario",$usuario);

		$this->fmt->form->radio_activar_form($fila_activar);
		$this->fmt->form->botones_editar($fila_id,$fila_nombre,'cargo','eliminar_cargo');
        ?>
      </div>
    </div>
    <?php
    $this->fmt->class_modulo->script_form("modulos/rrhh/inventario.adm.php","");
    $this->fmt->form->footer_page();
  }

  function form_editar_cat(){

		$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $_GET['id'];
		$sql="SELECT * from categoria_almacen where cat_alm_id='".$id."'";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					list($fila_id,$fila_nombre,$fila_descripcion,$fila_activar,$fila_padre,$fila_usuario)=$this->fmt->query->obt_fila($rs);
				}
			}
		$botones .= $this->fmt->class_pagina->crear_btn("inventario.adm.php?tarea=busqueda&id_mod=$this->id_mod&cat_id=".$fila_padre,"btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $this->fmt->class_pagina->crear_head_mod( "", "Editar Categoria",'',$botones,'col-xs-6 col-xs-offset-4');
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" action="inventario.adm.php?tarea=modificar_cat"  enctype="multipart/form-data" method="POST" id="form_editar">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre:','inputNombre','',$fila_nombre,'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputId",$fila_id);
		 $this->fmt->form->input_hidden_form("inputIdPadre",$fila_padre);
		 $this->fmt->form->input_hidden_form("inputMod",$this->id_mod);
		$this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$fila_descripcion,'','','3','','');
		$usuario = $this->fmt->sesion->get_variable('usu_id');
		$usuario_n =  $this->fmt->usuario->nombre_usuario( $usuario );
		$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
		$this->fmt->form->radio_activar_form($fila_activar);
		$this->fmt->form->botones_editar($fila_id,$fila_nombre,'categoria_almacen','eliminar_cat');
        ?>
      </div>
    </div>
    <?php
    $this->fmt->class_modulo->script_form("modulos/rrhh/inventario.adm.php","");
    $this->fmt->form->footer_page();
  }




  function ingresar(){
    if ($_POST["btn-accion"]=="activar"){
      $activar=1;
    }
    if ($_POST["btn-accion"]=="guardar"){
      $activar=0;
    }
    $ingresar ="alm_nombre,
				alm_descripcion,
				alm_cant_min,
				alm_imagen,
				alm_costo_unit,
				alm_usuario,
				alm_id_categoria,
				alm_activar";
    $valores_post  ="inputNombre,
				inputDescripcion,
				inputCantMin,
				inputUrl,
				inputCostoUnit,
				inputUsuario,
				inputCategoria,
				inputActivar=".$activar;

    $this->fmt->class_modulo->ingresar_tabla('almacen',$ingresar,$valores_post);
		//$from,$filas,$valores_post
    header("location: inventario.adm.php?tarea=busqueda&id_mod=".$_POST["inputMod"]."&p=items");
  }

  function ingresar_cat(){
	if ($_POST["btn-accion"]=="activar"){
      $activar=1;
    }
    if ($_POST["btn-accion"]=="guardar"){
      $activar=0;
    }

    $ingresar ="cat_alm_nombre,
				cat_alm_descripcion,
				cat_alm_id_padre,
				cat_alm_usuario,
				cat_alm_activar";
    $valores_post  ="inputNombre,
				inputDescripcion,
				inputIdPadre,
				inputUsuario,inputActivar=".$activar;

    $this->fmt->class_modulo->ingresar_tabla('categoria_almacen',$ingresar,$valores_post);
		//$from,$filas,$valores_post
    header("location: inventario.adm.php?tarea=busqueda&id_mod=".$_POST["inputMod"]."&cat_id=".$_POST["inputIdPadre"]);
  }



	function modificar(){
		if ($_POST["btn-accion"]=="eliminar"){}
		if ($_POST["btn-accion"]=="actualizar"){

			$filas='alm_id,
					alm_nombre,
					alm_descripcion,
					alm_cant_min,
					alm_imagen,
					alm_costo_unit,
					alm_usuario,
					alm_id_categoria,
					alm_activar';
			$valores_post='inputId,
					inputNombre,
					inputDescripcion,
					inputCantMin,
					inputUrl,
					inputCostoUnit,
					inputUsuario,
					inputCategoria,
					inputActivar';
			$this->fmt->class_modulo->actualizar_tabla('almacen',$filas,$valores_post); //$from,$filas,$valores_post
			header("location: inventario.adm.php?tarea=busqueda&id_mod=".$_POST["inputMod"]."&p=items");
		}
	}

	function modificar_cat(){

		if ($_POST["btn-accion"]=="eliminar"){}
		if ($_POST["btn-accion"]=="actualizar"){

			$filas='cat_alm_id,
					cat_alm_nombre,
					cat_alm_descripcion,
					cat_alm_id_padre,
					cat_alm_usuario,
					cat_alm_activar';
			$valores_post='inputId,
					inputNombre,
					inputDescripcion,
					inputIdPadre,
					inputUsuario,
					inputActivar';
		$this->fmt->class_modulo->actualizar_tabla('categoria_almacen',$filas,$valores_post); //$from,$filas,$valores_post
		 header("location: inventario.adm.php?tarea=busqueda&id_mod=".$_POST["inputMod"]."&cat_id=".$_POST["inputIdPadre"]);
		}
	}



	function eliminar(){
      $this->fmt->class_modulo->eliminar_get_id("categoria_almacen","cat_alm_");
	  header("location: inventario.adm.php?tarea=busqueda&id_mod=".$_GET["id_mod"]);
    }

    function eliminar_item(){
      $this->fmt->class_modulo->eliminar_get_id("almacen","alm_");
      header("location: inventario.adm.php?tarea=busqueda&id_mod=".$_GET["id_mod"]."&p=items");
    }

  function activar(){
      $this->fmt->class_modulo->activar_get_id("almacen","alm_");
       header("location: inventario.adm.php?tarea=busqueda&id_mod=".$_GET["id_mod"]."&p=items");
  }

  function activar_cat(){
      $this->fmt->class_modulo->activar_get_id("categoria_almacen","cat_alm_");
       header("location: inventario.adm.php?tarea=busqueda&id_mod=".$_GET["id_mod"]);
  }

  function activar_division(){
      $this->fmt->class_modulo->activar_get_id("mod_kardex_division","mod_kdx_div_");
      header("location: inventario.adm.php?tarea=busqueda&p=divisiones");
  }



}
