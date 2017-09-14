<?php
header("Content-Type: text/html;charset=utf-8");

class USUARIOS{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function usuarios($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){
		$botones = $this->fmt->class_modulo->botones_hijos_modulos($this->id_mod);
    $this->fmt->class_pagina->crear_head( $this->id_mod, $botones); // id modulo, botones
		$this->fmt->class_pagina->head_mod();
		?>
    <div class="body-modulo">
			<?php
			$botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nueva noticia","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
			$this->fmt->class_pagina->head_modulo_inner("Lista de Usuarios", $botones); //
			?>
    <div class="table-responsive">
      <table class="table table-hover" id="table_id">
        <thead>
          <tr>
            <th>Nombre del Usuario</th>
            <th>E-mail</th>
            <th>Roles</th>
            <th>Grupos</th>
            <th>Estado</th>
            <th class="col-xl-offset-2">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql="SELECT usu_id, usu_nombre,usu_apellidos, usu_imagen, usu_email, usu_estado,usu_activar  FROM usuario	ORDER BY usu_id desc";
            $rs =$this->fmt->query-> consulta($sql,__METHOD__);
            $num=$this->fmt->query->num_registros($rs);
            if($num>0){
              for($i=0;$i<$num;$i++){
                $row=$this->fmt->query->obt_fila($rs);
								$fila_id= $row["usu_id"];
								$fila_nombre= $row["usu_nombre"];
								$fila_apellido= $row["usu_apellidos"];
								$fila_imagen= $row["usu_imagen"];
								$fila_email= $row["usu_email"];
								$fila_estado= $row["usu_estado"];
								$fila_activar= $row["usu_activar"];
                ?>
                <tr class="row row-<?php echo $fila_id; ?>">
                  <td  class="col-nombre"><?php
										 	$img=$this->fmt->usuario->imagen_usuario_mini($fila_id);
											echo '<img class="img-user img-list img-responsive" src="'.$img.'" />';
                      echo '<span class="nombre-user">'.$fila_nombre." ".$fila_apellido."</span>";
                    ?>
                  </td>
                  <td class="td-user"><?php echo $fila_email; ?></td>
                  <td class="td-user"><?php echo $this->fmt->usuario->rol_usuario($fila_id); ?></td>
                  <td class="td-user"></td>
                  <td class="td-user">
                  <?php
                    $this->fmt->class_modulo->estado_publicacion($fila_activar, $this->id_mod,"",$fila_id);
                  ?>
                  </td>
                  <td class="td-user  acciones">
										<?php
											$this->fmt->form->btn_acciones_form($this->id_mod,$fila_id,$fila_nombre." ".$fila_apellido,$aux="");
										?>
                  </td>
                </tr>
                <?
              }
            }
          ?>
        </tbody>
      </table>
    </div>
    </div>
    <?

		$this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","4","25",true);
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
		$this->fmt->form->imagen_unica_form("inputImagen");
		?>
		<div class="form-group">
			<label>Rol:</label>
			<div class="group">
				<?php echo $this->opciones_roles();  ?>
			</div>
		</div>
		<?php  $this->fmt->class_modulo->grupos_select("Grupo Roles","inputRolGrupo","");  ?>
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

	function opciones_roles($rol){
		$sql="select rol_id, rol_nombre from rol ORDER BY rol_id asc";
            $rs =$this->fmt->query->consulta($sql,__METHOD__);
            $num=$this->fmt->query->num_registros($rs);
            if($num>0){
              for($i=0;$i<$num;$i++){
                $row=$this->fmt->query->obt_fila($rs);
								$fila_id=$row["rol_id"];
								$fila_nombre=$row["rol_nombre"];
                $ch="";
				if (in_array($fila_id, $rol)){
					$ch="checked";
				}

		?>
		<div class="checkbox">

            <input name="inputRol" <?php echo $ch; ?> id="inputRol<?php echo $fila_id; ?>" type="radio" value="<?php echo $fila_id; ?>">
						<label><?php echo $fila_nombre; ?></label>

    </div>
		<?php
				}
			}
	}

	function ingresar(){
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

		$rol = $_POST['inputRol'];

		$ingresar1 ="usu_rol_usu_id, usu_rol_rol_id";
		$valores1 = "'".$id."','".$rol."'";
		$sql1="insert into usuario_roles (".$ingresar1.") values (".$valores1.")";
		$this->fmt->query->consulta($sql1,__METHOD__);

		$ingresar1 ="usu_grupo_usu_id, usu_grupo_grupo_id";

		$grupo_rol = $_POST['inputRolGrupo'];
		$cont_grupo_rol= count($grupo_rol);
		for($i=0;$i<$cont_grupo_rol;$i++){
			$valores1 = "'".$id."','".$grupo_rol[$i]."'";
			$sql2="insert into usuario_grupos (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql2,__METHOD__);
		}

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}

  function activar(){
    $this->fmt->get->validar_get ( $_GET['estado'] );
    $this->fmt->get->validar_get ( $_GET['id'] );
    $sql="update usuarios set
        usu_estado='".$_GET['estado']."' where usu_id='".$_GET['id']."'";
    $this->fmt->query->consulta($sql,__METHOD__);

    $url="usuarios.adm.php?id_mod=".$this->id_mod;
	$this->fmt->class_modulo->script_location($url);
  }



	function eliminar(){

		$this->fmt->get->validar_get( $_GET['id'] );
		echo $id= $_GET['id'];
		$this->fmt->class_modulo->eliminar_fila($id,"usuario_roles","usu_rol_usu_id");
		$this->fmt->class_modulo->eliminar_fila($id,"usuario_grupos","usu_grupo_usu_id");
		$sql="DELETE FROM usuario WHERE usu_id='".$id."'";
		$this->fmt->query->consulta($sql,__METHOD__);

		$up_sqr6 = "ALTER TABLE usuario AUTO_INCREMENT=1";
		$this->fmt->query->consulta($up_sqr6,__METHOD__);


		$url="usuarios.adm.php?id_mod=".$this->id_mod;
		$this->fmt->class_modulo->script_location($url);
	}

	function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Usuario","","");
		$id_form="form-editar";
		//$this->fmt->form->head_editar('editar usuario','usuarios',$this->id_mod,''); //$nom,$archivo,$id_mod,$botones

		$id = $this->id_item;
		$consulta ="SELECT * FROM usuario WHERE usu_id='$id'";
		$rs = $this->fmt->query->consulta($consulta,__METHOD__);
		$fila=  $this->fmt->query->obt_fila($rs);

		$rols_id = $this->fmt->categoria->traer_rel_cat_id($id,'usuario_roles','usu_rol_rol_id','usu_rol_usu_id');

		$groups_id = $this->fmt->categoria->traer_rel_cat_id($id,'usuario_grupos','usu_grupo_grupo_id','usu_grupo_usu_id');
		$this->fmt->class_pagina->head_form_mod();
		//$this->fmt->form->finder("",$this->id_mod,"","multiple-accion","");
		$this->fmt->class_pagina->form_ini_mod($id_form);

		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","",$fila["usu_nombre"],"","","","","","required autofocus");
		 //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros
		$this->fmt->form->input_hidden_form('inputId',$fila["usu_id"]);
		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Apellidos:","inputApellidos","",$fila["usu_apellidos"],"","","","","","required");
		$this->fmt->form->input_form(" Email:","inputEmail","@",$fila["usu_email"],"","","","","","required");
		$this->fmt->form->password_form("Password:","inputPassword","",base64_decode($fila["usu_password"]),"","",""); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->password_form("Confirmar password:","inputPasswordConfirmar","",base64_decode($fila["usu_password"]),"","",""); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->imagen_unica_form("inputImagen",$fila["not_imagen"]);
		?>
		<div class="form-group">
			<label>Rol:</label>
			<div class="group">
				<?php echo $this->opciones_roles($rols_id);  ?>
			</div>
		</div>
		<?php
		$this->fmt->class_modulo->grupos_select("Grupo Roles","inputRolGrupo","",$groups_id);
		$this->fmt->form->vista_item($fila['usu_activar'],".box-botones-form");
		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_modulo->modal_script($this->id_mod);
		$this->fmt->finder->finder_window();
		$this->fmt->class_pagina->form_fin_mod($id_form);
		$this->fmt->class_pagina->footer_form_mod();
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
					$("#btn-activar").prop("disabled", false);
					$("#msg-pass-inputPasswordConfirmar").html("");
				}
				else{
					$("#msg-pass-inputPasswordConfirmar").html('<span class="text-danger"><font><font>Los password no coinciden.</font></font></span>');
					$("#btn-activar").prop("disabled", true);
				}
			}
		}
		</script>
		<?php
	}
	function modificar(){
		$id=$_POST['inputId'];

		$sql="UPDATE usuario SET
				usu_nombre='".$_POST['inputNombre']."',
				usu_apellidos='".$_POST['inputApellidos']."',
				usu_email ='".$_POST['inputEmail']."',
				usu_password='".base64_encode($_POST['inputPassword'])."',
				usu_imagen='".$_POST['inputImagen']."',
				usu_activar='".$_POST['inputActivar']."'
	          WHERE usu_id='".$id."'";

		$this->fmt->query->consulta($sql,__METHOD__);
		$this->fmt->class_modulo->eliminar_fila($id,"usuario_roles","usu_rol_usu_id");
		$this->fmt->class_modulo->eliminar_fila($id,"usuario_grupos","usu_grupo_usu_id");
		$rol = $_POST['inputRol'];

		$ingresar1 ="usu_rol_usu_id, usu_rol_rol_id";
		$valores1 = "'".$id."','".$rol."'";
		$sql1="insert into usuario_roles (".$ingresar1.") values (".$valores1.")";
		$this->fmt->query->consulta($sql1,__METHOD__);

		$ingresar1 ="usu_grupo_usu_id, usu_grupo_grupo_id";

		$grupo_rol = $_POST['inputRolGrupo'];
		$cont_grupo_rol= count($grupo_rol);
		for($i=0;$i<$cont_grupo_rol;$i++){
			$valores1 = "'".$id."','".$grupo_rol[$i]."'";
			$sql2="insert into usuario_grupos (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql2,__METHOD__);
		}
 		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}
}
