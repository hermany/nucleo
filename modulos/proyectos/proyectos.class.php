<?php
header("Content-Type: text/html;charset=utf-8");

class PROYECTO{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;
	var $est_boff='<i class="icn icn-tag est-boff"></i><span>Back Off</span>';
	var $est_ini='<i class="icn icn-tag est-ini"></i><span>Iniciado</span>';
	var $est_pro='<i class="icn icn-tag est-pro"></i><span>En proceso</span>';
	var $est_stb='<i class="icn icn-tag est-stb"></i><span>Stanby</span>';
	var $est_can='<i class="icn icn-tag est-can"></i><span>Cancelado</span>';
	var $est_ter='<i class="icn icn-tag est-ter"></i><span>Terminado</span>';

	function PROYECTO($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){

		$this->fmt->class_pagina->crear_head( $this->id_mod,$botones,"","","m-proyectos.css");
		$this->fmt->class_pagina->head_mod();

		$botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nuevo Punto","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
    $this->fmt->class_pagina->head_modulo_inner("Lista de Proyectos", $botones); // bd, id modulo, botones

    $this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Nombre:Estado:Cliente:Activar:Acciones');
    $this->fmt->form->tbody_table_open();

  	$consulta = "SELECT * FROM mod_proyecto";
  	$rs =$this->fmt->query->consulta($consulta);
  	$num=$this->fmt->query->num_registros($rs);

  	if($num>0){
  		for($i=0;$i<$num;$i++){
  			$row=$this->fmt->query->obt_fila($rs);
  			$row_id = $row["mod_proy_id"];
  			$row_nombre = $row["mod_proy_nombre"];
  			$row_estado = $row["mod_proy_estado"];
  			$row_activar = $row["mod_proy_activar"];

				echo "<tr class='row row-".$row_id."'>";
				echo '  <td class="col-id">'.$row_id.'</td>';
				echo '  <td class="col-name">'.$row_nombre.'</td>';
				echo '  <td class="col-estado"><a class="btn-cambiar-estado" item="'.$row_id.'" estado="'.$row["mod_proy_estado"].'">'.$this->estado($row["mod_proy_estado"]).'</a></td>';
				echo '  <td class="col-cliente">';
				echo $this->traer_cliente_proy($row_id);
				echo '</td>';
				echo '  <td class="col-activar">';
				 $this->fmt->class_modulo->estado_publicacion($row_activar,$this->id_mod,"",$row_id);
				echo '	</td>';
				echo '  <td class="col-acciones acciones">';
				$this->fmt->class_modulo->botones_tabla($row_id,$this->id_mod,$row_nombre);//
				echo '	</td>';
  		}
  	}
  	$this->fmt->query->liberar_consulta();
		
		$this->fmt->form->tbody_table_close();
    $this->fmt->form->footer_table();
    $this->fmt->class_pagina->footer_mod();

    ?>
    <div class="box-selector-estados">
    	<label for="">Estados</label>
    	<a class="list-estado" estado='0' item=''><?php echo $this->est_boff; ?></a>
    	<a class="list-estado" estado='1' item=''><?php echo $this->est_ini; ?></a>
    	<a class="list-estado" estado='2' item=''><?php echo $this->est_pro; ?></a>
    	<a class="list-estado" estado='3' item=''><?php echo $this->est_stb; ?></a>
    	<a class="list-estado" estado='4' item=''><?php echo $this->est_can; ?></a>
    	<a class="list-estado" estado='5' item=''><?php echo $this->est_ter; ?></a>
    </div>

    <script type="text/javascript">
    	$(document).ready(function() {
    		$('.btn-cambiar-estado').click(function(event) {
    			/* Act on the event */
    			var item = $(this).attr("item");
    			var estado = $(this).attr("estado");
    			var position = $(this).offset();
    			
    			$(".box-selector-estados").addClass('on');
    			$(".box-selector-estados .list-estado").removeClass('active');
    			$(".box-selector-estados .list-estado[estado="+ estado +"]").addClass('active');
    			$(".box-selector-estados").css("top",position.top -25);
    			$(".box-selector-estados").css("left",position.left );
    			$(".box-selector-estados .list-estado").attr('item',item);
    			//console.log(estado);
    		});

    		$('.box-selector-estados').mouseleave(function(event) {
    			/* Act on the event */
    			$(".box-selector-estados").removeClass('on');
    			$(".box-selector-estados .list-estado").removeClass('active');
    			$(".box-selector-estados .list-estado").attr("item","");
    		}) 

    		function estados(estado){
    			var est=['<?php echo $this->est_boff;?>','<?php echo $this->est_ini; ?>','<?php echo $this->est_pro; ?>','<?php echo $this->est_stb; ?>','<?php echo $this->est_can; ?>','<?php echo $this->est_ter; ?>'];
    			return est[estado] ;
    		}

    		$(".list-estado").click(function(event) {
    			var item = $(this).attr("item");
    			var estado = $(this).attr("estado");

    			$(".box-selector-estados").removeClass('on');
    			$(".box-selector-estados .list-estado").removeClass('active');
			  	$(".btn-cambiar-estado[item="+item+"]").html("<div class='loading on'></div>");
			  	$(".btn-cambiar-estado[item="+item+"]").addClass('disabled');

    			var ruta_ajax="ajax-proyectos-estados";
          var variables = item+","+estado;
          var datos = {ajax:ruta_ajax, inputIdMod:<?php echo $this->id_mod; ?> , inputVars : variables };

          var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";   
          $.ajax({ 
            url:ruta,
            type:"post",  
            async: true,   
            data:datos, 
            beforeSend: function () {},
            success:function(msg){  
               console.log(msg);
               var dat = msg.split(":");
               $('.btn-cambiar-estado[item='+dat[0]+']').attr("estado",dat[1]);
               $('.btn-cambiar-estado[item='+dat[0]+']').removeClass('disabled');
               var est = estados(dat[1]);
               $('.btn-cambiar-estado[item='+dat[0]+']').html(est);
            },
            complete: function(){}
          });
    		});

    		

    	});
    </script>
    <?php
		
		
    $this->fmt->class_modulo->script_table("table_id",$this->id_mod,"asc","3","25",true);
		$this->fmt->class_modulo->script_accion_modulo();
	} //fin buscqueda

	public function proyectos_activos(){
		$consulta = "SELECT mod_cli_proy_id,mod_cli_proy_nombre, mod_cli_proy_logo, mod_cli_proy_codigo,mod_cli_proy_etiqueta, mod_proy_id, mod_proy_nombre FROM mod_cliente_proyectos,mod_proyecto_clientes,mod_proyecto WHERE mod_proy_cli_proy_id=mod_proy_id and mod_proy_cli_cli_id = mod_cli_proy_id ORDER BY mod_proy_cli_orden ASC";
		$rs =$this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);
		$aux ="";
		if($num>0){
			for($i=0;$i<$num;$i++){
				$row=$this->fmt->query->obt_fila($rs);
				$row_id = $row["mod_cli_proy_id"];
				$row_nombre = $row["mod_cli_proy_nombre"];
				$id_proy = $row["mod_proy_id"];
				$row_nombre_proy = $row["mod_proy_nombre"];
				$row_codigo = $row["mod_cli_proy_codigo"];
				$row_logo = $row["mod_cli_proy_logo"];
				$logo = _RUTA_IMAGES.$this->fmt->archivos->url_add($row_logo,"-mini");
				if (!empty($row_logo)){
					$row_codigo="";
				}
				$row_etiqueta = $row["mod_cli_proy_etiqueta"];

				$aux .= "<div class='proy' proy='$id_proy' nombre='$row_nombre_proy' logo='$logo' codigo='$row_codigo'><span class='logo border-e-$row_etiqueta' style='background:url($logo) no-repeat center center'>$row_codigo</span> <span class='cliente'>$row_nombre</span> <span class='nombre-proy'>$row_nombre_proy</span></div>";
			}
		}

		return $aux;
		$this->fmt->query->liberar_consulta($rs);

		//return $consulta = "SELECT mod_proy_id, mod_proy_nombre, mod_cli_proy_logo FROM mod_proyecto WHERE mod_proy_estado='1' or mod_proy_estado='2' or mod_proy_estado='3' and mod_proy_activar='1' and mod_proy_cli_proy_id=proy_id and mod_proy_cli_cli_id = mod_cli_proy_id ORDER BY mod_proy_cli_orden ASC";
  	// $rs =$this->fmt->query->consulta($consulta);
  	// $num=$this->fmt->query->num_registros($rs);
  	// $aux="";
  	// if($num>0){
  	// 	for($i=0;$i<$num;$i++){
  	// 		$row=$this->fmt->query->obt_fila($rs);
  	// 		$row_id = $row["mod_proy_id"];
  	// 		$row_nombre = $row["mod_proy_nombre"];
  	// 		$logo =  $row["mod_cli_proy_logo"];
  	// 		$aux="<div class='proy' proy='$row_id'><span class='logo' style='background:url($logo) no-repeat center center'></span> - $row_nombre</div>";
  	// 	}
  	// }
  	// return $aux;
  	// $this->fmt->query->liberar_consulta($rs);
	}

	public function traer_cliente_proy($id){
		$consulta = "SELECT mod_cli_proy_id,mod_cli_proy_nombre, mod_cli_proy_logo, mod_cli_proy_codigo, mod_cli_proy_etiqueta FROM mod_cliente_proyectos,mod_proyecto_clientes WHERE mod_proy_cli_proy_id='$id' and mod_proy_cli_cli_id = mod_cli_proy_id ORDER BY mod_proy_cli_orden ASC";
		$rs =$this->fmt->query->consulta($consulta);
		$num=$this->fmt->query->num_registros($rs);
		$aux ="";
		if($num>0){
			for($i=0;$i<$num;$i++){
				$row=$this->fmt->query->obt_fila($rs);
				$row_id = $row["mod_cli_proy_id"];
				$row_nombre = $row["mod_cli_proy_nombre"];
				$row_logo = $row["mod_cli_proy_logo"];
				$row_codigo = $row["mod_cli_proy_codigo"];
				$row_etiqueta = $row["mod_cli_proy_etiqueta"];
				$aux .= "<div class='etiqueta-cliente'><span class='etiqueta etiqueta-$row_etiqueta'>$row_etiqueta </span>".$row_nombre."</div>";
			}
		}
		return $aux;
		$this->fmt->query->liberar_consulta($rs);
	}

	public function estado($id){
		switch ($id) {
			case '0':
				return $this->est_boff;
				break;			
			case '1':
				return $this->est_ini;
				break;			
			case '2':
				return $this->est_pro;
				break;			
			case '3':
				return $this->est_stb;
				break;			
			case '4':
				return $this->est_can;
				break;			
			case '5':
				return $this->est_ter;
				break;
		}
	}

	public function form_nuevo(){
		$this->fmt->class_pagina->crear_head_form("Nuevo Proyecto","","");
		$id_form="form-nuevo";
		$this->fmt->class_pagina->head_form_mod();
		$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');

		$this->fmt->class_pagina->form_ini_mod($id_form,"form-proyectos");

		$this->fmt->form->input_form("* Nombre:","inputNombre","","","input-lg","","","",""); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros
		$this->fmt->form->input_form("Descripción:","inputDescripcion","","");

		$this->fmt->form->input_form("Horas estimadas:","inputHoras","","");


		$this->fmt->form->input_form_date('{
				"label":"Fecha Inicio:",
				"id":"inputInicio",
				"format":"dd-mm-yyyy",
				"fecha":"'.$fecha.'"
		}');		
		$this->fmt->form->input_form_date('{
				"label":"Fecha Fin:",
				"id":"inputFin",
				"format":"dd-mm-yyyy",
				"fecha":"'.$fecha.'"
		}');

		$this->fmt->form->nodo_form('{
																	"label":"Cliente:",
																	"id":"inputCliente",
																	"id_raiz":"0",
																	"valores":",mod_proyecto_clientes,mod_proy_cli_cli_id,mod_proy_cli_proy_id",
																	"from":"mod_cliente_proyectos",
																	"tipo":"lineal",
																	"orden":"etiqueta",
																	"prefijo":"mod_cli_proy_"
																}');

		// $this->fmt->form->arbol_editable_nodo('mod_lista','mod_list_','0',$this->id_mod);

		$this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_editor_texto("inputInfo");
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} //fin form_nuevo

	function form_editar(){
		$this->fmt->class_pagina->crear_head_form("Editar Proyecto","","");
		$id_form="form-editar";

		$id = $this->id_item;
		$consulta= "SELECT * FROM mod_proyecto WHERE mod_proy_id='".$id."'";
	  $rs =$this->fmt->query->consulta($consulta);
	  $row=$this->fmt->query->obt_fila($rs);

		$this->fmt->class_pagina->head_form_mod();
		$this->fmt->class_pagina->form_ini_mod($id_form,"form-proyectos");
		
		// $this->fmt->form->hidden_modulo($this->id_mod,"modificar");
		$this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","",$row["mod_proy_nombre"],"input-lg","","");
		$this->fmt->form->input_hidden_form("inputId",$id);

		$this->fmt->form->input_form("Descripción:","inputDescripcion","",$row["mod_proy_descripcion"]);
		$this->fmt->form->input_form("Horas estimadas:","inputHoras","",$row["mod_proy_horas"]);

		$this->fmt->form->input_form_date('{
				"label":"Fecha Inicio:",
				"id":"inputInicio",
				"format":"dd-mm-yyyy",
				"fecha":"'.$row["mod_proy_fecha_inicio"].'"
		}');		
		$this->fmt->form->input_form_date('{
				"label":"Fecha Fin:",
				"id":"inputFin",
				"format":"dd-mm-yyyy",
				"fecha":"'.$row["mod_proy_fecha_fin"].'"
		}');

		$this->fmt->form->nodo_form('{
																	"label":"Cliente:",
																	"id":"inputCliente",
																	"id_raiz":"0",
																	"valores":"'.$row["mod_proy_id"].',mod_proyecto_clientes,mod_proy_cli_cli_id,mod_proy_cli_proy_id",
																	"from":"mod_cliente_proyectos",
																	"tipo":"lineal",
																	"orden":"etiqueta",
																	"prefijo":"mod_cli_proy_"
																}');
		

		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
		$this->fmt->class_pagina->form_fin_mod();
		$this->fmt->class_pagina->footer_form_mod();
		
		$this->fmt->finder->finder_window();
		$this->fmt->class_modulo->modal_script($this->id_mod);
	} // fin form_editar



	public function ingresar(){
		if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
		$ingresar ="mod_proy_nombre,mod_proy_descripcion,mod_proy_estado,mod_proy_fecha_registro,mod_proy_fecha_inicio,mod_proy_fecha_fin,mod_proy_horas,mod_proy_activar";

		$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');

		$valores  ="'".$_POST['inputNombre']."','".
					$_POST['inputDescripcion']."','0','".
					$fecha."','".
					$_POST['inputHoras']."','".
					$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputInicio'])."','".
					$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputFin'])."','".
					$activar."'";
		$sql="insert into mod_proyecto (".$ingresar.") values (".$valores.")";
		$this->fmt->query->consulta($sql);

		$sql="select max(mod_proy_id) as id from mod_proyecto";
		$rs= $this->fmt->query->consulta($sql);
		$fila = $this->fmt->query->obt_fila($rs);
		$id = $fila ["id"];

		$ingresar1 ="mod_proy_cli_proy_id, mod_proy_cli_cli_id, mod_proy_cli_orden";
		$valor_cat= $_POST['inputCliente'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$id."','".$valor_cat[$i]."','".$i."'";
			$sql1="insert into mod_proyecto_clientes (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	} // fin ingresar


	public function modificar(){

		echo $sql="UPDATE mod_proyecto SET
						mod_proy_nombre='".$_POST['inputNombre']."',
						mod_proy_descripcion ='".$_POST['inputDescripcion']."',
						mod_proy_horas ='".$_POST['inputHoras']."',
						mod_proy_fecha_inicio ='".$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputInicio'])."',
						mod_proy_fecha_fin ='".$this->fmt->class_modulo->desestructurar_fecha_hora($_POST['inputFin'])."'
						WHERE mod_proy_id='".$_POST['inputId']."'";
		$this->fmt->query->consulta($sql);

		$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"mod_proyecto_clientes","mod_proy_cli_proy_id");

		$ingresar1 ="mod_proy_cli_proy_id, mod_proy_cli_cli_id, mod_proy_cli_orden";
		$valor_cat= $_POST['inputCliente'];
		$num=count( $valor_cat );
		for ($i=0; $i<$num;$i++){
			$valores1 = "'".$_POST['inputId']."','".$valor_cat[$i]."','".$i."'";
			$sql1="insert into mod_proyecto_clientes (".$ingresar1.") values (".$valores1.")";
			$this->fmt->query->consulta($sql1);
		}

		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}

} //fin clase