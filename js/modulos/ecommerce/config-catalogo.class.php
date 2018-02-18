<?php
header("Content-Type: text/html;charset=utf-8");

class CONFIG_CATALOGO{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function CONFIG_CATALOGO($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){
    $this->fmt->class_pagina->crear_head( $this->id_mod, ""); // id modulo, botones
    ?>
    <div class="body-modulo container-fluid">
			<div class="container">
				<?php $this->fmt->class_pagina->head_modulo_inner("Estructura del Catálogo interno", ""); ?>
				<div class='arbol'>
				<?php
					$consulta = "SELECT mod_catg_id, mod_catg_nombre,mod_catg_ruta_amigable FROM mod_catalogo WHERE mod_catg_id_padre='0' ORDER BY mod_catg_orden asc";
					$rs = $this->fmt->query->consulta($consulta,__METHOD__);
					$num=$this->fmt->query->num_registros($rs);

					if($num>0){
						echo "<div class='arbol-nuevo'><a class='btn-nuevo-i' cat='0'><i class='icn-plus'></i> nuevo</a></div>";
						for($i=0;$i<$num;$i++){
							list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
							if ($i==$num-1) { $aux = 'icn-point-4'; } else { $aux = 'icn-point-1'; }
							echo "<div class='nodo' id='nodo-$fila_id' ><i class='".$aux." i-nodo'></i> ".$fila_nombre;
							$this->fmt->categoria->accion($fila_id,'mod_catalogo','mod_catg_',$this->id_mod);
							echo "</div>";
							if ($this->fmt->categoria->tiene_hijos($fila_id,'mod_catalogo','mod_catg_')){
							$this->fmt->categoria->hijos($fila_id,'0','mod_catalogo','mod_catg_',$this->id_mod);
							}
						}
					}else{
						echo "<div class='arbol-nuevo'><a class='btn-nuevo-i' cat='0'><i class='icn-plus'></i> nuevo</a></div>";
					}
				?>
				</div>
	  	</div>
    </div>
    <style>
      .btn-contenedores{ display:none; }
    </style>
    <script>
      $(".btn-activar-i").click(function(e){
				var cat = $( this ).attr("cat");
				var estado = $( this ).attr("estado");
				var id_mod = "<?php echo $this->id_mod; ?>";
				var variables =  $( this ).attr("vars");
				var ruta = "ajax-activar";
				var datos = {ajax:ruta, inputIdMod:id_mod , inputVars : variables };
				accion_modulo(datos);
      });
			$(".btn-editar-i").click(function(e){
				var id_mod = "<?php echo $this->id_mod; ?>";
				var cat = $( this ).attr("cat");
				var variables = "form_editar,"+cat;
				var ruta = "ajax-adm";
				var datos = {ajax:ruta, inputIdMod:id_mod , inputVars : variables };
				abrir_modulo(datos);
			});
			$(".btn-nuevo-i").click(function(e){
				var id_mod = "<?php echo $this->id_mod; ?>";
				var cat = $( this ).attr("cat");
				var variables = "form_nuevo,"+cat;
				var ruta = "ajax-adm";
				var datos = {ajax:ruta, inputIdMod:id_mod , inputVars : variables };
				abrir_modulo(datos);
			});
			$(".btn-eliminar-i").click(function(e){
					e.preventDefault();
					var nom= $(this).attr('nombre');
					var variables = $(this).attr('vars');
					var id_mod= $(this).attr('id_mod');

					$(".modal-form").addClass("on");
					$(".body-page").css("overflow-y","hidden");
					$(".modal .modal-inner").addClass("mensaje-eliminar");
					$(".modal .modal-inner").html('<div class="modal-title"></div><div class="modal-body"> <i class="icn icn-trash"></i> <label>"'+nom+'" se eliminará, estas seguro de eliminarlo. </label><span>No podrás deshacer esta acción.<span> </div><div class="modal-footer"><a class="btn btn-cancelar btn-small btn-full">Cancelar</a><a class="btn btn-info btn-m-eliminar btn-small" id_mod="'+id_mod+'" vars="'+variables+'" >Eliminar</a></div>');

					$(".btn-cancelar").on("click",function(e){
						e.preventDefault();
						$(".modal").removeClass("on");
						$(".modal .modal-inner").removeClass("mensaje-eliminar");
						$(".modal .modal-inner").html(" ");
						$(".content-page").css("overflow-y","auto");
					});

					$(".btn-m-eliminar").on("click",function(e){
						e.preventDefault();
						var variables = $(this).attr('vars');
						var id_mod= $(this).attr('id_mod');
						var ruta='ajax-eliminar';
						var datos= { ajax:ruta,inputIdMod:id_mod, inputVars:variables };
						//alert(variables);
						accion_modulo(datos);
					});

				});
			function abrir_modulo(datos){
				$(".modal-form").addClass("on");
				$(".modal-form").addClass("<?php echo $url_a; ?>");
				$(".body-page").css("overflow-y","hidden");
				$.ajax({
					url:"<?php echo _RUTA_WEB; ?>ajax.php",
					type:"post",
					data:datos,
					success: function(msg){

						$("#modal .modal-inner").html(msg);

					},
					complete : function() {
						$('.preloader-page').fadeOut('slow');
					}
				});
			}
			function accion_modulo(datos){
				//console.log(datos);
				$.ajax({
					url:"<?php echo _RUTA_WEB; ?>ajax.php",
					type:"post",
					async: true,
					data:datos,
					success: function(msg){
						console.log("pag:" + msg );
						var variables = msg;
						var cadena = variables.split(':');
						var accion = cadena[0];
						var id_item = cadena[1];
						var estado = cadena[2];
						var id_mod = cadena[3];
						switch ( accion ){
							case 'activar':
								//console.log(id_mod+"-"+id_item+"-"+estado);
								$("#btn-p-"+id_mod+"-"+id_item+" i").removeClass();
								$("#btn-pi-"+id_item).removeClass();
								if(estado==1){
									$("#btn-pi-"+id_item).addClass("icn-eye-open");
									$("#btn-pi-"+id_item).attr("vars","activar,"+id_item+",0");
								}else{
									$("#btn-pi-"+id_item).addClass("icn-eye-close");
									$("#btn-pi-"+id_item).attr("vars","activar,"+id_item+",1");
								}
								$(".content-page").css("overflow-y","auto");
							break;
							case 'eliminar':
								console.log(id_item);
								$(".modal").removeClass("on");
								$(".modal .modal-inner").removeClass("mensaje-eliminar");
								$(".modal .modal-inner").html("");
								$(".row-"+id_item).addClass("removiendo");
								$("#nodo-"+id_item).addClass("removiendo");
								setTimeout(function() {
									$(".row-"+id_item).remove();
									$("#nodo-"+id_item).remove();
									$(".content-page").css("overflow-y","auto");
								}, 1500 );

							break;
							default:
							alert("no hay una acción determinada, revisar error base de datos");
						}
					}
				});
			}
    </script>
    <style>
      .btn-contenedores{ display:none; }
    </style>
    <?php
		//$this->fmt->class_modulo->script_form("modulos/productos/config-catalogo.adm.php",$this->id_mod,"desc");
		$this->fmt->class_modulo->script_accion_modulo();
  }

  function rel_id_cat($id_cat_prod){
		$consulta = "SELECT mod_catg_cat_cat_id FROM mod_productos_catg_cat WHERE mod_catg_cat_catg_id='".$id_cat_prod."'";
		$rs = $this->fmt->query->consulta($consulta,__METHOD__);
		$num=$this->fmt->query->num_registros($rs);
		if ($num>0){
			for ($i=0;$i<$num;$i++){
				list($fila_id)=$this->fmt->query->obt_fila($rs);
				$valor[$i]=$fila_id;
			}
		}
		return $valor;
  }

  function tiene_hijos_cat_config($cat){
    $consulta = "SELECT mod_catg_id  FROM mod_catalogo WHERE mod_catg_id_padre='$cat'";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      return true;
    }else{
      return false;
    }
  }

  function traer_opciones_cat_config($cat){
    $consulta = "SELECT mod_catg_id, mod_catg_nombre FROM mod_catalogo WHERE mod_catg_id_padre='0'  ORDER BY mod_catg_orden asc";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    $aux="";
    if($cat==0)
    	$aux="selected";
    echo "<option class='' ".$aux." value='0'>Raiz (0)</option>";
      if($num>0){
      for($i=0;$i<$num;$i++){
        list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
        if ($fila_id==$cat){ $aux="selected"; }else{ $aux=""; }
        echo "<option class='' value='$fila_id' $aux $aux1 > ".$fila_nombre;
        echo "</option>";
        if ($this->tiene_hijos_cat_config($fila_id)){
          $this->hijos_opciones_cat_config($fila_id,'1',$id_padre);
        }
      }
    }
  }

  function hijos_opciones_cat_config($cat,$nivel,$id_padre){
    $consulta = "SELECT mod_catg_id, mod_catg_nombre FROM mod_catalogo WHERE mod_catg_id_padre='$cat' ORDER BY mod_catg_orden asc";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      for($i=0;$i<$num;$i++){
        list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
        $valor_n="";
        for ($j=0;$j<$nivel;$j++){
          $valor_n .='--';
        }
        if ($fila_id==$id_padre){ $aux="selected"; }else{ $aux=""; }

        echo "<option class='' value='$fila_id' $aux  $aux1 > ".$valor_n.$fila_nombre;
        echo "</option>";
        if ( $this->tiene_hijos_cat_config($fila_id) ){
          $nivel++;
          $this->hijos_opciones_cat_config($fila_id, $nivel);
        }
      }
    }
  }

  function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Catalogo","","");
		$id_form="form-nuevo";
		$id_padre=$this->id_item;

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"");

		$this->fmt->form->input_form('Nombre del catálogo:','inputNombre','','','requerido requerido-texto input-lg','','');
		$this->fmt->form->ruta_amigable_form("inputNombre","","","inputRutaamigable","","input-lg"); //$id,$ruta,$valor,$form
		$this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','','3','','');
		?>
			<div class="form-group">
				<label>Configuración padre:</label>
				<select class="form-control" id="inputPadre" name="inputPadre">
					<?php $this->traer_opciones_cat_config($id_padre); ?>
				</select>
			</div>
		<?php
		$this->fmt->form->categoria_form('Categoria','inputCat',"0","","","");

		$consulta = "SELECT rol_id,rol_nombre FROM rol ORDER BY rol_id asc";
		$rs = $this->fmt->query->consulta($consulta,__METHOD__);
		$num=$this->fmt->query->num_registros($rs);
		if ($num>0){
			for ($i=0;$i<$num;$i++){
				list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
				//echo $fila_nombre;
				$valor[$i]=$fila_id;
				$campo[$i]=$fila_nombre;
			}
		}
		$this->fmt->form->check_form("Roles:","inputRol",$valor,$campo,"");

		$consulta = "SELECT emp_id,emp_nombre FROM empresa ORDER BY emp_id asc";
		$rs = $this->fmt->query->consulta($consulta,__METHOD__);
		$num=$this->fmt->query->num_registros($rs);
		if ($num>0){
			for ($i=0;$i<$num;$i++){
				list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
				$valores[$i]=$fila_id;
				$labels[$i]=$fila_nombre;
			}
		}
		$this->fmt->form->radio_form("Empresa:","inputEmpresa",$valores,$labels,"");
		$this->fmt->form->input_form('Orden:','inputOrden','','0','','','');
		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");

		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		$this->fmt->class_modulo->modal_script($this->id_mod);
  }

  function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Catalogo","","");
		$id_form="form-editar";
		$id = $this->id_item;
    $sql="select * from mod_catalogo where mod_catg_id='".$id."'";
    $rs=$this->fmt->query->consulta($sql,__METHOD__);
    $fila=$this->fmt->query->obt_fila($rs);
		?>
		<div class="body-modulo " id="">
			<div class="form-group" id="mensaje-login"></div>
			<form class="form form-modulo form-noticia"  method="POST" id="<?php echo $id_form?>">
			<?php
			$this->fmt->form->input_form('Nombre del catálogo:','inputNombre','',$fila['mod_catg_nombre'],'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
			$this->fmt->form->input_hidden_form("inputId",$id);
			$this->fmt->form->ruta_amigable_form("inputNombre","",$fila["mod_catg_ruta_amigable"],"inputRutaamigable","","input-lg"); //$id,$ruta="",$valor,$id_form,$ext="",$div_class
			$this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$fila["mod_catg_descripcion"],'','','3','','');
			?>
				<div class="form-group">
					<label>Configuración padre:</label>
					<select class="form-control" id="inputPadre" name="inputPadre">
						<?php $this->traer_opciones_cat_config($fila["mod_catg_id_padre"]); ?>
					</select>
				</div>
			<?php
			$cats_id = $this->fmt->categoria->traer_rel_cat_id($id,'mod_catalogo_categorias','mod_catg_cat_cat_id','mod_catg_cat_catg_id'); //$fila_id,$from,$prefijo_cat,$prefijo_rel
			$this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"",""); //$label,$id,$cat_raiz,$cat_valor,$class,$class_div
			//echo $fila['mod_catg_id_cat_arranque'];
			$this->fmt->form->select_form_cat_id("Categoría arranque:","inputArranque",$fila['mod_catg_id_cat_arranque']); //$label,$id,$id_item,$div_class,$id_padre
			$consulta = "SELECT rol_id,rol_nombre FROM rol ORDER BY rol_id asc";
			$rs = $this->fmt->query->consulta($consulta,__METHOD__);
			$num=$this->fmt->query->num_registros($rs);
			if ($num>0){
				for ($i=0;$i<$num;$i++){
					list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
					//echo $fila_nombre;
					$valor[$i]=$fila_id;
					$campo[$i]=$fila_nombre;
				}
			}
			$roles_id = $this->fmt->categoria->traer_rel_cat_id($id,'mod_catalogo_roles','mod_catg_rol_rol_id','mod_catg_rol_catg_id'); //$fila_id,$from,$prefijo_cat,$prefijo_rel
			$this->fmt->form->check_form("Roles:","inputRol",$valor,$campo,$roles_id);//$label,$id,$valor,$campo,$check=""

			$consulta = "SELECT emp_id,emp_nombre FROM empresa ORDER BY emp_id asc";
			$rs = $this->fmt->query->consulta($consulta,__METHOD__);
			$num=$this->fmt->query->num_registros($rs);
			if ($num>0){
				for ($i=0;$i<$num;$i++){
					list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
					$valores[$i]=$fila_id;
					$labels[$i]=$fila_nombre;
				}
			}
			$this->fmt->form->radio_form("Empresa:","inputEmpresa",$valores,$labels,$fila["mod_catg_id_empresa"]);
			$this->fmt->form->input_form('Orden:','inputOrden',$fila["mod_catg_orden"],'0','','','');
			$this->fmt->form->radio_activar_form($fila['mod_catg_activar']);
			$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
			?>
			</form>
		</div>
		<?php
		$this->fmt->class_modulo->modal_script($this->id_mod);
  }

  function ingresar(){
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
    $ingresar ="mod_catg_nombre,mod_catg_descripcion,mod_catg_ruta_amigable,mod_catg_id_cat_arranque,mod_catg_orden,mod_catg_id_padre,mod_catg_id_empresa,mod_catg_activar";
		$valores  ="'".$_POST['inputNombre']."','".
					   $_POST['inputDescripcion']."','".
             $_POST['inputRutaamigable']."','".
             $_POST['inputArranque']."','".
             $_POST['inputOrden']."','".
             $_POST['inputPadre']."','".
             $_POST['inputEmpresa']."','".
             $activar."'";

			$sql="insert into mod_catalogo (".$ingresar.") values (".$valores.")";
			$this->fmt->query->consulta($sql,__METHOD__);

			$sql="select max(mod_catg_id) as id from mod_catalogo";
			$rs= $this->fmt->query->consulta($sql,__METHOD__);
			$fila = $this->fmt->query->obt_fila($rs);
			$id = $fila ["id"];

			$ingresar1 = "mod_catg_cat_catg_id, mod_catg_cat_cat_id";
			$valor_cat=$_POST['inputCat'];
			$num_cat=count( $valor_cat );
			for ($i=0; $i<$num_cat;$i++){
				$valores1 = "'".$id."','".$valor_cat[$i]."'";
				$sql1="insert into mod_catalogo_categorias  (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1,__METHOD__);
			}

			$ingresar2 = "mod_catg_rol_catg_id, mod_catg_rol_rol_id";
			$valor_rol=$_POST['inputRol'];
			$num_rol=count( $valor_rol );
			for ($xy=0; $xy<$num_rol;$xy++){
			  $valores2 = "'".$id."','".$valor_rol[$xy]."'";
			 	$sql2="insert into mod_catalogo_roles (".$ingresar2.") values (".$valores2.")";
				$this->fmt->query->consulta($sql2,__METHOD__);
			}

			// $url="config-catalogo.adm.php?id_mod=".$this->id_mod;
			// $this->fmt->class_modulo->script_location($url);
			$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }

  function modificar(){
    $sql="UPDATE mod_catalogo SET mod_catg_nombre='".$_POST['inputNombre']."',
            mod_catg_descripcion='".$_POST['inputDescripcion']."',
            mod_catg_ruta_amigable='".$_POST['inputRutaamigable']."',
            mod_catg_id_cat_arranque='".$_POST['inputArranque']."',
            mod_catg_orden = '".$_POST['inputOrden']."',
            mod_catg_id_padre = '".$_POST['inputPadre']."',
            mod_catg_id_empresa = '".$_POST['inputEmpresa']."',
            mod_catg_activar='".$_POST['inputActivar']."'
            WHERE mod_catg_id='".$_POST['inputId']."'";
    $this->fmt->query->consulta($sql,__METHOD__);

		$id=$_POST['inputId'];

		$sqld="DELETE FROM mod_catalogo_categorias WHERE mod_catg_cat_catg_id='".$id."'";
		$this->fmt->query->consulta($sqld,__METHOD__);

    $ingresar1 = "mod_catg_cat_catg_id,mod_catg_cat_cat_id";
	  $valor_cat=$_POST['inputCat'];
	  $num_cat=count( $valor_cat );
	  for ($i=0; $i<$num_cat;$i++){
			$valores1 = "'".$id."','".$valor_cat[$i]."'";
			$sql1="insert into mod_catalogo_categorias  (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1,__METHOD__);
	  }


		$sqld="DELETE FROM mod_catalogo_roles WHERE mod_catg_rol_catg_id='".$id."'";
		$this->fmt->query->consulta($sqld,__METHOD__);


		$ingresar2 = "mod_catg_rol_catg_id, mod_catg_rol_rol_id";
		$valor_rol=$_POST['inputRol'];
		$num_rol=count( $valor_rol );
		for ($xy=0; $xy<$num_rol;$xy++){
			$valores2 = "'".$id."','".$valor_rol[$xy]."'";
			$sql2="insert into mod_catalogo_roles (".$ingresar2.") values (".$valores2.")";
			$this->fmt->query->consulta($sql2,__METHOD__);
		}
    $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }

  function activar(){
    $this->fmt->get->validar_get ( $_GET['estado'] );
    $this->fmt->get->validar_get ( $_GET['id'] );
    $estado = $_GET['estado'];
    if ($estado=='1'){ $estado=0; }else{ $estado=1; }
    $sql="update mod_productos_catalogo set mod_catg_activar='".$estado."' where mod_catg_id='".$_GET['id']."'";
    $this->fmt->query->consulta($sql,__METHOD__);
    header("location: config-catalogo.adm.php?id_mod=".$this->id_mod);
  }

  function eliminar(){
    $this->fmt->get->validar_get ( $_GET['id'] );
    $id= $_GET['id'];
    $sql="DELETE FROM mod_productos_catalogo WHERE mod_catg_id='".$id."'";
    $this->fmt->query->consulta($sql,__METHOD__);
    $up_sqr6 = "ALTER TABLE mod_productos_catalogo AUTO_INCREMENT=1";
    $this->fmt->query->consulta($up_sqr6,__METHOD__);
    $this->eliminar_categoria($id);
    header("location: config-catalogo.adm.php?id_mod=".$this->id_mod);
  }
  function eliminar_categoria($id){
	$sql="DELETE FROM mod_productos_catg_cat WHERE mod_catg_cat_catg_id='".$id."'";
    $this->fmt->query->consulta($sql,__METHOD__);
  }
}
