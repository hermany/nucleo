<?php
header("Content-Type: text/html;charset=utf-8");

class PRODUCTOS{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function PRODUCTOS($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){

	  $ruta_server=_RUTA_NUCLEO;

    $id_rol = _USU_ID_ROL;

		$this->fmt->class_pagina->crear_head( $this->id_mod, $botones); // bd, id modulo, botones //$nom,$archivo,$id_mod,$botones
		$fecha_hoy= $this->fmt->class_modulo->fecha_hoy("America/La_Paz");

    ?>
    <link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/m-productos.css?reload" rel="stylesheet" type="text/css">
		<div class="body-modulo container-fluid">
			<div class="container">
				<?php
				$botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nueva noticia","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
				$botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nueva noticia","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars

				$this->fmt->class_pagina->head_modulo_inner("Lista de Productos", $botones); // bd, id modulo, botones
				?>
				<div class="table-responsive">
				  <table class="table table-hover" id="table_id">
				    <thead>
				      <tr>
				        <th class="col-id">Id</th>
				        <th style="width:10%" >Imagen</th>
				        <th>Nombre del producto</th>
				        <th>Categoria/s</th>
				        <th class="estado">Publicación</th>
				        <th class="col-xl-offset-2 acciones">Acciones</th>
				      </tr>
				    </thead>
				    <tbody>
				      <?php
				      	if($id_rol==1)
				        	$sql="select mod_prod_id, mod_prod_nombre, mod_prod_imagen,  mod_prod_id_dominio, mod_prod_activar from mod_productos ORDER BY mod_prod_id desc";
				        else{
				        	$aux="";
				        	$or="";
				        	$sql="select rol_cat_cat_id from roles_categorias where rol_cat_rol_id=".$id_rol." and rol_cat_cat_id not in (0) ORDER BY rol_cat_cat_id asc";
					      $rs =$this->fmt->query->consulta($sql);
						  $num=$this->fmt->query->num_registros($rs);
						  if($num>0){
						  	for($i=0;$i<$num;$i++){
						    	$row=$this->fmt->query->obt_fila($rs);
									$fila_id = $row["mod_prod_id"];
								$aux.=$or."mod_prod_rel_cat_id=".$fila_id;
								$or=" or ";
								if($this->fmt->categoria->tiene_hijos_cat($fila_id)){
									$ids_cat=array();
									$this->fmt->categoria->traer_hijos_array($fila_id,$ids_cat);
									$num_cat=count($ids_cat);
									if ($num_cat>0){
										for($j=0;$j<$num_cat;$j++){
											$aux.=$or."mod_prod_rel_cat_id=".$ids_cat[$j];
										}
									}
								}
							}
					      }
				        	$sql="select mod_prod_id, mod_prod_nombre, mod_prod_imagen,  mod_prod_id_dominio, mod_prod_activar from mod_productos, mod_productos_rel where mod_prod_rel_prod_id=mod_prod_id and ($aux) ORDER BY mod_prod_id desc";

				        }
				        $rs =$this->fmt->query->consulta($sql);
				        $num=$this->fmt->query->num_registros($rs);
				        if($num>0){
				        for($i=0;$i<$num;$i++){
				          $row=$this->fmt->query->obt_fila($rs);
									$fila_id=$row["mod_prod_id"];
									$fila_nombre=$row["mod_prod_nombre"];
									$fila_imagen=$row["mod_prod_imagen"];
									$fila_activar=$row["mod_prod_activar"];

									//if (empty($fila_dominio)){ $aux=_RUTA_WEB; } else { $aux = $this->fmt->categoria->traer_dominio_cat_id($fila_dominio); }
									//$img=$this->fmt->archivos->convertir_url_thumb( $fila_imagen );
									$imgx=$this->fmt->archivos->convertir_url_mini( $fila_imagen );
									$id_cat = $this->fmt->categoria->traer_id_cat_dominio($aux);
									$sit_cat = $this->fmt->categoria->ruta_amigable($id_cat);

									$mystring = $fila_url;
									$findme   = 'http';
									$pos = strpos($mystring, $findme);
									if ($pos===false){
										$imgx=$this->fmt->archivos->convertir_url_mini($fila_imagen);
										if(file_exists(_RUTA_HOST.$imgx)){
											$img=_RUTA_IMAGES.$imgx;
										}else{
											$img=_RUTA_WEB_NUCLEO."images/producto-icon.png";
										}
									}else{
										$img = $fila_url;
									}
									//
									// if(file_exists(_RUTA_ROOT."/".$imgx)){
									//  	//$img=$aux.$this->fmt->archivos->convertir_url_thumb( $fila_imagen );
									//  	$img=$aux.$imgx;
									// }else{
									//  	$img=_RUTA_WEB_NUCLEO."images/producto-icon.png";
									// }
				        ?>
				        <tr class='row row-<?php echo $fila_id; ?>'>
									<td class="col-id"><?php echo $fila_id; ?></td>
				          <td class="img-previo"><?php
									//echo "id:".$fila_id;
									//echo _RUTA_HOST.$imgx;
									//echo _RUTA_ROOT.$sit_cat."/".$imgx; ?><img class="img-responsive" width="60px" src="<?php echo $img; ?>" alt="" /></td>
				          <td><strong><a class="btn-menu-ajax" id_mod="<?php echo  $this->id_mod; ?>" vars='form_editar,<?php echo $fila_id; ?>' ><?php echo $fila_nombre; ?></a></strong></td>
				          <td><?php $this->traer_rel_cat_nombres($fila_id); ?> </td>
				          <td><?php
										//echo "activar:".$fila_activar;
				          $this->fmt->class_modulo->estado_publicacion($fila_activar, $this->id_mod,"",$fila_id);
				           ?></td>
				          <td>
						  <?php
							  echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar ".$fila_id,"btn btn-accion btn-m-editar ",$this->id_mod,"form_editar,".$fila_id);
						  ?>
				            <!-- <a  title="eliminar <?php echo $fila_id; ?>" type="button" idEliminar="<?php echo $fila_id; ?>" nombre="<?php echo $fila_nombre_e; ?>" id="btn-em<?php echo $fila_id; ?>" id_mod="<?php echo $this->id_mod; ?>" vars="eliminar,<?php echo $fila_id; ?>" class="btn btn-eliminar btn-accion "><i class="icn-trash"></i></a> -->
										<a  title="eliminar <?php echo $fila_id; ?>" type="button" nombre="<?php echo $fila_nombre; ?>" id="btn-m<?php echo $this->id_mod; ?>" id_mod="<?php echo $this->id_mod; ?>" vars="eliminar,<?php echo $fila_id; ?>" class="btn btn-fila-eliminar btn-accion "><i class="icn-trash"></i></a>

				          </td>
				        </tr>
				        <?php
				         } // Fin for query1
				        }// Fin if query1
				      ?>
				    	</tbody>
				  	</table>
					</div> <!-- fin table responsive -->
			</div> <!-- fin container -->
		</div> <!-- fin body modulo -->
		<?php
		$this->fmt->class_modulo->script_accion_modulo();
		$this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","25",true);
  }

	function productos_cat($cat,$limite="0,1",$tipo_orden="id",$orden="desc",$addend="",$tipo_img="thumb"){

		require_once(_RUTA_NUCLEO."modulos/finanzas/finanzas.class.php");
		$finanzas = new FINANZAS($this->fmt);

		if ($tipo_orden=="id"){ $tipo_o = "mod_prod_id"; }
		if ($tipo_orden=="fecha"){ $tipo_o = "mod_prod_fecha"; }
		if ($tipo_orden=="orden"){ $tipo_o = "mod_prod_cat_orden"; }
		$ra_cat= $this->fmt->categoria->ruta_amigable($cat)."/";
		$sql="select mod_prod_id, mod_prod_nombre, mod_prod_detalles, mod_prod_ruta_amigable, mod_prod_imagen, mod_prod_precio, mod_prod_precio_detalle, mod_prod_tags, mod_prod_disponibilidad from mod_productos, mod_productos_categorias where mod_prod_cat_prod_id=mod_prod_id and mod_prod_cat_cat_id='$cat' ORDER BY $tipo_o  $orden LIMIT ".$limite;
		$rs =$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
			for($i=0;$i<$num;$i++){
		 		$row=$this->fmt->query->obt_fila($rs);
				$id=$row["mod_prod_id"];
				$nombre=$row["mod_prod_nombre"];
				$resumen=$row["mod_prod_resumen"];
				$detalles=$row["mod_prod_detalles"];
				$ra=$row["mod_prod_ruta_amigable"];
				$imagen=$row["mod_prod_imagen"];
				$precio=$row["mod_prod_precio"];
				$precio_detalle=$row["mod_prod_precio_detalle"];
				$tags=$row["mod_prod_tags"];
				$disponibilidad=$row["mod_prod_disponibilidad"];
				// echo $tipo_img;
				if ($tipo_img=="thumb"){
					$img = _RUTA_IMAGES.$this->fmt->archivos->convertir_url_thumb($imagen);
				}
				if($tipo_img=="original"){
					$img = _RUTA_IMAGES.$imagen;
				}				
				if($tipo_img=="web"){
					$img = _RUTA_IMAGES.$this->fmt->archivos->convertir_url_web($imagen);
				}
				$url = _RUTA_WEB.$ra_cat.$ra.".prod";

				if ($i%2==0){
				    $au = "par";
				}else{
				    $au = "impar";
				}
				?>
				<div class="item-producto <?php echo $au; ?> item-prod-<?php echo $i; ?>"  item="<?php echo $id; ?>" id="item-<?php echo $id; ?>" >
					<div class="item-img" style="background:url(<?php echo $img; ?>) no-repeat center center;"><a href="<?php echo $url; ?>"></a></div>
					<div class="item-datos">
						<div class="item item-cat">
							<?php
								$cts= $this->rel_id_cat($id);
								//echo "cats:".$cat;
								for ($ix=0; $ix < count($cts) ; $ix++) {
									if ($cts[$ix]!=$cat) {
										echo "<div class='i-cat'>".$this->fmt->categoria->nombre_categoria($cts[$ix]),"</div>";
									}
								}
							?>
						</div>
						<div class="item-nombre"><a href="<?php echo $url; ?>"><?php echo $nombre; ?></a></div>
						<div class="item item-resumen"><?php echo $resumen; ?></div>
						<div class="item item-detalles"><?php echo $detalles; ?></div>
						<div class="precio-anterior"><?php echo $precio_detalle; ?></div>
						<?php
						if (!empty($addend)){
							echo "<a class='item item-btn' href='$url' item='$id' >$addend</a>";
						}
						if (!empty($precio)){
							?>
							<div class="precio">Bs. <?php echo $finanzas->convertir_moneda($precio); ?></div>
							<!-- <div class="disponibilidad"><?php echo $disponibilidad; ?></div> -->
							<div class="botones">
								<div class="botones-inner">
									<button item="<?php echo $id; ?>" type="button" name="button" class="btn btn-adicionar-carrito"><i></i>Adicionar a carrito</button>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>
				<?
			}
		}
	}

	function precio_producto($id){
		$sql="SELECT  mod_prod_precio FROM mod_productos WHERE  mod_prod_id='$id' and mod_prod_activar=1";
		$rs =$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
		$fila=$this->fmt->query->obt_fila($rs);
		return $fila["mod_prod_precio"];
	}

  function ordenar(){
		$id_cat = $this->id_item;
		$this->fmt->class_pagina->crear_head_form("Ordenar: ".$this->fmt->categoria->nombre_categoria($id_cat),"","");
		$id_form="form-editar";
		?>
		<div class="body-modulo">
		  <form class="form form-modulo form-ordenar"  method="POST" id="<?php echo $id_form?>">
				<ul id="orden-cat" class="list-group">
					<?php
					$sql="select mod_prod_id, mod_prod_nombre, mod_prod_imagen, mod_prod_cat_orden from mod_productos, mod_productos_categorias where mod_prod_cat_prod_id=mod_prod_id and mod_prod_cat_cat_id='$id_cat' ORDER BY mod_prod_cat_orden asc";

	        $rs =$this->fmt->query->consulta($sql,__METHOD__);
	        $num=$this->fmt->query->num_registros($rs);
	        if($num>0){
		        for($i=0;$i<$num;$i++){
		          $row=$this->fmt->query->obt_fila($rs);
							$prod_id=$row["mod_prod_id"];
							$prod_nom=$row["mod_prod_nombre"];
							$prod_imagen=_RUTA_WEB.$this->fmt->archivos->convertir_url_thumb($row["mod_prod_imagen"]);
							echo "<li id_var='$prod_id'><i class='icn icn-reorder'></i><span class='img-prod' style='background:url($prod_imagen)no-repeat center center'></span><span class='nombre'>$prod_nom</span></li>";
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
						formdata.append("cat","<?php echo $id_cat;?>");
						formdata.append("ajax","ajax-adm");
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
		$id_cat=$_POST["cat"];
	  $this->fmt->class_modulo->eliminar_fila($id_cat,"mod_productos_categorias","mod_prod_cat_cat_id");
	  $ingresar2 ="mod_prod_cat_prod_id,mod_prod_cat_cat_id,mod_prod_cat_orden";
	  $valor_doc= $_POST['id_item'];
	  $num=count( $valor_doc );
	  for ($i=0; $i<$num;$i++){
		  $valores2 = "'".$valor_doc[$i]."','".$id_cat."','".$i."'";
		  $sql2="insert into mod_productos_categorias (".$ingresar2.") values (".$valores2.")";
		  $this->fmt->query->consulta($sql2);
	  }
	 // $this->fmt->class_modulo->redireccionar($this->ruta_modulo,"1");
	}

	function traer_rel_cat_nombres($fila_id){
		$consulta = "SELECT DISTINCT cat_id, cat_nombre FROM categoria, mod_productos_categorias WHERE mod_prod_cat_prod_id='".$fila_id."' and cat_id = mod_prod_cat_cat_id";
		$rs = $this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);
		if ($num>0){
			for ($i=0;$i<$num;$i++){
				$row=$this->fmt->query->obt_fila($rs);
				echo "- <a class='btn-menu-ajax' id_mod='".$this->id_mod."' vars='ordenar,".$row["cat_id"]."' > ".$row["cat_nombre"]."</a><br/>";
			}
		}
	}

	function rel_id_cat($id_prod){
		$consulta = "SELECT mod_prod_cat_cat_id FROM mod_productos_categorias WHERE mod_prod_cat_prod_id='".$id_prod."'";
		$rs = $this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);
		if ($num>0){
			for ($i=0;$i<$num;$i++){
				$row=$this->fmt->query->obt_fila($rs);
				$valor[$i]=$row["mod_prod_cat_cat_id"];
			}
		}
		return $valor;
	}


	function form_editar(){

		$this->fmt->class_pagina->crear_head_form("Editar Producto","","");
		$id_form="form-editar";
		//echo "id:".
		$id = $this->id_item;
		$id_rol = $this->fmt->sesion->get_variable("usu_rol");

		$consulta= "SELECT * FROM mod_productos_conf";
		$rs =$this->fmt->query->consulta($consulta);
		$row_conf=$this->fmt->query->obt_fila($rs);

		$consulta= "SELECT * FROM mod_productos WHERE mod_prod_id='".$id."'";
		$rs =$this->fmt->query->consulta($consulta);
		$fila=$this->fmt->query->obt_fila($rs);
		if (!empty($fila['mod_prod_ruta_amigable'])){
		  $valor_ra = $fila['mod_prod_ruta_amigable'];
		}else{
		  $valor_ra = $this->fmt->get->convertir_url_amigable($fila['mod_prod_nombre']);
		}

		$fila_id = $fila['mod_prod_id'];
		$fila_imagen = $fila['mod_prod_imagen'];
		$fila_dominio = $fila['mod_prod_id_dominio'];

		if (empty($fila_dominio)){ $aux=_RUTA_WEB; } else {
			$aux = $this->fmt->categoria->traer_dominio_cat_id($fila_dominio);
		}

		//$img=$aux.$this->fmt->archivos->convertir_url_thumb( $fila_imagen );
		$img=$aux.$this->fmt->archivos->convertir_url_mini($fila_imagen);
		//echo "aqui";
		// $this->fmt->form->finder("inputImagen",$this->id_mod,$fila_imagen,"individual-multimedia","imagenes-videos",$fila['mod_prod_tipo_archivo']); //$id,$id_mod,$url,$tipo_upload="individual",$tipo_archivo
		//$this->fmt->form->finder("",$this->id_mod,"","multiple-accion","");

		?>
		<div class="body-modulo">
		  <form class="form form-modulo form-producto"  method="POST" id="<?php echo $id_form?>">
				<?php
					// $this->fmt->form->imagen_form_block("inputImagen",$fila_id,$fila_imagen);  // $id,$id_item,$valor,$class_div
					//$this->fmt->form->imagen_unica("inputImagen",$fila_imagen);

					$this->fmt->form->imagen_unica_form("inputImagen",$fila_imagen);
					if ($row_conf["mod_prod_conf_multimedia"]==1){
					$this->fmt->form->multimedia_form_block($fila_id,$this->id_mod);
				}

					$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","",$fila['mod_prod_nombre'],"","","");
		      $this->fmt->form->ruta_amigable_form("inputNombre",_RUTA_WEB,$fila['mod_prod_ruta_amigable'],"inputRutaamigable"); //$id,$ruta,$valor,$form
					$this->fmt->form->input_hidden_form("inputId",$id);
		      //$this->fmt->form->hidden_modulo($this->id_mod,"modificar");
		      $this->fmt->form->input_form("Tags:","inputTags","",$fila['mod_prod_tags'],"","","");
					if ($row_conf["mod_prod_conf_codigo"]==1){
						$this->fmt->form->input_form("Codigo:","inputCodigo","",$fila['mod_prod_codigo'],"box-md-4","","");
					}
					if ($row_conf["mod_prod_conf_sap"]==1){
						$this->fmt->form->input_form("Codigo SAP:","inputCodigoSAP","",$fila['mod_prod_sap'],"box-md-4","","");
					}
					if ($row_conf["mod_prod_conf_modelo"]==1){
		      	$this->fmt->form->input_form("Modelo:","inputModelo","",$fila['mod_prod_modelo'],"box-md-4","","");
					}
					if ($row_conf["mod_prod_conf_marca"]==1){
						$url_marca=_RUTA_WEB."dashboard/marcas";
		      	$this->fmt->form->select_form("Marca:","inputMarca","mod_mar_","mod_marcas",$fila["mod_prod_id_marca"],"","","box-md-4","<a href='".$url_marca."' target='_blank' class='btn btn-full'>Añadir Marca</a>"); //$label,$id,$prefijo,$from,$id_select,$id_disabled,$class_div,$class_select

					}

		      $this->fmt->form->textarea_form("Resumen:","inputResumen","",$fila['mod_prod_resumen'],"","","5",""); //$label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje
					if ($row_conf["mod_prod_conf_detalles"]==1){
						$this->fmt->form->textarea_form("Detalles:","inputDetalles","",$fila['mod_prod_detalles'],"editor-texto","","5","");
						$this->fmt->class_modulo->modal_editor_texto("inputDetalles");
					}
					if ($row_conf["mod_prod_conf_especificaciones"]==1){
		      	$this->fmt->form->textarea_form("Especificaciones:","inputEspecificaciones","",$fila['mod_prod_especificaciones'],"editor-texto","","5","");
						$this->fmt->class_modulo->modal_editor_texto("inputEspecificaciones");
					}
					if ($row_conf["mod_prod_conf_disponibilidad"]==1){
		      	$this->fmt->form->input_form("Disponibilidad:","inputDisponibilidad","Inmediata, a 30 días, a 15 días, definido por pedido",$fila['mod_prod_disponibilidad'],"","","");
					}
					if ($row_conf["mod_prod_conf_precio"]==1){
		      	$this->fmt->form->input_form("Precio:","inputPrecio","",$fila['mod_prod_precio'],"","","");
		      }

					if ($row_conf["mod_prod_conf_precio_detalle"]==1){
		      	$this->fmt->form->input_form("Precio Detalle:","inputPrecioDetalle","",$fila['mod_prod_precio_detalle'],"","","");
					}
					if ($row_conf["mod_prod_conf_modelo"]==1){
		      	$this->fmt->form->input_form("Precio:","inputPrecio","",$fila['mod_prod_precio'],"","","");
					}
					if ($row_conf["mod_prod_conf_docs"]==1){
		      	//$this->fmt->form->agregar_documentos("Documentos:","inputDoc",$fila['mod_prod_id'],"","","","mod_marcas_productos","mod_mar_prod_id","mod_prod_rel_doc_id"); //$label,$id,$valor,$class,$class_div,$mensaje,$from,$id_item
						$this->fmt->form->documentos_form($fila_id,$this->id_mod,"","Documentos relacionados:","mod_productos_docs","mod_prod_doc_","prod_");
					}
					if ($row_conf["mod_prod_conf_pestana"]==1){
		      	$this->fmt->form->agregar_pestana("Pestaña:","inputPestana",$fila['mod_prod_id'],"","","","mod_productos_pestana","mod_pro_pes_pro_id","mod_pro_pes_pes_id"); //$label,$id,$valor,$class,$class_div,$mensaje,$from,$id_item
					}
		      if($id_rol==1)
		      $this->fmt->form->categoria_form('Categoria','inputCat',"0",$this->rel_id_cat($fila['mod_prod_id']),"",""); //$$label,$id,$cat_raiz,$cat_valor,$class,$class_div
		      else{
		        $sql="select rol_cat_cat_id from roles_categorias where rol_rel_rol_id=".$id_rol." and rol_cat_cat_id not in (0) ORDER BY rol_cat_cat_id asc";
		              $rs =$this->fmt->query->consulta($sql);
		            $num=$this->fmt->query->num_registros($rs);
		            if($num>0){
		              for($i=0;$i<$num;$i++){
		                list($fila_id)=$this->fmt->query->obt_fila($rs);
		                $nombre_cat=$this->fmt->categoria->nombre_categoria($fila_id);
		              $this->fmt->form->categoria_form('Categoria - '.$nombre_cat,'inputCat',$fila_id,$this->rel_id_cat($fila['mod_prod_id']),"","");
		            }
		              }
		      }
		      $label[0]="Mostrar Categoria";
		      $nombreinput[0]="inputActCat";
		      $valor_in[0]="1";
		      $campo_in[0]=$fila['mod_prod_activar_cat'];
		      $this->fmt->form->input_check_form($label,$nombreinput,$valor_in,$campo_in);
		      // $this->fmt->form->radio_activar_form($fila['mod_prod_activar']);
					$this->fmt->form->vista_item($fila['mod_prod_activar'],".box-botones-form");
		      $this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");//$id_form,$id_mod,$tarea,$div_class
		       //$id_form,$id_mod,$tarea
				?>
		  </form>
		</div>
		<?php
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Producto","","");
		$id_form="form-nuevo";
		//echo "id:".
		$consulta= "SELECT * FROM mod_productos_conf";
		$rs =$this->fmt->query->consulta($consulta);
		$row_conf=$this->fmt->query->obt_fila($rs);

		//$this->fmt->form->finder("inputImagen",$this->id_mod,"","individual","imagenes"); //$id,$id_mod,$url,$tipo_upload="individual",$tipo_archivo
		// $this->fmt->form->finder("inputImagen",$this->id_mod,"","individual-multimedia","imagenes-videos","");
		//$this->fmt->form->finder("",$this->id_mod,"","multiple-accion","");
		?>
		<div class="body-modulo">
		  <form class="form form-modulo form-producto"  method="POST" id="<?php echo $id_form?>">
					<?php
					//$this->fmt->form->imagen_form_block("inputImagen","","");
					$this->fmt->form->imagen_unica_form("inputImagen",$fila_imagen);
					$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","","","","","");
					$this->fmt->form->ruta_amigable_form("inputNombre",_RUTA_WEB,"","inputRutaamigable"); //$id,$ruta,$valor,$form

					//$this->fmt->form->input_form("Nombre Amigable:","inputNombreAmigable","",$valor_ra,"","","","");
					//$this->fmt->form->input_hidden_form("inputId",$id);
					//$this->fmt->form->hidden_modulo($this->id_mod,"modificar");
					$this->fmt->form->input_form("Tags:","inputTags","","","","","");
					//$this->fmt->form->input_hidden_form("inputArchivosEdit",$fila['mod_prod_imagen']);
					if ($row_conf["mod_prod_conf_codigo"]==1){
						$this->fmt->form->input_form("Codigo:","inputCodigo","","","box-md-4","","");
					}
					if ($row_conf["mod_prod_conf_sap"]==1){
						$this->fmt->form->input_form("Codigo SAP:","inputCodigoSAP","","","box-md-4","","");
					}
					if ($row_conf["mod_prod_conf_modelo"]==1){
						$this->fmt->form->input_form("Modelo:","inputModelo","","","box-md-4","","");
					}
					if ($row_conf["mod_prod_conf_marca"]==1){
						$url_marca=_RUTA_WEB."dashboard/marcas";
						$this->fmt->form->select_form("Marca:","inputMarca","mod_mar_","mod_marcas","","","","box-md-4","<a href='".$url_marca."' target='_blank' class='btn btn-full'>Añadir Marca</a>"); //$label,$id,$prefijo,$from,$id_select,$id_disabled,$class_div,$class_select
					}
					$this->fmt->form->textarea_form("Resumen:","inputResumen","","","","","5",""); //$label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje
					if ($row_conf["mod_prod_conf_detalles"]==1){
						$this->fmt->form->textarea_form("Detalles:","inputDetalles","","","editor-texto","","5","");
						$this->fmt->class_modulo->modal_editor_texto("inputDetalles");
					}
					if ($row_conf["mod_prod_conf_especificaciones"]==1){
						$this->fmt->form->textarea_form("Especificaciones:","inputEspecificaciones","","","editor-texto","","5","");
						$this->fmt->class_modulo->modal_editor_texto("inputEspecificaciones");
					}
					if ($row_conf["mod_prod_conf_disponibilidad"]==1){
						$this->fmt->form->input_form("Disponibilidad:","inputDisponibilidad","Inmediata, a 30 días, a 15 días, definido por pedido","","","","");
					}
					if ($row_conf["mod_prod_conf_precio_detalle"]==1){
						$this->fmt->form->input_form("Precio Detalle:","inputPrecioDetalle","","","","","");
					}
					if ($row_conf["mod_prod_conf_precio"]==1){
						$this->fmt->form->input_form("Precio:","inputPrecio","","","","","");
					}
					if ($row_conf["mod_prod_conf_docs"]==1){
						$this->fmt->form->agregar_documentos("Documentos:","inputDoc","","","","","mod_productos_rel","mod_prod_rel_prod_id","mod_prod_rel_doc_id"); //$label,$id,$valor,$class,$class_div,$mensaje,$from,$id_item
					}
					if ($row_conf["mod_prod_conf_pestana"]==1){
						$this->fmt->form->agregar_pestana("Pestaña:","inputPestana","","","","","mod_productos_pestana","mod_pro_pes_pro_id","mod_pro_pes_pes_id"); //$label,$id,$valor,$class,$class_div,$mensaje,$from,$id_item
					}

					$this->fmt->form->categoria_form('Categoria','inputCat',"0","","",""); //$$label,$id,$cat_raiz,$cat_valor,$class,$class_div
					$label[0]="Mostrar Categoria";
					$this->fmt->form->input_check_form($label,"inputActCat");
					//$this->fmt->form->radio_activar_form();

					$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar"); //botones_nuevo($form,$id_mod,$modo="",$tarea)
					 //$id_form,$id_mod,$tarea
					?>
			</form>
		</div>
		<?php
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}


	function ingresar(){
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}

		if($_POST['inputActCat']=="1"){
			$activar_cat=1;
		}
		else{
			$activar_cat=0;
		}
		if($_POST["inputNombre"]!=""){
			$ingresar ="mod_prod_nombre, mod_prod_ruta_amigable,mod_prod_imagen, mod_prod_tags, mod_prod_codigo,mod_prod_sap, mod_prod_modelo,mod_prod_resumen, mod_prod_detalles, mod_prod_especificaciones, mod_prod_disponibilidad, mod_prod_precio,mod_prod_precio_detalle, mod_prod_id_marca, mod_prod_id_doc, mod_prod_id_mul, mod_prod_id_dominio, mod_prod_activar_cat, mod_prod_activar";
			$valores  ="'".$_POST['inputNombre']."','".
										 $_POST['inputRutaamigable']."','".
										 $_POST['inputImagen']."','".
										 $_POST['inputTags']."','".
										 $_POST['inputCodigo']."','".
										 $_POST['inputCodigoSAP']."','".
										 $_POST['inputModelo']."','".
										 $_POST['inputResumen']."','".
										 $_POST['inputDetalles']."','".
										 $_POST['inputEspecificaciones']."','".
										 $_POST['inputDisponibilidad']."','".
										 $_POST['inputPrecio']."','".
										 $_POST['inputPrecioDetalle']."','".
										 $_POST['inputMarca']."','".
										 $_POST['inputDoc']."','".
										 $_POST['inputMul']."','".
										 $_POST['inputDominio']."','".
										 $activar_cat."','".
										 $activar."'";

			$sql="insert into mod_productos (".$ingresar.") values (".$valores.")";
			$this->fmt->query->consulta($sql);

			$sql="select max(mod_prod_id) as id from mod_productos";
			$rs= $this->fmt->query->consulta($sql);
			$fila = $this->fmt->query->obt_fila($rs);
			$id = $fila ["id"];

		  // $sql_upd="UPDATE mod_productos_mul SET mod_prod_mul_prod_id='$id' where mod_prod_mul_prod_id='0'";
		  // $this->fmt->query->consulta($sql_upd);

			// $ingresar1 = "mod_prod_rel_prod_id,mod_prod_rel_cat_id";
			// $valor_cat=$_POST['inputCat'];
			// $num_cat=count( $valor_cat );
			// //var_dump($valor_cat);
			//
			// for ($i=0; $i<$num_cat;$i++){
			//   $valores1 = "'".$id."','".$valor_cat[$i]."'";
			//   $sql1="insert into mod_productos_rel  (".$ingresar1.") values (".$valores1.")";
			//   $this->fmt->query->consulta($sql1);
			// }

			// producto_categoria
			$ingresar1 = "mod_prod_cat_prod_id,mod_prod_cat_cat_id";
			$valor_cat=$_POST['inputCat'];
			$num_cat=count( $valor_cat );
			//var_dump($valor_cat);

			for ($i=0; $i<$num_cat;$i++){
			  $valores1 = "'".$id."','".$valor_cat[$i]."'";
			  $sql1="insert into mod_productos_categorias  (".$ingresar1.") values (".$valores1.")";
			  $this->fmt->query->consulta($sql1);
			}

 			if ($row_conf["mod_prod_conf_docs"]==1){
				$ingresar2 ="mod_prod_rel_prod_id,mod_prod_rel_doc_id,mod_prod_rel_orden";
				//var_dump($_POST['inputDoc']);
				$valor_doc= $_POST['inputDoc'];
				$num=count( $valor_doc );
				for ($i=0; $i<$num;$i++){
				  $valores2 = "'".$id."','".$valor_doc[$i]."','".$i."'";
				  $sql2="insert into mod_productos_rel (".$ingresar2.") values (".$valores2.")";
				  $this->fmt->query->consulta($sql2);
				}
			}
			if ($row_conf["mod_prod_conf_pertana"]==1){
				$ingresar3 = "mod_pro_pes_pro_id,mod_pro_pes_pes_id,mod_pro_pes_contenido,mod_pro_pes_orden";
				$valor_cat=$_POST['inputPestana'];
				$num_cat=count( $valor_cat );
				//var_dump($valor_cat);

				for ($i=0; $i<$num_cat;$i++){
				  $id_pes=$valor_cat[$i];
				  $valores3 = "'".$id."','".$id_pes."','".$_POST["contenido".$id_pes]."','".$_POST["orden_pest".$id_pes]."'";
				  $sql1="insert into mod_productos_pestana
				(".$ingresar3.") values (".$valores3.")";
				  $this->fmt->query->consulta($sql1);
				}
			}

		}
		 $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}

	function modificar(){
		$consulta= "SELECT * FROM mod_productos_conf";
		$rs =$this->fmt->query->consulta($consulta);
		$row_conf=$this->fmt->query->obt_fila($rs);

		if($_POST['inputActCat']=="1"){
		  $activar_cat=1;
		}
		else{
		  $activar_cat=0;
		}

		if($_POST["inputNombre"]!=""){
		    $sql="UPDATE mod_productos SET
		          mod_prod_nombre='".$_POST['inputNombre']."',
		          mod_prod_ruta_amigable ='".$_POST['inputRutaamigable']."',
		          mod_prod_imagen ='".$_POST['inputImagen']."',
		          mod_prod_tags ='".$_POST['inputTags']."',
		          mod_prod_codigo='".$_POST['inputCodigo']."',
		          mod_prod_sap='".$_POST['inputCodigoSAP']."',
		          mod_prod_modelo='".$_POST['inputModelo']."',
		          mod_prod_resumen='".$_POST['inputResumen']."',
		          mod_prod_detalles='".$_POST['inputDetalles']."',
		          mod_prod_especificaciones='".$_POST['inputEspecificaciones']."',
		          mod_prod_disponibilidad='".$_POST['inputDisponibilidad']."',
		          mod_prod_precio='".$_POST['inputPrecio']."',
		          mod_prod_precio_detalle='".$_POST['inputPrecioDetalle']."',
		          mod_prod_id_marca='".$_POST['inputMarca']."',
		          mod_prod_id_dominio='".$_POST['inputDominio']."',
		          mod_prod_id_doc='".$_POST['inputDoc']."',
		          mod_prod_id_mul='".$_POST['inputMul']."',
		          mod_prod_activar_cat='".$activar_cat."',
		          mod_prod_activar='".$_POST['inputActivar']."'
		          WHERE mod_prod_id='".$_POST['inputId']."'";
		    //echo $sql;
		    $this->fmt->query->consulta($sql);

		    // $this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"mod_productos_rel","mod_prod_rel_prod_id","0");  //$valor,$from,$fila

		    $this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"mod_productos_categorias","mod_prod_cat_prod_id","0");  //$valor,$from,$fila
		    $this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"mod_productos_mul","mod_prod_mul_prod_id","0");  //$valor,$from,$fila
		    $this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"mod_productos_docs","mod_prod_doc_prod_id","0");  //$valor,$from,$fila

		    $ingresar = "mod_prod_mul_prod_id,mod_prod_mul_mul_id,mod_prod_mul_orden";
		    $valor_mul=$_POST['inputModItemMul'];
		    $num_mul=count( $valor_mul );
		    //var_dump($valor_mul);
		    for ($i=0; $i<$num_mul;$i++){
		      $valores = "'".$_POST['inputId']."','".$valor_mul[$i]."','$i'";
		      $sql="insert into mod_productos_mul  (".$ingresar.") values (".$valores.")";
		      $this->fmt->query->consulta($sql);
		    }


		    // producto_categoria
		    $id =$_POST['inputId'];
		    $ingresar1 = "mod_prod_cat_prod_id,mod_prod_cat_cat_id";
		    $valor_cat=$_POST['inputCat'];
		    $num_cat=count( $valor_cat );
		    for ($i=0; $i<$num_cat;$i++){
		      $valores1 = "'".$id."','".$valor_cat[$i]."'";
		      $sql1="insert into mod_productos_categorias  (".$ingresar1.") values (".$valores1.")";
		      $this->fmt->query->consulta($sql1);
		    }


		    if ($row_conf["mod_prod_conf_docs"]==1){
		      $ingresar2 ="mod_prod_doc_prod_id,mod_prod_doc_doc_id,mod_prod_doc_orden";
		      //var_dump($_POST['inputDoc']);
		      $valor_doc= $_POST['inputModItemDoc'];
		      $num=count( $valor_doc );
		      for ($i=0; $i<$num;$i++){
		        $valores2 = "'".$_POST['inputId']."','".$valor_doc[$i]."','".$i."'";
		        $sql2="insert into mod_productos_docs (".$ingresar2.") values (".$valores2.")";
		        $this->fmt->query->consulta($sql2);
		      }
		    }


		    if ($row_conf["mod_prod_conf_pertana"]==1){
		      $this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"mod_productos_pestana","mod_pro_pes_pro_id");

		      $ingresar3 = "mod_pro_pes_pro_id,mod_pro_pes_pes_id,mod_pro_pes_contenido,mod_pro_pes_orden";
		      $valor_cat=$_POST['inputPestana'];
		      $num_cat=count( $valor_cat );
		      //var_dump($valor_cat);

		      for ($i=0; $i<$num_cat;$i++){
		        $id_pes=$valor_cat[$i];
		        $valores3 = "'".$_POST['inputId']."','".$id_pes."','".$_POST["contenido".$id_pes]."','".$_POST["orden_pest".$id_pes]."'";
		        $sql1="insert into mod_productos_pestana
		        (".$ingresar3.") values (".$valores3.")";
		        $this->fmt->query->consulta($sql1);
		      }
		    }
		}
		$this->fmt->class_modulo->redireccionar($this->ruta_modulo,"1");
	}




	function activar(){
		$this->fmt->class_modulo->activar_get_id("mod_productos","mod_prod_",$this->id_estado,$this->id_item);
		$this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
	}

	function eliminar(){

		$id= $this->id_item;

		$sql="DELETE FROM mod_productos WHERE mod_prod_id='".$id."'";
		$this->fmt->query->consulta($sql);

		$sql="DELETE FROM mod_productos_rel WHERE mod_prod_rel_prod_id='".$id."'";
		$this->fmt->query->consulta($sql);

		$sql="DELETE FROM mod_productos_pestana WHERE mod_pro_pes_pro_id='".$id."'";
		$this->fmt->query->consulta($sql);

		$this->eliminar_mul($id);

		$up_sqr6 = "ALTER TABLE mod_productos AUTO_INCREMENT=1";
		$this->fmt->query->consulta($up_sqr6);

		$up_sqr7 = "ALTER TABLE mod_productos_rel AUTO_INCREMENT=1";
		$this->fmt->query->consulta($up_sqr7);



		$this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
	}
	function eliminar_mul($id){
		$sql="DELETE FROM mod_productos_mul WHERE mod_pro_mul_id_prod='".$id."'";
		$this->fmt->query->consulta($sql);
		$up_sqr8 = "ALTER TABLE mod_productos_mul AUTO_INCREMENT=1";
		$this->fmt->query->consulta($up_sqr8);
	}
}
