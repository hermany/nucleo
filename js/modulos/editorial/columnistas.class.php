<?php
header("Content-Type: text/html;charset=utf-8");

class COLUMNISTAS{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;
	var $id_col;

	function COLUMNISTAS($fmt,$id_mod=0,$id_item=0,$id_estado=0,$id_col){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
		$this->id_col = $this->id_conf_columnista_usuario();
	}

	function busqueda(){
		$this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
		$this->fmt->class_pagina->head_mod();
		$botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nuevo Columnista","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
    $this->fmt->class_pagina->head_modulo_inner("Lista de Columnistas", $botones); // bd, id modulo, botones
		echo '<link rel="stylesheet" href="'._RUTA_WEB_NUCLEO.'css/m-columnistas.css?reload" rel="stylesheet" type="text/css">';
		$id_col =  $this->id_col;
		$consulta = "SELECT DISTINCT usu_id,usu_nombre,usu_apellidos,usu_imagen FROM usuario,mod_columnista WHERE usu_id=mod_col_usu_id  ORDER BY mod_col_orden asc";
    $rs =$this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
		echo "<div class='body-modulo-inner'>";
    if($num>0){
      for($i=0;$i<$num;$i++){
        $row=$this->fmt->query->obt_fila($rs);
				$fila_id=$row["usu_id"];
				$nombre=$row["usu_nombre"];
				$apellidos=$row["usu_apellidos"];
				$img =$row["usu_imagen"];
				$imgz=$this->fmt->usuario->imagen_usuario_mini($fila_id);
				$col = $this->fmt->categoria->traer_rel_cat_id($fila_id,"mod_columnista_categorias","mod_col_cat_cat_id","mod_col_cat_usu_id");
				$n_colum = count($col);
				$columna="";
				for ($j=0; $j < $n_colum; $j++) {
					$columna .= $this->fmt->categoria->nombre_categoria($col[$j])."</br>";
				}
				echo "<div class='usu usu-$fila_id'>";
				echo "<div class='imagen' style='background:url($imgz) no-repeat center center'></div>";
				echo "<div class='nombre'>".$nombre." ".$apellidos."</div>";
				echo "<div class='columna'>".$columna."</div>";
				// echo "<a class='btn btn-full btn-small btn-editar'><i class='icn icn-pencil'></i>Editar</a>";
				// echo "<a class='btn btn-full btn-small btn-eliminar'><i class='icn icn-trash'></i></a>";
				echo $this->fmt->class_pagina->crear_btn_m("Editar","icn-pencil","editar ".$fila_id,"btn btn-full btn-accion btn-editar btn-menu-ajax  btn-small",$this->id_mod,"form_editar,".$fila_id);
        echo $this->fmt->class_pagina->crear_btn_m("","icn-trash","eliminar ".$fila_id,"btn btn-accion btn-full btn-small btn-m-eliminar",$this->id_mod,"eliminar,".$fila_id,"",$nombre);
				echo "</div>";
			}
		}
		$this->fmt->query->liberar_consulta($rs);
		echo "</div>";
		echo "<div class='footer-modulo'>";
		echo "</div>";
		$this->fmt->class_modulo->script_accion_modulo();
		$this->fmt->class_pagina->footer_mod();
  }

	function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Usuario","","");
		$id_form="form-nuevo";
		$this->fmt->class_pagina->head_form_mod();
		//$this->fmt->form->finder("",$this->id_mod,"","multiple-accion","");

		$this->fmt->class_pagina->form_ini_mod($id_form,"form-usuarios");
		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","",$fila["cat_nombre"],"input-lg","","");
		$this->fmt->form->input_form("Apellidos:","inputApellidos","","");
		$this->fmt->form->input_form("E-mail:","inputEmail","@","");
		$this->fmt->form->password_form("Password:","inputPassword","","","","",""); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->password_form("Confirmar Password:","inputPasswordConfirmar","","","","",""); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->imagen_unica_form("inputImagen","",$titulo="Imagen principal","","Foto:");
		$this->fmt->form->categoria_form('Categoria','inputCat',"0","","","");
		?>
		<script>
		$(document).ready(function(){
			$("#inputPassword").keyup(function(){
			  validarpass();
			});
			$("#inputPasswordConfirmar").keyup(function(){
			  validarpass();
			});
		});
		function validarpass(){
			var pass = $("#inputPassword").val();
			var re_pass = $("#inputPasswordConfirmar").val();
			if(pass.length>0 && re_pass.length>0){
			  if(pass==re_pass){
			    $("#btn-guardar").prop("disabled", false);
			    $("#btn-activar").prop("disabled", false);
			    $("#msg-pass-inputPasswordConfirmar").html("");
			  }
			  else{
			    $("#msg-pass-inputPasswordConfirmar").html('<span class="text-danger"><font>Los password no coinciden.</font></span>');
			    $("#btn-guardar").prop("disabled", true);
			    $("#inputPasswordConfirmar #btn-activar").prop("disabled", true);
			  }
			}
		}
		</script>
		<?php
		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	}

	function id_conf_columnista_usuario(){
		$consulta = "SELECT mod_col_conf_columnista_rol_id FROM mod_columnista_conf";
    $rs =$this->fmt->query->consulta($consulta);
		$fila=$this->fmt->query->obt_fila($rs);
		return $fila["mod_col_conf_columnista_rol_id"];
	}

	function relacion_columnista_cat(){

	}

	function ingresar(){
		 $rol = $this->id_col;
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
		$ingresar = "usu_nombre, usu_apellidos, usu_email, usu_password, usu_imagen, usu_activar,usu_estado,usu_padre";
		$valores  = "'".$_POST['inputNombre']."','".
					$_POST['inputApellidos']."','".
					$_POST['inputEmail']."','".
					base64_encode($_POST['inputPassword'])."','".
					$_POST['inputUrl']."','".
					$activar."','".
					$activar."','1'";

		$sql="insert into usuario (".$ingresar.") values (".$valores.")";
		$this->fmt->query->consulta($sql,__METHOD__);

		$sql="select max(usu_id) as id_usu from usuario";
		$rs= $this->fmt->query->consulta($sql,__METHOD__);
		$fila = $this->fmt->query->obt_fila($rs);
		$id = $fila ["id_usu"];
		$this->fmt->query->liberar_consulta($rs);


		$ingresar1 ="usu_rol_usu_id, usu_rol_rol_id";
		$valores1 = "'".$id."','".$rol."'";
		$sql1="insert into usuario_roles (".$ingresar1.") values (".$valores1.")";
		$this->fmt->query->consulta($sql1,__METHOD__);
		$this->fmt->query->liberar_consulta($sql1);

		$ingresar1 ="mod_col_usu_id, mod_col_orden";
		$valores1 = "'".$id."','0'";
		$sql1="insert into mod_columnista (".$ingresar1.") values (".$valores1.")";
		$this->fmt->query->consulta($sql1,__METHOD__);
		$this->fmt->query->liberar_consulta($sql1);

		$ingresar1 ="mod_col_cat_usu_id,mod_col_cat_cat_id,mod_col_cat_orden";
		$valor_cat= $_POST['inputCat'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$id."','".$valor_cat[$i]."','".$i."'";
			$sql1="insert into mod_columnista_categorias (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
			$this->fmt->query->liberar_consulta($sql1);
		}

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}
}
