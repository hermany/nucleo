<?php
header("Content-Type: text/html;charset=utf-8");

class NOTICIAS{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function NOTICIAS($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){
		//$this->fmt->form->head_busqueda_simple('Nueva Noticia',$this->id_mod,'');
		$botones = $this->fmt->class_pagina->crear_btn_m("Configuración","icn-conf","Configuración","btn btn-link btn-menu-ajax btn-congig",$this->id_mod,"form_config");  //$nom,$icon,$title,$clase,$id_mod,$vars,$attr


		$this->fmt->class_pagina->crear_head( $this->id_mod, $botones); // bd, id modulo, botones //$nom,$archivo,$id_mod,$botones
		$fecha_hoy= $this->fmt->class_modulo->fecha_hoy("America/La_Paz");
		?>
		<link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO; ?>css/m-notas.css" rel="stylesheet" type="text/css">

		<div class="body-modulo container-fluid">
			<div class="container">
				<?php
					$botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nueva noticia","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars

					$this->fmt->class_pagina->head_modulo_inner("Lista de Notas", $botones); // bd, id modulo, botones
					$this->fmt->form->head_table('table_id');
					$this->fmt->form->thead_table('#:Titulo:Autor:Categorías:Fecha:Estado:Acciones');
					$this->fmt->form->tbody_table_open();
					$sql="SELECT * FROM nota ORDER BY not_fecha desc";
					$rs =$this->fmt->query->consulta($sql);
					$num=$this->fmt->query->num_registros($rs);
					if($num>0){
					  for($i=0;$i<$num;$i++){
					    $fila=$this->fmt->query->obt_fila($rs);

					    echo "<tr class='row row-".$fila["not_id"]."'>";
					    echo '<td class="">'.$fila["not_id"].'</td>';
					    echo '<td class=""><strong>'.$fila["not_titulo"].'</strong></td>';
					    echo '<td class="">'.$this->fmt->usuario->nombre_usuario( $fila["not_usuario"]).'</td>';
					    echo '<td class="">';
					      //$this->fmt->categoria->traer_rel_cat_nombres($fila["not_id"],'nota_categorias','not_cat_cat_id','not_cat_not_id');

								$this->traer_rel_cat_nombres($fila["not_id"]);
								 //$fila_id,$from,$prefijo_cat,$prefijo_rel

					    echo '</td>';
					    //echo '<td class="">'.$fila["not_tags"].'</td>';
							$fh =$fila["not_fecha"];
							$fecha= $this->fmt->class_modulo->tiempo_restante($fh,$fecha_hoy);
					    echo '<td class=""><span class="display-none">'.$fh.'</span>'.$fecha.'</td>';
					    echo '<td class="">';
					    $this->fmt->class_modulo->estado_publicacion($fila["not_activar"], $this->id_mod,"", $fila["not_id"]);
					    echo '</td>';

					    echo '<td class="td-user col-xl-offset-2 acciones">';
					    echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar","btn btn-accion btn-editar btn-menu-ajax ",$this->id_mod,"form_editar,".$fila["not_id"]);
					      ?>
					        <a  title="eliminar <?php echo $fila["not_id"]; ?>" nombre="<?php echo $fila["not_titulo"]; ?>" type="button" id="btn-m<?php echo $this->id_mod; ?>" id_mod="<?php echo $this->id_mod; ?>" vars="eliminar,<?php echo $fila["not_id"]; ?>" class="btn btn-fila-eliminar btn-accion "><i class="icn-trash"></i></a>
					      <?php
					    echo '</td>';
					    echo "</tr>";
						}
					}
					$this->fmt->form->tbody_table_close();
					$this->fmt->form->footer_table();
				 ?>
			</div> <!-- fin container -->
		</div> <!-- fin container-fluid -->

		<?php

		$this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","4","10",true);
		$this->fmt->class_modulo->script_accion_modulo();
 //$id,$id_mod,$tipo="asc",$orden=0,$cant=25,$pag_up=fals
	}

	function traer_rel_cat_nombres($fila_id){
		$consulta = "SELECT DISTINCT cat_id, cat_nombre FROM categoria, nota_categorias WHERE not_cat_not_id='".$fila_id."' and cat_id = not_cat_cat_id";
		$rs = $this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);
		if ($num>0){
			for ($i=0;$i<$num;$i++){
				$row=$this->fmt->query->obt_fila($rs);
				echo "- <a class='btn-menu-ajax' id_mod='".$this->id_mod."' vars='ordenar,".$row["cat_id"]."' > ".$row["cat_nombre"]."</a><br/>";
			}
		}
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
					$sql="SELECT not_id, not_titulo, not_imagen, not_cat_orden FROM nota, nota_categorias where not_cat_not_id=not_id and not_cat_cat_id='$id_cat' ORDER BY not_cat_orden asc";

	        $rs =$this->fmt->query->consulta($sql,__METHOD__);
	        $num=$this->fmt->query->num_registros($rs);
	        if($num>0){
		        for($i=0;$i<$num;$i++){
		          $row=$this->fmt->query->obt_fila($rs);
							$row_id=$row["not_id"];
							$row_nom=$row["not_titulo"];
							$row_imagen=_RUTA_WEB.$this->fmt->archivos->convertir_url_thumb($row["not_imagen"]);
							echo "<li id_var='$row_id'><i class='icn icn-reorder'></i><span class='img-prod' style='background:url($row_imagen)no-repeat center center'></span><span class='nombre'>$row_nom</span></li>";
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
		$id_cat=$_POST["cat"];
		$this->fmt->class_modulo->eliminar_fila($id_cat,"nota_categorias","not_cat_cat_id");
		$ingresar2 ="not_cat_not_id,not_cat_cat_id,not_cat_orden";
		$valor_doc= $_POST['id_item'];
		$num=count( $valor_doc );
		for ($i=0; $i<$num;$i++){
			$valores2 = "'".$valor_doc[$i]."','".$id_cat."','".$i."'";
			$sql2="insert into nota_categorias (".$ingresar2.") values (".$valores2.")";
			$this->fmt->query->consulta($sql2);
		}
	 //$this->fmt->class_modulo->redireccionar($this->ruta_modulo,"1");
	}

	function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nueva Noticia","","");
		$id_form="form-nuevo";

		//$this->fmt->form->finder("inputCuerpo",$this->id_mod,"","individual-complejo","imagenes");
		//$this->fmt->form->finder("",$this->id_mod,"","multiple-accion","");
		?>
		<div class="body-modulo" id="body-modulo-notas">
			<form class="form form-modulo form-noticia"  method="POST" id="<?php echo $id_form?>">
				<div class=" col-form">
					<?php
						$this->fmt->form->input_form("<span class='obligatorio'>*</span> Titulo:","inputTitulo","","","input-lg","row-lg","","","","autocomplete=off"); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros
						$this->fmt->form->ruta_amigable_form("inputTitulo","","","inputRutaamigable","","input-lg"); //$id,$ruta,$valor,$form

						$this->fmt->form->input_form('Tags:','inputTags','','','');

						$this->fmt->form->textarea_form('Resumen:','inputResumen','','','','','3','','');
						$this->fmt->form->textarea_form('Cuerpo:','inputCuerpo','','','editor-texto','textarea-cuerpo','40',''); //label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje
						$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');
						//$this->fmt->form->input_form('Fecha:','inputFecha','',$fecha,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
						$usuario = $this->fmt->sesion->get_variable('usu_id');
						// $usuario_n =  $this->fmt->usuario->nombre_usuario( $usuario );
						// $this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
						$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
						//$this->fmt->form->input_form('Orden:','inputOrden','','0','','','');
						$this->fmt->form->input_form('Lugar de procedencia:','inputLugar','','','','','');
						//$this->nombre_autor($fila["not_id_autor"]);
						$this->fmt->form->input_form('Autor:','inputAutor','','','','','');
					?>
				</div>
				<div class=" col-nav">
					<?php
						// $this->fmt->form->imagen_unica("inputImagen","","Imagen principal","img-control");
						$this->fmt->form->imagen_unica_form("inputImagen");
						$this->fmt->form->video_unico("inputVideo","","inputTipoVideo","","Video Nota","video-control");
					 //	$text="Cargar archivo";
						//$this->fmt->form->imagen_form("Imagen:",$text,"inputImagen","","");
						$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');
						$this->fmt->form->input_date_form('','inputFecha','',$fecha,'','form-vertical','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
						$this->fmt->form->categoria_form('Categoria','inputCat',"0","","","");

					 ?>
				</div>
				<?php
					$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar"); //$id_form,$id_mod,$tarea
				?>
			</form>

		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#inputTitulo").on('keyup', function(){
					var id_val = $("#inputTitulo").val().length;
					if (id_val<110){
						$("#input-inputTitulo .mensajes-aux").html(id_val);
					}else{
						$("#input-inputTitulo .mensajes-aux").html("<span class='alert-danger'>"+id_val+"</span>");
					}

				});
			});
		</script>
		<?php
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputCuerpo");
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	function form_editar(){
		// $fila= $this->fmt->form->form_head_form_editar('Editar Noticia','noticia','not_', $this->id_mod,'head-noticias','noticias',$this->id_item);//$nom,$from,$prefijo,$id_mod,$class,$archivo

		$sql="select * from nota	where not_id='".$this->id_item."'";
		$rs=$this->fmt->query->consulta($sql);
		$fila= $this->fmt->query->obt_fila($rs);

  	$this->fmt->class_pagina->crear_head_form("Editar Nota","","");
		$id_form="form-editar";

		//$this->fmt->form->finder("inputCuerpo",$this->id_mod,"","individual-complejo","imagenes");
		//$this->fmt->form->finder("",$this->id_mod,"","multiple-accion","");

		?>
		<div class="body-modulo" id="body-modulo-notas">
			<form class="form form-modulo form-noticia"  method="POST" id="<?php echo $id_form?>">
				<div class="col-form">
					<?php
						$this->fmt->form->input_form("<span class='obligatorio'>*</span> Titulo:","inputTitulo","",$fila['not_titulo'],"input-lg","row-lg","","","","autocomplete=off"); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros
						$this->fmt->form->ruta_amigable_form("inputTitulo",_RUTA_WEB,$fila['not_ruta_amigable'],"inputRutaamigable","","input-lg"); //$id,$ruta,$valor,$form
						$this->fmt->form->input_hidden_form("inputId",$fila['not_id']);
						//$this->fmt->form->hidden_modulo($this->id_mod,"modificar");
						$this->fmt->form->input_form('Tags:','inputTags','',$fila['not_tags'],'');

						$this->fmt->form->textarea_form('Resumen:','inputResumen','',$fila['not_resumen'],'','','3','','');
						$this->fmt->form->textarea_form('Cuerpo:','inputCuerpo','',$fila['not_cuerpo'],'editor-texto','textarea-cuerpo','40',''); //label,$id,$placeholder,$valor,$class,$class_div,$rows,$mensaje
						//$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');

						$usuario = $this->fmt->sesion->get_variable('usu_id');
						//$usuario_n =  $this->fmt->usuario->nombre_usuario( $usuario );
						//$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
						$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
						//$this->fmt->form->input_form('Orden:','inputOrden','','0','','','');
						$this->fmt->form->input_form('Lugar de procedencia:','inputLugar','',$fila["not_lugar"],'','','');
						//$this->nombre_autor($fila["not_id_autor"]);
						$this->fmt->form->input_form('Autor:','inputAutor','',$fila["not_autor"],'','','');
						$this->fmt->usuario->agregar_usuarios_input('inputAutor','mod_columnista','simple');

						$this->fmt->form->productos_notas('inputProductos',$fila['not_id'],'Añadir Producto','',"Productos Relacionados:"); //$id,$valor,$titulo="Elegir Producto",$class_div,$label_form=""
					?>
				</div>
				<div class="col-nav">
					<?php

						// if ($fila["not_imagen"]){ $text="Actualizar";  }else{ $text="Cargar archivo";   }
						// $this->fmt->form->imagen_form("",$text,"inputImagen",$fila["not_id"],$fila["not_imagen"]);
						$this->fmt->form->imagen_unica_form("inputImagen",$fila["not_imagen"]);
						$this->fmt->form->video_unico("inputVideo",$fila["not_id_video"],"Video Nota","video-control");
						$this->fmt->form->input_date_form('','inputFecha','',$fila['not_fecha'],'','form-vertical','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
						$cats_id = $this->fmt->categoria->traer_rel_cat_id($fila["not_id"],'nota_categorias','not_cat_cat_id','not_cat_not_id'); //$fila_id,$from,$prefijo_cat,$prefijo_rel
						$this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"","");


						//$this->fmt->form->radio_activar_form($fila['not_activar']);
						$this->fmt->form->vista_item($fila['not_activar'],".box-botones-form");
					 ?>
				</div>
				<?php

					$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar"); //$id_form,$id_mod,$tarea

				?>
			</form>
			<script type="text/javascript">
				$(document).ready(function() {
					$("#inputTitulo").on('keyup', function(){
						var id_val = $("#inputTitulo").val().length;
						if (id_val<110){
							$("#input-inputTitulo .mensajes-aux").html(id_val);
						}else{
							$("#input-inputTitulo .mensajes-aux").html("<span class='alert-danger'>"+id_val+"</span>");
						}

					});
				});
			</script>
		</div>
		<?php
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputCuerpo");
		$this->fmt->class_modulo->modal_script($this->id_mod);

	}

	function ingresar(){
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}

		if(isset($_POST["inputUrl"]))
			$imagen=$_POST["inputUrl"];
		else
			$imagen=$_POST['inputImagen'];

		$ingresar ="not_titulo,
                not_ruta_amigable,
                not_resumen,
                not_cuerpo,
                not_imagen,
                not_tags,
                not_fecha,
                not_usuario,
                not_autor,
                not_lugar,
								not_id_video,
                not_activar";
		$valores  ="'".$_POST['inputTitulo']."','".$this->fmt->get->convertir_url_amigable($_POST['inputTitulo'])."','".
					$_POST['inputResumen']."','".
					$_POST['inputCuerpo']."','".
					$imagen."','".
					trim($_POST['inputTags'])."','".
					$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputFecha'])."','".
					$_POST['inputUsuario']."','".
					$_POST['inputAutor']."','".
					$_POST['inputLugar']."','".
					$_POST['inputVideo']."','".
					$activar."'";

		$sql="insert into nota (".$ingresar.") values (".$valores.")";
		$this->fmt->query->consulta($sql);

		

		$sql="select max(not_id) as id from nota";
		$rs= $this->fmt->query->consulta($sql);
		$fila = $this->fmt->query->obt_fila($rs);
	  	$id = $fila ["id"];
		$ingresar1 ="not_cat_not_id, not_cat_cat_id,not_cat_orden";
		$valor_cat= $_POST['inputCat'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$id."','".$valor_cat[$i]."','".$_POST['inputOrden']."'";
			$sql1="insert into nota_categorias (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}

	$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}

	function traer_orden($id_nota,$cats){

		$nc =  count($cats);
		for ($i=0; $i < $nc; $i++) { 
			$sql= "SELECT not_cat_orden FROM nota_categorias WHERE not_cat_cat_id=$cats[$i] and not_cat_not_id=$id_nota";
			$rs= $this->fmt->query->consulta($sql);
		  $fila = $this->fmt->query->obt_fila($rs);
			$orden[$i] = $fila["not_cat_orden"]; 
		}
		return $orden;
	}

	function modificar(){
		if ($_POST["estado-mod"]=="eliminar"){
		}else{

				$imagen=$_POST['inputImagen'];

			  $sql="UPDATE nota SET
						not_titulo='".$_POST['inputTitulo']."',
						not_ruta_amigable ='".$_POST['inputRutaamigable']."',
						not_tags ='".trim($_POST['inputTags'])."',
						not_resumen ='".$_POST['inputResumen']."',
						not_cuerpo ='".$_POST['inputCuerpo']."',
						not_imagen ='".$imagen."',
						not_fecha='".$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputFecha'])."',
						not_usuario='".$_POST['inputUsuario']."',
						not_autor='".$_POST['inputAutor']."',
						not_lugar='".$_POST['inputLugar']."',
						not_id_video='".$_POST['inputVideo']."',
						not_activar='".$_POST['inputActivar']."'
						WHERE not_id='".$_POST['inputId']."'";
				// exit();
			$this->fmt->query->consulta($sql);

			$ingresar1 ="not_cat_not_id,not_cat_cat_id,not_cat_orden";
			$valor_cat= $_POST['inputCat'];
			 
			$orden = $this->traer_orden($_POST['inputId'],$valor_cat);
			
			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"nota_categorias","not_cat_not_id");  //$valor,$from,$fila

			$num=count( $valor_cat );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."','".$orden[$i]."'";
				$sql1="insert into nota_categorias (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}

			$ingresar1 ="not_prod_not_id,not_prod_prod_id,not_prod_orden";
			$valor_cat= $_POST['inputProductos'];
			$num=count( $valor_cat );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."','".$i."'";
				$sql1="insert into nota_productos (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}

		}
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}


	function eliminar(){
  		$this->fmt->class_modulo->eliminar_get_id("nota","not_",$this->id_item);
  		$this->fmt->class_modulo->eliminar_get_id("noticia_rel","not_rel_not_",$this->id_item);
  		$this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
  	}

  	function activar(){
	    $this->fmt->class_modulo->activar_get_id("nota","not_",$this->id_estado,$this->id_item);
	   $this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
  	}


}
?>
