<?php
header("Content-Type: text/html;charset=utf-8");

class PROD_CAT{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;

	function PROD_CAT($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
	}

	function busqueda(){
    $this->fmt->class_pagina->crear_head( $this->id_mod, ""); // id modulo, botones
    ?>
		<div class="body-modulo container-fluid">
			<div class="container">
      <?php
			$this->fmt->class_pagina->head_modulo_inner("Categorias activas de productos", $botones);
      $id_rol = $this->fmt->sesion->get_variable("usu_rol");
      if($id_rol==1)
      	$sql="select categoria_cat_id from modulos_categoria where modulos_mod_id=".$this->id_mod." ORDER BY categoria_cat_id asc";
      else
      	$sql="select rol_rel_cat_id from roles_rel where rol_rel_rol_id=".$id_rol." and rol_rel_cat_id not in (0) ORDER BY rol_rel_cat_id asc";
      $rs =$this->fmt->query->consulta($sql);
	  $num=$this->fmt->query->num_registros($rs);
	  if($num>0){
	  	for($i=0;$i<$num;$i++){
	    	list($fila_id)=$this->fmt->query->obt_fila($rs);
			$this->fmt->categoria->arbol_editable_nodo('categoria','cat_',$fila_id,$this->id_mod);
		}
      }
	  ?>
    	</div>
    </div>
    <script>
      $(".btn-activar-i").click(function(e){
        var cat = $( this ).attr("cat");
        var estado = $( this ).attr("estado");
        var id_mod = "<?php echo $this->id_mod; ?>";
        var variables = "activar,"+cat+","+estado;
				var ruta = "ajax-adm";
				var datos = {ajax:ruta, inputIdMod:id_mod , inputVars : variables };
				abrir_modulo(datos);
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

			function abrir_modulo(datos){
				$(".modal").addClass("on");
				$(".modal").addClass("<?php echo $url_a; ?>");
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
						// var wmi =   $("#modal .modal-inner").width();
						// var hmi =   $("#modal .modal-inner").height();
						// var x_wmi = Math.round(wmi /2);
						// var y_hmi = Math.round(hmi /2);
						// $("#modal .modal-inner").css("margin-left","-"+x_wmi+"px");
						// $("#modal .modal-inner").css("margin-top","-"+y_hmi+"px");
					}
				});
			}
  $(".btn-eliminar-i").click(function(e){
			e.preventDefault();
			var nom= $(this).attr('nombre');
			var variables = $(this).attr('vars');
			var id_mod= $(this).attr('id_mod');

			$(".modal").addClass("on");
			$(".content-page").css("overflow-y","hidden");
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


		function accion_modulo(datos){
			$.ajax({
				url:"<?php echo _RUTA_WEB; ?>ajax.php",
				type:"post",
				async: true,
				data:datos,
				success: function(msg){
					console.log( msg );
					var variables = msg;
					var cadena = variables.split(':');
					var accion = cadena[0];
					var id_item = cadena[1];
					var estado = cadena[2];
					var id_mod = cadena[3];
					switch ( accion ){
						case 'activar':
							console.log(estado);
							$("#btn-p-"+id_mod+"-"+id_item+" i").removeClass();
							if(estado==1){
								$("#btn-p-"+id_mod+"-"+id_item+" i").addClass("icn-eye-close");
								$("#btn-p-"+id_mod+"-"+id_item).attr("vars","activar,"+id_item+",1");
							}else{
								$("#btn-p-"+id_mod+"-"+id_item+" i").addClass("icn-eye-open");
								$("#btn-p-"+id_mod+"-"+id_item).attr("vars","activar,"+id_item+",0");
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
		};
    </script>
    <?php
    //$this->fmt->class_modulo->script_form("modulos/productos/prod-cat.adm.php",$this->id_mod);
		$this->fmt->class_modulo->script_accion_modulo();
  }

  function form_editar(){
    $botones = $this->fmt->class_pagina->crear_btn_m("Volver","icn-chevron-left","volver","btn btn-link btn-menu-ajax",$this->id_mod,"busqueda");
    $this->fmt->class_pagina->crear_head_form("Editar Categoria Productos", $botones,"");// nombre, botones-left, botones-right
    $id_rol = $this->fmt->sesion->get_variable("usu_rol");
	if($id_rol==1)
      	$sql="select categoria_cat_id from modulos_categoria where modulos_mod_id=".$this->id_mod." ORDER BY categoria_cat_id asc";
     else
      	$sql="select rol_rel_cat_id from roles_rel where rol_rel_rol_id=".$id_rol." and rol_rel_cat_id not in (0) ORDER BY rol_rel_cat_id asc";
     $rs =$this->fmt->query->consulta($sql);
	 $num=$this->fmt->query->num_registros($rs);
	 if($num>0){
		for($i=0;$i<$num;$i++){
	    	list($fila_id)=$this->fmt->query->obt_fila($rs);
			$padres[$i]=$fila_id;
		}
     }

    $id = $this->id_item;

    $sql="select * from categoria	where cat_id='".$id."'";
    $rs=$this->fmt->query->consulta($sql);
    $fila=$this->fmt->query->obt_fila($rs);
    ?>
    <div class="body-modulo col-xs-12  col-md-6 col-xs-offset-0 col-md-offset-3">
			<form class="form form-modulo" action="prod-cat.adm.php?tarea=modificar&id_mod=<?php echo $this->id_mod; ?>"  method="POST" id="form-editar">
				<div class="form-group" id="mensaje-form"></div>
        <div class="form-group">
					<label>Nombre Categoria</label>
					<input class="form-control input-lg required"  id="inputNombre" name="inputNombre" value="<?php echo $fila["cat_nombre"]; ?>" placeholder=" " type="text" autofocus />
					<input type="hidden" id="inputId" name="inputId" value="<?php echo $fila["cat_id"]; ?>" />
				</div>

        <div class="form-group">
          <label>Descripción</label>
          <textarea class="form-control" rows="2" id="inputDescripcion" name="inputDescripcion" placeholder=""><?php echo $fila["cat_descripcion"]; ?></textarea>
        </div>
		<?php
			$this->fmt->form->hidden_modulo($this->id_mod,"modificar");
		?>
        <div class="form-group">
          <label>Ruta amigable:</label>
          <input class="form-control" id="inputRutaamigable" name="inputRutaamigable" placeholder="" value="<?php echo $fila["cat_ruta_amigable"]; ?>" />

        </div>

		<div class="form-group">
          <label>Categoria padre:</label>
          <select class="form-control" id="inputPadre" name="inputPadre">
            <?php $this->fmt->categoria->traer_opciones_cat_padre($padres,$fila['cat_id']); ?>
          </select>
        </div>

        <div class="form-group">
          <label>Orden:</label>
          <input class="form-control" id="inputOrden" name="inputOrden" placeholder="" value="<?php echo $fila["cat_orden"]; ?>" />

        </div>

        <div class="form-group">
          <label class="radio-inline">
            <input type="radio" name="inputActivar" id="inputActivar" value="0" <?php if ($fila['cat_activar']==0){ echo "checked"; } ?> > Desactivar
          </label>
          <label class="radio-inline">
            <input type="radio" name="inputActivar" id="inputActivar" value="1" <?php if ($fila['cat_activar']==1){ echo "checked"; $aux="Activo"; } else { $aux="Activar"; } ?> > <?php echo $aux; ?>
          </label>
        </div>
        <div class="form-group form-botones">

           <button type="button" class="btn btn-info  btn-actualizar btn-form hvr-fade btn-lg color-bg-celecte-c btn-lg" name="btn-accion" form="form-editar" id="btn-activar" value="actualizar"><i class="icn-sync" ></i> Actualizar</button>
        </div>

      </form>
    </div>
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
										$("#inputRutaamigable").val(datos);
									}
							});
					});

			});
		</script>
    <?php
    $this->fmt->class_modulo->script_form("modulos/categorias/categorias.adm.php",$this->id_mod);
  }

  function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Contenido","","");
		$id_form="form-nuevo";

		$id_padre = $this->id_item;

		if (empty($id_padre)){
			$id_padre='0';
		}

		$sql="select cat_theme, cat_id_plantilla from categoria where cat_id=".$id_padre;
		$rs =$this->fmt->query->consulta($sql);
		list($fila_theme, $fila_plant)=$this->fmt->query->obt_fila($rs);


		?>
		<div class="body-modulo">
			<form class="form form-modulo form-multimedia"  method="POST" id="<?php echo $id_form?>">
				<div class="form-group" id="mensaje-form"></div>
        <div class="form-group">
					<label>Nombre Categoria</label>
					<input class="form-control input-lg required"  id="inputNombre" name="inputNombre" value="" placeholder=" " type="text" autofocus />
				</div>

        <div class="form-group">
          <label>Descripción</label>
          <textarea class="form-control" rows="2" id="inputDescripcion" name="inputDescripcion" placeholder=""></textarea>
        </div>
        <div class="form-group">
          <label>Ruta amigable:</label>
          <input class="form-control" id="inputRutaamigable" name="inputRutaamigable" placeholder="" value="" />
          <input type="hidden" id="inputTheme" name="inputTheme" value="<?php echo $fila_theme; ?>" />
          <input type="hidden" id="inputPadre" name="inputPadre" value="<?echo $id_padre; ?>">
          <input type="hidden" id="inputPlantilla" name="inputPlantilla" value="<?echo $fila_plant; ?>">
        </div>

        <div class="form-group">
          <label>Categoria padre:</label>
          <input class="form-control" disabled  placeholder="<?php echo $this->fmt->categoria->nombre_categoria($id_padre); ?>" />
          <input type="hidden" id="inputPadre" name="inputPadre" value="<?echo $id_padre; ?>">
        </div>

        <div class="form-group">
          <label>Orden:</label>
          <input class="form-control" id="inputOrden" name="inputOrden" placeholder="" value="" />

        </div>

      <?php
				$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
			 ?>

      </form>
    </div>
    <?php
    $this->fmt->class_modulo->modal_script($this->id_mod);
  }

  function nombre_categoria($cat){
    if ($cat==0){
      return 'no definido (0)';
    }
    $this->fmt->get->validar_get($cat);
    $consulta = "SELECT mod_prod_cat_nombre FROM mod_productos_cat WHERE mod_prod_cat_id='$cat' ";
    $rs = $this->fmt->query->consulta($consulta);
    $fila = $this->fmt->query->obt_fila($rs);
    $nombre=$fila["mod_prod_cat_nombre"];
    return $nombre;

  }

  function ingresar(){
    if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
    $ingresar ="cat_nombre, cat_descripcion, cat_ruta_amigable, cat_theme, cat_id_plantilla, cat_id_padre, cat_orden, cat_activar";
	$valores  ="'".$_POST['inputNombre']."','".
				   $_POST['inputDescripcion']."','".
                   $_POST['inputRutaamigable']."','".
                   $_POST['inputTheme']."','".
                   $_POST['inputPlantilla']."','".
                   $_POST['inputPadre']."','".
                   $_POST['inputOrden']."','".
                   $activar."'";

	$sql="insert into categoria (".$ingresar.") values (".$valores.")";

	$this->fmt->query->consulta($sql);

	$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }

  function modificar(){

      $sql="UPDATE categoria SET
            cat_nombre='".$_POST['inputNombre']."',
            cat_descripcion='".$_POST['inputDescripcion']."',
            cat_ruta_amigable='".$_POST['inputRutaamigable']."',
            cat_orden = '".$_POST['inputOrden']."',
            cat_id_padre = '".$_POST['inputPadre']."',
            cat_activar='".$_POST['inputActivar']."'
            WHERE cat_id='".$_POST['inputId']."'";
      $this->fmt->query->consulta($sql);

    $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }

  function activar(){
    $estado = $this->id_estado;
    if ($estado=='1'){ $estado=0; }else{ $estado=1; }
    $sql="update categoria set cat_activar='".$estado."' where cat_id='".$this->id_item."'";
    $this->fmt->query->consulta($sql);
  $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }

  function eliminar(){
    $id= $this->id_item;
    $sql="DELETE FROM categoria WHERE cat_id='".$id."'";
    $this->fmt->query->consulta($sql);
    $up_sqr6 = "ALTER TABLE categoria AUTO_INCREMENT=1";
    $this->fmt->query->consulta($up_sqr6);
    $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }

}
