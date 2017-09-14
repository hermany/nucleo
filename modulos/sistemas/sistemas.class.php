<?php
header("Content-Type: text/html;charset=utf-8");

class SISTEMAS{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
  var $ruta_modulo;

	function SISTEMAS($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
    $this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

  function busqueda(){
    $botones = $this->fmt->class_modulo->botones_hijos_modulos($this->id_mod);
    $this->fmt->class_pagina->crear_head( $this->id_mod, $botones); // bd, id modulo, botones
    ?>
    <div class="body-modulo container-fluid">
      <div class="container">
        <?php
            $botones = $this->fmt->class_pagina->crear_btn(_RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($this->id_mod),"btn btn-link","icn icn-sync","Actualizar");  //$nom,$icon,$title,$clase,$id_mod,$vars
            $botones .= $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nuevo sistema","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars

            $this->fmt->class_pagina->head_modulo_inner("Sistemas activos", $botones); // bd, id modulo, botones
        ?>
        <div class="table-responsive">
          <table class="table table-hover" id="table_id">
            <thead>
              <tr>
                <!-- <th class="no-mobile">Id</th> -->
                <th>Nombre</th>
                <th class="no-mobile">Tipo</th>
                <th class="no-mobile">Modulos</th>
                <th class="compress">Público</th>
                <th class="acciones">Acciones</th>
              </tr>
            </thead>
            <tbody id="row-sorteable">
              <?php
                $sql="select sis_id, sis_nombre, sis_descripcion, sis_icono, sis_color, sis_tipo, sis_activar, sis_orden from sistema	ORDER BY sis_orden asc";
                $rs =$this->fmt->query-> consulta($sql,__METHOD__);
                $num=$this->fmt->query->num_registros($rs);
                if($num>0){
                for($i=0;$i<$num;$i++){
                  $row=$this->fmt->query->obt_fila($rs);
									$fila_id = $row["sis_id"];
									$fila_nombre = $row["sis_nombre"];
									$fila_descripcion = $row["sis_descripcion"];
									$fila_icono = $row["sis_icono"];
									$color = $row["sis_color"];
									$fila_tipo = $row["sis_tipo"];
									$fila_activar = $row["sis_activar"];
									$fila_orden = $row["sis_orden"];
                ?>
                <tr sis="<?php echo $fila_id; ?>" class="row row-sis row-<?php echo $fila_id; ?>">
                  <!-- <td></td> -->
                  <td  class="col-name"><i class='icn icn-reorder icn-reorder-row'><span style="display:none;"><?php echo $i; ?><span></i>  <i class="icn <?php echo $fila_icono; ?>" style="color:<?php echo $color;?>"></i> <span><?php echo $fila_nombre; ?></span> </td>
                  <?php // if($fila_tipo=="2"){ $aux ="disabled"; } ?>
                  <td class="col-tipo-modulo no-mobile"><?php echo $this->tipo_sistema($fila_tipo); ?></td>
                  <td  class="no-mobile no-visible-drag col-list-sorteable" ><?php   $this->fmt->class_sistema->modulos_sistema($fila_id); ?></td>
                  <td class="estado">
                    <?php
                      $this->fmt->class_modulo->estado_publicacion($fila_activar,$this->id_mod,"",$fila_id);
                    ?>
                  </td>
                  <td class="acciones">
                    <?php
                    echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar ".$fila_id,"btn btn-accion btn-editar btn-menu-ajax ",$this->id_mod,"form_editar,".$fila_id); //$nom,$icon,$title,$clase,$id_mod,$vars
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
      <script type="text/javascript">
      $( function() {
        $("#row-sorteable" ).sortable({
          start: function(event, ui) {
            var start_pos = ui.item.index();
            ui.item.data('start_pos', start_pos);
          },
          change: function(event, ui) {
              var start_pos = ui.item.data('start_pos');
              var index = ui.placeholder.index();
              var cant = <?php echo $num; ?>;
          },
          update: function(event, ui) {
            var count=0;
            var lista="";
            $("#row-sorteable .row-sis").each(function(i) {
              var am = $(this).attr("sis");
              if (count==0){
                lista  = am;
              }else{
                lista  = lista +":"+ am;
              }
              count++;
            });
            //console.log(lista);
            var formdata = new FormData();
            formdata.append("inputVars", lista );
            formdata.append("ajax", "ajax-orden-sistemas");
            var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
            $.ajax({
              url:ruta,
              type:"post",
              data:formdata,
              processData: false,
              contentType: false,
              success: function(msg){
                console.log(msg);
              }
            })
          }
        });
        $( "#row-sorteable" ).disableSelection();
      });
      </script>
    </div>
    <?php
    $this->fmt->class_modulo->script_accion_modulo();
    $this->fmt->class_modulo->script_table("table_id",$this->id_mod,"asc","0","25",true);
  }  // fin busqueda

  function form_nuevo(){
		//$botones = $this->fmt->class_pagina->crear_btn_m("Volver","icn-chevron-left","volver","btn btn-link btn-menu-ajax",$this->id_mod,"busqueda");
		$this->fmt->class_pagina->crear_head_form("Nuevo Sistema", "","");// nombre, botones-left, botones-right
		//echo "<a href='javascript:location.reload()'><i class='icn-sync'></i></a>";
    $id_form="form-nuevo";
		?>
		<div class="body-modulo">
			<form class="form form-modulo"  method="POST" id="<?php echo $id_form?>">
				<div class="form-group" id="mensaje-login"></div> <!--Mensaje form -->
				<div class="form-group">
					<label>Nombre Sistema</label>
					<div class="input-group controls input-icon-addon">
						<span class="input-group-addon form-input-icon"><i class="<?php echo $this->fmt->class_modulo->icono_modulo($this->id_mod); ?>"></i></span>
						<input class="form-control input-lg color-border-gris-a color-text-gris form-nombre"  id="inputNombre" name="inputNombre" placeholder=" " type="text" autofocus />
					</div>
				</div>

				<div class="form-group form-descripcion">
					<label>Descripción</label>
					<textarea class="form-control" rows="5" id="inputDescripcion" name="inputDescripcion" placeholder=""></textarea>
				</div>

				<div class="form-group">
					<label>Icono Sistema </label>
					<input class="form-control box-md-4" id="inputIcono" name="inputIcono"  placeholder="" />
					<span class="input-link"><a href="<?php echo _RUTA_WEB_NUCLEO; ?>includes/icons.php" target="_blank">ver iconos</a></span>
				</div>
					<div class="form-group">
						<label>Color</label>
						<input type="color" class="form-control box-md-2" id="inputColor" name="inputColor"  placeholder="" />
					 	<?php
							require_once( _RUTA_NUCLEO."includes/color.php");
						?>
					</div>
				<div class="form-group">
					<label>Modulos:  </label>
					<div class="group">
						<?php echo $this->fmt->class_modulo->opciones_modulos("");  ?>
					</div>
				</div>
				<div class="form-group form-fluid">
					<label>Tipo Sistema:  </label>
					<select class="form-control form-select" name="inputTipo" id="inputTipo">
							<?php echo $this->opciones_tipo("");  ?>
					</select>
				</div>
        <?php
          $this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar"); //$form,$modo
        ?>
			</form>
		</div>
		<?php
    $this->fmt->class_modulo->modal_script($this->id_mod);
		//$this->fmt->class_modulo->script_form("modulos/modulos/modulos.adm.php",$this->id_mod);
	}

	function form_editar(){
		// $botones = $this->fmt->class_pagina->crear_btn_m("Volver","icn-chevron-left","volver","btn btn-link btn-menu-ajax",$this->id_mod,"busqueda");
		// $this->fmt->class_pagina->crear_head_form("Editar Sistema", $botones,"");// nombre, botones-left, botones-right
		$id = $this->id_item;
		$sql="select sis_id, sis_nombre, sis_descripcion, sis_tipo, sis_icono, sis_color, sis_activar, sis_orden from sistema	where sis_id='".$id."'";
		$rs=$this->fmt->query->consulta($sql,__METHOD__);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					list($fila_id,$fila_nombre,$fila_descripcion,$fila_tipo,$fila_icono,$fila_color,$fila_activar,$orden)=$this->fmt->query->obt_fila($rs);
				}
			}
      $this->fmt->class_pagina->crear_head_form("Editar Sistema", "","");// nombre, botones-left, botones-right
  		//echo "<a href='javascript:location.reload()'><i class='icn-sync'></i></a>";
      $id_form="form-editar";
		?>
		<div class="body-modulo">
			<form class="form form-modulo"  method="POST" id="<?php echo $id_form?>">
				<div class="form-group" id="mensaje-login"></div> <!--Mensaje form -->
        <div class="form-group">
					<label>Nombre Sistema:</label>
					<div class="input-group controls input-icon-addon">
						<span class="input-group-addon form-input-icon"><i class="<?php echo $fila_icono; ?>" style="color:<?php echo $fila_color; ?>"></i></span>
						<input class="form-control input-lg color-border-gris-a color-text-gris form-nombre"  id="inputNombre" name="inputNombre" placeholder=" " value="<?php echo $fila_nombre; ?>" type="text" autofocus />
            <!-- <input type="hidden" id="inputId" name="inputId" value="<?php echo $fila_id; ?>" /> -->
					</div>
				</div>

        <?php $this->fmt->form->input_form('Id:','inputId','',$fila_id,'','','');  ?>
				<div class="form-group form-descripcion">
					<label>Descripción:</label>
					<textarea class="form-control" rows="5" id="inputDescripcion" name="inputDescripcion" placeholder=""><?php echo $fila_descripcion; ?></textarea>
				</div>
				<div class="form-group">
					<label>Icono sistema:</label>
					<input class="form-control box-md-4" id="inputIcono" name="inputIcono"  placeholder="" value="<?php echo $fila_icono; ?>"/>
          <span class="input-link"><a href="<?php echo _RUTA_WEB_NUCLEO; ?>includes/icons.php" target="_blank">ver iconos</a></span>
        </div>
					<div class="form-group">
						<label>Color</label>
            <?php
  					 if (empty($fila_color)){
  						 $color="#333333";
  					 }else{
  						 $color= $fila_color;
  					 }
             //echo _RUTA_HOST;
  					?>
						<input type="color" class="form-control box-md-2" id="inputColor" name="inputColor"  value="<?php echo $color; ?>" />
					 	<?php
							require_once( _RUTA_NUCLEO."includes/color.php");
						?>
					</div>

				<div class="form-group form-fluid">
					<label>Modulos:  </label>
          <div class="group">
            <?php echo $this->fmt->class_modulo->opciones_modulos($fila_id);  ?>
          </div>
				</div>

        <?php
          //$this->fmt->form->input_check_form_bd("Permisos para Roles","inputSistemasRoles","rol_","rol","sis_rol_","sistema_roles","sis_rol_sis_id",$fila_id,"sis_rol_sis_id","","1","1")
        ?>


				<div class="form-group">
					<label>Tipo Sistema:  </label>
					<select class="form-control form-select" name="inputTipo" id="inputTipo">
						<?php  echo $this->opciones_tipo($fila_tipo);  ?>
					</select>
				</div>
        <?php
          $this->fmt->form->input_form("Orden:","inputOrden","",$orden,"box-md-2");
          $this->fmt->form->radio_activar_form($fila_activar);
					$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar"); //$id_form,$id_mod,$tarea
				?>
			</form>
		</div>
		<?php
    $this->fmt->class_modulo->modal_script($this->id_mod);
		//  $this->fmt->class_modulo->script_form("modulos/sistemas/sistemas.adm.php",$this->id_mod);
	}

	function ingresar(){

		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}

		$ingresar ="sis_nombre, sis_descripcion, sis_tipo, sis_icono, sis_color, sis_activar";
		$valores  ="'".$_POST['inputNombre']."','".
									 $_POST['inputDescripcion']."','".
									 $_POST['inputTipo']."','".
									 $_POST['inputIcono']."','".
									 $_POST['inputColor']."','".
									 $activar."'";

	  $sql="insert into sistema (".$ingresar.") values (".$valores.")";
  	$this->fmt->query->consulta($sql,__METHOD__);

  	$sql="select max(sis_id) as id_sis from sistema";
  	$rs= $this->fmt->query->consulta($sql,__METHOD__);
  	$fila = $this->fmt->query->obt_fila($rs);
    $id = $fila ["id_sis"];

  	$mod = $_POST['inputModulo'];
  	$cont_mod= count($mod);
  	$ingresar1 ="sis_mod_sis_id, sis_mod_mod_id";
  	for($i=0; $i < $cont_mod; $i++){
  		$valores1 = "'".$id."','".$mod[$i]."'";
  		$sql1="insert into sistema_modulos (".$ingresar1.") values (".$valores1.")";
  		$this->fmt->query->consulta($sql1,__METHOD__);
  	}

		//$this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
    $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin funcion ingresar

	function modificar(){
		if ($_POST["estado-mod"]=="eliminar"){

		}else{
			$id=$_POST['inputId'];

			$sql="UPDATE sistema SET
						sis_nombre='".$_POST['inputNombre']."',
						sis_descripcion='".$_POST['inputDescripcion']."',
						sis_tipo='".$_POST['inputTipo']."',
						sis_icono='".$_POST['inputIcono']."',
						sis_color='".$_POST['inputColor']."',
						sis_orden='".$_POST['inputOrden']."',
						sis_activar='".$_POST['inputActivar']."'
	          WHERE sis_id='".$_POST['inputId']."'";
			$this->fmt->query->consulta($sql,__METHOD__);

			$sql1="DELETE FROM sistema_modulos WHERE sis_mod_sis_id='".$_POST['inputId']."'";
			$this->fmt->query->consulta($sql1,__METHOD__);

			$mod = $_POST['inputModulo'];
			$cont_mod= count($mod);
			$ingresar1 ="sis_mod_sis_id,sis_mod_mod_id";
			for($i=0; $i < $cont_mod; $i++){
				$valores1 = "'".$_POST['inputId']."','".$mod[$i]."'";
				$sql1="insert into sistema_modulos (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1,__METHOD__);
			}

		}
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}


	function tipo_sistema($sis_tipo){

		switch ($sis_tipo) {
			case '0':
				$sis_tipo="Multiproposito";
				break;
			case '1':
				$sis_tipo="CMS";
				break;
			case '2':
				$sis_tipo="CRM";
			break;
			case '3':
				$sis_tipo="ERP";
				break;
			case '4':
				$sis_tipo="RRHH";
				break;
			case '5':
				$sis_tipo="PROYECTOS";
				break;
			case '6':
				$sis_tipo="FINANZAS";
				break;
			case '7':
				$sis_tipo="GERENCIA";
				break;
			case '8':
				$sis_tipo="TIC";
				break;
			case '9':
				$sis_tipo="ADM";
				break;
			case '10':
				$sis_tipo="E-commerce";
				break;
			default:
				$sis_tipo="no definido";
				break;
		}
		return $sis_tipo;
	}

	function opciones_tipo($fila_tipo){
		$tipos = Array();
		for ($i = 0; $i <= 10; $i++) {
			$tipos [$i]= $this->tipo_sistema($i);
		}

		for ($i = 0; $i <= 10; $i++) {
			if (!empty($fila_tipo)){
				if ($fila_tipo==$i){ $sel="selected"; } else {$sel="";}
			}else {
			$sel="";
			}
			$aux .='<option value="'.$i.'" '.$sel.'>'.$tipos[$i].'</option>';
		}
		return $aux;

	}

} // fin clase
?>
