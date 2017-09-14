<?php
header("Content-Type: text/html;charset=utf-8");

class KARDEX{

	var $fmt;
	var $id_mod;

	function KARDEX($fmt){
		$this->fmt = $fmt;
		$this->fmt->get->validar_get($_GET['id_mod']);
		$this->id_mod=$_GET['id_mod'];
	}

	function busqueda(){
		$botones .= $this->fmt->class_pagina->crear_btn("kardex-config.adm.php","btn btn-link","icn-conf","Configuraciones");

		$botones .= $this->fmt->class_pagina->crear_btn("kardex.adm.php?tarea=list_papelera&id_mod=$this->id_mod","btn btn-link","icn-trash","Papelera");

		$botones .= $this->fmt->class_pagina->crear_btn("kardex.adm.php?tarea=form_nuevo&id_mod=$this->id_mod","btn btn-primary","icn-plus","Nuevo Registro");


		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones); // bd, id modulo, botones
		$this->fmt->class_modulo->script_form("modulos/rrhh/kardex.adm.php",$this->id_mod,"asc","0","25",true);
		$id_rol = $this->fmt->sesion->get_variable("usu_rol");
		?>
		<div class="body-modulo">
    <div class="table-responsive">
      <table class="table table-hover" id="table_id">
        <thead>
          <tr>
            <th style="width:10%" >#</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Correo Corporativo</th>
            <th>Nro CI/DNI</th>
            <th>Exp.</th>
            <th class="estado">Publicación</th>
            <th class="col-xl-offset-2 acciones">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          	//if($id_rol==1)
            	$sql="select mod_kdx_id, mod_kdx_nombre, mod_kdx_apellido_paterno,  mod_kdx_apellido_materno, mod_kdx_identificacion, mod_kdx_extension, mod_kdx_email_corporativo, mod_kdx_activar, mod_kdx_imagen from mod_kardex where mod_kdx_papelera=0 ORDER BY mod_kdx_id desc";
           /* else{
            	$aux="";
            	$or="";
            	$sql="select rol_rel_cat_id from roles_rel where rol_rel_rol_id=".$id_rol." and rol_rel_cat_id not in (0) ORDER BY rol_rel_cat_id asc";
			      $rs =$this->fmt->query->consulta($sql);
				  $num=$this->fmt->query->num_registros($rs);
				  if($num>0){
				  	for($i=0;$i<$num;$i++){
				    	list($fila_id)=$this->fmt->query->obt_fila($rs);
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

            }*/
            $rs =$this->fmt->query->consulta($sql);
            $num=$this->fmt->query->num_registros($rs);
            if($num>0){
            for($i=0;$i<$num;$i++){
            	list($fila_id,$fila_nombre,$fila_ape_p,$fila_ape_m,$fila_ci, $fila_exp, $fila_mail, $fila_activar,$fila_imagen)=$this->fmt->query->obt_fila($rs);

				$url ="kardex.adm.php?tarea=form_editar&id=".$fila_id."&id_mod=".$this->id_mod;
            ?>
            <tr>
              <td><?php echo $fila_id; ?></td>
              <td  class="col-nombre"><?php if (!empty($fila_imagen)){
											$img=$this->fmt->archivos->convertir_url_mini($fila_imagen);

                      echo '<img class="img-user img-responsive" src="'._RUTA_WEB.$img.'" />';
                    } else {
                      echo '<img class="img-user img-responsive" src="'._RUTA_WEB.'images/user/user-default.png" ?>';
                    }
                      echo '<span class="nombre-user"><strong><a href="'.$url.'">'.$fila_nombre.'</a></strong></span>';
                    ?>
              </td>
              <td><?php	echo $fila_ape_p; ?> </td>
              <td><?php	echo $fila_ape_m; ?> </td>
              <td><?php	echo $fila_mail; ?> </td>
              <td><?php	echo $fila_ci; ?> </td>
              <td><?php	echo $fila_exp; ?> </td>
              <td><?php $this->fmt->class_modulo->estado_publicacion($fila_activar,"modulos/rrhh/kardex.adm.php", $this->id_mod,$aux, $fila_id ); ?></td>
              <td>
                <a  id="btn-editar-modulo" class="btn btn-accion btn-editar" href="<?php echo $url; ?>" title="Editar <?php echo $fila_id; ?>" ><i class="icn-pencil"></i></a>
                <a  title="eliminar <?php echo $fila_id; ?>" type="button" idEliminar="<?php echo $fila_id; ?>" nombreEliminar="<?php echo $fila_nombre; ?>" tarea="eliminar" class="btn btn-eliminar btn-accion"><i class="icn-trash"></i></a>
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
		<?php
  }

  function list_papelera(){
  		$botones .= $this->fmt->class_pagina->crear_btn("kardex.adm.php?tarea=busqueda&id_mod=$this->id_mod","btn btn-link","icn-chevron-left","Volver");
	 	$botones .= '<a id="restaurar_all" link="kardex.adm.php?tarea=restaurar&id_mod='.$this->id_mod.'" class="btn btn-link"><i class="icn-sync"></i>Restaurar Seleccionado</a>';

	 	$botones .= '<a id="vaciar_all" link="kardex.adm.php?tarea=vaciar&id_mod='.$this->id_mod.'" class="btn btn-link"><i class="icn-trash"></i>Vaciar Seleccionado</a>';

		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones); // bd, id modulo, botones
		$this->fmt->class_modulo->script_form("modulos/rrhh/kardex.adm.php",$this->id_mod,"asc","1","25",true);
		$id_rol = $this->fmt->sesion->get_variable("usu_rol");
		?>
		<div class="body-modulo">
    <div class="table-responsive">
    	<form action="" id="form_papelera" method="post">
      <table class="table table-hover" id="table_id">
        <thead>
          <tr>
            <th style="width:10%" ><input type="checkbox" id="select_all"></th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Correo Corporativo</th>
            <th>Nro CI/DNI</th>
            <th>Exp.</th>
            <th class="col-xl-offset-2 acciones">Acciones</th>
          </tr>
        </thead>
        <tbody>

          <?php
          	//if($id_rol==1)
            	$sql="select mod_kdx_id, mod_kdx_nombre, mod_kdx_apellido_paterno,  mod_kdx_apellido_materno, mod_kdx_identificacion, mod_kdx_extension, mod_kdx_email_corporativo, mod_kdx_activar, mod_kdx_imagen from mod_kardex where mod_kdx_papelera=1 ORDER BY mod_kdx_id desc";
           /* else{
            	$aux="";
            	$or="";
            	$sql="select rol_rel_cat_id from roles_rel where rol_rel_rol_id=".$id_rol." and rol_rel_cat_id not in (0) ORDER BY rol_rel_cat_id asc";
			      $rs =$this->fmt->query->consulta($sql);
				  $num=$this->fmt->query->num_registros($rs);
				  if($num>0){
				  	for($i=0;$i<$num;$i++){
				    	list($fila_id)=$this->fmt->query->obt_fila($rs);
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

            }*/
            $rs =$this->fmt->query->consulta($sql);
            $num=$this->fmt->query->num_registros($rs);
            if($num>0){
            for($i=0;$i<$num;$i++){
            	list($fila_id,$fila_nombre,$fila_ape_p,$fila_ape_m,$fila_ci, $fila_exp, $fila_mail, $fila_activar,$fila_imagen)=$this->fmt->query->obt_fila($rs);

				$url ="kardex.adm.php?tarea=restaurar_id&id=".$fila_id."&id_mod=".$this->id_mod;
            ?>
            <tr>
              <td><input type="checkbox" name="InputId[]" id="InputId<?php echo $fila_id; ?>" value="<?php echo $fila_id; ?>"></td>
              <td  class="col-nombre"><?php if (!empty($fila_imagen)){
											$img=$this->fmt->archivos->convertir_url_mini($fila_imagen);
                      echo '<img class="img-user img-responsive" src="'._RUTA_WEB.$img.'" />';
                    } else {
                      echo '<img class="img-user img-responsive" src="'._RUTA_WEB.'images/user/user-default.png" ?>';
                    }
                      echo '<span class="nombre-user"><strong><a href="'.$url.'">'.$fila_nombre.'</a></strong></span>';
                    ?>
              </td>
              <td><?php	echo $fila_ape_p; ?> </td>
              <td><?php	echo $fila_ape_m; ?> </td>
              <td><?php	echo $fila_mail; ?> </td>
              <td><?php	echo $fila_ci; ?> </td>
              <td><?php	echo $fila_exp; ?> </td>
              <td>
              	 <a  id="btn-restaurar-modulo" class="btn btn-accion btn-restaurar" href="<?php echo $url; ?>" title="Editar <?php echo $fila_id; ?>" ><i class="icn-sync"></i></a>
                <a  title="Eliminar <?php echo $fila_nombre; ?>" type="button" idEliminar="<?php echo $fila_id; ?>" nombreEliminar="<?php echo $fila_nombre; ?>" tarea="vaciar_id" class="btn btn-eliminar btn-accion"><i class="icn-trash"></i></a>
              </td>
            </tr>
            <?php
             } // Fin for query1
            }// Fin if query1
          ?>

        	</tbody>
      	</table>
      	</form>
    	</div>

  	</div>
  	<?php
  }

  function form_nuevo(){
    $this->fmt->form->head_nuevo('Nuevo registro','kardex',$this->id_mod,'','form_nuevo','form_kardex',''); //$nom,$archivo,$id_mod,$botones,$id_form,$class,$modo
		?>
		<div class="box-pasos">
			<a class="paso on" idf="form-paso-1" id='paso-1'><num>1</num><label>Datos personales</label><hr class="barra"></a>
			<a class="paso" idf="form-paso-2" id='paso-2'><num>2</num><label>Datos Laborales actuales</label><hr class="barra"></a>
			<a class="paso" idf="form-paso-3" id='paso-3' ><num>3</num><label>Formación</label><hr class="barra"></a>
			<a class="paso" idf="form-paso-4" id='paso-4'><num>4</num><label>Datos Laborales corpororación</label><hr class="barra"></a>
			<a class="paso" idf="form-paso-5" id='paso-5'><num>5</num><label>Consolidado</label></a>
		</div>

		<div class="form-paso on animated fadeIn " id='form-paso-1'>
			<?php $this->paso_1_nuevo(); ?>
			<div class='clearfix'></div>
			<a class="btn btn-info btn-siguiente pull-right btn-lg clear-both" paso-siguiente='2'>Siguiente <i class="icn-chevron-right"></i></a>
		</div>

		<div class="form-paso animated fadeIn" id='form-paso-2'>
			<?php $this->paso_2_nuevo(); ?>
			<div class='clearfix'></div>
			<a class="btn btn-info btn-anterior   btn-lg " paso-anterior='1'>Anterior <i class="icn-chevron-left"></i></a>
			<a class="btn btn-info btn-siguiente pull-right btn-lg " paso-siguiente='3'>Siguiente <i class="icn-chevron-right"></i></a>
		</div>
		<div class="form-paso animated fadeIn" id='form-paso-3'>
			<?php $this->paso_3_nuevo(); ?>
			<div class='clearfix'></div>
			<a class="btn btn-info btn-anterior   btn-lg " paso-anterior='2'>Anterior <i class="icn-chevron-left"></i></a>
			<a class="btn btn-info btn-siguiente pull-right btn-lg " paso-siguiente='4'>Siguiente <i class="icn-chevron-right"></i></a>
		</div>
		<div class="form-paso animated fadeIn" id='form-paso-4'>
			<?php $this->paso_4_nuevo(); ?>
			<a class="btn btn-info btn-anterior   btn-lg " paso-anterior='3'>Anterior <i class="icn-chevron-left"></i></a>
			<a class="btn btn-info btn-siguiente pull-right btn-lg " paso-siguiente='5'>Siguiente <i class="icn-chevron-right"></i></a>
		</div>
		<div class="form-paso animated fadeIn" id='form-paso-5'>
			<?php $this->paso_5_nuevo(); ?>
			<button type="submit" class="btn btn-info pull-right btn-guardar color-bg-celecte-b btn-lg" name="btn-accion" id="btn-guardar" value="guardar" disabled="true" ><i class="icn-save" ></i> Guardar</button>
			<button type="submit" class="btn btn-success pull-right color-bg-verde btn-activar btn-lg" name="btn-accion" id="btn-activar" value="activar" disabled="true" ><i class="icn-eye-open" ></i> Activar</button>
			<a class="btn btn-info btn-anterior   btn-lg " paso-anterior='4'>Anterior <i class="icn-chevron-left"></i></a>
		</div>
		<script>
			$(document).ready(function () {
				$(".paso").click(function(event) {
					var id = $(this).attr('idf');
					$(".paso").removeClass('on');
					$(".form-paso").removeClass('on');
					$("#"+id).addClass('on');
					$(this).addClass('on');
					/* Act on the event */
				});

				$(".btn-siguiente").click(function(event) {
					var id = $(this).attr('paso-siguiente');
					$(".paso").removeClass('on');
					$(".form-paso").removeClass('on');
					$("#form-paso-"+id).addClass('on');
					$("#paso-"+id).addClass('on');
				});
				$(".btn-anterior").click(function(event) {
					var id = $(this).attr('paso-anterior');
					$(".paso").removeClass('on');
					$(".form-paso").removeClass('on');
					$("#form-paso-"+id).addClass('on');
					$("#paso-"+id).addClass('on');
				});
				$("#inputPasword").keyup(function(){
					validarpass();
				});
				$("#inputPaswordConfirmar").keyup(function(){
					validarpass();
				});
			});
			function validarpass(){
			var pass = $("#inputPasword").val();
			var re_pass = $("#inputPaswordConfirmar").val();
			if(pass.length>0 && re_pass.length>0){
				if(pass==re_pass){
					$("#btn-guardar").prop("disabled", false);
					$("#btn-activar").prop("disabled", false);
					$("#msg_pass").html("");
				}
				else{
					$("#msg_pass").html('<span class="text-danger"><font><font>Los password no coinciden.</font></font></span>');
					$("#btn-guardar").prop("disabled", true);
					$("#btn-activar").prop("disabled", true);
				}
			}
		}
		</script>
		<?php
    $this->fmt->form->footer_page();
  }
  function form_editar(){
  	$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $_GET['id'];
		$id_rol = $this->fmt->sesion->get_variable("usu_rol");
		$consulta= "SELECT * FROM mod_kardex WHERE mod_kdx_id='".$id."'";
		$rs =$this->fmt->query->consulta($consulta);
		$fila=$this->fmt->query->obt_fila($rs);
		$this->fmt->form->head_editar('Editar Registro','kardex',$this->id_mod,'','form_editar');
	  ?>
		<div class="box-pasos">
			<a class="paso on" idf="form-paso-1" id='paso-1'><num>1</num><label>Datos personales</label><hr class="barra"></a>
			<a class="paso" idf="form-paso-2" id='paso-2'><num>2</num><label>Datos Laborales actuales</label><hr class="barra"></a>
			<a class="paso" idf="form-paso-3" id='paso-3' ><num>3</num><label>Formación</label><hr class="barra"></a>
			<a class="paso" idf="form-paso-4" id='paso-4'><num>4</num><label>Datos Laborales corpororación</label><hr class="barra"></a>
			<a class="paso" idf="form-paso-5" id='paso-5'><num>5</num><label>Consolidado</label></a>
		</div>

		<div class="form-paso on animated fadeIn " id='form-paso-1'>
			<?php $this->paso_1_nuevo($id,$fila["mod_kdx_nombre"],$fila["mod_kdx_apellido_paterno"],$fila["mod_kdx_apellido_materno"],$fila["mod_kdx_identificacion"],$fila["mod_kdx_extension"],$fila["mod_kdx_fecha_vencimiento_identificacion"],$fila["mod_kdx_fecha_vencimiento_licencia_conducir"],$fila["mod_kdx_fecha_nacimiento"],$fila["mod_kdx_nacionalidad"],$fila["mod_kdx_lugar_nacimiento"],$fila["mod_kdx_sexo"],$fila["mod_kdx_estado_civil"],$fila["mod_kdx_nombre_esp"],$fila["mod_kdx_formacion_esp"],$fila["mod_kdx_trabajo_esp"],$fila["mod_kdx_fecha_nac_esp"],$fila["mod_kdx_telefono_domicilio"],$fila["mod_kdx_telefono_corporativo"],$fila["mod_kdx_telefono_corporativo_ext"],$fila["mod_kdx_cecular_personal"],$fila["mod_kdx_cecular_corporativo"],$fila["mod_kdx_email_personal"],$fila["mod_kdx_email_corporativo"],$fila["mod_kdx_direccion_domicilio"],$fila["mod_kdx_coordenadas_domicilio"],$fila["mod_kdx_nro_afiliacion_cns"],$fila["mod_kdx_nro_afp"],$fila["mod_kdx_afp"],$fila["mod_kdx_talla_camisa"],$fila["mod_kdx_talla_pantalon"],$fila["mod_kdx_talla_botines"],$fila["mod_kdx_tipo_sangre"],$fila["mod_kdx_imagen"],$fila["mod_kdx_id_usuario"]); ?>
			<div class='clearfix'></div>
			<a class="btn btn-info btn-siguiente pull-right btn-lg clear-both" paso-siguiente='2'>Siguiente <i class="icn-chevron-right"></i></a>
		</div>

		<div class="form-paso animated fadeIn" id='form-paso-2'>
			<?php $this->paso_2_nuevo($id,$fila["mod_kdx_empresa_actual"],$fila["mod_kdx_division"],$fila["mod_kdx_cargo"],$fila["mod_kdx_departamento"],$fila["mod_kdx_fecha_ingreso"],$fila["mod_kdx_fecha_retiro"],$fila["mod_kdx_antiguedad"],$fila["mod_kdx_codigo_sap"],$fila["mod_kdx_manual_funciones"],$fila["mod_kdx_formato_contrato"],$fila["mod_kdx_cargo_act"],$fila["mod_kdx_cargo_ant"],$fila["mod_kdx_abono_sueldo"]); ?>
			<div class='clearfix'></div>
			<a class="btn btn-info btn-anterior   btn-lg " paso-anterior='1'>Anterior <i class="icn-chevron-left"></i></a>
			<a class="btn btn-info btn-siguiente pull-right btn-lg " paso-siguiente='3'>Siguiente <i class="icn-chevron-right"></i></a>
		</div>
		<div class="form-paso animated fadeIn" id='form-paso-3'>
			<?php $this->paso_3_nuevo($id,$fila["mod_kdx_curriculum"],$fila["mod_kdx_nivel_edu"],$fila["mod_kdx_institucion_edu"],$fila["mod_kdx_titulo"],$fila["mod_kdx_fecha_titulo"]); ?>
			<div class='clearfix'></div>
			<a class="btn btn-info btn-anterior   btn-lg " paso-anterior='2'>Anterior <i class="icn-chevron-left"></i></a>
			<a class="btn btn-info btn-siguiente pull-right btn-lg " paso-siguiente='4'>Siguiente <i class="icn-chevron-right"></i></a>
		</div>
		<div class="form-paso animated fadeIn" id='form-paso-4'>
			<?php $this->paso_4_nuevo($id,$fila["mod_kdx_empresa_ant"],$fila["mod_kdx_cargo_emp_ant"],$fila["mod_kdx_fecha_ing_emp_ant"],$fila["mod_kdx_fecha_sal_emp_ant"],$fila["mod_kdx_antiguedad_emp_ant"]); ?>
			<a class="btn btn-info btn-anterior   btn-lg " paso-anterior='3'>Anterior <i class="icn-chevron-left"></i></a>
			<a class="btn btn-info btn-siguiente pull-right btn-lg " paso-siguiente='5'>Siguiente <i class="icn-chevron-right"></i></a>
		</div>
		<div class="form-paso animated fadeIn" id='form-paso-5'>

			<?php
			 $this->paso_5_nuevo($fila["mod_kdx_id_usuario"]);

			 $this->fmt->form->radio_activar_form($fila['mod_kdx_activar']);
			 ?>
			 <a class="btn btn-info btn-anterior   btn-lg " paso-anterior='4'>Anterior <i class="icn-chevron-left"></i></a>
			 <?php
			 $this->fmt->form->botones_editar($id,$fila['mod_kdx_nombre'],'Kardex');
			 ?>
		</div>
		<script>
			$(document).ready(function () {
				$(".paso").click(function(event) {
					var id = $(this).attr('idf');
					$(".paso").removeClass('on');
					$(".form-paso").removeClass('on');
					$("#"+id).addClass('on');
					$(this).addClass('on');
					/* Act on the event */
				});

				$(".btn-siguiente").click(function(event) {
					var id = $(this).attr('paso-siguiente');
					$(".paso").removeClass('on');
					$(".form-paso").removeClass('on');
					$("#form-paso-"+id).addClass('on');
					$("#paso-"+id).addClass('on');
				});
				$(".btn-anterior").click(function(event) {
					var id = $(this).attr('paso-anterior');
					$(".paso").removeClass('on');
					$(".form-paso").removeClass('on');
					$("#form-paso-"+id).addClass('on');
					$("#paso-"+id).addClass('on');
				});

				$("#inputPasword").keyup(function(){
					validarpass();
				});
				$("#inputPaswordConfirmar").keyup(function(){
					validarpass();
				});
			});
			function validarpass(){
			var pass = $("#inputPasword").val();
			var re_pass = $("#inputPaswordConfirmar").val();
			if(pass.length>0 && re_pass.length>0){
				if(pass==re_pass){
					$("#btn-activar").prop("disabled", false);
					$("#msg_pass").html("");
				}
				else{
					$("#msg_pass").html('<span class="text-danger"><font><font>Los password no coinciden.</font></font></span>');
					$("#btn-activar").prop("disabled", true);
				}
			}
		}
		</script>
		<?php
    $this->fmt->form->footer_page();
  }

  function ingresar(){
	  if ($_POST["btn-accion"]=="activar"){
			$activar=1;
		}
		if ($_POST["btn-accion"]=="guardar"){
			$activar=0;
		}
		$id_us=0;
		if($_POST["inputEmailUsuario"]!=""){
			$ingresar = "usu_nombre, usu_apellidos, usu_email, usu_password, usu_estado, usu_padre";
			$valores  = "'".$_POST['inputNombreK']."','".
						$_POST['inputApellidoPaterno']." ".$_POST['inputApellidoMaterno']."','".
						$_POST['inputEmailUsuario']."','".
						base64_encode($_POST['inputPasword'])."','".
						$activar."','1'";

			$sql="insert into usuarios (".$ingresar.") values (".$valores.")";
			$this->fmt->query->consulta($sql);

			$sql="select max(usu_id) as id_usu from usuarios";
			$rs= $this->fmt->query->consulta($sql);
			$fila = $this->fmt->query->obt_fila($rs);
			$id_us = $fila ["id_usu"];

			$rol = $_POST['inputRol'];

			$ingresar1 ="usuarios_usu_id, roles_rol_id";
			$valores1 = "'".$id_us."','".$rol."'";
			$sql1="insert into usuarios_roles (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);

			$ingresar1 ="usuarios_usu_id, grupos_grupo_id";

			$grupo_rol = $_POST['inputRolGrupo'];
			$cont_grupo_rol= count($grupo_rol);
			for($i=0;$i<$cont_grupo_rol;$i++){
				$valores1 = "'".$id_us."','".$grupo_rol[$i]."'";
				$sql2="insert into usuarios_grupos (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql2);

			}
		}

		$ruta_arch = $_POST["inputRutaArchivosDocs"];
		$nombre_arch=$_FILES["inputArchivosDocs"]["name"];
		$archivo_cv="";
		if($nombre_arch!=""){
			$data = explode("sitios", $ruta_arch);
			$archivo_cv = "sitios".$data[1]."/".strtolower($nombre_arch);
		}

		$ingresar ="mod_kdx_nombre,
		mod_kdx_apellido_paterno,
		mod_kdx_apellido_materno,
		mod_kdx_identificacion,
		mod_kdx_extension,
		mod_kdx_fecha_vencimiento_identificacion,
		mod_kdx_fecha_vencimiento_licencia_conducir,
		mod_kdx_fecha_nacimiento,
		mod_kdx_nacionalidad,
		mod_kdx_lugar_nacimiento,
		mod_kdx_sexo,
		mod_kdx_estado_civil,
		mod_kdx_nombre_esp,
		mod_kdx_formacion_esp,
		mod_kdx_trabajo_esp,
		mod_kdx_fecha_nac_esp,
		mod_kdx_telefono_domicilio,
		mod_kdx_telefono_corporativo,
		mod_kdx_telefono_corporativo_ext,
		mod_kdx_cecular_personal,
		mod_kdx_cecular_corporativo,
		mod_kdx_email_personal,
		mod_kdx_email_corporativo,
		mod_kdx_direccion_domicilio,
		mod_kdx_coordenadas_domicilio,
		mod_kdx_nro_afiliacion_cns,
		mod_kdx_nro_afp,
		mod_kdx_afp,
		mod_kdx_talla_camisa,
		mod_kdx_talla_pantalon,
		mod_kdx_talla_botines,
		mod_kdx_tipo_sangre,
		mod_kdx_empresa_actual,
		mod_kdx_division,
		mod_kdx_cargo,
		mod_kdx_departamento,
		mod_kdx_fecha_ingreso,
		mod_kdx_fecha_retiro,
		mod_kdx_antiguedad,
		mod_kdx_codigo_sap,
		mod_kdx_manual_funciones,
		mod_kdx_formato_contrato,
		mod_kdx_cargo_act,
		mod_kdx_cargo_ant,
		mod_kdx_abono_sueldo,
		mod_kdx_curriculum,
		mod_kdx_nivel_edu,
		mod_kdx_institucion_edu,
		mod_kdx_titulo,
		mod_kdx_fecha_titulo,
		mod_kdx_empresa_ant,
		mod_kdx_cargo_emp_ant,
		mod_kdx_fecha_ing_emp_ant,
		mod_kdx_fecha_sal_emp_ant,
		mod_kdx_antiguedad_emp_ant,
		mod_kdx_imagen,
		mod_kdx_id_usuario,
		mod_kdx_activar";
		$valores  ="'".$_POST['inputNombreK']."','".
				$_POST['inputApellidoPaterno']."','".
				$_POST['inputApellidoMaterno']."','".
				$_POST['inputIdentificacion']."','".
				$_POST['inputExtension']."','".
				$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputFechaVencimientoCiDni'])."','".
				$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputFechaVencimientoLicenciaConducir'])."','".
				$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputFechaNaciento'])."','".
				$_POST['inputNacionalidad']."','".
				$_POST['inputLugarNacimiento']."','".
				$_POST['inputSexo']."','".
				$_POST['inputEstadoCivil']."','".
				$_POST['inputEsposa']."','".
				$_POST['inputFormacionEsposa']."','".
				$_POST['inputTrabajoEsposa']."','".
				$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputFechaCumpleEsposa'])."','".
				$_POST['inputTelefonoDomicilio']."','".
				$_POST['inputTelefonoCorporativo']."','".
				$_POST['inputInterno']."','".
				$_POST['inputCelularPersonal']."','".
				$_POST['inputCelularCorporativo']."','".
				$_POST['inputEmailPersonal']."','".
				$_POST['inputEmailCorporativo']."','".
				$_POST['inputDireccionDomicilio']."','".
				$_POST['inputMapaDomicilio']."','".
				$_POST['inputAfiliacionCNS']."','".
				$_POST['inputAfiliacionAFP']."','".
				$_POST['inputAFP']."','".
				$_POST['inputTallaCamisa']."','".
				$_POST['inputTallaPantalon']."','".
				$_POST['inputTallaBotines']."','".
				$_POST['inputTipoSangre']."','".
				$_POST['inputEmpresaActual']."','".
				$_POST['inputDivision']."','".
				$_POST['inputCargoActual']."','".
				$_POST['inputDepartamento']."','".
				$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputIngresoEmpresaActual'])."','".
				$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputRetiroActual'])."','".
				$_POST['inputAntiguedad']."','".
				$_POST['inputCodigoSAP']."','".
				$_POST['inputManualFunc']."','".
				$_POST['inputFormatoContrato']."','".
				$_POST['inputCargoActualContrato']."','".
				$_POST['inputCargoAnteriorContrato']."','".
				$_POST['inputCuentaSueldo']."','".
				$archivo_cv."','".
				$_POST['inputNivelEducacion']."','".
				$_POST['inputInstitucion']."','".
				$_POST['inputTitulo']."','".
				$_POST['inputFechaTitulo']."','".
				$_POST['inputEmpresaAnterior']."','".
				$_POST['inputCargoEmpresaAnterior']."','".
				$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputFechaEmpresaAnterior'])."','".
				$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputRetiroAnterior'])."','".
				$_POST['inputAntiguedadAnterior']."','".
				$_POST['inputUrl']."','".
				$id_us."','".
				$activar."'";

			$sql="insert into mod_kardex (".$ingresar.") values (".$valores.")";
			echo $sql;
			$this->fmt->query->consulta($sql);

			$sql="select max(mod_kdx_id) as id from mod_kardex";
			$rs= $this->fmt->query->consulta($sql);
			$fila = $this->fmt->query->obt_fila($rs);
			$id = $fila ["id"];

		$ingresar1 ="mod_kdx_hj_nombre, mod_kdx_hj_inst_educativa, mod_kdx_hj_fecha_nac, mod_kdx_hj_id_kardex";

		$nombre_hijo = $_POST['inputNombreHijo'];
		$institucion = $_POST['inputInstitucionHijo'];
		$fecha_hijo = $_POST['inputFechaCumpleHijo'];
		$cant_hijo= count($nombre_hijo);
		for($i=0;$i<$cant_hijo;$i++){
			$valores1 = "'".$nombre_hijo[$i]."','".$institucion[$i]."', '".$this->fmt->class_modulo->Restructurar_Fecha($fecha_hijo[$i])."', '".$id."'";
			$sql2="insert into mod_kardex_hijos (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql2);
		}

		$ingresar1 ="mod_kdx_ref_emg_nombre, mod_kdx_ref_emg_parentesco, mod_kdx_ref_emg_telefono, mod_kdx_ref_emg_id_kardex";

		$nombre_emer = $_POST['inputNombreEmergencia'];
		$parentesco_emer = $_POST['inputParentescoEmergencia'];
		$telefono_emer = $_POST['inputTelefonoEmergencia'];
		$cant_emer= count($nombre_emer);
		for($i=0;$i<$cant_emer;$i++){
			$valores1 = "'".$nombre_emer[$i]."','".$parentesco_emer[$i]."', '".$telefono_emer[$i]."', '".$id."'";
			$sql2="insert into mod_kardex_ref_emergencia (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql2);
		}

		$ingresar1 ="mod_kardex_ref_nombre, mod_kardex_ref_telefono, mod_kardex_ref_id_kardex";

		$nombre_ref = $_POST['inputNombreReferencia'];
		$telefono_ref = $_POST['inputTelefonoReferencia'];
		$cant_ref= count($nombre_ref);
		for($i=0;$i<$cant_ref;$i++){
			$valores1 = "'".$nombre_ref[$i]."', '".$telefono_ref[$i]."', '".$id."'";
			$sql2="insert into mod_kardex_referencias (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql2);
		}

		$ingresar1 ="mod_kdx_bnc_nombre, mod_kdx_bnc_nro_cuenta, mod_kdx_bnc_moneda, mod_kdx_bnc_id_kardex";

		$nombre_banco = $_POST['inputBanco'];
		$numero_cuenta = $_POST['inputCuentaBanco'];
		$moneda_banco = $_POST['inputMoneda'];
		$cant_cuenta= count($numero_cuenta);
		for($i=0;$i<$cant_cuenta;$i++){
			$valores1 = "'".$nombre_banco[$i]."','".$numero_cuenta[$i]."', '".$moneda_banco[$i]."', '".$id."'";
			$sql2="insert into mod_kardex_bancos (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql2);
		}

		$ingresar1 ="mod_kdx_frm_nombre, mod_kdx_frm_institucion, mod_kdx_frm_fecha, mod_kdx_frm_id_kardex";

		$nombre_curso = $_POST['inputCurso'];
		$inst_curso = $_POST['inputInstitucionCurso'];
		$fecha_curso = $_POST['inputFechaCurso'];
		$cant_curso= count($nombre_curso);
		for($i=0;$i<$cant_curso;$i++){
			$valores1 = "'".$nombre_curso[$i]."','".$inst_curso[$i]."', '".$fecha_curso[$i]."', '".$id."'";
			$sql2="insert into mod_kardex_formacion (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql2);
		}

		$ingresar1 ="mod_kdx_his_corp_empresa, mod_kdx_his_corp_fecha_ingreso, mod_kdx_his_corp_fecha_salida, mod_kdx_his_corp_cargo, mod_kdx_his_corp_id_kardex";

		$emp_anterior = $_POST['inputHEmpresaAnterior'];
		$emp_fecha_ing = $_POST['inputHIngresoEmpresa'];
		$emp_fecha_sal = $_POST['inputHCargoEmpsera'];
		$emp_cargo = $_POST['inputHCargoEmpsera'];
		$cant_emp= count($emp_cargo);
		for($i=0;$i<$cant_emp;$i++){
			$valores1 = "'".$emp_anterior[$i]."','".$this->fmt->class_modulo->Restructurar_Fecha($emp_fecha_ing[$i])."', '".$this->fmt->class_modulo->Restructurar_Fecha($emp_fecha_sal[$i])."','!".$emp_cargo[$i]."','".$id."'";
			$sql2="insert into mod_kardex_historial_corporativo (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql2);
		}
		$url="kardex.adm.php?id_mod=".$this->id_mod;
		$this->fmt->class_modulo->script_location($url);
  }

  function modificar(){
		if ($_POST["btn-accion"]=="eliminar"){}

		if ($_POST["btn-accion"]=="actualizar"){
			$id=$_POST["inputId"];
			$id_us=$_POST["inputIdUsuario"];
			if($id_us>0){
				$sql = "UPDATE usuarios SET usu_nombre='".$_POST['inputNombreK']."', usu_apellidos='".$_POST['inputApellidoPaterno']." ".$_POST['inputApellidoMaterno']."', usu_email='".$_POST['inputEmailUsuario']."', usu_password='".base64_encode($_POST['inputPasword'])."', usu_estado='".$_POST['inputActivar']."', usu_padre='1' where usu_id='".$id_us."'";
				$this->fmt->query->consulta($sql);

				$this->fmt->class_modulo->eliminar_fila($id_us,"usuarios_roles","usuarios_usu_id");
				$this->fmt->class_modulo->eliminar_fila($id_us,"usuarios_grupos","usuarios_usu_id");

				$rol = $_POST['inputRol'];

				$ingresar1 ="usuarios_usu_id, roles_rol_id";
				$valores1 = "'".$id_us."','".$rol."'";
				$sql1="insert into usuarios_roles (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);

				$ingresar1 ="usuarios_usu_id, grupos_grupo_id";

				$grupo_rol = $_POST['inputRolGrupo'];
				$cont_grupo_rol= count($grupo_rol);
				for($i=0;$i<$cont_grupo_rol;$i++){
					$valores1 = "'".$id_us."','".$grupo_rol[$i]."'";
					$sql2="insert into usuarios_grupos (".$ingresar1.") values (".$valores1.")";
					$this->fmt->query->consulta($sql2);

				}
			}

			$ruta_arch = $_POST["inputRutaArchivosDocs"];
			$nombre_arch=$_FILES["inputArchivosDocs"]["name"];
			$archivo_cv="";
			if($nombre_arch!=""){
				$data = explode("sitios", $ruta_arch);
				$archivo_cv = "sitios".$data[1]."/".strtolower($nombre_arch);
			}
			$sql ="UPDATE mod_kardex SET mod_kdx_nombre='".$_POST['inputNombreK']."',
			mod_kdx_apellido_paterno='".$_POST['inputApellidoPaterno']."',
			mod_kdx_apellido_materno='".$_POST['inputApellidoMaterno']."',
			mod_kdx_identificacion='".$_POST['inputIdentificacion']."',
			mod_kdx_extension='".$_POST['inputExtension']."',
			mod_kdx_fecha_vencimiento_identificacion='".$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputFechaVencimientoCiDni'])."',
			mod_kdx_fecha_vencimiento_licencia_conducir='".$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputFechaVencimientoLicenciaConducir'])."',
			mod_kdx_fecha_nacimiento='".$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputFechaNaciento'])."',
			mod_kdx_nacionalidad='".$_POST['inputNacionalidad']."',
			mod_kdx_lugar_nacimiento='".$_POST['inputLugarNacimiento']."',
			mod_kdx_sexo='".$_POST['inputSexo']."',
			mod_kdx_estado_civil='".$_POST['inputEstadoCivil']."',
			mod_kdx_nombre_esp='".$_POST['inputEsposa']."',
			mod_kdx_formacion_esp='".$_POST['inputFormacionEsposa']."',
			mod_kdx_trabajo_esp='".$_POST['inputTrabajoEsposa']."',
			mod_kdx_fecha_nac_esp='".$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputFechaCumpleEsposa'])."',
			mod_kdx_telefono_domicilio='".$_POST['inputTelefonoDomicilio']."',
			mod_kdx_telefono_corporativo='".$_POST['inputTelefonoCorporativo']."',
			mod_kdx_telefono_corporativo_ext='".$_POST['inputInterno']."',
			mod_kdx_cecular_personal='".$_POST['inputCelularPersonal']."',
			mod_kdx_cecular_corporativo='".$_POST['inputCelularCorporativo']."',
			mod_kdx_email_personal='".$_POST['inputEmailPersonal']."',
			mod_kdx_email_corporativo='".$_POST['inputEmailCorporativo']."',
			mod_kdx_direccion_domicilio='".$_POST['inputDireccionDomicilio']."',
			mod_kdx_coordenadas_domicilio='".$_POST['inputMapaDomicilio']."',
			mod_kdx_nro_afiliacion_cns='".$_POST['inputAfiliacionCNS']."',
			mod_kdx_nro_afp='".$_POST['inputAfiliacionAFP']."',
			mod_kdx_afp='".$_POST['inputAFP']."',
			mod_kdx_talla_camisa='".$_POST['inputTallaCamisa']."',
			mod_kdx_talla_pantalon='".$_POST['inputTallaPantalon']."',
			mod_kdx_talla_botines='".$_POST['inputTallaBotines']."',
			mod_kdx_tipo_sangre='".$_POST['inputTipoSangre']."',
			mod_kdx_empresa_actual='".$_POST['inputEmpresaActual']."',
			mod_kdx_division='".$_POST['inputDivision']."',
			mod_kdx_cargo='".$_POST['inputCargoActual']."',
			mod_kdx_departamento='".$_POST['inputDepartamento']."',
			mod_kdx_fecha_ingreso='".$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputIngresoEmpresaActual'])."',
			mod_kdx_fecha_retiro='".$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputRetiroActual'])."',
			mod_kdx_antiguedad='".$_POST['inputAntiguedad']."',
			mod_kdx_codigo_sap='".$_POST['inputCodigoSAP']."',
			mod_kdx_manual_funciones='".$_POST['inputManualFunc']."',
			mod_kdx_formato_contrato='".$_POST['inputFormatoContrato']."',
			mod_kdx_cargo_act='".$_POST['inputCargoActualContrato']."',
			mod_kdx_cargo_ant='".$_POST['inputCargoAnteriorContrato']."',
			mod_kdx_abono_sueldo='".$_POST['inputCuentaSueldo']."',
			mod_kdx_curriculum='".$archivo_cv."',
			mod_kdx_nivel_edu='".$_POST['inputNivelEducacion']."',
			mod_kdx_institucion_edu='".$_POST['inputInstitucion']."',
			mod_kdx_titulo='".$_POST['inputTitulo']."',
			mod_kdx_fecha_titulo='".$_POST['inputFechaTitulo']."',
			mod_kdx_empresa_ant='".$_POST['inputEmpresaAnterior']."',
			mod_kdx_cargo_emp_ant='".$_POST['inputCargoEmpresaAnterior']."',
			mod_kdx_fecha_ing_emp_ant='".$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputFechaEmpresaAnterior'])."',
			mod_kdx_fecha_sal_emp_ant='".$this->fmt->class_modulo->Restructurar_Fecha($_POST['inputRetiroAnterior'])."',
			mod_kdx_antiguedad_emp_ant='".$_POST['inputAntiguedadAnterior']."',
			mod_kdx_imagen='".$_POST['inputUrl']."',
			mod_kdx_activar='".$_POST['inputActivar']."'
			WHERE mod_kdx_id='".$id."'";

			$this->fmt->query->consulta($sql);

			$this->eliminar_relaciones($id);

			$ingresar1 ="mod_kdx_hj_nombre, mod_kdx_hj_inst_educativa, mod_kdx_hj_fecha_nac, mod_kdx_hj_id_kardex";

			$nombre_hijo = $_POST['inputNombreHijo'];
			$institucion = $_POST['inputInstitucionHijo'];
			$fecha_hijo = $_POST['inputFechaCumpleHijo'];
			$cant_hijo= count($nombre_hijo);
			for($i=0;$i<$cant_hijo;$i++){
				$valores1 = "'".$nombre_hijo[$i]."','".$institucion[$i]."', '".$this->fmt->class_modulo->Restructurar_Fecha($fecha_hijo[$i])."', '".$id."'";
				$sql2="insert into mod_kardex_hijos (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql2);
			}

			$ingresar1 ="mod_kdx_ref_emg_nombre, mod_kdx_ref_emg_parentesco, mod_kdx_ref_emg_telefono, mod_kdx_ref_emg_id_kardex";

			$nombre_emer = $_POST['inputNombreEmergencia'];
			$parentesco_emer = $_POST['inputParentescoEmergencia'];
			$telefono_emer = $_POST['inputTelefonoEmergencia'];
			$cant_emer= count($nombre_emer);
			for($i=0;$i<$cant_emer;$i++){
				$valores1 = "'".$nombre_emer[$i]."','".$parentesco_emer[$i]."', '".$telefono_emer[$i]."', '".$id."'";
				$sql2="insert into mod_kardex_ref_emergencia (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql2);
			}

			$ingresar1 ="mod_kardex_ref_nombre, mod_kardex_ref_telefono, mod_kardex_ref_id_kardex";

			$nombre_ref = $_POST['inputNombreReferencia'];
			$telefono_ref = $_POST['inputTelefonoReferencia'];
			$cant_ref= count($nombre_ref);
			for($i=0;$i<$cant_ref;$i++){
				$valores1 = "'".$nombre_ref[$i]."', '".$telefono_ref[$i]."', '".$id."'";
				$sql2="insert into mod_kardex_referencias (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql2);
			}

			$ingresar1 ="mod_kdx_bnc_nombre, mod_kdx_bnc_nro_cuenta, mod_kdx_bnc_moneda, mod_kdx_bnc_id_kardex";

			$nombre_banco = $_POST['inputBanco'];
			$numero_cuenta = $_POST['inputCuentaBanco'];
			$moneda_banco = $_POST['inputMoneda'];
			$cant_cuenta= count($numero_cuenta);
			for($i=0;$i<$cant_cuenta;$i++){
				$valores1 = "'".$nombre_banco[$i]."','".$numero_cuenta[$i]."', '".$moneda_banco[$i]."', '".$id."'";
				$sql2="insert into mod_kardex_bancos (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql2);
			}

			$ingresar1 ="mod_kdx_frm_nombre, mod_kdx_frm_institucion, mod_kdx_frm_fecha, mod_kdx_frm_id_kardex";

			$nombre_curso = $_POST['inputCurso'];
			$inst_curso = $_POST['inputInstitucionCurso'];
			$fecha_curso = $_POST['inputFechaCurso'];
			$cant_curso= count($nombre_curso);
			for($i=0;$i<$cant_curso;$i++){
				$valores1 = "'".$nombre_curso[$i]."','".$inst_curso[$i]."', '".$fecha_curso[$i]."', '".$id."'";
				$sql2="insert into mod_kardex_formacion (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql2);
			}

			$ingresar1 ="mod_kdx_his_corp_empresa, mod_kdx_his_corp_fecha_ingreso, mod_kdx_his_corp_fecha_salida, mod_kdx_his_corp_cargo, mod_kdx_his_corp_id_kardex";

			$emp_anterior = $_POST['inputHEmpresaAnterior'];
			$emp_fecha_ing = $_POST['inputHIngresoEmpresa'];
			$emp_fecha_sal = $_POST['inputHCargoEmpsera'];
			$emp_cargo = $_POST['inputHCargoEmpsera'];
			$cant_emp= count($emp_cargo);
			for($i=0;$i<$cant_emp;$i++){
				$valores1 = "'".$emp_anterior[$i]."','".$this->fmt->class_modulo->Restructurar_Fecha($emp_fecha_ing[$i])."', '".$this->fmt->class_modulo->Restructurar_Fecha($emp_fecha_sal[$i])."','!".$emp_cargo[$i]."','".$id."'";
				$sql2="insert into mod_kardex_historial_corporativo (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql2);
			}
		}
		$url="kardex.adm.php?id_mod=".$this->id_mod;
		$this->fmt->class_modulo->script_location($url);
	}
	function eliminar_relaciones($id_kardex){
		$this->fmt->class_modulo->eliminar_fila($id_kardex,"mod_kardex_bancos","mod_kdx_bnc_id_kardex");
		$this->fmt->class_modulo->eliminar_fila($id_kardex,"mod_kardex_formacion","mod_kdx_frm_id_kardex");
		$this->fmt->class_modulo->eliminar_fila($id_kardex,"mod_kardex_hijos","mod_kdx_hj_id_kardex");
		$this->fmt->class_modulo->eliminar_fila($id_kardex,"mod_kardex_historial_corporativo","mod_kdx_his_corp_id_kardex");
		$this->fmt->class_modulo->eliminar_fila($id_kardex,"mod_kardex_referencias","mod_kardex_ref_id_kardex");
		$this->fmt->class_modulo->eliminar_fila($id_kardex,"mod_kardex_ref_emergencia","mod_kdx_ref_emg_id_kardex");
	}
	function activar(){
	    $this->fmt->class_modulo->activar_get_id("mod_kardex","mod_kdx_");
	    $url="kardex.adm.php?id_mod=".$this->id_mod;
		$this->fmt->class_modulo->script_location($url);
  	}

  	function eliminar(){
  		$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $_GET['id'];
		$sql ="UPDATE mod_kardex SET mod_kdx_papelera='1' where mod_kdx_id='".$id."'";
  		$this->fmt->query->consulta($sql);
  		$url="kardex.adm.php?id_mod=".$this->id_mod;
		$this->fmt->class_modulo->script_location($url);
  	}

  	function restaurar_id(){
	  	$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $_GET['id'];
		$sql ="UPDATE mod_kardex SET mod_kdx_papelera='0' where mod_kdx_id='".$id."'";
  		$this->fmt->query->consulta($sql);
  		$url="kardex.adm.php?tarea=list_papelera&id_mod=".$this->id_mod;
		$this->fmt->class_modulo->script_location($url);
  	}

  	function restaurar(){
	  	$ids = $_POST["InputId"];
	  	$num = count($ids);
	  	for($i=0;$i<$num;$i++){
			$sql ="UPDATE mod_kardex SET mod_kdx_papelera='0' where mod_kdx_id='".$ids[$i]."'";
			$this->fmt->query->consulta($sql);
  		}
  		$url="kardex.adm.php?tarea=list_papelera&id_mod=".$this->id_mod;
		$this->fmt->class_modulo->script_location($url);
  	}

  	function vaciar(){
	  	$ids = $_POST["InputId"];
	  	$num = count($ids);
	  	for($i=0;$i<$num;$i++){
			$this->fmt->class_modulo->eliminar_fila($ids[$i],"mod_kardex","mod_kdx_id");
			$this->eliminar_relaciones($ids[$i]);
  		}
  		$url="kardex.adm.php?tarea=list_papelera&id_mod=".$this->id_mod;
		$this->fmt->class_modulo->script_location($url);
  	}

  	function vaciar_id(){
  		$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $_GET['id'];
  		$this->fmt->class_modulo->eliminar_get_id("mod_kardex","mod_kdx_");
  		$this->eliminar_relaciones($id);
  		$url="kardex.adm.php?tarea=list_papelera&id_mod=".$this->id_mod;
		$this->fmt->class_modulo->script_location($url);
  	}

	function paso_1_nuevo($id=0,$nombre,$apellido_pa,$apellido_ma,$ci,$ci_ext,$fecha_ven_ci,$fecha_ven_lic,$fecha_nac,$nacionalidad,$lugar_nac,$sexo,$estado_civil,$nombre_esp,$formacion_esp,$trabajo_esp,$fecha_nac_esp,$telefono_dom,$telefono_corp,$telefono_corp_ext,$cecular_per,$cecular_corp,$email_per,$email_corp,$direccion_dom,$coordenadas_dom,$nro_afiliacion_cns,$nro_afp,$afp,$talla_camisa,$talla_pantalon,$talla_botines,$tipo_sangre,$imagen,$id_usuario){
		if($fecha_ven_ci!="")
			$fecha_ven_ci=$this->fmt->class_modulo->Estructurar_Fecha_input($fecha_ven_ci);
		if($fecha_ven_lic!="")
			$fecha_ven_lic=$this->fmt->class_modulo->Estructurar_Fecha_input($fecha_ven_lic);
		if($fecha_nac!="")
			$fecha_nac=$this->fmt->class_modulo->Estructurar_Fecha_input($fecha_nac);
		if($fecha_nac_esp!="")
			$fecha_nac_esp=$this->fmt->class_modulo->Estructurar_Fecha_input($fecha_nac_esp);
		$consulta = "select * from mod_kardex_hijos where mod_kdx_hj_id_kardex=$id";
		$rs =$this->fmt->query->consulta($consulta);
		$num_h=$this->fmt->query->num_registros($rs);
        if($num_h>0){
         for($i=0;$i<$num_h;$i++){
           	list($fila_id_hijo,$fila_nombre_hijo,$fila_inst_hijo,$fila_fecha_nac_hijo)=$this->fmt->query->obt_fila($rs);
           	$nombre_hijo[$i]=$fila_nombre_hijo;
	        $institucion_hijo[$i]=$fila_inst_hijo;
	        $fecha_nac_hijo[$i]=$this->fmt->class_modulo->Estructurar_Fecha_input($fila_fecha_nac_hijo);
         }
        }
        else{
	        $nombre_hijo[0]="";
	        $institucion_hijo[0]="";
	        $fecha_nac_hijo[0]="";
        }

        $consulta = "select * from mod_kardex_referencias where mod_kardex_ref_id_kardex=$id";
		$rs =$this->fmt->query->consulta($consulta);
		$num_r=$this->fmt->query->num_registros($rs);
        if($num_r>0){
         for($i=0;$i<$num_r;$i++){
           	list($fila_id_ref,$fila_nombre_ref,$fila_telefono_ref)=$this->fmt->query->obt_fila($rs);
           	$nombre_ref[$i]=$fila_nombre_ref;
	        $telefono_ref[$i]=$fila_telefono_ref;
         }
        }
        else{
	        $nombre_ref[0]="";
	        $telefono_ref[0]="";
        }

        $consulta = "select * from mod_kardex_ref_emergencia where mod_kdx_ref_emg_id_kardex=$id";
		$rs =$this->fmt->query->consulta($consulta);
		$num_r_m=$this->fmt->query->num_registros($rs);
        if($num_r_m>0){
         for($i=0;$i<$num_r_m;$i++){
           	list($fila_id_ref_em,$fila_nombre_ref_em,$fila_parent_ref_em,$fila_telefono_ref_em)=$this->fmt->query->obt_fila($rs);
           	$nombre_ref_em[$i]=$fila_nombre_ref_em;
	        $parentesco_ref_em[$i]=$fila_parent_ref_em;
	        $telefono_ref_em[$i]=$fila_telefono_ref_em;
         }
        }
        else{
	        $nombre_ref_em[0]="";
	        $parentesco_ref_em[0]="";
	        $telefono_ref_em[0]="";
        }
		echo '<script type="text/javascript" language="javascript" src="'._RUTA_WEB.'js/mapa.js"></script>'."\n";

		echo "<h3>Datos personales</h3>";
		$this->fmt->form->input_hidden_form("inputId",$id);
		$this->fmt->form->input_hidden_form("inputIdUsuario",$id_usuario);
		$this->fmt->form->input_form('Nombre Completo:','inputNombreK','',$nombre,'requerido requerido-texto','box-md-4',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_form('Apellido Paterno:','inputApellidoPaterno','',$apellido_pa,'requerido requerido-texto','box-md-4',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_form('Apellido Materno:','inputApellidoMaterno','',$apellido_ma,'requerido requerido-texto','box-md-4',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_form('CI/DNI:','inputIdentificacion','',$ci,'requerido requerido-texto','box-md-2','');
		$options = $this->extensiones();
		$valores = $this->extensiones();
		$this->fmt->form->select_form_simple('Extensión:','inputExtension',$options,$valores,'',$ci_ext,'box-md-1'); //$label,$id,$options,$valores,$desabilitado,$defecto,$class_div
		$this->fmt->form->input_date('Fecha de vencimiento CI/DNI:','inputFechaVencimientoCiDni','',$fecha_ven_ci,'','box-md-3',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_date('Fecha de vencimiento de licencia de conducir:','inputFechaVencimientoLicenciaConducir','',$fecha_ven_lic,'','box-md-4',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_date('Fecha de nacimiento:','inputFechaNaciento','',$fecha_nac,'','box-md-2',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_form('Nacionalidad:','inputNacionalidad','',$nacionalidad,'requerido requerido-texto','box-md-3',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_form('Lugar de nacimiento','inputLugarNacimiento','',$lugar_nac,'requerido requerido-texto','box-md-3',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$options=array("M","F","otro");
		$valores=array("0","1","2");
		$this->fmt->form->select_form_simple('Sexo:','inputSexo',$options,$valores,'',$sexo,'box-md-1'); //$label,$id,$options,$valores,$desabilitado,$defecto,$class_div
		$options=array("Soltero(a)","Casado(a)","Divorciado(a)","Viudo(a)","Concubino(a)");
		$valores=array("0","1","2","3","4");
		$this->fmt->form->select_form_simple('Estado Civil:','inputEstadoCivil',$options,$valores,'',$estado_civil,'box-md-1'); //$label,$id,$options,$valores,$desabilitado,$defecto,$class_div
		$this->fmt->form->input_form('Tipo de sangre:','inputTipoSangre','',$tipo_sangre,'requerido requerido-texto','box-md-2',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_form('Telf. Domicilio','inputTelefonoDomicilio','',$telefono_dom,'requerido requerido-telefono','box-md-3 clear-left','Ej: (591) 3 340-32323'); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
		$this->fmt->form->input_form('Telf. Corporativo','inputTelefonoCorporativo','',$telefono_corp,'requerido requerido-telefono','box-md-2','Ej: (591) 3 340-32323'); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
		$this->fmt->form->input_form('Interno:','inputInterno','',$telefono_corp_ext,'requerido requerido-numero','box-md-1',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
		$this->fmt->form->input_form('Celular personal:','inputCelularPersonal','',$cecular_per,'requerido requerido-celular','box-md-3','Ej: (591) 755-23452'); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
		$this->fmt->form->input_form('Celular Corporativo:','inputCelularCorporativo','',$cecular_corp,'requerido requerido-celular','box-md-3','Ej: (591) 755-23452'); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
		$this->fmt->form->input_form('E-mail personal:','inputEmailPersonal','@',$email_per,'requerido requerido-email','box-md-4',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
		$this->fmt->form->input_form('E-mail Corporativo:','inputEmailCorporativo','@',$email_corp,'requerido requerido-email','box-md-4',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
		$this->fmt->form->input_form('Dirección Domicilio:','inputDireccionDomicilio','',$direccion_dom,'requerido requerido-email','box-md-7 clear-left',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
		$this->fmt->form->input_form('Mapa Domicilio:','inputMapaDomicilio','',$coordenadas_dom,'requerido requerido-email','box-md-2',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
		//$this->fmt->form->input_form('Dirección Empresa:','inputDireccionDomicilio','','','mapa','box-md-7 clear-left',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
		//$this->fmt->form->input_form('Mapa Empresa:','inputMapaEmpresa','','','mapa','box-md-2 ',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
		echo '<div class="form-group box-md-2"><button class="btn btn-info" type="button" onclick="AbrirMapa()">Ver Mapa</button></div>';
		$this->fmt->form->input_form('Nro. Afiliación CNS:','inputAfiliacionCNS','',$nro_afiliacion_cns,'requerido requerido-texto','box-md-3 clear-left','');
		$this->fmt->form->input_form('Nro. Afiliación AFP:','inputAfiliacionAFP','',$nro_afp,'requerido requerido-texto','box-md-3','');
		$options=array(" ","AFP Previsión","AFP Futuro","BBVA");
		$valores=array("0","1","2","3");
		$this->fmt->form->select_form_simple('AFP:','inputAFP',$options,$valores,'',$afp,'box-md-2');
		$this->fmt->form->input_form('Talla de camisa:','inputTallaCamisa','',$talla_camisa,'','box-md-2 clear-left','');
		$this->fmt->form->input_form('Talla de pantalón:','inputTallaPantalon','',$talla_pantalon,'','box-md-2','');
		$this->fmt->form->input_form('Talla de botines:','inputTallaBotines','','',$talla_botines,'box-md-2','');
		echo "<h3 class='clear-both'>Datos Familia</h3>";
		$this->fmt->form->input_form('Nombre Completo Esposo(a):','inputEsposa','',$nombre_esp,'','box-md-7',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_date('Fecha cumpleaños esposo(a):','inputFechaCumpleEsposa','',$fecha_nac_esp,'','box-md-3',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_form('Formación Esposo(a):','inputFormacionEsposa','',$formacion_esp,'','box-md-7',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_form('Trabajo esposo(a):','inputTrabajoEsposa','',$trabajo_esp,'','box-md-3',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

		echo "<div class='box-hijos'>";

		$num_h=count($nombre_hijo);
		for($i=0;$i<$num_h;$i++){
		echo "<div class='box-hijo clear-both' id='klon".($i+1)."'>";
		$this->fmt->form->input_form('Nombre Completo hijo(a):','inputNombreHijo[]','',$nombre_hijo[$i],'','box-md-4 clear-left','');
		$this->fmt->form->input_form('Institución Educativa hijo(a):','inputInstitucionHijo[]','',$institucion_hijo[$i],'','box-md-3','');
		$this->fmt->form->input_date('Fecha cumpleaños hijo(a):','inputFechaCumpleHijo[]','',$fecha_nac_hijo[$i],'','box-md-3','');
		echo "</div>";
		}

		echo "</div>";
		echo "<a class='btn btn-success clear-both box-md-2 btn-ahijo'><i class='icn-plus'></i> Añadir hijo(a)</a>";

		echo "<div class='clearfix'></div>";

		echo "<h3 class='clear-both'>Datos Referencias</h3>";
		echo "<div class='box-referencias-emergencia'>";
		$num_r_m=count($nombre_ref_em);
		for($i=0;$i<$num_r_m;$i++){
		echo "<div class='box-referencia-emergencia clear-both' id='krefemerg_".($i+1)."'>";
		$this->fmt->form->input_form('Nombre Completo Ref. Emergencia:','inputNombreEmergencia[]','',$nombre_ref_em[$i],'','box-md-4 clear-both','');
		 //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
 		$this->fmt->form->input_form('Parentesco Emergencia','inputParentescoEmergencia[]','',$parentesco_ref_em[$i],'','box-md-3','Ej: Padre');
 		$this->fmt->form->input_form('Telf. Emergencia','inputTelefonoEmergencia[]','',$telefono_ref_em[$i],'','box-md-3','Ej: (591) 3 340-32323 o (591) 768-78789');
 		echo "</div>"; 		}

		echo "</div>";
		echo "<div class='clearfix'></div>";
		echo "<a class='btn btn-success clear-both box-md-2 btn-ref-emg'><i class='icn-plus'></i> Añadir Referencia Emergencia</a>";
		echo "<div class='clearfix'></div>";
		echo "<div class='box-referencias'>";
		$num_r=count($nombre_ref);
		for($i=0;$i<$num_r;$i++){
		echo "<div class='box-referencia clear-both' id='kref_".($i+1)."'>";
		$this->fmt->form->input_form('Nombre Completo Referencia:','inputNombreReferencia[]','',$nombre_ref[$i],'','box-md-7 clear-left',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
			$this->fmt->form->input_form('Telf. Referencia','inputTelefonoReferencia[]','',$telefono_ref[$i],'','box-md-3','Ej: (591) 3 340-32323 o (591) 768-78789'); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		echo "</div>";
		}
		echo "</div>";
		echo "<div class='clearfix'></div>";
		echo "<a class='btn btn-success clear-both box-md-2 btn-ref'><i class='icn-plus'></i> Añadir Referencia</a>";
		//echo $src_imagen;
		if($id!=0)
			$tarea_k="form_editar";
		else
			$tarea_k="form_nuevo";

		echo "<div class='clearfix'></div>";
		?>
		<div class="box-imagen box-md-7">
			<label>Imagen:</label>
			<div class="panel panel-default" >
				<div class="panel-body">
					<?php
					$this->fmt->form->file_form_nuevo_croppie_thumb('Cargar Archivo (max 8MB)','',$tarea_k,'form-file','','box-file-form','archivos/usuarios','350x350',$imagen); //$nom,$ruta,$id_form,$class,$class_div,$id_div,$directorio_p,$sizethumb,$imagen
					?>
				</div>
			</div>
		</div>
		<div class="modal auxiliar">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button onclick="CerrarModal();" type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
	        <h4 id="titulo_aux_modal" class="modal-title"></h4>
	      </div>
	      <div class="modal-body">
	        <div id="modal_aux"></div>
	      </div>
	      <div class="modal-footer">

	      </div>
	    </div>
	</div>
	</div>
		<style>
		.modal-backdrop {
		    position: inherit !important;
		}
		</style>
		<?php
		//$this->fmt->form->input_file("Cargar Imagen:","inputImagen","","","","box-md-4","Archivo no mayo a 1M jpg,png","",""); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar



		$this->fmt->class_modulo->script_form("modulos/rrhh/kardex.adm.php",$this->id_mod);
		?>
		<script>
			$(document).ready(function () {
				$(".btn-ahijo").click(function(event) {
					//var $button = $("#klon1").clone();
					var $div = $('div[id^="klon"]:last');
					var num = parseInt( $div.prop("id").match(/\d+/g), 10 ) +1;
					var $klon = $div.clone().prop('id', 'klon'+num );
					$('.box-hijos').append($klon).append('<a class="btn-eliminar-box-hijo pull-left color-text-rojo" v="klon'+num+'"><i class="icn-close"></i></a>');


					$('.dp').datetimepicker({
						format: 'DD/MM/YYYY',
						locale: 'es'
					});

					$(".btn-eliminar-box-hijo").click(function(event) {
						//alert("hola");
						var id = $(this).attr('v');
						$("#"+id).remove();
						$(this).remove();
					});
				});

				$(".btn-ref").click(function(event) {
					//var $button = $("#klon1").clone();
					var $divz = $('div[id^="kref_"]:last');
					var numz = parseInt( $divz.prop("id").match(/\d+/g), 10 ) +1;
					var $klonz = $divz.clone().prop('id', 'kref_'+numz );
					$('.box-referencias').append($klonz).append('<a class="btn-eliminar-box-ref pull-left color-text-rojo" v="kref_'+numz+'"><i class="icn-close"></i></a>');

					$(".btn-eliminar-box-ref").click(function(event) {
						//alert("hola");
						var id = $(this).attr('v');
						$("#"+id).remove();
						$(this).remove();
					});
				});

				$(".btn-ref-emg").click(function(event) {
					//var $button = $("#klon1").clone();
					var $divz = $('div[id^="krefemerg_"]:last');
					var numz = parseInt( $divz.prop("id").match(/\d+/g), 10 ) +1;
					var $klonz = $divz.clone().prop('id', 'krefemerg_'+numz );
					$('.box-referencias-emergencia').append($klonz).append('<a class="btn-eliminar-box-ref-emg pull-left color-text-rojo" v="krefemerg_'+numz+'"><i class="icn-close"></i></a>');

					$(".btn-eliminar-box-ref-emg").click(function(event) {
						//alert("hola");
						var id = $(this).attr('v');
						$("#"+id).remove();
						$(this).remove();
					});
				});

			});
		</script>
		<?php
	}

	function paso_2_nuevo($id=0,$empresa_actual,$division,$cargo,$departamento,$fecha_ingreso,$fecha_retiro,$antiguedad,$codigo_sap,$manual_funciones,$formato_contrato,$cargo_act,$cargo_ant,$abono_sueldo){
		if($fecha_ingreso!="")
			$fecha_ingreso=$this->fmt->class_modulo->Estructurar_Fecha_input($fecha_ingreso);
		if($fecha_retiro!="")
			$fecha_retiro=$this->fmt->class_modulo->Estructurar_Fecha_input($fecha_retiro);
		$consulta = "select * from mod_kardex_bancos where mod_kdx_bnc_id_kardex=$id";
		$rs =$this->fmt->query->consulta($consulta);
		$num_b=$this->fmt->query->num_registros($rs);
        if($num_b>0){
         for($i=0;$i<$num_b;$i++){
           	list($fila_id_bc,$fila_nombre_bc,$fila_cuenta_bc,$fila_moneda_bc)=$this->fmt->query->obt_fila($rs);
           	$nombre_bc[$i]=$fila_nombre_bc;
	        $cuenta_bc[$i]=$fila_cuenta_bc;
	        $moneda_bc[$i]=$fila_moneda_bc;
         }
        }
        else{
	        $nombre_bc[0]=0;
	        $cuenta_bc[0]=0;
	        $moneda_bc[0]=0;
        }

		echo "<h3>Datos Laborales actuales</h3>";
		$this->fmt->form->select_form("Empresa actual:","inputEmpresaActual","mod_kdx_emp_","mod_kardex_empresa",$empresa_actual,"","box-md-3"); //$label,$id,$prefijo,$from,$id_select,$id_disabled
		$this->fmt->form->select_form("División/área:","inputDivision","mod_kdx_div_","mod_kardex_division",$division,"","box-md-4"); //$label,$id,$prefijo,$from,$id_select,$id_disabled
		$this->fmt->form->select_form("Cargo:","inputCargoActual","mod_kdx_car_","mod_kardex_cargo",$cargo,"","box-md-4"); //$label,$id,$prefijo,$from,$id_select,$id_disabled

		$options=$this->departamentos();
		$valores=$this->departamentos();
		$this->fmt->form->select_form_simple('Departamentos:','inputDepartamento',$options,$valores,'',$departamento,'box-md-4');
		$this->fmt->form->input_date('Fecha de ingreso:','inputIngresoEmpresaActual','',$fecha_ingreso,'','box-md-3',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_date('Fecha de retiro:','inputRetiroActual','',$fecha_retiro,'','box-md-3','','retiro-actual'); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_form('Antiguedad (años):','inputAntiguedad','',$antiguedad,'','box-md-2','');
		$this->fmt->form->select_form('Manual de Funciones:','inputManualFunc','mod_kdx_mf_','mod_kardex_manual_funciones',$manual_funciones,'','box-md-3');
		$this->fmt->form->input_form('CODIGO SAP:','inputCodigoSAP','',$codigo_sap,'','box-md-3','');
		$this->fmt->form->input_form('Cuenta abono de sueldo (Bmsc):','inputCuentaSueldo','',$abono_sueldo,'','box-md-4','');
		echo "<div class='box-bancos'>";
		$num_b=count($nombre_bc);
		for($i=0;$i<$num_b;$i++){
		echo "<div class='box-banco clear-both' id='kcuenta_".($i+1)."'>";
		$this->fmt->form->input_form('Cuenta Banco:','inputCuentaBanco[]','',$cuenta_bc[$i],'','box-md-4 clear-left',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$options=$this->bancos();
		$valores= array("0","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15");
		$this->fmt->form->select_form_simple('Banco:','inputBanco[]',$options,$valores,'',$nombre_bc[$i],'box-md-4');
		$options=array("Bolivianos","Dolares");
		$valores= array("0","1");
		$this->fmt->form->select_form_simple('Modeda:','inputMoneda[]',$options,$valores,'',$moneda_bc[$i],'box-md-2');
		echo "</div>";
		}
		echo "</div>";
		echo "<a class='btn btn-success clear-both box-md-2 btn-acuenta'><i class='icn-plus'></i> Añadir otra cuenta</a>";
		echo "<div class='clearfix'></div>";

		$options=$this->contratos();
		$valores= array("0","1","2","3","4","5");
		$this->fmt->form->select_form_simple('Formato de Contrato:','inputFormatoContrato',$options,$valores,'',$formato_contrato,'box-md-3');
		$this->fmt->form->select_form("Cargo Actual:","inputCargoActualContrato","mod_kdx_car_","mod_kardex_cargo",$cargo_act,"","box-md-4");
		$this->fmt->form->select_form("Cargo Anterior:","inputCargoAnteriorContrato","mod_kdx_car_","mod_kardex_cargo",$cargo_ant,"","box-md-4");

		?>
		<script>
			$(document).ready(function () {
				$(".btn-acuenta").click(function(event) {
					//var $button = $("#klon1").clone();
					var $div = $('div[id^="kcuenta"]:last');
					var num = parseInt( $div.prop("id").match(/\d+/g), 10 ) +1;
					var $klon = $div.clone().prop('id', 'kcuenta'+num );
					$('.box-bancos').append($klon).append('<a class="btn-eliminar-box-cuenta pull-left color-text-rojo" v="kcuenta'+num+'"><i class="icn-close"></i></a>');


					$('.dp').datetimepicker({
						format: 'DD/MM/YYYY',
						locale: 'es'
					});

					$(".btn-eliminar-box-cuenta").click(function(event) {
						//alert("hola");
						var id = $(this).attr('v');
						$("#"+id).remove();
						$(this).remove();
					});
				});
				$(".dp").on("dp.change", function (e) {
					var fecha_in=$("#inputIngresoEmpresaActual").val();
					var fecha_hoy="<?php echo date("Y-m-d");?>";
					var dia = 86400000;
					var anho = dia * (365);
					fecha_in = CambiarFormatFecha(fecha_in);
					var diferencia =  Math.floor(( Date.parse(fecha_hoy) - Date.parse(fecha_in) ) / anho);
					// if(diferencia < 0){
					// diferencia = diferencia*(-1);
					// }
					$("#inputAntiguedad").val(diferencia);
				});
		});
		function CambiarFormatFecha(fecha){
			var dato = fecha.split("/");
			return dato[2]+"-"+dato[1]+"-"+dato[0];
		}
		</script>
		<?php

	}

	function paso_3_nuevo($id=0,$curriculum,$nivel_edu,$institucion_edu,$titulo,$fecha_titulo){
		$consulta = "select * from mod_kardex_formacion where mod_kdx_frm_id_kardex=$id";
		$rs =$this->fmt->query->consulta($consulta);
		$num_f=$this->fmt->query->num_registros($rs);
        if($num_f>0){
         for($i=0;$i<$num_f;$i++){
           	list($fila_id_fm,$fila_nombre_fm,$fila_inst_fm,$fila_fecha_fm)=$this->fmt->query->obt_fila($rs);
           	$nombre_fm[$i]=$fila_nombre_fm;
	        $institucion_fm[$i]=$fila_inst_fm;
	        $fecha_fm[$i]=$fila_fecha_fm;
         }
        }
        else{
	        $nombre_fm[0]="";
	        $institucion_fm[0]="";
	        $fecha_fm[0]="";
        }

        if($id!=0)
			$tarea_k="form_editar";
		else
			$tarea_k="form_nuevo";

		echo "<h3>Datos Formación</h3>";
		//$this->fmt->form->input_file("Cargar CV:","inputCV","Cargar CV","","","box-md-4","Archivo no mayo a 8M pdf,doc","",""); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
		$this->fmt->form->file_form_doc("Cargar CV (pdf, doc/x):","",$tarea_k,"input-form-doc","","","kardex","");
		if($curriculum!="")
		echo "<a href='"._RUTA_WEB.$curriculum."' class='btn btn-success clear-both box-md-2 btn-descarga'><i class='fa fa-download'></i> Descargar </a>";
		echo "<div class='clearfix'></div>";
		$options=$this->nivel_formacion();
		$valores= array("0","1","2","3","4","5","6","7","8");
		$this->fmt->form->select_form_simple('Nivel Educación:','inputNivelEducacion',$options,$valores,'',$nivel_edu,'box-md-4');
		echo "<div class='clearfix'></div>";
		echo "<h3>Formación profesional</h3>";
		echo "<div class='box-estudios'>";
		echo "<div class='box-titulo clear-both' id='kt_1'>";
		$this->fmt->form->input_form('Titulo:','inputTitulo','',$titulo,'','box-md-4 ',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_form('Institución:','inputInstitucion','',$institucion_edu,'','box-md-3  ','');
		$this->fmt->form->input_form('Fecha de obtención::','inputFechaTitulo','',$fecha_titulo,'','box-md-3  ','');
		//$this->fmt->form->input_date('Fecha de obtención:','inputFechaTitulo[]','','','','box-md-3','','');
		echo "</div>";
		echo "</div>";
		//echo "<a class='btn btn-success clear-both box-md-2 btn-atitulo'><i class='icn-plus'></i> Añadir Titulo</a>";
		echo "<div class='clearfix'></div>";

		echo "<h3>Formación Complementaria</h3>";
		echo "<div class='box-estudiosc'>";
		$num_f=count($nombre_fm);
		for($i=0;$i<$num_f;$i++){
		echo "<div class='box-curso clear-both' id='kccfc_".($i+1)."'>";
		$this->fmt->form->input_form('Nombre del curso/capacitación:','inputCurso[]','',$nombre_fm[$i],'','box-md-4 ',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_form('Institución:','inputInstitucionCurso[]','',$institucion_fm[$i],'','box-md-3  ','');
		$this->fmt->form->input_form('Fecha de obtención:','inputFechaCurso[]','',$fecha_fm[$i],'','box-md-3','','');
		echo "</div>";
		}
		echo "</div>";
		echo "<a class='btn btn-success clear-both box-md-3 btn-acurso'><i class='icn-plus'></i> Añadir Curso/Capacitación</a>";
		echo "<div class='clearfix'></div>";
		?>
		<script>
			$(document).ready(function () {
				$(".btn-atitulo").click(function(event) {
					//var $button = $("#klon1").clone();
					var $div = $('div[id^="kt"]:last');
					var num = parseInt( $div.prop("id").match(/\d+/g), 10 ) +1;
					var $klon = $div.clone().prop('id', 'kt'+num );
					$('.box-estudios').append($klon).append('<a class="btn-eliminar-box-titulo pull-left color-text-rojo" v="kt'+num+'"><i class="icn-close"></i></a>');


					$('.dp').datetimepicker({
						format: 'DD/MM/YYYY',
						locale: 'es'
					});

					$(".btn-eliminar-box-titulo").click(function(event) {
						//alert("hola");
						var id = $(this).attr('v');
						$("#"+id).remove();
						$(this).remove();
					});
				});

				$(".btn-acurso").click(function(event) {
					//var $button = $("#klon1").clone();
					var $div = $('div[id^="kccfc"]:last');
					var num = parseInt( $div.prop("id").match(/\d+/g), 10 ) +1;
					var $klon = $div.clone().prop('id', 'kccfc'+num );
					$('.box-estudiosc').append($klon).append('<a class="btn-eliminar-box-curso pull-left color-text-rojo" v="kccfc'+num+'"><i class="icn-close"></i></a>');


					$('.dp').datetimepicker({
						format: 'DD/MM/YYYY',
						locale: 'es'
					});

					$(".btn-eliminar-box-curso").click(function(event) {
						//alert("hola");
						var id = $(this).attr('v');
						$("#"+id).remove();
						$(this).remove();
					});
				});
		});
		</script>
		<?php
	}

	function paso_4_nuevo($id=0,$empresa_ant,$cargo_emp_ant,$fecha_ing_emp_ant,$fecha_sal_emp_ant,$antiguedad_emp_ant){
		if($fecha_ing_emp_ant!="")
			$fecha_ing_emp_ant=$this->fmt->class_modulo->Estructurar_Fecha_input($fecha_ing_emp_ant);
		if($fecha_sal_emp_ant!="")
			$fecha_sal_emp_ant=$this->fmt->class_modulo->Estructurar_Fecha_input($fecha_sal_emp_ant);
		$consulta = "select * from mod_kardex_historial_corporativo where mod_kdx_his_corp_id_kardex=$id";
		$rs =$this->fmt->query->consulta($consulta);
		$num_hs=$this->fmt->query->num_registros($rs);
        if($num_hs>0){
         for($i=0;$i<$num_hs;$i++){
           	list($fila_id_hs,$fila_empresa_hs,$fila_fecha_ing_hs,$fila_fecha_sal_hs,$fila_cargo_hs)=$this->fmt->query->obt_fila($rs);
           	$empresa_hs[$i]=$fila_empresa_hs;
	        $fecha_ing_hs[$i]=$fila_fecha_ing_hs;
	        $fecha_sal_hs[$i]=$fila_fecha_sal_hs;
	        $cargo_hs[$i]=$fila_cargo_hs;
         }
        }
        else{
	        $empresa_hs[0]=0;
	        $fecha_ing_hs[0]="";
	        $fecha_sal_hs[0]="";
	        $cargo_hs[0]="";
        }

		echo "<h3>Datos Laborales Corporativos</h3>";

		$this->fmt->form->input_form('Empresa Anterior:','inputEmpresaAnterior','',$empresa_ant,'','box-md-3','');
		$this->fmt->form->input_form('Cargo:','inputCargoEmpresaAnterior','',$cargo_emp_ant,'','box-md-3','');
		$this->fmt->form->input_date('Fecha de ingreso:','inputFechaEmpresaAnterior','',$fecha_ing_emp_ant,'','box-md-3','');
		$this->fmt->form->input_date('Fecha de retiro:','inputRetiroAnterior','',$fecha_sal_emp_ant,'','box-md-3','','retiro-actual');
		$this->fmt->form->input_form('Antiguedad (años):','inputAntiguedadAnterior','',$antiguedad_emp_ant,'','box-md-2','');
		echo "<div class='clearfix'></div>";
		echo "<h3>Historial Corporativo</h3>";
		echo "<div class='box-otros-cargos'>";

		$num_hs=count($empresa_hs);
		for($i=0;$i<$num_hs;$i++){
			echo "<div class='box-otros clear-both' id='kcorp_".($i+1)."'>";
			$this->fmt->form->select_form("Empresa:","inputHEmpresaAnterior[]","mod_kdx_emp_","mod_kardex_empresa",$empresa_hs[$i],"","box-md-3");
			$this->fmt->form->input_date('Fecha de ingreso:','inputHIngresoEmpresa[]','',$fecha_ing_hs[$i],'','box-md-2',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
			$this->fmt->form->input_date('Fecha de retiro:','inputHRetiroEmpresa[]','',$fecha_sal_hs[$i],'','box-md-2','','retiro-actual');
			$this->fmt->form->select_form("Cargo:","inputHCargoEmpsera[]","mod_kdx_car_","mod_kardex_cargo",$cargo_hs[$i],"","box-md-4");
			echo "</div>";
		}
		echo "</div>";
		echo "<a class='btn btn-success clear-both box-md-2 btn-acargo'><i class='icn-plus'></i> Añadir otra cargo</a>";
		echo "<div class='clearfix'></div>";
		?>
		<script>
			$(document).ready(function () {
				$(".btn-acargo").click(function(event) {
					//var $button = $("#klon1").clone();
					var $div = $('div[id^="kcorp"]:last');
					var num = parseInt( $div.prop("id").match(/\d+/g), 10 ) +1;
					var $klon = $div.clone().prop('id', 'kcorp'+num );
					$('.box-otros-cargos').append($klon).append('<a class="btn-eliminar-box-cargo pull-left color-text-rojo" v="kcorp'+num+'"><i class="icn-close"></i></a>');


					$('.dp').datetimepicker({
						format: 'DD/MM/YYYY',
						locale: 'es'
					});

					$(".btn-eliminar-box-cargo").click(function(event) {
						//alert("hola");
						var id = $(this).attr('v');
						$("#"+id).remove();
						$(this).remove();
					});
				});
			});
		</script>
		<?php
	}

	function paso_5_nuevo($id_usuario_kardex){
		$consulta= "SELECT usu_email,usu_password FROM usuarios WHERE usu_id='".$id_usuario_kardex."'";
		$rs =$this->fmt->query->consulta($consulta);
		$fila_us=$this->fmt->query->obt_fila($rs);
		$pass=base64_decode($fila_us["usu_password"]);
		$rols_id = $this->fmt->categoria->traer_rel_cat_id($id_usuario_kardex,'usuarios_roles','roles_rol_id','usuarios_usu_id');

		$groups_id = $this->fmt->categoria->traer_rel_cat_id($id_usuario_kardex,'usuarios_grupos','grupos_grupo_id','usuarios_usu_id');

		echo "<h3>Consolidado</h3>";
		$this->fmt->form->input_form('E-mail /Usuario:','inputEmailUsuario','',$fila_us["usu_email"],'','box-md-5','');
		$this->fmt->form->input_form('Password:','inputPasword','',$pass,'','box-md-3','');
		$this->fmt->form->input_form('confirmar Password:','inputPaswordConfirmar','',$pass,'','box-md-3','');
		echo '<div id="msg_pass"></div>';
		echo "<div class='clearfix'></div>";
		?>
		<div class="form-group">
			<div class="row">
				<div class="col-xs-6" >
					<label>Rol:  </label>
					<?php echo $this->fmt->usuario->opciones_roles($rols_id);  ?>
				</div>
				<div class="col-xs-6" >

					<?php  $this->fmt->class_modulo->grupos_select("Grupo Roles","inputRolGrupo","",$groups_id);  ?>
				</div>
			</div>
		</div>
		<?php

	}

	function extensiones(){
		$ext[0] = '';
		$ext[1] = 'SC';
		$ext[2] = 'LP';
		$ext[3] = 'CB';
		$ext[4] = 'CH';
		$ext[5] = 'OR';
		$ext[6] = 'PD';
		$ext[7] = 'PT';
		$ext[8] = 'TR';
		$ext[9] = 'BN';

		return $ext;
	}

	function nivel_formacion(){
		$ext[0] = 'Seleccione una opción';
		$ext[1] = 'Básico incial';
		$ext[2] = 'Bachilerato';
		$ext[3] = 'Egresado';
		$ext[4] = 'Tecnico Superior';
		$ext[5] = 'Licencitura';
		$ext[6] = 'Diplomado';
		$ext[7] = 'Master';
		$ext[8] = 'PHD';


		return $ext;
	}

	function contratos(){
		$ext[0] = 'Seleccione una opción';
		$ext[1] = 'Contrato a plazo fijo';
		$ext[2] = 'Contrato indefinido';
		$ext[3] = 'Contrato de pasantias';
		$ext[4] = 'Contrato de reemplazo';
		$ext[5] = 'Contrato de capacitación pagada';

		return $ext;
	}

	function departamentos(){
		$ext[0] = 'Seleccione una opción';
		$ext[1] = 'Departamento Comercial Agro';
		$ext[2] = 'Departamento Comercial  Maquinaria';
		$ext[3] = 'Departamento Comercial Línea Eco';
		$ext[4] = 'Departamento Comercial Construcción';
		$ext[5] = 'Departamento Investigación & Desarrollo';
		$ext[6] = 'Departamento Marketing';
		$ext[7] = 'Departamento Registros';
		$ext[8] = 'Departamento Logística e Importaciones';
		$ext[9] = 'Departamento Activos Fijos y Servicios';
		$ext[10] = 'Departamento Tecnología de la Información';
		$ext[11] = 'Departamento Finanzas';
		$ext[12] = 'Departamento Contable';
		$ext[13] = 'Departamento Legal';
		$ext[14] = 'Departamento Crédito y Cobranzas';
		$ext[15] = 'Departamento Servicio Técnico';
		$ext[16] = 'Departamento Administración y Gestión de Calidad';
		$ext[17] = 'Departamento Desarrollo Organizacional';

		return $ext;
	}

	function bancos(){
		$ext[0] = 'Seleccione una opción';
		$ext[1] = 'Banco Mercantil Santa Cruz';
		$ext[2] = 'Banco Nacional de Bolivia';
		$ext[3] = 'Banco Central de Bolivia';
		$ext[4] = 'Banco de Crédito de Bolivia';
		$ext[5] = 'Banco Do Brasil';
		$ext[6] = 'Banco Bisa S.A.';
		$ext[7] = 'Banco Unión S.A.';
		$ext[8] = 'Banco Económico';
		$ext[9] = 'Banco Solidario S.A.';
		$ext[10] = 'Banco Ganadero';
		$ext[11] = 'Banco Los Andes Pro Credit S.A.';
		$ext[12] = 'Banco Fie';
		$ext[13] = 'BANCO FASSIL S.A.';
		$ext[14] = 'Banco Fortaleza';
		$ext[15] = 'Banco Pyme Ecofuturo S.A.';
		return $ext;
	}

}
