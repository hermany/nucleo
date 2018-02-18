<?php
header("Content-Type: text/html;charset=utf-8");

class CONTENEDORES{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $nombre_modulo;
	var $nombre_tabla;
	var $prefijo_tabla;
	var $ruta_modulo;

	function CONTENEDORES($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;

		$this->nombre_modulo="contenedores.adm.php";
		$this->nombre_tabla="contenedor";
		$this->prefijo_tabla="cont_";
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){
	  $this->fmt->class_pagina->crear_head( $this->id_mod, $botones); // bd, id
		?>
		<div class="body-modulo">
			<div class="container">
			<?php
				$this->fmt->categoria->arbol_editable_mod('contenedor','cont_','cont_id_padre=0','','box-contenedores',$this->id_mod); //$from,$prefijo,$where,$url_modulo,$class_div,$id_mod)
			?>
			</div>
			<script type="text/javascript">

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
				$(".modal-form").addClass("on");
				$(".modal-form").addClass("<?php echo $url_a; ?>");
				$(".body-page").css("overflow-y","hidden");
				$.ajax({
					url:"<?php echo _RUTA_WEB; ?>ajax.php",
					type:"post",
					data:datos,
					success: function(msg){
						$(".modal-form .modal-inner").html(msg);
					},
					complete : function() {
						$('.preloader-page').fadeOut('slow');

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
					//var id_mod= $(this).attr('id_mod');
					var ruta='ajax-eliminar';
					var datos= { ajax:ruta,inputIdMod:id_mod, inputVars:variables };
					//alert(variables);
					accion_modulo(datos);
				});

				});

				$(".btn-activar-i").click(function(e){
					var cat = $( this ).attr("cat");
					var estado = $( this ).attr("estado");
					var id_mod = "<?php echo $this->id_mod; ?>";
					var variables =  $( this ).attr("vars");
					var ruta = "ajax-activar";
					var datos = {ajax:ruta, inputIdMod:id_mod , inputVars : variables };
					accion_modulo(datos);
				});


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
				};
			</script>
			<style media="screen">
				.btn-contenedores{
					display: none;
				}
			</style>
		</div>
		<?php
		$this->fmt->class_modulo->script_accion_modulo();
	}

	function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Categoria","","");
		$id_form="form-editar";

		$id = $this->id_item;

	  $sql="SELECT * FROM contenedor where cont_id='".$id."'";
	  $rs=$this->fmt->query->consulta($sql);
	  $num=$this->fmt->query->num_registros($rs);
	  if($num>0){
	    for($i=0;$i<$num;$i++){
	      $row=$this->fmt->query->obt_fila($rs);
	      $fila_id =$row["cont_id"];
	      $fila_nombre=$row["cont_nombre"];
	      $fila_clase=$row["cont_clase"];
	      $fila_css=$row["cont_css"];
	      $fila_codigos=$row["cont_codigos"];
	      $fila_id_padre=$row["cont_id_padre"];
	      $fila_orden=$row["cont_orden"];
	      $fila_activar=$row["cont_activar"];
	    }
	  }
	  ?>
	    <div class="body-modulo col-xs-6 col-xs-offset-3">
				<form class="form form-modulo"  method="POST" id="<?php echo $id_form ;?>">
					<div class="form-group" id="mensaje-form"></div>
	        <?php
	        $this->fmt->form->input_form('Nombre del contenedor:','inputNombre','',$fila_nombre,'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	    $this->fmt->form->input_hidden_form("inputId",$fila_id);

	    $this->fmt->form->input_form('Clase:','inputClase','',$fila_clase,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->input_form('Css:','inputCss','',$fila_css,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

	        $this->fmt->form->textarea_form('Codigos:','inputCodigos','',$fila_codigos,'','','3','','');
	        $this->fmt->form->input_form('Orden:','inputOrden','',$fila_orden,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

	        $this->fmt->form->select_form('Contenedor padre:','inputPadre','cont_','contenedor',$fila_id_padre,'',''); //$label,$id,$prefijo,$from,$id_select,$id_disabled,$class_div


					$this->fmt->form->radio_activar_form($fila_activar);
					$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar"); //$fila_id,$fila_nombre,$nombre,$tarea_eliminar
	        ?>
	      </form>
	      </div>
	  <?php
	    $this->fmt->class_modulo->modal_script($this->id_mod);
	}

	function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nueva Categoria","","");
		$id_form="form-nuevo";
		$id = $this->id_item;
	  ?>
		<div class="body-modulo">
			<form class="form form-modulo"  method="POST" id="<?php echo $id_form ;?>">
				<div class="form-group" id="mensaje-form"></div>
	        <?php
	        $this->fmt->form->input_form('Nombre del contenedor:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

	        //$this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','','3','','');
	        $this->fmt->form->input_form('Clase:','inputClase','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        $this->fmt->form->input_form('Css:','inputCss','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

	        $this->fmt->form->textarea_form('Codigos:','inputCodigos','','','','','3','','');
	        $this->fmt->form->input_form('Orden:','inputOrden','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
	        if (!empty($id_padre)){
	          $a=$id_padre;
	        }else{
	          $a="";
	        }
	        $this->fmt->form->select_form('Contenedor padre:','inputPadre','cont_','contenedor',$a,'',''); //$label,$id,$prefijo,$from,$id_select,$id_disabled,$class_div

	        $this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar"); //$id_form,$id_mod,$tarea
	        ?>
	      </form>
	      </div>
	  <?php
	  $this->fmt->class_modulo->modal_script($this->id_mod);
	}
	function ingresar(){
	    if ($_POST["estado-mod"]=="activar"){ $activar=1; }else{ $activar=0;}
	    $ingresar ="cont_nombre,
	        cont_clase,
	        cont_css,
	        cont_codigos,
	        cont_id_padre,
	        cont_orden,
	        cont_activar";

	    $valores_post  ="inputNombre,
	          inputClase,
	          inputCss,
	          inputCodigos,
	          inputPadre,
	          inputOrden,
	          inputActivar=".$activar;

	    $this->fmt->class_modulo->ingresar_tabla('contenedor',$ingresar,$valores_post);
	    //$from,$filas,$valores_post

	  $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}
	function modificar(){

	    $filas='cont_id,
	        cont_nombre,
	        cont_clase,
	        cont_css,
	        cont_codigos,
	        cont_id_padre,
	        cont_orden,
	        cont_activar';

	    $valores_post='inputId,inputNombre,
	          inputClase,
	          inputCss,
	          inputCodigos,
	          inputPadre,
	          inputOrden,
	          inputActivar';

	    $this->fmt->class_modulo->actualizar_tabla($this->nombre_tabla,$filas,$valores_post); //$from,$filas,$valores_post
	    $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}
}
