<?php
header("Content-Type: text/html;charset=utf-8");

class PEDIDO{

	var $fmt;
	var $id_mod;
	var $ruta_modulo;
	var $nombre_modulo;

	function PEDIDO($fmt){
		$this->fmt = $fmt;
		$this->fmt->get->validar_get($_GET['id_mod']);
		$this->id_mod=$_GET['id_mod'];
	}

	function busqueda(){
		$id_cat=0;
	if(isset($_GET["cat_id"]))
		//$id_cat=$_GET["cat_id"];

   $this->fmt->class_pagina->crear_head( $this->id_mod, ""); // bd, id modulo, botones

    $this->fmt->class_modulo->script_form("modulos/rrhh/inventario.adm.php",$this->id_mod,"asc","0","25",true);

    $id_rol = $this->fmt->sesion->get_variable("usu_rol");
		$perm = $this->fmt->usuario->permisos_roles_mod($id_rol,$this->id_mod);


	if (isset($_GET["p"])){
			$clase_activa = $_GET["p"];
		}else{
			$clase_activa = "nuevo_pedido";
		}
    ?>
    <ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="<?php if ($clase_activa=="nuevo_pedido"){ echo "active"; }?>"><a href="#nuevo_pedido" aria-controls="nuevo_pedido" role="tab" data-toggle="tab"><i class="icn-category-o color-text-azul-b"></i>Nuevo Pedido</a></li>
		<li role="presentation" class="<?php if ($clase_activa=="lista_pedido"){ echo "active"; }?>"><a href="#lista_pedido" aria-controls="lista_pedido" role="tab" data-toggle="tab"><i class="icn-category-o color-text-azul-b"></i>Lista Pedidos</a></li>
	</ul>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane <?php if ($clase_activa=="nuevo_pedido"){ echo "active"; }?>" id="nuevo_pedido">
			<div class="head-modulo row head-modal head-pedidos ">
			<h1 class="title-form col-xs-4 col-xs-offset-3">Nuevo Pedido <a href="javascript:location.reload()"><i class="icn-sync"></i></a></h1>
			</div>
			<div class="body-modulo body-pedidos col-xs-6 col-xs-offset-3 form_pedido ">
      <form class="form form-modulo" action="pedido.adm.php?tarea=ingresar&id_mod=<?php echo $this->id_mod; ?>" enctype="multipart/form-data" method="POST" id="form_nuevo">
        <div class="form-group" id="mensaje-form"></div>
			<?php
			$usuario = $this->fmt->sesion->get_variable('usu_id');
			$rol_id = $this->fmt->sesion->get_variable('usu_rol');
			$cats_n = $this->fmt->usuario->nombre_cat( $rol_id );
			$cats_id = $this->fmt->usuario->id_cat_roles( $rol_id );
			$this->fmt->form->input_form_sololectura('Cats:','','',$cats_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
			$this->fmt->form->input_hidden_form("inputCat",$cats_id);

			$usuario_n =  $this->fmt->usuario->nombre_usuario( $usuario );
			$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
			$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
			$this->fmt->form->input_form('Centro de Costo:','inputCosto','','','requerido requerido-texto input-lg','','');
			$this->fmt->form->agregar_pedidos("Pedidos:","inputPedidos","","","","");
			?>
			<div class="form-group form-botones">
       <button type="submit" class="btn-accion-form btn btn-info  btn-guardar color-bg-celecte-b btn-lg" name="btn-accion" id="btn-guardar" value="guardar"><i class="icn-save"></i> Guardar</button>
    </div>
			<?php
			$this->fmt->form->footer_page();
			?>
		</div>

		<div role="tabpanel" class="tab-pane <?php if ($clase_activa=="lista_pedido"){ echo "active"; }?>" id="lista_pedido">
			<label><h4>Lista Pedido</h4></label>
			   <div class="table-responsive">
				   <table class="table table-hover" id="table_id">
					   <thead>
						  	<tr>
							  	<th style="width:10%" >#</th>
							   	<th>Usuario</th>
							   	<th>Cats</th>
									<th>Fecha</th>
					        <th class="estado">Estado</th>
					        <th class="col-xl-offset-2 acciones">Acciones</th>
					    	</tr>
					   </thead>
					   <tbody>
          <?php
          	if(($id_rol==1)||($perm["activar"]==1))
            	$sql="select ped_id, ped_id_cats, ped_id_usuario, ped_estado, ped_fecha_registro, ped_fecha_aprobacion, ped_fecha_entrega from pedido ORDER BY ped_id desc";
            else{
							$id_usu_k=$this->fmt->sesion->get_variable('usu_id');
							$sql="select ped_id, ped_id_cats, ped_id_usuario, ped_estado, ped_fecha_registro, ped_fecha_aprobacion, ped_fecha_entrega from pedido where ped_id_usuario=$id_usu_k ORDER BY ped_id desc";
            }

            $rs =$this->fmt->query->consulta($sql);
            $num=$this->fmt->query->num_registros($rs);
            if($num>0){
	            for($i=0;$i<$num;$i++){
	              list($fila_id,$fila_id_cats,$fila_id_usuario,$fila_estado,$fila_fecha_reg,$fila_fecha_apro, $fila_fecha_ent)=$this->fmt->query->obt_fila($rs);
					$url ="pedido.adm.php?tarea=form_editar&id=".$fila_id."&id_mod=".$this->id_mod;
								switch( $fila_estado ){
									case 0:
												$fecha_list = $fila_fecha_reg;
												$est = "Pendiente";
												$estado = '<a  id="btn-aprobar-modulo" class="btn btn-accion btn-aprobar" href="pedido.adm.php?tarea=aprobar&id='.$fila_id.'&id_mod='.$this->id_mod.'" title="Aprobar '.$fila_id.'" ><i class="fa fa-check"></i></a>';
												$estado .= '<a  id="btn-cancelar-modulo" class="btn btn-accion btn-cancelar" href="pedido.adm.php?tarea=cancelar&id='.$fila_id.'&id_mod='.$this->id_mod.'" title="Cancelar '.$fila_id.'" ><i class="icn-close"></i></a>';
												break;
									case 1:
												$fecha_list = $fila_fecha_apro;
												$est = "Aprobado";
												$estado = '<a  id="btn-entregar-modulo" class="btn btn-accion btn-entregar" href="pedido.adm.php?tarea=entregar&id='.$fila_id.'&id_mod='.$this->id_mod.'" title="Entregar '.$fila_id.'" ><i class="fa fa-gift"></i></a>';
												break;
									case 2:
												$fecha_list = $fila_fecha_apro;
												$est = "Cancelado";
												$estado = '';
												break;
									case 3:
												$fecha_list = $fila_fecha_ent;
												$est = "Entregado";
												$estado = '';
												break;
								}
								$usuario_nom = $this->fmt->usuario->nombre_usuario( $fila_id_usuario );
								$cats_nom = $this->fmt->usuario->nombre_cat_id( $fila_id_cats );
            ?>
				            <tr>
				              <td><?php echo $fila_id; ?></td>
				              <td><strong><a href="<?php echo $url; ?>" ><?php echo $usuario_nom; ?></a></strong></td>
				              <td><?php echo $cats_nom; ?></td>
				              <td><?php echo $fecha_list; ?></td>
											<td><?php echo $est; ?></td>
				              <td>
												<?php
												if($fila_estado==0){
													if(($id_rol==1)||($perm["editar"]==1)){
												?>
				                <a  id="btn-editar-modulo" class="btn btn-accion btn-editar" href="<?php echo $url; ?>" title="Editar <?php echo $fila_id; ?>" ><i class="icn-pencil"></i></a>
				                <?php
													}
													if(($id_rol==1)||($perm["eliminar"]==1)){
						                $this->fmt->form->botones_acciones("btn-eliminar","btn btn-eliminar btn-accion","","Eliminar -".$fila_id,"icn-trash","eliminar&id_mod=$this->id_mod","Pedido",$fila_id);
													}
												}
												if(($id_rol==1)||($perm["activar"]==1))
													echo $estado;
				                ?>

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
	</div>
  	<?php
		//$this->fmt->class_modulo->script_form($this->ruta_modulo,"");

  }


  function form_editar(){
    $botones .= $this->fmt->class_pagina->crear_btn("pedido.adm.php?tarea=busqueda&id_mod=$this->id_mod&p=lista_pedido","btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $this->fmt->class_pagina->crear_head_mod( "", "Editar Pedido",'',$botones,'col-xs-6 col-xs-offset-4');
		$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $_GET['id'];
		$sql="SELECT * from pedido where ped_id='".$id."'";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					list($fila_id,$fila_costo,$fila_fecha_reg,$fila_fecha_apro,$fila_fecha_ent,$fila_id_cats,$fila_id_usuario,$fila_estado)=$this->fmt->query->obt_fila($rs);
				}
			}
		?>
		<div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" action="pedido.adm.php?tarea=modificar&id_mod=<?php echo $this->id_mod; ?>"  enctype="multipart/form-data" method="POST" id="form_editar">
        <div class="form-group" id="mensaje-form"></div>
		<?php
		$cats_n = $this->fmt->usuario->nombre_cat_id( $fila_id_cats );
		$this->fmt->form->input_hidden_form("inputId",$fila_id);
		$this->fmt->form->input_form_sololectura('Cats:','','',$cats_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputCat",$fila_id_cats);

		$usuario_n =  $this->fmt->usuario->nombre_usuario( $fila_id_usuario );
		$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputUsuario",$fila_id_usuario);
		$this->fmt->form->input_form('Centro de Costo:','inputCosto','',$fila_costo,'requerido requerido-texto input-lg','','');
		$this->fmt->form->agregar_pedidos("Pedidos:","inputPedidos",$fila_id,"","","");
		$this->fmt->form->botones_editar($fila_id,$fila_nombre,'pedido','eliminar_pedido');
		?>
			</div>
		</div>
		<?php
    $this->fmt->class_modulo->script_form("modulos/rrhh/pedido.adm.php","");
    $this->fmt->form->footer_page();
  }

  function ingresar(){
    if ($_POST["btn-accion"]=="activar"){
      $activar=1;
    }
    if ($_POST["btn-accion"]=="guardar"){
      $activar=0;
    }
		$fecha = date("Y-m-d H:i:s");
    $ingresar ="ped_costo,
				ped_fecha_registro,
				ped_id_cats,
				ped_id_usuario,
				ped_estado";
		$valores  ="'".$_POST['inputCosto']."','".
				$fecha."','".
				$_POST['inputCat']."','".
				$_POST['inputUsuario']."','0'";

		$sql="insert into pedido (".$ingresar.") values (".$valores.")";
		$this->fmt->query->consulta($sql);
		$sql="select max(ped_id) as id from pedido";
		$rs= $this->fmt->query->consulta($sql);
		$fila = $this->fmt->query->obt_fila($rs);
		$id = $fila ["id"];

		$ingresar1 = "ped_alm_id_pedido, ped_alm_id_almacen, ped_alm_cantidad, ped_alm_unidad, ped_alm_observaciones";
		$valor_cat=$_POST['inputPedidos'];
		$num_cat=count( $valor_cat );
		//var_dump($valor_cat);

		for ($i=0; $i<$num_cat;$i++){
			$id_alm = $valor_cat[$i];
			$valores1 = "'".$id."','".$id_alm."','".$_POST["cant".$id_alm]."','".$_POST["unidad".$id_alm]."','".$_POST["observacion".$id_alm]."'";
			$sql1="insert into pedido_almacen  (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}

		$url="pedido.adm.php?tarea=busqueda&id_mod=".$this->id_mod."&p=lista_pedido";
    $this->fmt->class_modulo->script_location($url);
  }

	function modificar(){
		if ($_POST["btn-accion"]=="eliminar"){}
		if ($_POST["btn-accion"]=="actualizar"){
			$id = $_POST["inputId"];
			$filas='ped_id,
					ped_costo,
					ped_id_cats,
					ped_id_usuario';
			$valores_post='inputId,
					inputCosto,
					inputCat,
					inputUsuario';
			$this->fmt->class_modulo->actualizar_tabla('pedido',$filas,$valores_post);

			$this->eliminar_relacion($id);
			$ingresar1 = "ped_alm_id_pedido, ped_alm_id_almacen, ped_alm_cantidad, ped_alm_unidad, ped_alm_observaciones";
			$valor_cat=$_POST['inputPedidos'];
			$num_cat=count( $valor_cat );

			for ($i=0; $i<$num_cat;$i++){
				$id_alm = $valor_cat[$i];
				$valores1 = "'".$id."','".$id_alm."','".$_POST["cant".$id_alm]."','".$_POST["unidad".$id_alm]."','".$_POST["observacion".$id_alm]."'";
				$sql1="insert into pedido_almacen  (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}

			$url="pedido.adm.php?tarea=busqueda&id_mod=".$this->id_mod."&p=lista_pedido";
	    $this->fmt->class_modulo->script_location($url);
		}
	}
	function eliminar_relacion($id){
		$this->fmt->class_modulo->eliminar_fila($id,"pedido_almacen","ped_alm_id_pedido");
	}
	function eliminar(){
      $this->fmt->class_modulo->eliminar_get_id("pedido","ped_");
			$this->eliminar_relacion($_GET["id"]);
			$url="pedido.adm.php?tarea=busqueda&id_mod=".$this->id_mod."&p=lista_pedido";
			$this->fmt->class_modulo->script_location($url);
  }

  function aprobar(){
			$fecha = date("Y-m-d H:i:s");
			$sql ="UPDATE pedido SET ped_fecha_aprobacion	='".$fecha."',
			ped_estado='1' where ped_id='".$_GET["id"]."'";
			$this->fmt->query->consulta($sql);
			$url="pedido.adm.php?tarea=busqueda&id_mod=".$this->id_mod."&p=lista_pedido";
	    $this->fmt->class_modulo->script_location($url);
  }

	function cancelar(){
		$fecha = date("Y-m-d H:i:s");
		$sql ="UPDATE pedido SET ped_fecha_aprobacion	='".$fecha."',
		ped_estado='2' where ped_id='".$_GET["id"]."'";
		$this->fmt->query->consulta($sql);
		$url="pedido.adm.php?tarea=busqueda&id_mod=".$this->id_mod."&p=lista_pedido";
		$this->fmt->class_modulo->script_location($url);
	}
	function entregar(){
		$fecha = date("Y-m-d H:i:s");
		$sql ="UPDATE pedido SET ped_fecha_entrega	='".$fecha."',
		ped_estado='3' where ped_id='".$_GET["id"]."'";
		$this->fmt->query->consulta($sql);
		$url="pedido.adm.php?tarea=busqueda&id_mod=".$this->id_mod."&p=lista_pedido";
		$this->fmt->class_modulo->script_location($url);
	}
}
