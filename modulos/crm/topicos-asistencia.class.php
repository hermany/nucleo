<?php
header("Content-Type: text/html;charset=utf-8");

class TOPICOS_ASISTENCIA{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function TOPICOS_ASISTENCIA($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	public function busqueda(){
		
		$this->fmt->class_pagina->crear_head( $this->id_mod);
		$this->fmt->class_pagina->head_mod();
		?>
			<div class="body-modulo container-fluid">
				<div class="container">
					<?php 
						$this->fmt->class_pagina->head_modulo_inner("Estructura Tópicos", ""); 
						$this->fmt->categoria->arbol_editable_mod('mod_topicos_asistencia','mod_tpa_','mod_tpa_id_padre=0','','box-topicos',$this->id_mod); //$from,$prefijo,$where,$url_modulo,$class_div,$id_mod
					?>
				</div>
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

				$(".btn-ordenar-i").click(function(e){
					var id_mod = "<?php echo $this->id_mod; ?>";
					var cat = $( this ).attr("id_padre");
					var variables = "ordenar,"+cat;
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

	public function traer_nombre_lista($id){
		$consulta = "SELECT mod_list_nombre FROM mod_lista  WHERE mod_list_id='$id'";
		$rs =$this->fmt->query->consulta($consulta);
		$row=$this->fmt->query->obt_fila($rs);
		return $row["mod_list_nombre"];
		$this->fmt->query->liberar_consulta();
	}

	public function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Topico","","");
		$id_form="form-nuevo";
		$id = $this->id_item;

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-topicos");

		$this->fmt->form->input_form("* Nombre:","inputNombre","","","input-lg","","","",""); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros
		$this->fmt->form->ruta_amigable_form("inputNombre","","","inputRutaamigable","","","1"); //$id,$ruta,$valor,$form
		$this->fmt->form->input_form("Descripcion:","inputDescripcion","","");
		$this->fmt->form->input_form("tags:","inputTags","","");
		/*$this->fmt->form->input_info('{"label":"Padre:",
                    							 "id":"inputPadre",
								                   "value":"'.$id.'"}');*/

		$this->fmt->form->select_form_nodo('{
																				"label":"Padre:",
																				"id":"inputPadre",
																				"item":"",
																				"id_padre":"'.$id.'",
																				"id_inicio":"0",
																				"from":"mod_topicos_asistencia",
																				"prefijo":"mod_tpa_"
																			}');

		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} //fin form_nuevo

	function ingresar(){
		if ($_POST["estado-mod"]=="activar"){ $activar=1; }else{ $activar=0;}

    $num= $this->fmt->categoria->numero_hijos($_POST['inputPadre']) + 1; 

    $ingresar ="mod_tpa_nombre, mod_tpa_tags,mod_tpa_descripcion, mod_tpa_ruta_amigable, mod_tpa_orden,mod_tpa_id_padre,mod_tpa_activar";
		$valores  ="'".$_POST['inputNombre']."','".
		 							 $_POST['inputTags']."','".
									 $_POST['inputDescripcion']."','".
                   $_POST['inputRutaamigable']."','".
                   $num."','".
                   $_POST['inputPadre']."','".
									 $activar."'";

		$sql="insert into mod_topicos_asistencia (".$ingresar.") values (".$valores.")";
		

		$this->fmt->query->consulta($sql,__METHOD__);

		// $this->fmt->class_sistema->update_htaccess();
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin ingresar

	public function datos_topico_asistencia($id){
			$consulta = "SELECT * FROM mod_topicos_asistencia WHERE mod_tpa_id='$id'";
			$rs =$this->fmt->query->consulta($consulta);
			$row=$this->fmt->query->obt_fila($rs);
			$cadena[0]= $row["mod_tpa_id"];
			$cadena[1]= $row["mod_tpa_nombre"];
			$cadena[2]= $row["mod_tpa_descripcion"];
			$cadena[3]= $row["mod_tpa_ruta_amigable"];
			$cadena[4]= $row["mod_tpa_tags"];
			$cadena[5]= $row["mod_tpa_id_padre"];
			$cadena[6]= $row["mod_tpa_orden"];
			$cadena[7]= $row["mod_tpa_activar"];

			return $cadena;

			$this->fmt->query->liberar_consulta();
	}

	function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Lista Lugar","","");
		$id_form="form-editar";

		$id = $this->id_item;
		$consulta= "SELECT * FROM mod_topicos_asistencia WHERE mod_tpa_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-lugares");
		
		// $this->fmt->form->hidden_modulo($this->id_mod,"modificar");
		$this->fmt->form->input_form("* Nombre:","inputNombre","",$row["mod_tpa_nombre"],"input-lg","","");
		$this->fmt->form->ruta_amigable_form("inputNombre","",$row["mod_tpa_ruta_amigable"],"inputRutaamigable","","","1"); //$id,$ruta,$valor,$form
		$this->fmt->form->input_hidden_form("inputId",$id);

		$this->fmt->form->input_form("Descripcion:","inputDescripcion","",$row["mod_tpa_descripcion"]);
		$this->fmt->form->input_form("Tags:","inputTags","",$row["mod_tpa_tags"]);
 

		// $this->fmt->form->select_form_nodo("Categoría padre:","inputPadre",$fila['cat_id_padre']);
		$this->fmt->form->select_form_nodo('{
																				"label":"Categoría padre:",
																				"id":"inputPadre",
																				"item":"'.$id.'",
																				"id_padre":"'.$row["mod_tpa_id_padre"].'",
																				"id_inicio":"0",
																				"from":"mod_topicos_asistencia",
																				"prefijo":"mod_tpa_"
																			}');

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} // fin form_editar

	function modificar(){

		$sql="UPDATE mod_topicos_asistencia SET
				mod_tpa_nombre='".$_POST['inputNombre']."',
				mod_tpa_ruta_amigable='".$_POST['inputRutaamigable']."',
				mod_tpa_descripcion='".$_POST['inputDescripcion']."',
				mod_tpa_tags ='".$_POST['inputTags']."',
				mod_tpa_id_padre='".$_POST['inputPadre']."'
				WHERE mod_tpa_id='".$_POST['inputId']."'";
		//echo $sql;
		$this->fmt->query->consulta($sql);


		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin modificar

	function ordenar(){
		$id_cat = $this->id_item;
		$this->fmt->class_pagina->crear_head_form("Ordenar:","","");
		$id_form="form-ordenar";
		?>
		<div class="body-modulo">
		  <form class="form form-modulo form-ordenar"  method="POST" id="<?php echo $id_form?>">
				<ul id="orden-cat" class="list-group">
					<?php
					$sql="SELECT mod_tpa_id, mod_tpa_nombre, mod_tpa_orden FROM mod_topicos_asistencia where mod_tpa_id_padre=$id_cat ORDER BY mod_tpa_orden asc";

	        $rs =$this->fmt->query->consulta($sql,__METHOD__);
	        $num=$this->fmt->query->num_registros($rs);
	        if($num>0){
		        for($i=0;$i<$num;$i++){
		          $row=$this->fmt->query->obt_fila($rs);
							$row_id=$row["mod_tpa_id"];
							$row_nom=$row["mod_tpa_nombre"];
							
							echo "<li id_var='$row_id'><i class='icn icn-reorder'></i><span class='nombre'>$row_nom</span></li>";
						}
					}
					?>
				</ul>
				<div class="form-group form-botones box-botones-form">
					<div class="group">
						<?php
						echo $this->fmt->class_pagina->crear_btn_m("Actualizar","icn-sync","update","btn btn-info btn-update",$this->id_mod,"ordenar_update");
						 ?>
					</div>
				</div>
			</form>
			<script type="text/javascript">
				$(document).ready(function() {
					$("#orden-cat" ).sortable();
					$(".btn-update").click(function(){
						var formdata = new FormData();
						var id_mod = $(this).attr("id_mod");
						var vars = $(this).attr("vars");
						formdata.append("inputVars", vars);
						formdata.append("cat", "<?php echo $id_cat;?>");
						formdata.append("ajax", "ajax-adm");
						formdata.append("inputIdMod", id_mod);
						$('#orden-cat li').each(function(index){
						  var id_var = $(this).attr("id_var");
						  console.log(id_var);
						  var orden = index+1;
						  formdata.append("id_item[]", id_var);
						  //formdata.append("orden[]", orden);
						});

						var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";

						$.ajax({
						      url:ruta,
						      type:"post",
						      data:formdata,
						      processData: false,
						    contentType: false,
						      success: function(msg){

						        $("#popup-div").html(msg);
										document.location.href="<?php echo $this->ruta_modulo; ?>";
						      }
						});
					});
				});
			</script>
		</div>
		<?php
	}

	function ordenar_update(){
		//$id_cat=$_POST["cat"];
		//$ingresar2 ="cat_id,cat_orden";
		$valor_doc= $_POST['id_item'];
		$num=count( $valor_doc );
		for ($i=0; $i<$num;$i++){

			$sql="UPDATE mod_topicos_asistencia SET
          mod_tpa_orden='".$i."'
          WHERE mod_tpa_id='".$valor_doc[$i]."'";

			 $this->fmt->query->consulta($sql,__METHOD__);
		}
	  $this->fmt->class_modulo->redireccionar($this->ruta_modulo,"1");
	}
}