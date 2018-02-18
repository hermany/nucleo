<?php
header("Content-Type: text/html;charset=utf-8");

class KARDEX_CONF{

	var $fmt;
	var $id_mod;
	var $ruta_modulo;
	var $nombre_modulo;

	function KARDEX_CONF($fmt){
		$this->fmt = $fmt;
		$this->ruta_modulo="modulos/rrhh/kardex-config.adm.php";
		$this->nombre_modulo="kardex-config.adm.php";
	}

	function busqueda(){
    $botones = $this->fmt->class_pagina->crear_btn("kardex.adm.php?id_mod=16","btn btn-link","icn-kardex","Kardex");
    $this->fmt->class_pagina->crear_head_mod("icn-conf color-text-rojo","Configuraciones Kardex",$botones ); // $icon, $nom,$botones
		if (isset($_GET["p"])){
			$clase_activa = $_GET["p"];
		}else{
			$clase_activa = "empresas";
		}
    ?>
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="<?php if ($clase_activa=="empresas"){ echo "active"; }?>"><a href="#empresas" aria-controls="empresas" role="tab" data-toggle="tab"><i class="icn-category-o color-text-azul-b"></i> Empresas</a></li>
					<li role="presentation" class="<?php if ($clase_activa=="cargos"){ echo "active"; }?>"><a href="#cargos" aria-controls="cargos" role="tab" data-toggle="tab"><i class="icn-credential-o color-text-naranja"></i> Cargos</a></li>
					<li role="presentation" class="<?php if ($clase_activa=="divisiones"){ echo "active"; }?>"><a href="#divisiones" aria-controls="divisiones" role="tab" data-toggle="tab"><i class="icn-division color-text-verde"></i> Divisiones</a></li>
					<li role="presentation" class="<?php if ($clase_activa=="departamentos"){ echo "active"; }?>"><a href="#departamentos" aria-controls="departamentos" role="tab" data-toggle="tab"><i class="icn-category-v color-text-rojo"></i> Departamentos</a></li>

					<li role="presentation" class="<?php if ($clase_activa=="cats"){ echo "active"; }?>"><a href="#cats" aria-controls="cats" role="tab" data-toggle="tab"><i class="icn-category-v color-text-azul"></i> CATs</a></li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane <?php if ($clase_activa=="empresas"){ echo "active"; }?>" id="empresas">
						<label><h4>Nuestras Empresas</h4></label>
	          <a href="kardex-config.adm.php?tarea=form_nuevo_empresa" class='btn btn-primary pull-right'><i class="icn-plus"></i> Nueva Empresa</a>
						<div class="box-tabla">
	              <?php
	              $sql="SELECT mod_kdx_emp_id, mod_kdx_emp_nombre, mod_kdx_emp_activar  FROM mod_kardex_empresa ORDER BY mod_kdx_emp_id asc";
	              $rs =$this->fmt->query->consulta($sql);
	              $num=$this->fmt->query->num_registros($rs);
	              if($num>0){
	                for($i=0;$i<$num;$i++){
	                  list($fila_id,$fila_nombre_e,$fila_activar)=$this->fmt->query->obt_fila($rs);
	                  echo "<div class='box-tr'>";
	          				echo '<div class="box-td box-md-6">'.$fila_nombre_e.'</div>';
	          				echo '<div class="box-td box-md-3">';
	                  $this->fmt->class_modulo->estado_activar($fila_activar,"modulos/rrhh/kardex-config.adm.php?tarea=activar_empresa","","",$fila_id );
	                  echo '</div>';
	          				echo '<div class="box-td box-md-3">';
										$url_editar= "kardex-config.adm.php?tarea=form_editar_empresa&id=".$fila_id;
										$this->fmt->form->botones_acciones("btn-editar-modulo","btn btn-accion btn-editar",$url_editar,"Editar","icn-pencil","editar_empresa",'editar',$fila_id); //$id,$class,$href,$title,$icono,$tarea,$nom,$ide
										$this->fmt->form->botones_acciones("btn-eliminar","btn btn-eliminar btn-accion","","Eliminar -".$fila_id,"icn-trash","eliminar_empresa",$fila_nombre,$fila_id);
										//$id,$class,$href,$title,$icono,$tarea,$nom,$ide
										echo '</div>';
	                  echo "</div>";
	                }
	              }
	              ?>
	          </div>
					</div>
					<div role="tabpanel" class="tab-pane <?php if ($clase_activa=="cargos"){ echo "active"; }?>" id="cargos">
						<label><h4>Cargos</h4></label>
						<a href="kardex-config.adm.php?tarea=form_nuevo_cargo" class='btn btn-primary pull-right'><i class="icn-plus"></i> Nuevo Cargo</a>
						<div class="box-tabla">
							<?php
							$this->fmt->form->head_table('table_id');
							$this->fmt->form->thead_table('ID:Nombre del Cargo:Estado:Acciones','td-id::td-acciones');
							$this->fmt->form->tbody_table_open();
							$sql="SELECT mod_kdx_car_id,mod_kdx_car_nombre,mod_kdx_car_activar FROM mod_kardex_cargo ORDER BY mod_kdx_car_id asc";
							$rs =$this->fmt->query->consulta($sql);
							$num=$this->fmt->query->num_registros($rs);
							if($num>0){
								for($i=0;$i<$num;$i++){
									list($fila_id,$fila_nombre,$fila_activar)=$this->fmt->query->obt_fila($rs);
									echo "<tr>";
									echo '<td class="">'.$fila_id.'</td>';
									echo '<td class=""><strong>'.$fila_nombre.'</strong></td>';
									echo '<td class="">';
										$this->fmt->class_modulo->estado_activar($fila_activar,"modulos/rrhh/kardex-config.adm.php?tarea=activar_cargo","","",$fila_id );
									echo '</td>';

									echo '<td class="">';
									$url_editar= "kardex-config.adm.php?tarea=form_editar_cargo&id=".$fila_id;
									$this->fmt->form->botones_acciones("btn-editar-modulo","btn btn-accion btn-editar",$url_editar,"Editar","icn-pencil","editar_cargo",'editar',$fila_id); //$id,$class,$href,$title,$icono,$tarea,$nom,$ide
									$this->fmt->form->botones_acciones("btn-eliminar","btn btn-eliminar btn-accion","","Eliminar -".$fila_id,"icn-trash","eliminar_cargo",$fila_nombre,$fila_id);
									//$id,$class,$href,$title,$icono,$tarea,$nom,$ide
									echo '</td>';
									echo "</tr>";
								}
							}
							$this->fmt->form->tbody_table_close();
							$this->fmt->form->footer_table();
							?>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane <?php if ($clase_activa=="divisiones"){ echo "active"; }?>" id="divisiones">
						<label><h4>Divisiones/Áreas</h4></label>
						<a href="kardex-config.adm.php?tarea=form_nueva_division" class='btn btn-primary pull-right'><i class="icn-plus"></i> Nuevo División/área</a>
						<div class="box-tabla">
							<?php
							$this->fmt->form->head_table('table_div');
							$this->fmt->form->thead_table('ID:Nombre de la división/área:Estado:Acciones','td-id::td-acciones');
							$this->fmt->form->tbody_table_open();
							$sql="SELECT mod_kdx_div_id,mod_kdx_div_nombre,mod_kdx_div_activar FROM mod_kardex_division ORDER BY mod_kdx_div_id asc";
							$rs =$this->fmt->query->consulta($sql);
							$num=$this->fmt->query->num_registros($rs);
							if($num>0){
								for($i=0;$i<$num;$i++){
									list($fila_id,$fila_nombre,$fila_activar)=$this->fmt->query->obt_fila($rs);
									echo "<tr>";
									echo '<td class="">'.$fila_id.'</td>';
									echo '<td class=""><strong>'.$fila_nombre.'</strong></td>';
									echo '<td class="">';
										$this->fmt->class_modulo->estado_activar($fila_activar,"modulos/rrhh/kardex-config.adm.php?tarea=activar_division","","",$fila_id );
									echo '</td>';

									echo '<td class="">';
									$url_editar= "kardex-config.adm.php?tarea=form_editar_division&id=".$fila_id;
									$this->fmt->form->botones_acciones("btn-editar-modulo","btn btn-accion btn-editar",$url_editar,"Editar","icn-pencil","editar_division",'editar',$fila_id); //$id,$class,$href,$title,$icono,$tarea,$nom,$ide
									$this->fmt->form->botones_acciones("btn-eliminar","btn btn-eliminar btn-accion","","Eliminar -".$fila_id,"icn-trash","eliminar_division",$fila_nombre,$fila_id);
									//$id,$class,$href,$title,$icono,$tarea,$nom,$ide
									echo '</td>';
									echo "</tr>";
								}
							}
							$this->fmt->form->tbody_table_close();
							$this->fmt->form->footer_table();
							?>
						</div>
						<script>
						$('#table_div').DataTable({
							"language": {
				            "url": "<?php echo _RUTA_WEB; ?>js/spanish_datatable.json"
				            },
				            "pageLength": 25,
				            "order": [[ 0, 'asc' ]]
						});
						</script>
					</div>
					<div role="tabpanel" class="tab-pane <?php if ($clase_activa=="departamentos"){ echo "active"; }?>" id="departamentos">
						<label><h4>Departamentos</h4></label>
						<?php
							$this->fmt->categoria->arbol_editable_mod('mod_kardex_departamento','mod_kdx_dep_','mod_kdx_dep_id_padre=0','modulos/rrhh/kardex-config.adm.php?tarea=form_nuevo_departamento','box-departamentos');
						?>
						<script>
							$(".btn-activar-i").click(function(e){
				        var cat = $( this ).attr("cat");
				        var estado = $( this ).attr("estado");
				        url="kardex-config.adm.php?tarea=activar_departamento&id="+cat+"&estado="+estado;
				        alert(url);
				        window.location=(url);
				      });
				      $(".btn-editar-i").click(function(e){
				        var cat = $( this ).attr("cat");
				        url="kardex-config.adm.php?tarea=form_editar_departamento&id="+cat;
				        //alert(url);
				        window.location=(url);
				      });
							$(".btn-nuevo-i").click(function(e){
								var cat = $( this ).attr("cat");
								url="kardex-config.adm.php?tarea=form_nuevo_departamento&id_padre="+cat;
								//alert(url);
								window.location=(url);
							});
						</script>
					</div>

					<div role="tabpanel" class="tab-pane <?php if ($clase_activa=="cats"){ echo "active"; }?>" id="cats">
						<label><h4>CATs</h4></label>
						<a href="kardex-config.adm.php?tarea=form_nuevo_cat" class='btn btn-primary pull-right'><i class="icn-plus"></i> Nuevo Cat</a>
						<div class="box-tabla">
							<?php
							$this->fmt->form->head_table('table_id_modal');
							$this->fmt->form->thead_table('ID:Nombre:Nombre del Cargo:Zona:Dirección:Celular:Estado:Acciones','td-id::td-acciones');
							$this->fmt->form->tbody_table_open();
							$sql="SELECT cts_id,cts_nombre,cts_encargado,cts_zona,cts_direccion,cts_celular,cts_activar FROM cats ORDER BY cts_id asc";
							$rs =$this->fmt->query->consulta($sql);
							$num=$this->fmt->query->num_registros($rs);
							if($num>0){
								for($i=0;$i<$num;$i++){
									list($fila_id,$fila_nombre,$fila_encargado,$fila_zona,$fila_direccion,$fila_celular,$fila_activar)=$this->fmt->query->obt_fila($rs);
									echo "<tr>";
									echo '<td class="">'.$fila_id.'</td>';
									echo '<td class=""><strong>'.$fila_nombre.'</strong></td>';
									echo '<td class="">'.$fila_encargado.'</td>';
									echo '<td class="">'.$fila_zona.'</td>';
									echo '<td class="">'.$fila_direccion.'</td>';
									echo '<td class="">'.$fila_celular.'</td>';
									echo '<td class="">';
										$this->fmt->class_modulo->estado_activar($fila_activar,"modulos/rrhh/kardex-config.adm.php?tarea=activar_cat","","",$fila_id );
									echo '</td>';

									echo '<td class="">';
									$url_editar= "kardex-config.adm.php?tarea=form_editar_cat&id=".$fila_id;
									$this->fmt->form->botones_acciones("btn-editar-modulo","btn btn-accion btn-editar",$url_editar,"Editar","icn-pencil","editar_cat",'editar',$fila_id); //$id,$class,$href,$title,$icono,$tarea,$nom,$ide
									$this->fmt->form->botones_acciones("btn-eliminar","btn btn-eliminar btn-accion","","Eliminar -".$fila_id,"icn-trash","eliminar_cat",$fila_nombre,$fila_id);
									//$id,$class,$href,$title,$icono,$tarea,$nom,$ide
									echo '</td>';
									echo "</tr>";
								}
							}
							$this->fmt->form->tbody_table_close();
							$this->fmt->form->footer_table();
							?>
						</div>
					</div>

				</div>
    <?php
		$this->fmt->class_modulo->script_form($this->ruta_modulo,"");
    echo $this->fmt->form->footer_page();
  }

  function form_nuevo_empresa(){
    $botones .= $this->fmt->class_pagina->crear_btn("kardex-config.adm.php?tarea=busqueda&p=empresas","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $this->fmt->class_pagina->crear_head_mod( "", "Nueva Empresa",'',$botones,'col-xs-6 col-xs-offset-4');
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" action="kardex-config.adm.php?tarea=ingresar_empresa"  enctype="multipart/form-data" method="POST" id="form_nuevo">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre de la empresa:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','','3','','');
        $this->fmt->form->input_form('Logo:','inputLogo','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Razón social:','inputRazonSocial','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('NIT:','inputNit','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Dirección:','inputDireccion','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Coordenadas:','inputCoordenadas','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Rubro:','inputRubro','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('E-mail:','inputEmail','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Web:','inputWeb','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->botones_nuevo();
        ?>
      </form>
    </div>
    <?php
    $this->fmt->class_modulo->script_form($this->ruta_modulo,"");
    $this->fmt->form->footer_page();
  }

  function form_nuevo_cargo(){
    $botones .= $this->fmt->class_pagina->crear_btn("kardex-config.adm.php?tarea=busqueda&p=cargos","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $this->fmt->class_pagina->crear_head_mod( "", "Nuevo Cargo",'',$botones,'col-xs-6 col-xs-offset-4');
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" action="kardex-config.adm.php?tarea=ingresar_cargo"  enctype="multipart/form-data" method="POST" id="form_nuevo">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre del cargo:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','','3','','');
        $this->fmt->form->textarea_form('Atribuciones:','inputAtribuciones','','','','','3','','');
        $this->fmt->form->textarea_form('Responsabilidades:','inputResponsabilidades','','','','','3','','');

				$this->fmt->form->select_form('Dependencia:','inputDependiente','mod_kdx_car_','mod_kardex_cargo'); //$label,$id,$prefijo,$from,$id_select,$id_disabled

				$this->fmt->form->select_form('Ascedencia:','inputAscendencia','mod_kdx_car_','mod_kardex_cargo'); //$label,$id,$prefijo,$from,$id_select,$id_disabled
				$this->fmt->form->textarea_form('Destrezas:','inputDestrezas','','','','','3','','');
        $this->fmt->form->input_form('Lugar de trabajo:','inputLugarTrabajo','Local, Nacional, internacional','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->textarea_form('Requisitos:','inputRequisitos','','','','','7','','');

        $this->fmt->form->botones_nuevo();
        ?>
      </form>
    </div>
    <?php
    $this->fmt->class_modulo->script_form($this->ruta_modulo,"");
    $this->fmt->form->footer_page();
  }

	function form_nueva_division(){
    $botones .= $this->fmt->class_pagina->crear_btn("kardex-config.adm.php?tarea=busqueda&p=divisiones","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $this->fmt->class_pagina->crear_head_mod( "", "Nueva División/Área",'',$botones,'col-xs-6 col-xs-offset-4');
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" action="kardex-config.adm.php?tarea=ingresar_division"  enctype="multipart/form-data" method="POST" id="form_nuevo">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre de la disivión/área:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','','3','','');
        $this->fmt->form->botones_nuevo();
        ?>
      </form>
    </div>
    <?php
    $this->fmt->class_modulo->script_form($this->ruta_modulo,"");
    $this->fmt->form->footer_page();
  }

	function form_nuevo_departamento(){
		if (empty($_GET["id_padre"])){ $padre=""; }else{ $padre=$_GET["id_padre"]; }
    $botones .= $this->fmt->class_pagina->crear_btn("kardex-config.adm.php?tarea=busqueda&p=departamentos","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $this->fmt->class_pagina->crear_head_mod( "", "Nuevo departamento",'',$botones,'col-xs-6 col-xs-offset-4');
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" action="kardex-config.adm.php?tarea=ingresar_departamento"  enctype="multipart/form-data" method="POST" id="form_nuevo">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre del departamento:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','','3','','');
				$this->fmt->form->select_form('Departamento padre:','inputPadre','mod_kdx_dep_','mod_kardex_departamento',$padre,'',''); //$label,$id,$prefijo,$from,$id_select,$id_disabled,$class_div
				$this->fmt->form->input_form('Orden:','inputOrden','','0','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->botones_nuevo();
        ?>
      </form>
    </div>
    <?php
    $this->fmt->class_modulo->script_form($this->ruta_modulo,"");
    $this->fmt->form->footer_page();
  }

  function form_nuevo_cat(){
    $botones .= $this->fmt->class_pagina->crear_btn("kardex-config.adm.php?tarea=busqueda&p=cats","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $this->fmt->class_pagina->crear_head_mod( "", "Nuevo Cat",'',$botones,'col-xs-6 col-xs-offset-4');
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" action="kardex-config.adm.php?tarea=ingresar_cat"  enctype="multipart/form-data" method="POST" id="form_nuevo">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_mail('Correo:','inputCorreo','','','requerido requerido-texto input-lg','','');
        $this->fmt->form->input_form('Nombre Encargado:','inputEncargado','','','requerido requerido-texto input-lg','','');
        $this->fmt->form->input_form('Zona:','inputZona','','','input-lg','','');
        $this->fmt->form->textarea_form('Dirección:','inputDireccion','','','','','3','','');
        $this->fmt->form->input_form('Telefono Fijo:','inputtelefono','','','input-lg','','');
				$this->fmt->form->input_form('Celular:','inputCelular','','','input-lg','','');
				$this->fmt->form->select_form("Roles","InputRol","rol_","roles","","","");
				$usuario = $this->fmt->sesion->get_variable('usu_id');
				$usuario_n =  $this->fmt->usuario->nombre_usuario( $usuario );
				$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
        $this->fmt->form->botones_nuevo();
        ?>
      </form>
    </div>
    <?php
    $this->fmt->class_modulo->script_form($this->ruta_modulo,"");
    $this->fmt->form->footer_page();
  }

  function form_editar_empresa(){
    $botones .= $this->fmt->class_pagina->crear_btn("kardex-config.adm.php?tarea=busqueda&p=empresas","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $this->fmt->class_pagina->crear_head_mod( "", "Editar Empresa",'',$botones,'col-xs-6 col-xs-offset-4');
		$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $_GET['id'];
		$sql="SELECT mod_kdx_emp_id,mod_kdx_emp_nombre,mod_kdx_emp_descripcion,mod_kdx_emp_logo,mod_kdx_emp_razon_social,mod_kdx_emp_nit,mod_kdx_emp_direccion,mod_kdx_emp_coordenadas,mod_kdx_emp_rubro,mod_kdx_emp_email,mod_kdx_emp_web,mod_kdx_emp_activar from mod_kardex_empresa	where mod_kdx_emp_id='".$id."'";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					list($fila_id,$fila_nombre,$fila_descripcion,$fila_logo,$fila_razon_social,$fila_nit,$fila_direccion,$fila_coordenadas,$fila_rubro,$fila_email,$fila_web,$fila_activar)=$this->fmt->query->obt_fila($rs);
				}
			}
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" action="kardex-config.adm.php?tarea=modificar_empresa"  enctype="multipart/form-data" method="POST" id="form_editar">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre de la empresa:','inputNombre','',$fila_nombre,'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$this->fmt->form->input_hidden_form("inputId",$fila_id);
				$this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$fila_descripcion,'','','3','','');
        $this->fmt->form->input_form('Logo:','inputLogo','',$fila_logo,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Razón social:','inputRazonSocial','',$fila_razon_social,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('NIT:','inputNit','',$fila_nit,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Dirección:','inputDireccion','',$fila_direccion,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Coordenadas:','inputCoordenadas','',$fila_coordenadas,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Rubro:','inputRubro','',$fila_rubro,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('E-mail:','inputEmail','',$fila_email,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Web:','inputWeb','',$fila_web,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$this->fmt->form->radio_activar_form($fila_activar);
				$this->fmt->form->botones_editar($fila_id,$fila_nombre,'empresa','eliminar_empresa');
        ?>
      </div>
    </div>
    <?php
    $this->fmt->class_modulo->script_form("modulos/rrhh/kardex-config.adm.php","");
    $this->fmt->form->footer_page();
  }

  function form_editar_cargo(){
    $botones .= $this->fmt->class_pagina->crear_btn("kardex-config.adm.php?tarea=busqueda&p=cargos","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $this->fmt->class_pagina->crear_head_mod( "", "Editar Cargo",'',$botones,'col-xs-6 col-xs-offset-4');
		$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $_GET['id'];
		$sql="SELECT mod_kdx_car_id,mod_kdx_car_nombre,mod_kdx_car_descripcion,mod_kdx_car_atribuciones,mod_kdx_car_responsabilidades,mod_kdx_car_dependiente,mod_kdx_car_ascendencia,mod_kdx_car_destrezas,mod_kdx_car_lugar_de_trabajo,mod_kdx_car_requisitos,mod_kdx_car_activar from mod_kardex_cargo	where mod_kdx_car_id='".$id."'";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					list($fila_id,$fila_nombre,$fila_descripcion,$fila_atribuciones,$fila_responsabilidades,$fila_dependiente,$fila_ascendencia,$fila_destrezas,$fila_lugar_de_trabajo,$fila_requisitos,$fila_activar)=$this->fmt->query->obt_fila($rs);
				}
			}
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" action="kardex-config.adm.php?tarea=modificar_cargo"  enctype="multipart/form-data" method="POST" id="form_editar">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre del cargo:','inputNombre','',$fila_nombre,'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$this->fmt->form->input_hidden_form("inputId",$fila_id);
				$this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$fila_descripcion,'','','3','','');
				$this->fmt->form->textarea_form('Atribuciones:','inputAtribuciones','',$fila_atribuciones,'','','3','','');
				$this->fmt->form->textarea_form('Responsabilidades:','inputResponsabilidades','',$fila_responsabilidades,'','','3','','');

				$this->fmt->form->select_form('Dependencia:','inputDependiente','mod_kdx_car_','mod_kardex_cargo',$fila_dependiente); //$label,$id,$prefijo,$from,$id_select,$id_disabled

				$this->fmt->form->select_form('Ascedencia:','inputAscendencia','mod_kdx_car_','mod_kardex_cargo',$fila_ascendencia); //$label,$id,$prefijo,$from,$id_select,$id_disabled
				$this->fmt->form->textarea_form('Destrezas:','inputDestrezas','',$fila_destrezas,'','','3','','');
				$this->fmt->form->input_form('Lugar de trabajo:','inputLugarTrabajo','Local, Nacional, internacional',$fila_lugar_de_trabajo,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$this->fmt->form->textarea_form('Requisitos:','inputRequisitos','',$fila_requisitos,'','','7','','');

				$this->fmt->form->radio_activar_form($fila_activar);
				$this->fmt->form->botones_editar($fila_id,$fila_nombre,'cargo','eliminar_cargo');
        ?>
      </div>
    </div>
    <?php
    $this->fmt->class_modulo->script_form("modulos/rrhh/kardex-config.adm.php","");
    $this->fmt->form->footer_page();
  }

  function form_editar_cat(){
	  $botones .= $this->fmt->class_pagina->crear_btn("kardex-config.adm.php?tarea=busqueda&p=cats","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $this->fmt->class_pagina->crear_head_mod( "", "Editar Cat",'',$botones,'col-xs-6 col-xs-offset-4');
		$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $_GET['id'];
		$sql="SELECT cts_id,cts_nombre,cts_email,cts_encargado,cts_zona,cts_direccion,cts_telefono,cts_celular,cts_activar,cts_id_usuario, cts_id_roles from cats where cts_id='".$id."'";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					list($fila_id,$fila_nombre,$fila_correo,$fila_encargado,$fila_zona,$fila_direccion,$fila_telefono,$fila_celular,$fila_activar,$fila_usuario,$fila_rol)=$this->fmt->query->obt_fila($rs);
				}
			}
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" action="kardex-config.adm.php?tarea=modificar_cat"  enctype="multipart/form-data" method="POST" id="form_editar">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre:','inputNombre','',$fila_nombre,'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputId",$fila_id);

        $this->fmt->form->input_mail('Correo:','inputCorreo','',$fila_correo,'requerido requerido-texto input-lg','','');
        $this->fmt->form->input_form('Nombre Encargado:','inputEncargado','',$fila_encargado,'requerido requerido-texto input-lg','','');
        $this->fmt->form->input_form('Zona:','inputZona','',$fila_zona,'input-lg','','');
        $this->fmt->form->textarea_form('Dirección:','inputDireccion','',$fila_direccion,'','','3','','');
        $this->fmt->form->input_form('Telefono Fijo:','inputtelefono','',$fila_telefono,'input-lg','','');
				$this->fmt->form->input_form('Celular:','inputCelular','',$fila_celular,'input-lg','','');
				$this->fmt->form->select_form("Roles","InputRol","rol_","roles",$fila_rol,"","");
				$usuario = $this->fmt->sesion->get_variable('usu_id');
				$usuario_n =  $this->fmt->usuario->nombre_usuario( $usuario );
				$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
				$this->fmt->form->radio_activar_form($fila_activar);
				$this->fmt->form->botones_editar($fila_id,$fila_nombre,'cat','eliminar_cat');
        ?>
      </div>
    </div>
    <?php
    $this->fmt->class_modulo->script_form("modulos/rrhh/kardex-config.adm.php","");
    $this->fmt->form->footer_page();
  }

	function form_editar_division(){
    $botones .= $this->fmt->class_pagina->crear_btn("kardex-config.adm.php?tarea=busqueda&p=divisiones","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $this->fmt->class_pagina->crear_head_mod( "", "Editar División/área",'',$botones,'col-xs-6 col-xs-offset-4');
		$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $_GET['id'];
		$sql="SELECT mod_kdx_div_id,mod_kdx_div_nombre,mod_kdx_div_descripcion,mod_kdx_div_activar from mod_kardex_division	where mod_kdx_div_id='".$id."'";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					list($fila_id,$fila_nombre,$fila_descripcion,$fila_activar)=$this->fmt->query->obt_fila($rs);
				}
			}
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" action="kardex-config.adm.php?tarea=modificar_division"  enctype="multipart/form-data" method="POST" id="form_editar">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre del cargo:','inputNombre','',$fila_nombre,'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
				$this->fmt->form->input_hidden_form("inputId",$fila_id);
				$this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$fila_descripcion,'','','3','','');
				$this->fmt->form->radio_activar_form($fila_activar);
				$this->fmt->form->botones_editar($fila_id,$fila_nombre,'division','eliminar_division');
        ?>
      </div>
    </div>
    <?php
    $this->fmt->class_modulo->script_form($this->ruta_modulo,"");
    $this->fmt->form->footer_page();
  }

  function ingresar_empresa(){
    if ($_POST["btn-accion"]=="activar"){
      $activar=1;
    }
    if ($_POST["btn-accion"]=="guardar"){
      $activar=0;
    }
    $ingresar ="mod_kdx_emp_nombre,
                mod_kdx_emp_descripcion,
                mod_kdx_emp_logo,
                mod_kdx_emp_razon_social,
                mod_kdx_emp_nit,
                mod_kdx_emp_direccion,
                mod_kdx_emp_coordenadas,
                mod_kdx_emp_rubro,
                mod_kdx_emp_email,
                mod_kdx_emp_web,
                mod_kdx_emp_activar";
    $valores_post  ="inputNombre,
									inputDescripcion,
									inputLogo,
									inputRazonSocial,
									inputNit,
									inputDireccion,
									inputCoordenadas,
									inputRubro,
									inputEmail,
									inputWeb,inputActivar=".$activar;

    $this->fmt->class_modulo->ingresar_tabla('mod_kardex_empresa',$ingresar,$valores_post);
		//$from,$filas,$valores_post
    $url="kardex-config.adm.php?tarea=busqueda&p=empresas";
    $this->fmt->class_modulo->script_location($url);
  }

  function ingresar_cargo(){
    if ($_POST["btn-accion"]=="activar"){
      $activar=1;
    }
    if ($_POST["btn-accion"]=="guardar"){
      $activar=0;
    }
    $ingresar ="mod_kdx_car_nombre,
								mod_kdx_car_descripcion,
								mod_kdx_car_atribuciones,
								mod_kdx_car_responsabilidades,
								mod_kdx_car_dependiente,
								mod_kdx_car_ascendencia,
								mod_kdx_car_destrezas,
								mod_kdx_car_lugar_de_trabajo,
								mod_kdx_car_requisitos,
								mod_kdx_car_activar";
    $valores_post  ="inputNombre,
										inputDescripcion,
										inputAtribuciones,
										inputResponsabilidades,
										inputDependiente,
										inputAscendencia,
										inputDestrezas,
										inputLugarTrabajo,
										inputRequisitos,inputActivar=".$activar;

    $this->fmt->class_modulo->ingresar_tabla('mod_kardex_cargo',$ingresar,$valores_post);
		//$from,$filas,$valores_post
    $url="kardex-config.adm.php?tarea=busqueda&p=cargos";
    $this->fmt->class_modulo->script_location($url);
  }

  function ingresar_cat(){
	if ($_POST["btn-accion"]=="activar"){
      $activar=1;
    }
    if ($_POST["btn-accion"]=="guardar"){
      $activar=0;
    }
    $ingresar ="cts_nombre,
				cts_email,
				cts_encargado,
				cts_zona,
				cts_direccion,
				cts_telefono,
				cts_celular,
				cts_id_usuario,
				cts_id_roles,
				cts_activar";
    $valores_post  ="inputNombre,
				inputCorreo,
				inputEncargado,
				inputZona,
				inputDireccion,
				inputtelefono,
				inputCelular,
				inputUsuario,
				InputRol,inputActivar=".$activar;

    $this->fmt->class_modulo->ingresar_tabla('cats',$ingresar,$valores_post);
		//$from,$filas,$valores_post
    $url="kardex-config.adm.php?tarea=busqueda&p=cats";
    $this->fmt->class_modulo->script_location($url);
  }

	function ingresar_division(){
    if ($_POST["btn-accion"]=="activar"){
      $activar=1;
    }
    if ($_POST["btn-accion"]=="guardar"){
      $activar=0;
    }
    $ingresar ="mod_kdx_div_nombre,
								mod_kdx_div_descripcion,
								mod_kdx_div_activar";
    $valores_post  ="inputNombre,
										inputDescripcion,inputActivar=".$activar;

    $this->fmt->class_modulo->ingresar_tabla('mod_kardex_division',$ingresar,$valores_post);
		//$from,$filas,$valores_post
    $url="kardex-config.adm.php?tarea=busqueda&p=divisiones";
    $this->fmt->class_modulo->script_location($url);
  }

	function ingresar_departamento(){

    if ($_POST["btn-accion"]=="activar"){
      $activar=1;
    }
    if ($_POST["btn-accion"]=="guardar"){
      $activar=0;
    }
    $ingresar ="mod_kdx_dep_nombre,
								mod_kdx_dep_descripcion,
								mod_kdx_dep_id_padre,
								mod_kdx_dep_orden,
								mod_kdx_dep_activar";
    $valores_post  ="inputNombre,
										inputDescripcion,inputPadre,inputOrden,inputActivar=".$activar;

    $this->fmt->class_modulo->ingresar_tabla('mod_kardex_departamento',$ingresar,$valores_post);
		//$from,$filas,$valores_post
    $url="kardex-config.adm.php?tarea=busqueda&p=departamentos";
    $this->fmt->class_modulo->script_location($url);
  }

	function modificar_empresa(){
		if ($_POST["btn-accion"]=="eliminar"){}
		if ($_POST["btn-accion"]=="actualizar"){

			$filas='mod_kdx_emp_id,
							mod_kdx_emp_nombre,
              mod_kdx_emp_descripcion,
              mod_kdx_emp_logo,
              mod_kdx_emp_razon_social,
              mod_kdx_emp_nit,
              mod_kdx_emp_direccion,
              mod_kdx_emp_coordenadas,
              mod_kdx_emp_rubro,
              mod_kdx_emp_email,
              mod_kdx_emp_web,
              mod_kdx_emp_activar';
			$valores_post='inputId,inputNombre,
										inputDescripcion,
										inputLogo,
										inputRazonSocial,
										inputNit,
										inputDireccion,
										inputCoordenadas,
										inputRubro,
										inputEmail,
										inputWeb,
										inputActivar';
			$this->fmt->class_modulo->actualizar_tabla('mod_kardex_empresa',$filas,$valores_post); //$from,$filas,$valores_post
			$url="kardex-config.adm.php?tarea=busqueda?p=empresas";
			$this->fmt->class_modulo->script_location($url);
		}
	}

	function modificar_cargo(){
		if ($_POST["btn-accion"]=="eliminar"){}
		if ($_POST["btn-accion"]=="actualizar"){

			$filas='mod_kdx_car_id,
							mod_kdx_car_nombre,
							mod_kdx_car_descripcion,
							mod_kdx_car_atribuciones,
							mod_kdx_car_responsabilidades,
							mod_kdx_car_dependiente,
							mod_kdx_car_ascendencia,
							mod_kdx_car_destrezas,
							mod_kdx_car_lugar_de_trabajo,
							mod_kdx_car_requisitos,
							mod_kdx_car_activar';
			$valores_post='inputId,
										inputNombre,
										inputDescripcion,
										inputAtribuciones,
										inputResponsabilidades,
										inputDependiente,
										inputAscendencia,
										inputDestrezas,
										inputLugarTrabajo,
										inputRequisitos,
										inputActivar';
			$this->fmt->class_modulo->actualizar_tabla('mod_kardex_cargo',$filas,$valores_post); //$from,$filas,$valores_post
			$url="kardex-config.adm.php?tarea=busqueda&p=cargos";
			$this->fmt->class_modulo->script_location($url);
		}
	}

	function modificar_cat(){
		if ($_POST["btn-accion"]=="eliminar"){}
		if ($_POST["btn-accion"]=="actualizar"){

			$filas='cts_id,
					cts_nombre,
					cts_email,
					cts_encargado,
					cts_zona,
					cts_direccion,
					cts_telefono,
					cts_celular,
					cts_id_usuario,
					cts_id_roles,
					cts_activar';
			$valores_post='inputId,
					inputNombre,
					inputCorreo,
					inputEncargado,
					inputZona,
					inputDireccion,
					inputtelefono,
					inputCelular,
					inputUsuario,
					InputRol,
					inputActivar';
		$this->fmt->class_modulo->actualizar_tabla('cats',$filas,$valores_post); //$from,$filas,$valores_post
		$url="kardex-config.adm.php?tarea=busqueda&p=cats";
		$this->fmt->class_modulo->script_location($url);
		}
	}

	function modificar_division(){
		if ($_POST["btn-accion"]=="eliminar"){}
		if ($_POST["btn-accion"]=="actualizar"){

			$filas='mod_kdx_div_id,
							mod_kdx_div_nombre,
							mod_kdx_div_descripcion,
							mod_kdx_div_activar';
			$valores_post='inputId,
										inputNombre,
										inputDescripcion,
										inputActivar';
			$this->fmt->class_modulo->actualizar_tabla('mod_kardex_division',$filas,$valores_post); //$from,$filas,$valores_post
			$url="kardex-config.adm.php?tarea=busqueda&p=divisiones";
			$this->fmt->class_modulo->script_location($url);
		}
	}

  function eliminar_empresa(){
      $this->fmt->class_modulo->eliminar_get_id("mod_kardex_empresa","mod_kdx_emp_");
      $url="kardex-config.adm.php?tarea=busqueda&p=empresas";
      $this->fmt->class_modulo->script_location($url);
    }

  function activar_empresa(){
      $this->fmt->class_modulo->activar_get_id("mod_kardex_empresa","mod_kdx_emp_");
      $url="kardex-config.adm.php?tarea=busqueda&p=empresas";
      $this->fmt->class_modulo->script_location($url);
  }

	function eliminar_cat(){
      $this->fmt->class_modulo->eliminar_get_id("cats","cts_");
      $url="kardex-config.adm.php?tarea=busqueda&p=cats";
      $this->fmt->class_modulo->script_location($url);
    }

    function eliminar_cargo(){
      $this->fmt->class_modulo->eliminar_get_id("mod_kardex_cargo","mod_kdx_car_");
      $url="kardex-config.adm.php?tarea=busqueda&p=cargos";
      $this->fmt->class_modulo->script_location($url);
    }

  function activar_cargo(){
      $this->fmt->class_modulo->activar_get_id("mod_kardex_cargo","mod_kdx_car_");
      $url="kardex-config.adm.php?tarea=busqueda&p=cargos";
      $this->fmt->class_modulo->script_location($url);
  }

	function eliminar_division(){
      $this->fmt->class_modulo->eliminar_get_id("mod_kardex_division","mod_kdx_div_");
      $url="kardex-config.adm.php?tarea=busqueda&p=divisiones";
      $this->fmt->class_modulo->script_location($url);
    }

  function activar_division(){
      $this->fmt->class_modulo->activar_get_id("mod_kardex_division","mod_kdx_div_");
      $url="kardex-config.adm.php?tarea=busqueda&p=divisiones";
      $this->fmt->class_modulo->script_location($url);
  }

	function eliminar_departamento(){
      $this->fmt->class_modulo->eliminar_get_id("mod_kardex_departamento","mod_kdx_dep_");
      $url="kardex-config.adm.php?tarea=busqueda&p=departamentos";
      $this->fmt->class_modulo->script_location($url);
    }

  function activar_departamento(){
      $this->fmt->class_modulo->activar_get_id("mod_kardex_departamento","mod_kdx_dep_");
      $url="kardex-config.adm.php?tarea=busqueda&p=departamentos";
      $this->fmt->class_modulo->script_location($url);
  }

}
