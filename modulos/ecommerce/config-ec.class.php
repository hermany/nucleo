<?php
header("Content-Type: text/html;charset=utf-8");

class CONFIG_EC{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function CONFIG_EC($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){
   	$this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
   	$this->fmt->class_pagina->head_mod();
		$tabs = array ("Activar campos","pestañas");
		$iconos = array ("icn icn-btn-on color-red","icn-tabs-mobile color-violet-c");
		$this->fmt->class_pagina->tabs_mod("","config-ec",$tabs,$iconos,0,"tabs-config head-modulo-inner");
		// if (isset($_GET["p"])){
		// 	$clase_activa = $_GET["p"];
		// }else{
		// 	$clase_activa = "pestana";
		// }
    ?>
		<div class="tabs-body">
			<div class="tbody tab-content on" id="content-0">
				<?php $this->ajustes(); ?>
			</div>
		</div> <!-- fin   tabs-body -->
		<?php
		$this->fmt->class_modulo->script_accion_modulo();
		$this->fmt->class_pagina->footer_mod();
  }

	function ajustes(){
		$sql="select * from mod_productos_conf";
		$rs =$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
			for($i=0;$i<$num;$i++){
				list($codigo,$codigo_sap,$modelo,$aimg,$detalles,$esp,$disp,$marca,$precio,$precio_detalle,$docs,$multimedia,$pes,$json)=$this->fmt->query->obt_fila($rs);
			}
		}
		?>
		<form class="form form-modulo"  method="post" id="form-ajustes">
		<?php
			$this->fmt->form->on_off_form("Codigo","inputCodigo","mod_prod_conf_codigo","Codigo de producto",$codigo,"","form-group-on-off"); //$label,$id,$resumen,$valor,$class,$class_div
			$this->fmt->form->on_off_form("Codigo SAP","inputCodigoSAP","mod_prod_conf_sap","",$codigo_sap,"","form-group-on-off");
			$this->fmt->form->on_off_form("Modelo","inputModelo","mod_prod_conf_modelo","",$modelo,"","form-group-on-off");
			$this->fmt->form->on_off_form("Imagenes Avanzado","inputIa","mod_prod_conf_avanzado_img","",$aimg,"","form-group-on-off");
			$this->fmt->form->on_off_form("Detalles","inputDetalles","mod_prod_conf_detalles","",$detalles,"","form-group-on-off");
			$this->fmt->form->on_off_form("Especificaciones","inputEsp","mod_prod_conf_especificaciones","",$esp,"","form-group-on-off");
			$this->fmt->form->on_off_form("Disponibilidad","inputDis","mod_prod_conf_disponibilidad","",$disp,"","form-group-on-off");
			$this->fmt->form->on_off_form("Marca","inputMarca","mod_prod_conf_marca","",$marca,"","form-group-on-off");
			$this->fmt->form->on_off_form("Precio","inputPrecio","mod_prod_conf_precio","",$precio,"","form-group-on-off");
			$this->fmt->form->on_off_form("Precio detalle","inputPrecioDetalle","mod_prod_conf_precio_detalle","",$precio_precio_detalle,"","form-group-on-off");
			$this->fmt->form->on_off_form("Documentos","inputDocs","mod_prod_conf_docs","",$docs,"","form-group-on-off");
			$this->fmt->form->on_off_form("Multimedia","inputMul","mod_prod_conf_multimedia","",$multimedia,"","form-group-on-off");
			$this->fmt->form->on_off_form("Pestañas","inputPes","mod_prod_conf_pestana","",$pes,"","form-group-on-off");
			$this->fmt->form->on_off_form("JSON","inputJSON","mod_prod_conf_json","",$json,"","form-group-on-off");

		?>
		</form>
		<script type="text/javascript">
			$(document).ready(function() {
				$(".btn-on-off").click(function(){
					var valor = $(this).attr("valor");
					var campo = $(this).attr("campo");
					var id = $(this).attr("id");
					//alert(campo);
					var ruta='ajax-on-off';
					var id_mod =0;
					var variables="on-off,"+id+",mod_productos_conf,"+campo+","+valor;
					var datos= { ajax:ruta,inputIdMod:id_mod, inputVars:variables };
					$.ajax({
						url:"<?php echo _RUTA_WEB; ?>ajax.php",
						type:"post",
						data:datos,
						success: function(datos){
							//console.log(datos);
							var myarr = datos.split(",");
							var num = myarr.length;
							if (myarr[0]=="on-off"){

								if (myarr[4]=="1"){
									$("#"+myarr[1]+" i").removeClass();
									$("#"+myarr[1]+" i").addClass("icn icn-btn-on");
									$("#"+myarr[1]).attr("valor","0");
								}
								if (myarr[4]=="0"){
									$("#"+myarr[1]+" i").removeClass();
									$("#"+myarr[1]+" i").addClass("icn icn-btn-off");
									$("#"+myarr[1]).attr("valor","1");
								}
							}
						},
						complete : function() {}
					});
				});
			});
		</script>
		<?php
	}

	function form_nuevo(){

    $botones = $this->fmt->class_pagina->crear_btn_m("Volver","icn-chevron-left","volver","btn btn-link btn-menu-ajax",$this->id_mod,"busqueda");
    $this->fmt->class_pagina->crear_head_mod( "", "Nueva Pestaña",'',$botones,'col-xs-6 col-xs-offset-4');
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" enctype="multipart/form-data" method="POST" id="form_nuevo_pest">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre de la pestaña:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->hidden_modulo($this->id_mod,"ingresar");
        $this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','','3','','');
		$usuario = $this->fmt->sesion->get_variable('usu_id');
		$usuario_n =  $this->fmt->usuario->nombre_usuario( $usuario );
		$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
		$fecha =  date("Y-m-d H:i:s");
		$this->fmt->form->input_hidden_form("inputFecha",$fecha);
        $this->fmt->form->botones_nuevo("form_nuevo_pest");
        ?>
      </form>
    </div>
    <?php
    $this->fmt->class_modulo->script_form($this->ruta_modulo,"");
    $this->fmt->form->footer_page();
  }

  function form_editar(){
		$botones = $this->fmt->class_pagina->crear_btn_m("Volver","icn-chevron-left","volver","btn btn-link btn-menu-ajax",$this->id_mod,"busqueda");
    $this->fmt->class_pagina->crear_head_mod( "", "Editar Petaña",'',$botones,'col-xs-6 col-xs-offset-4');

		$id = $this->id_item;
		$sql="SELECT * from pestana	where pes_id='".$id."'";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					list($fila_id,$fila_nombre,$fila_descripcion,$fila_fecha,$fila_usuario,$fila_activar)=$this->fmt->query->obt_fila($rs);
				}
			}
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" enctype="multipart/form-data" method="POST" id="form_editar">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre de la pestaña:','inputNombre','',$fila_nombre,'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_hidden_form("inputId",$fila_id);
        $this->fmt->form->hidden_modulo($this->id_mod,"modificar");
        $this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$fila_descripcion,'','','3','','');
		$usuario = $this->fmt->sesion->get_variable('usu_id');
		$usuario_n =  $this->fmt->usuario->nombre_usuario( $usuario );
		$this->fmt->form->input_form_sololectura('Usuario:','','',$usuario_n,'','','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_hidden_form("inputUsuario",$usuario);
		$fecha =  date("Y-m-d H:i:s");
		$this->fmt->form->input_hidden_form("inputFecha",$fecha);
        $this->fmt->form->radio_activar_form($fila_activar);
		$this->fmt->form->botones_editar($fila_id,$fila_nombre,'form_editar',$this->id_mod);
        ?>
      </form>
    </div>
    <?php
    $this->fmt->class_modulo->script_form($this->ruta_modulo,"");
    $this->fmt->form->footer_page();
  }


	function ingresar(){

    if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}

    $ingresar ="pes_nombre,
				pes_descripcion,
				pes_fecha,
				pes_usuario,
				pes_activar";
    $valores_post  ="inputNombre, inputDescripcion,inputFecha,inputUsuario,inputActivar=".$activar;

    $this->fmt->class_modulo->ingresar_tabla('pestana',$ingresar,$valores_post);
		//$from,$filas,$valores_post
   $this->fmt->class_modulo->script_location($this->id_mod,"busqueda");

  }

	function modificar(){
		if ($_POST["estado-mod"]=="eliminar"){
		}else{

			$filas='pes_id,pes_nombre, pes_descripcion, pes_fecha, pes_usuario, pes_activar';
			$valores_post='inputId,inputNombre, inputDescripcion,inputFecha,inputUsuario,inputActivar';

			$this->fmt->class_modulo->actualizar_tabla('pestana',$filas,$valores_post); //$from,$filas,$valores_post
			$this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
		}
	}




	function eliminar(){
      $this->fmt->class_modulo->eliminar_get_id("pestana","pes_",$this->id_item);
      $this->fmt->class_modulo->script_location($this->id_mod,"busqueda");
    }

  function activar(){
      $this->fmt->class_modulo->activar_get_id("pestana","pes_",$this->id_estado,$this->id_item);
      $this->fmt->class_modulo->script_location($this->id_mod,"busqueda");

  }

  function busqueda_seleccion($modo,$valor){
	  	//var_dump($valor);
  		$this->fmt->form->head_modal('Busqueda Pestaña',$modo);  //$nom,$archivo,$id_mod,$botones,$id_form,$class,$modo)
  		$this->fmt->form->head_table('table_id_modal_aux');
		$this->fmt->form->thead_table('Nombre:Acciones');
		$this->fmt->form->tbody_table_open();

		$sql="SELECT * FROM pestana ORDER BY pes_id asc";
		$rs =$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
		if($num>0){
		  for($i=0;$i<$num;$i++){

		    list($fila_id,$fila_nombre,$fila_descripcion,$fila_fecha,$fila_usuario,$fila_activar)=$this->fmt->query->obt_fila($rs);
			$class_a ='';
			$class_do ='';
		    if (!empty($valor)){
				$num_v = count($valor);




				for ($j=0; $j<$num_v;$j++){
					if ( $fila_id ==$valor[$j]){
						$class_a ="on";
						$class_do ="on";
					}
				}
			}



		    //var_dump($fila);
		    	echo "<tr>";
				echo '<td class="fila-url"><strong>'.$fila_nombre.'</strong></td>';
				echo "<td class='acciones' id='dp-".$fila_id."'><a class='btn btn-agregar-pes ".$class_a."' value='".$fila_id."' id='bp-".$fila_id."' nombre='".$fila_nombre."' ><i class='icn-plus'></i> Agregar</a> <span class='agregado btp-".$fila_id." ".$class_do."'>Agregado</span></td>";
				echo "</tr>";
		    }
		}

		$this->fmt->form->tbody_table_close();
		$this->fmt->form->footer_table();
		$this->fmt->form->footer_page($modo);
	}

}
