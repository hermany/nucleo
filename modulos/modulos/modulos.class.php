<?php
header("Content-Type: text/html;charset=utf-8");

class MODULOS{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function MODULOS($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){
			$botones = $this->fmt->class_modulo->botones_hijos_modulos($this->id_mod);
			$this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
			?>
			<div class="body-modulo container-fluid">
			  <div class="container">
			    <?php
			      $botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Crear","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
			      $this->fmt->class_pagina->head_modulo_inner("Modulos activos", $botones); // bd, id modulo, botones
			    ?>
			    <div class="table-responsive">
			      <table class="table table-hover" id="table_id">
			        <thead>
			          <tr>
			            <th class="col-id">id</th>
			            <th>Nombre del modulo</th>
			            <th class="no-mobile">Descripción</th>
			            <th class="no-mobile">Tipo Modulo</th>
			            <th class="no-mobile">Padre</th>
			            <th>Público</th>
			            <th>Acciones</th>
			          </tr>
			        </thead>
			        <tbody>
			          <?php
			            $sql="select mod_id, mod_nombre, mod_descripcion, mod_url, mod_tipo, mod_icono, mod_id_padre, mod_activar, mod_color from modulo	ORDER BY mod_id desc";
			            $rs =$this->fmt->query->consulta($sql,__METHOD__);
			            $num=$this->fmt->query->num_registros($rs);
			            if($num>0){
			            for($i=0;$i<$num;$i++){
			              $row=$this->fmt->query->obt_fila($rs);
										$fila_id = $row["mod_id"];
										$fila_nombre = $row["mod_nombre"];
										$fila_descripcion = $row["mod_descripcion"];
										$fila_url = $row["mod_url"];
										$fila_tipo = $row["mod_tipo"];
										$fila_icono = $row["mod_icono"];
										$fila_padre = $row["mod_id_padre"];
										$fila_activar = $row["mod_activar"];
										$color = $row["mod_color"];
			            ?>
			            <tr class="row row-<?php echo $fila_id; ?>">
			              <td><?php echo $fila_id; ?></td>
			              <td class="col-nombre"><i class="icn <?php echo $fila_icono; ?>" style="color:<?php echo $color; ?>"></i> <span class='label'><?php echo $fila_nombre; ?></span></td>
			              <?php  if($fila_tipo=="2"){ $aux ="disabled"; } ?>
			              <td><?php echo $fila_descripcion; ?></td>
			              <td class="tabla-col col-tipo-modulo no-mobile"><?php echo $this->tipo_modulo($fila_tipo); ?></td>
			              <td class="no-mobile"> <?php echo $this->fmt->class_modulo->nombre_modulo($fila_padre); ?></td>
			              <td class="estado">
			                <?php
			                  $this->fmt->class_modulo->estado_publicacion($fila_activar,$this->id_mod,$aux, $fila_id);
			                ?>
			              </td>
			              <td class="acciones">
			              <?php
			                //echo $this->fmt->class_pagina->crear_btn("form_editar,".$fila_id,$this->id_mod,"btn btn-accion btn-editar btn-menu-ajax ".$aux,"icn-pencil","","Editar");
			                echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar ".$fila_id,"btn btn-accion btn-fila-editar btn-menu-ajax ".$aux,$this->id_mod,"form_editar,".$fila_id); //$nom,$icon,$title,$clase,$id_mod,$vars
			              ?>
			                <a  title="eliminar <?php echo $fila_id; ?>" type="button" id_mod="<?php echo $this->id_mod; ?>" vars="eliminar,<?php echo $fila_id; ?>" nombre="<?php echo $fila_nombre; ?>" class="btn btn-fila-eliminar btn-accion <?php echo $aux; ?>"><i class="icn-trash"></i></a>
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
			</div>
			<?php
			$this->fmt->class_modulo->script_accion_modulo();
			$this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","55",true);
  }  // fin busqueda

	function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Modulo", "","");// nombre, botones-left
		$id_form="form-nuevo";
		?>
		<div class="body-modulo">
			<form class="form form-modulo"  method="POST" id="<?php echo $id_form; ?>">
				<div class="form-group" id="mensaje-login"></div> <!--Mensaje form -->
				<div class="form-group">
					<label><i class="obligatorio">*</i> Nombre Módulo</label>
					<div class="input-group controls input-icon-addon">
						<span class="input-group-addon form-input-icon"><i class="icn icn-box"></i></span>
						<input class="form-control input-lg color-border-gris-a color-text-gris form-nombre"  id="inputNombre" name="inputNombre" placeholder=" " value="<?php echo $fila_nombre; ?>" type="text" autofocus />
						<input type="hidden" id="inputId" name="inputId" value="" />
					</div>
				</div>

				<div class="form-group form-descripcion">
					<label>Descripción</label>
					<textarea class="form-control" rows="5" id="inputDescripcion" name="inputDescripcion" placeholder=""></textarea>
				</div>

				<?php
					$this->fmt->form->input_form('<i class="obligatorio">*</i> Url amigable:','inputRutaamigable','','','');
				?>

				<div class="form-group">
					<label><i class="obligatorio">*</i> Url módulo</label>
					<input class="form-control" id="inputUrl" name="inputUrl" placeholder="" value="" />
				</div>

				<div class="form-group">
					<label>Icono módulo</label>
					<input class="form-control box-md-4" id="inputIcono" name="inputIcono"  placeholder="" value=""/>
					<span class="input-link"><a href="<?php echo _RUTA_WEB_NUCLEO; ?>includes/icons.php" target="_blank">ver iconos</a></span>
				</div>

				<div class="form-group form-group-color">
					<label>Color</label>
					<?php
					 if (empty($fila_color)){
						 $color="#333333";
					 }else{
						 $color= $fila_color;
					 }
					?>
					<?php //echo  "rt:"._RUTA_SERVER; ?>
					<input type="color" class="form-control box-md-2" id="inputColor" name="inputColor"  value=""/>
					<?php
						require_once( _RUTA_NUCLEO."includes/color.php");
					?>
				</div>

				<div class="form-group">
					<label><i class="obligatorio">*</i> Tipo módulo:  </label>
					<select class="form-control form-select box-md-4" name="inputTipo" id="inputTipo">
						<?php  echo $this->opciones_tipo();  ?>
					</select>
				</div>
				<?php
					$this->fmt->form->select_form("Modulo padre:","inputPadre","mod_","modulo","","","","box-md-4"); //$label,$id,$prefijo,$from,$id_select,$id_disabled,$class_div,$class_select

					$this->fmt->form->categoria_form('Categoria','inputCat',"0","","","");
					$this->fmt->form->input_form("Nombre de la bd:","inputBd","","","box-md-5"); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
					$this->fmt->form->input_form("Prefijo de la bd:","inputBdPrefijo","","","box-md-5"); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
					$this->fmt->form->textarea_form("Relaciones de la bd:","inputBdRelaciones","","","box-md-5","","3");
					$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar"); //$id_form,$id_mod,$tarea
				?>
			</form>
		</div>
		<?php
		$this->fmt->class_modulo->modal_script($this->id_mod);
		 //$this->fmt->class_modulo->script_form("modulos/modulos/modulos.adm.php",$this->id_mod);
	}

	function form_editar(){

		$this->fmt->class_pagina->crear_head_form("Editar Modulo", "","");// nombre, botones-left
		$id_form="form-editar";

		$sql="select mod_id, mod_nombre, mod_descripcion, mod_ruta_amigable,mod_url, mod_tipo, mod_icono,mod_color, mod_id_padre, mod_activar,mod_bd,mod_bd_prefijo,mod_bd_relaciones from modulo	where mod_id='".$this->id_item."'";
		$rs=$this->fmt->query->consulta($sql,__METHOD__);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					$row=$this->fmt->query->obt_fila($rs);
					$fila_id = $row["mod_id"];
					$fila_nombre = $row["mod_nombre"];
					$fila_descripcion = $row["mod_descripcion"];
					$fila_ra = $row["mod_ruta_amigable"];
					$fila_url = $row["mod_url"];
					$fila_tipo = $row["mod_tipo"];
					$fila_icono = $row["mod_icono"];
					$fila_color = $row["mod_color"];
					$fila_padre = $row["mod_id_padre"];
					$fila_activar = $row["mod_activar"];
					$fila_inputBd = $row["mod_bd"];
					$fila_prefijo = $row["mod_bd_prefijo"];
					$fila_relaciones = $row["mod_bd_relaciones"];
				}
			}
		?>
		<div class="body-modulo">
			<form class="form form-modulo"  method="POST" id="<?php echo $id_form; ?>">
				<div class="form-group" id="mensaje-login"></div> <!--Mensaje form -->

				<div class="form-group">
					<label><i class="obligatorio">*</i> Nombre Módulo</label>
					<div class="input-group controls input-icon-addon">
						<span class="input-group-addon form-input-icon"><i class="<?php echo $fila_icono; ?>"></i></span>
						<input class="form-control input-lg color-border-gris-a color-text-gris form-nombre"  id="inputNombre" name="inputNombre" placeholder=" " value="<?php echo $fila_nombre; ?>" type="text" autofocus />
						<input type="hidden" id="inputIdOr" name="inputIdOr" value="<?php echo $fila_id; ?>" />
					</div>
				</div>
				<?php $this->fmt->form->input_form('Id:','inputId','',$fila_id,'','','');  ?>
				<div class="form-group form-descripcion">
					<label>Descripción</label>
					<textarea class="form-control" rows="5" id="inputDescripcion" name="inputDescripcion" placeholder=""><?php echo $fila_descripcion; ?></textarea>
				</div>

				<?php
					$this->fmt->form->input_form('<i class="obligatorio">*</i> Url amigable:','inputRutaamigable','',$fila_ra,'');
				?>

				<div class="form-group">
					<label><i class="obligatorio">*</i> Url módulo</label>
					<input class="form-control" id="inputUrl" name="inputUrl" placeholder="" value="<?php echo $fila_url; ?>" />
				</div>

				<div class="form-group">
					<label>Icono módulo</label>
					<input class="form-control box-md-4" id="inputIcono" name="inputIcono"  placeholder="" value="<?php echo $fila_icono; ?>"/>
					<span class="input-link"><a href="<?php echo _RUTA_WEB_NUCLEO; ?>includes/icons.php" target="_blank">ver iconos</a></span>
				</div>

				<div class="form-group form-group-color">
					<label>Color</label>
					<?php
					 if (empty($fila_color)){
						 $color="#ffffff";
					 }else{
						 $color= $fila_color;
					 }
					?>
					<?php //echo  "rt:"._RUTA_SERVER; ?>
					<input type="color" class="form-control box-md-2" id="inputColor" name="inputColor"  value="<?php echo $color; ?>"/>
					<?php
						require_once( _RUTA_NUCLEO."includes/color.php");
					?>
				</div>

				<div class="form-group">
					<label><i class="obligatorio">*</i> Tipo módulo:  </label>
					<select class="form-control form-select box-md-4" name="inputTipo" id="inputTipo">
						<?php  echo $this->opciones_tipo($fila_tipo);  ?>
					</select>
				</div>
				<?php
					$this->fmt->form->select_form("Modulo padre:","inputPadre","mod_","modulo",$fila_padre,"","","box-md-4"); //$label,$id,$prefijo,$from,$id_select,$id_disabled,$class_div,$class_select
					$cats_id = $this->fmt->categoria->traer_rel_cat_id($fila_id,'modulo_categorias','mod_cat_cat_id','mod_cat_mod_id'); //$fila_id,$from,$prefijo_cat,$prefijo_rel
					$this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"","");
					$this->fmt->form->input_form("Nombre de la bd:","inputBd","",$fila_inputBd,"box-md-5"); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
					$this->fmt->form->input_form("Prefijo de la bd:","inputBdPrefijo","",$fila_prefijo,"box-md-5"); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar
					$this->fmt->form->textarea_form("Relaciones de la bd:","inputBdRelaciones","",$fila_relaciones,"box-md-5","","3");
				?>
				<div class="form-group">
					<label>Estado:  </label>
					<div class="group">
						<label class="radio-inline">
							<input type="radio" name="inputActivar" id="inputActivar" value="0" <?php if ($fila_activar==0){ echo "checked"; } ?> > Desactivar
						</label>
						<label class="radio-inline">
							<input type="radio" name="inputActivar" id="inputActivar" value="1" <?php if ($fila_activar==1){ echo "checked"; $aux="Activo"; } else { $aux="Activar"; } ?> > <?php echo $aux; ?>
						</label>
					</div>
				</div>
				<?php
					$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar"); //$id_form,$id_mod,$tarea
				?>
			</form>
		</div>
		<?php
		$this->fmt->class_modulo->modal_script($this->id_mod);
		 //$this->fmt->class_modulo->script_form("modulos/modulos/modulos.adm.php",$this->id_mod);
	}

	function ingresar(){

		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}

		$ingresar ="mod_nombre, mod_ruta_amigable,mod_descripcion, mod_url, mod_tipo, mod_icono, mod_color, mod_id_padre, mod_activar";
		$valores  ="'".$_POST['inputNombre']."','".
									 $_POST['inputRutaamigable']."','".
									 $_POST['inputDescripcion']."','".
									 $_POST['inputUrl']."','".
									 $_POST['inputTipo']."','".
									 $_POST['inputIcono']."','".
									 $_POST['inputColor']."','".
									 $_POST['inputPadre']."','".
									 $activar."'";

		$sql="insert into modulo (".$ingresar.") values (".$valores.")";

		$this->fmt->query->consulta($sql,__METHOD__);

		$sql="select max(mod_id) as id from modulo";
		$rs= $this->fmt->query->consulta($sql,__METHOD__);
		$fila = $this->fmt->query->obt_fila($rs);
		$id = $fila ["id"];
		$ingresar1 ="mod_cat_mod_id, mod_cat_cat_id";
		$valor_cat= $_POST['inputCat'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$id."','".$valor_cat[$i]."'";
			$sql1="insert into modulo_categorias (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1,__METHOD__);
		}

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin funcion ingresar

	function modificar(){

		if ($_POST["estado-mod"]=="eliminar"){
		}else{
			$sql="UPDATE modulo SET
						mod_nombre='".$_POST['inputNombre']."',
						mod_id='".$_POST['inputId']."',
						mod_descripcion='".$_POST['inputDescripcion']."',
						mod_ruta_amigable ='".$_POST['inputRutaamigable']."',
						mod_url ='".$_POST['inputUrl']."',
						mod_tipo='".$_POST['inputTipo']."',
						mod_icono='".$_POST['inputIcono']."',
						mod_color='".$_POST['inputColor']."',
						mod_bd='".$_POST['inputBd']."',
						mod_bd_prefijo='".$_POST['inputBdPrefijo']."',
						mod_bd_relaciones='".$_POST['inputBdRelaciones']."',
						mod_id_padre='".$_POST['inputPadre']."',
						mod_activar='".$_POST['inputActivar']."'
	          WHERE mod_id='".$_POST['inputIdOr']."'";

			$this->fmt->query->consulta($sql,__METHOD__);
			$id = $_POST['inputId'];
			$this->fmt->class_modulo->eliminar_fila($id,"modulo_categorias","mod_cat_mod_id");

			$ingresar1 ="mod_cat_mod_id, mod_cat_cat_id";
			$valor_cat= $_POST['inputCat'];
			//var_dump($valor_cat);
			$num=count( $valor_cat );
			for ($i=0; $i<$num;$i++){
				$valores1 = "'".$id."','".$valor_cat[$i]."'";
			 	$sql1="insert into modulo_categorias (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1,__METHOD__);
			}
		}
			// $this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}

	function eliminar(){
		/*$this->fmt->get->validar_get ( $_GET['id'] );
		$id= $_GET['id'];*/
		$this->fmt->class_modulo->eliminar_fila($this->id_item,"modulo_categorias","mod_cat_mod_id");
		$sql="DELETE FROM modulo WHERE mod_id='".$this->id_item."'";
		$this->fmt->query->consulta($sql,__METHOD__);
		$up_sqr6 = "ALTER TABLE modulo AUTO_INCREMENT=1";
		$this->fmt->query->consulta($up_sqr6,__METHOD__);
		$this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
		//header("location: modulos.adm.php?id_mod=".$this->id_mod);
	}

	function activar($id,$estado){
		/*$this->fmt->get->validar_get ( $_GET['estado'] );
		$this->fmt->get->validar_get ( $_GET['id'] );*/

		$sql="update modulo set
				mod_activar='".$this->id_estado."' where mod_id='".$this->id_item."'";
		$this->fmt->query->consulta($sql,__METHOD__);
		$this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
		//header("location: modulos.adm.php?id_mod=".$this->id_mod);
	}

	function tipo_modulo($mod_tipo){

		switch ($mod_tipo) {
			case '0':
				$mod_tipo="Datos";
				break;
			case '1':
				$mod_tipo="Configuración";
				break;
			case '2':
				$mod_tipo="Esencial";
				break;
			case '3':
				$mod_tipo="no definido";
				break;			
			case '4':
				$mod_tipo="Personalizado";
				break;
		}
		return $mod_tipo;
	}

	function opciones_tipo($fila_tipo){
		$tipos = Array();
		for ($i = 0; $i <= 4; $i++) {
			$tipos [$i]= $this->tipo_modulo($i);
		}

		for ($i = 0; $i <= 4; $i++) {
			if (isset($fila_tipo)){
				if ($fila_tipo==$i){ $sel="selected"; } else {$sel="";}
			}else {
			$sel="";
			}
			$aux .='<option value="'.$i.'" '.$sel.'>'.$tipos[$i].'</option>';
		}
		return $aux;
	}

}
?>
