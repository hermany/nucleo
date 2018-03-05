<?php
header("Content-Type: text/html;charset=utf-8");

class ENLACES{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $vars_mod;
	var $ruta_modulo;

	public function ENLACES($fmt,$id_mod=0,$id_item=0,$id_estado=0,$vars_mod=""){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->vars_mod = $vars_mod;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	public function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
		$this->fmt->class_pagina->head_mod();
		$this->fmt->class_pagina->head_modulo_inner("Lista de Enlaces","","crear",$this->id_mod); // bd, id modulo, botones

		$this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Enlace:Categoria:Publicación:Grupo:Estado:Acciones');
    $this->fmt->form->tbody_table_open();

    $consulta = "SELECT * FROM enlace";
    $rs =$this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
    if($num>0){
      for($i=0;$i<$num;$i++){
        $row=$this->fmt->query->obt_fila($rs);
        $fila_id=$row["enl_id"];
        $fila_nombre=$row["enl_nombre"];
        $fila_link=$row["enl_link"];
        $fila_activar =$row["enl_activar"];
        $fila_image =$row["enl_imagen"];
        if (empty($fila_image)){
        	$img="";
        }else{
        	$img_1= _RUTA_IMAGES.$this->fmt->archivos->url_add($fila_image,"-thumb");
        	$img = '<span class="img" style="background:url('.$img_1.') no-repeat center center"></span>';
        }

        echo "<tr class='row row-".$fila_id."'>";
        echo "  <td class='col-id'>$fila_id</td>";
        echo "  <td class='col-img-nom'>$img <span class='nom' title='$fila_link'>$fila_nombre</span></td>";
        // echo "  <td>$fila_link</td>";
        echo "  <td>";
        $this->fmt->categoria->traer_rel_cat_nombres($fila_id,'enlace_categorias','enl_cat_cat_id','enl_cat_enl_id');
        echo "	</td>";
        echo "  <td>";
        echo $this->fmt->class_modulo->traer_rel_modulos_list('{
        						"select":"pub_id,pub_nombre",
		        				"from":"enlace_publicaciones,publicacion",
		        				"where":"enl_pub_enl_id='.$fila_id.' and pub_id = enl_pub_pub_id",
		        				"vars_mod":"publicacion",
		        				"id_mod":"'.$this->id_mod.'"}'); //$select,$from,$where,$id_mod

        echo "	</td>";
        echo "  <td></td>";
        echo "  <td>";
		    $this->fmt->class_modulo->estado_publicacion($fila_activar,$this->id_mod,"", $fila_id );
        echo " </td>";
        echo '  <td class="col-acciones acciones">';
        $this->fmt->class_modulo->botones_tabla($fila_id,$this->id_mod,$fila_nombre);//
        echo '  </td>';
        echo "</tr>";
      }
    }

    $this->fmt->form->tbody_table_close();
    $this->fmt->form->footer_table();

    $this->fmt->class_pagina->footer_mod();
    $this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","25",true);
		$this->fmt->class_modulo->script_accion_modulo();
	}

	public function form_nuevo(){
		$id_form="form-nuevo";
		$this->fmt->class_pagina->crear_head_form("Nuevo Enlace","","");
		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-enlaces");

		$this->fmt->form->input_form('Nombre del enlace:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','','3','','');
		$this->fmt->form->input_form('Link:','inputLink','','','','','');
		$valor= array('_self','_blank');
		$campo= array('Dentro la página','Fuera de la página');
		$this->fmt->form->radio_form("","inputTarget",$valor,$campo,'');
		$this->fmt->form->imagen_unica_form("inputImagen","","","form-row","Imagen relacionada:");

		$this->fmt->form->categoria_form('Categoria','inputCat',"0","","",""); //		

		$this->fmt->form->list_checkbox_form("Publicación:","inputPublicacion","publicacion","pub_","");//$label,$id,$from,$prefijo_mod,$prefijo_rel,$class,$class_div

		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}	

	public function form_editar(){
		$id_form="form-editar";
		$id = $this->id_item;
		$this->fmt->class_pagina->crear_head_form("Editar Enlace","","");
		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-enlaces");

		$consulta= "SELECT * FROM enlace WHERE enl_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->form->input_form('Nombre del enlace:','inputNombre','',$row['enl_nombre'],'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputId",$row["enl_id"]);
		$this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$row['enl_descripcion'],'','','3','','');
		$this->fmt->form->input_form('Link:','inputLink','',$row['enl_link'],'','','');
		$valor= array('_self','_blank');
		$campo= array('Dentro la página','Fuera de la página');
		$this->fmt->form->radio_form("","inputTarget",$valor,$campo,$row['enl_target']);
		$this->fmt->form->imagen_unica_form("inputImagen",$row["enl_imagen"],"","form-row","Imagen relacionada:");

		$cats_id = $this->fmt->categoria->traer_rel_cat_id($id,'enlace_categorias','enl_cat_cat_id','enl_cat_enl_id'); //$fila_id,$from,$prefijo_cat,$prefijo_rel
		$this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"",""); //		

		$pubs_id = $this->fmt->class_modulo->traer_rel_modulos($id,'enlace_publicaciones','enl_pub_pub_id','enl_pub_enl_id'); //$fila_id,$from,$prefijo_cat,$prefijo_rel
		//var_dump($pubs_id);
		$this->fmt->form->list_checkbox_form("Publicación:","inputPublicacion","publicacion","pub_",$pubs_id);//$label,$id,$from,$prefijo_mod,$prefijo_rel,$class,$class_div

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

 public function modificar(){

		$sql="UPDATE enlace SET
						enl_nombre='".$_POST['inputNombre']."',
						enl_descripcion ='".$_POST['inputDescripcion']."',
						enl_link ='".$_POST['inputLink']."',
						enl_target='".$_POST['inputTarget']."',
						enl_imagen='".$_POST['inputImagen']."' 
						WHERE enl_id='".$_POST['inputId']."'";
			//echo $sql;
			$this->fmt->query->consulta($sql);

			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"enlace_categorias","enl_cat_enl_id");  //$valor,$from,$fila
			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"enlace_publicaciones","enl_pub_enl_id");  //$valor,$from,$fila

			$ingresar1 ="enl_cat_enl_id, enl_cat_cat_id";
			$valor_cat= $_POST['inputCat'];
			$num=count( $valor_cat );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."'";
				$sql1="insert into enlace_categorias (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}			

			$ingresar2 ="enl_pub_enl_id,enl_pub_pub_id";
			$valor_pub= $_POST['inputPublicacion'];
			$num2=count( $valor_pub );
			for ($i=0; $i<$num2;$i++){
				$valores2 = "'".$_POST['inputId']."','".$valor_pub[$i]."'";
				$sql2="insert into enlace_publicaciones (".$ingresar2.") values (".$valores2.")";
				$this->fmt->query->consulta($sql2);
			}

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }

 public function ingresar($modo){

  	if ($_POST["estado-mod"]=="activar"){ $activar=1; }else{ $activar=0;}

		$ingresar ="enl_nombre,
                enl_descripcion,
                enl_link,
                enl_target,
                enl_imagen,
                enl_activar";

		$valores  ="'".$_POST['inputNombre']."','".
					$_POST['inputDescripcion']."','".
					$_POST['inputLink']."','".
					$_POST['inputTarget']."','".
					$_POST['inputImagen']."','".
					$activar."'";
		$sql="insert into enlace (".$ingresar.") values (".$valores.")";
		$this->fmt->query->consulta($sql);

		$sql="select max(enl_id) as id from enlace";
		$rs= $this->fmt->query->consulta($sql);
		$fila = $this->fmt->query->obt_fila($rs);
		$id = $fila ["id"];

		$ingresar1 ="enl_cat_enl_id, enl_cat_cat_id";
			$valor_cat= $_POST['inputCat'];
			$num=count( $valor_cat );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$id."','".$valor_cat[$i]."'";
				$sql1="insert into enlace_categorias (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}			

		$ingresar2 ="enl_pub_enl_id,enl_pub_pub_id";
		$valor_pub= $_POST['inputPublicacion'];
		$num2=count( $valor_pub );
		for ($i=0; $i<$num2;$i++){
			$valores2 = "'".$id."','".$valor_pub[$i]."'";
			$sql2="insert into enlace_publicaciones (".$ingresar2.") values (".$valores2.")";
			$this->fmt->query->consulta($sql2);
		}

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");

	} // ingresar

	public function ordenar(){
		$id = $this->id_item;
		$mod = $this->vars_mod;
		$id_form="form-editar";
		if ($mod=="publicacion"){
			$nombre = $this->fmt->publicacion->nombre_publicacion($id);
			$sql="SELECT enl_id, enl_nombre, enl_imagen, enl_pub_orden FROM enlace,enlace_publicaciones where enl_pub_pub_id='$id' and enl_pub_enl_id=enl_id ORDER BY enl_pub_orden asc";
		}
		$this->fmt->class_pagina->crear_head_form("Ordenar: $nombre","","");
		?>
		<div class="body-modulo">
		  <form class="form form-modulo form-ordenar"  method="POST" id="<?php echo $id_form?>">
				<ul id="orden-cat" class="list-group">
					<?php
	        $rs =$this->fmt->query->consulta($sql,__METHOD__);
	        $num=$this->fmt->query->num_registros($rs);
	        if($num>0){
		        for($i=0;$i<$num;$i++){
		          $row=$this->fmt->query->obt_fila($rs);
							$row_id=$row["enl_id"];
							$row_nom=$row["enl_nombre"];
							$row_imagen=$row["enl_imagen"];
							$row_imagen=_RUTA_WEB.$this->fmt->archivos->convertir_url_thumb($row_imagen);
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
						formdata.append("mod", "<?php echo $mod;?>");
						formdata.append("item", "<?php echo $id;?>");
						formdata.append("ajax", "ajax-adm");
						formdata.append("inputIdMod", id_mod);
						$('#orden-cat li').each(function(index){
						  var id_var = $(this).attr("id_var");
						  //console.log(id_var);
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

	public function ordenar_update(){
		$mod=$_POST["mod"];
		$item=$_POST["item"];

		
		// if ($mod=="categoria"){
		// 	$this->fmt->class_modulo->eliminar_fila($item,"enlace_categorias","enl_cat_cat_id");
		// 	$ingresar2 ="enl_cat_enl_id,enl_cat_cat_id,enl_cat_orden";
		// 	$valor= $_POST['id_item'];
		// 	$num=count( $valor);
		// 	for ($i=0; $i<$num;$i++){
		// 		$valores2 = "'".$valor[$i]."','".$item."','".$i."'";
		// 		$sql2="insert into enlace_categorias (".$ingresar2.") values (".$valores2.")";
		// 		$this->fmt->query->consulta($sql2);
		// 	}
		// }		

		if ($mod=="publicacion"){
			$this->fmt->class_modulo->eliminar_fila($item,"enlace_publicaciones","enl_pub_pub_id");
			$ingresar2 ="enl_pub_enl_id,enl_pub_pub_id,enl_pub_orden";
			$valor= $_POST['id_item'];
			$num=count( $valor);
			for ($i=0; $i<$num;$i++){
				$valores2 = "'".$valor[$i]."','".$item."','".$i."'";
				$sql2="insert into enlace_publicaciones (".$ingresar2.") values (".$valores2.")";
				$this->fmt->query->consulta($sql2);
			}
		}
		
	  //$this->fmt->class_modulo->redireccionar($this->ruta_modulo,"1");
	}
 
}