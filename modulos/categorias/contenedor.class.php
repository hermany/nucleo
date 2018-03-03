<?php
header("Content-Type: text/html;charset=utf-8");

class CONTENEDOR{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function CONTENEDOR($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function editar_contenidos(){
			$this->fmt->header->title_page("Estructura de Contenidos");
		  $cat = $_GET["cat"];
	    $pla = $this->fmt->categoria->id_plantilla_cat($cat);
	    $nombre_cat=$this->fmt->categoria->nombre_categoria($cat);

			// $sql="SELECT mod_id_padre FROM modulos where mod_id=".$this->id_mod;
			// $rs=$this->fmt->query->consulta($sql,__METHOD__);
			// $fila=$this->fmt->query->obt_fila($rs);

			?>
			<link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/estilos.cont.css?reload" rel="stylesheet" type="text/css">
			<script type="text/javascript" src="<?php echo _RUTA_WEB_NUCLEO;?>js/jquery-ui.min.js"></script>
	    <div class="box-md-2 box-publicaciones">
		    <label><a class='btn-volver-o' vars="busqueda" href="<?php echo _RUTA_WEB."dashboard/estructura#nod-$cat"; ?>"><i class='icn-chevron-left'></i></a><i class="icn-block-page"></i>  <span><?php echo $nombre_cat; ?></span><a class='small' href='javascript:location.reload()'><i class='icn-sync'></i></a></label>
		    <div class="tipo-pub">
			    <div class="group-tabs">
						<div class="tab">
							<ul class="group">
						    <li role="presentation" class="active category"><a  id="tab-todos" idtab="todos" >Todos</a></li>
						    <li role="presentation" class="category"><a id="tab-elementales" idtab="elementales" >Elementales</a></li>
					    </ul>
						</div>
			    </div>
			    <div class="tab-content">
					<div  class="tab-content on" id="todos">
						<div class="box-buscador">
							<i class="icn icn-search"></i>
							<input id="filtrar" type="text" class="form-control" placeholder="Buscar publicación">
							<a target="_blank" title="Nueva publicación" href="<?php echo _RUTA_WEB; ?>dashboard/publicaciones" class="btn btn-full btn-small btn-new-pub"><i class="icn icn-rocket"></i></a>
						</div>
						<ul class="pub-all connectedSortable">
						<?php
							$sql="SELECT pub_id, pub_nombre, pub_imagen, pub_activar FROM publicacion order by pub_id desc";
							$rs=$this->fmt->query->consulta($sql,__METHOD__);
							$num=$this->fmt->query->num_registros($rs);

							if($num>0){
								for($i=0;$i<$num;$i++){
									//list($fila_id,$fila_nombre,$fila_imagen,$fila_activar)=$this->fmt->query->obt_fila($rs);
									$row =$this->fmt->query->obt_fila($rs);
									$fila_id = $row["pub_id"];
									$fila_nombre = $row["pub_nombre"];
									$fila_imagen = $row["pub_imagen"];
									$fila_activar = $row["pub_activar"];

									if(empty($fila_imagen)){
										$im = _RUTA_WEB_NUCLEO."images/pub-icon.png";
									}else{
										$im = _RUTA_WEB.$fila_imagen;
									}
									// echo "<li class='ui-state' pub='".$fila_id."' act='1'><img src='".$im."' class='ui-state-disabled' ><span>".$fila_nombre."</span></li>";
									$this->cargar_pub($fila_nombre,$fila_id,$fila_activar,$im);
								}
							}
						?>
						</ul>
					</div>
					<!-- <div  class="tab-content" id="elementales">
						<?php
						?>
					</div> -->
			    </div>
			</div>
	    </div>
	    <div class="box-md-9 box-estructura">
				<div class="box-header">
					<form class="form-vertical">
		        <div class="form-group box-plantilla">
		        <label for="select" class="control-label">Plantilla:</label>
		        <div class="box-md-6">
		          <select class="form-control" name="inputPlantilla" id="inputPlantilla">
		          <?php
		            $sql="select pla_id, pla_nombre from plantilla order by pla_id asc";
		            $rs=$this->fmt->query->consulta($sql,__METHOD__);
		          $num=$this->fmt->query->num_registros($rs);
		            if($num>0){
		              for($i=0;$i<$num;$i++){
		                //list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
		                $row=$this->fmt->query->obt_fila($rs);
										$fila_id = $row["pla_id"];
										$fila_nombre = $row["pla_nombre"];
		                $sl="";
		                if($pla==$fila_id)
		                  $sl="selected";
		          ?>
		            <option <?php echo $sl; ?> value="<?php echo $fila_id; ?>"><?php echo $fila_nombre; ?></option>
		           <?php
		              }
		             }
		           ?>
		          </select>
		        </div>
		      </div>
		      </form>
		      <ul class="pull-right">
		        <li><a id="act-cont" class="btn btn-info btn-small"><i class='icn-sync'></i> Actualizar</a></li>
		      </ul>
				</div>
				<div id="resp-cont"></div>
				<div id="contenedor-principal" class="jumbotron"></div>
	    </div>
	    <script>
	    $(document).ready(function(){
		    $("#inputPlantilla").change(function(){
			   cargarcontenedor($(this).val());
		    });

				$('#filtrar').keyup(function () {
          var rex = new RegExp($(this).val(), 'i');
          $('.pub-all li').hide();
          $('.pub-all li').filter(function () {
              return rex.test($(this).text());
          }).show();
        });

		    $("#act-cont").click(function(){
		    	var formdata = new FormData();
		    	var pla = $("#inputPlantilla").val();
		    	formdata.append("action","actualizar");
		    	formdata.append("cat", "<?php echo $cat;?>");
		    	formdata.append("pla", pla);
		    	formdata.append("ajax", "ajax-act-contenedor");
		    	$.each( $('#contenedor-principal'), function(i, cont) {
				   $('ul', cont).each(function() {
					   var con = $(this).attr("cont");
					   if(tienehijos(con)){
					   	formdata.append("cont[]", con);
						   $('#cont-'+con+'>li').each(function(){
							   var pub = $(this).attr("pub");
							   var act = $(this).attr("act");
							   formdata.append("pub"+con+"[]", pub);
							   formdata.append("act"+con+"[]", act);
						   });
					  	 }
				   	});
					});
					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
		    	$.ajax({
					url: ruta,
					type: "POST",
					data: formdata,
					processData: false,
					contentType: false,
					success: function(datos){
						//console.log(datos);
						// var datos = {ajax:"ajax-adm", inputIdMod:"<?php echo $this->id_mod; ?>" , inputVars : "editar_contenidos,<?php echo $this->id_item; ?>" };
						// abrir_modulo(datos);
						redireccionar_tiempo("<?php echo $this->ruta_modulo."/".$cat; ?>",100);
					}
				});
		    });

		    $("body").on('click', '.btn-editar-pub', function() {
		    	var pub = $(this).attr("pub");
		    	//event.preventDefault();
		    	//console.log("editar pub "+ pub);
		    	var datos = {ajax:"ajax-adm", inputIdMod:"<?php echo $this->id_mod; ?>" , inputVars : "form_editar,"+pub };
					abrir_modulo(datos);
		    });

	    });

			function abrir_modulo(datos){
					$(".modal-form").addClass("on");
					$(".modal-form").addClass("<?php echo $url_a; ?>");
					$(".body-page").css("overflow-y","hidden");
					//console.log(datos);

					$.ajax({
						url:"<?php echo _RUTA_WEB; ?>ajax.php",
						type:"post",
						data:datos,
						success: function(msg){

							$(".modal-form .modal-inner").html(msg);
							var wbm = $(".modal-form .modal-inner").height();
              var wbmx = wbm - 108;
              console.log("body-modulo:"+wbmx);
              $(".body-modulo").height(wbmx);

						},
						complete : function() {
							$('.preloader-page').fadeOut('slow');
							// var wmi =   $("#modal .modal-inner").width();
							// var hmi =   $("#modal .modal-inner").height();
							// var x_wmi = Math.round(wmi /2);
							// var y_hmi = Math.round(hmi /2);
							// $("#modal .modal-inner").css("margin-left","-"+x_wmi+"px");
							// $("#modal .modal-inner").css("margin-top","-"+y_hmi+"px");
						}
					});
				}

	    function tienehijos(con){
		    var num = $('#cont-'+con+'>li').length;
		    if(num>0)
		    	return true;
		    else
		    	return false;
	    }

	    function cargarcontenedor(pla){
	    	var dato = [{name:"action", value:"contenedor"},{name:"pla", value: pla},{name:"cat", value:"<?php echo $cat; ?>"},{name:"ajax", value:"ajax-act-contenedor"}];
	    	var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
		    $.ajax({
					url: ruta,
					type: "POST",
					data: dato,
					success: function(datos){
						$("#contenedor-principal").html(datos);
						$(".icn-trash").click(function(){
							var r = confirm("Esta seguro quitar esta publicación.?");
							if (r == true) {
								var pub = $(this).attr("pub");
								//alert(pub);
								$("#pub-"+pub).remove();
								$("#pub-c-"+pub).remove();
							}
						});


					}

				});

	    }
			//console.log("pla:<?php echo $pla; ?>");
	    cargarcontenedor("<?php echo $pla; ?>");
	    </script>
	    <?php
	}

  function cargar_pub($pub_nombre,$pub_id,$pub_activar,$im){
    echo "<li class='cnt-publicacion' pub='".$pub_id."' id='pub-".$pub_id."' act='1'>";
    echo "<img src='$im' /> <i class='btn-i icn-move btn-mover ui-state-disabled'></i> <span class='ui-state-disabled'>".$pub_nombre."</span>";
    echo "<span class='ui-state-disabled box-accion pull-right'>";
    echo "  <i class='btn-i icn-pencil btn-editar-i ui-state-disabled' idpub='".$pub_id."'  ></i>";
    if ($pub_activar==1){ $aux="icn-eye-open";}else{$aux="icn-eye-close";}
    echo "<i class='btn-i icn-eye $aux btn-activar-i ui-state-disabled' idpub='".$pub_id."'  ></i>";
		echo "<i class='btn-i icn-trash btn-eliminar-i ui-state-disabled' pub='".$pub_id."'  ></i>";
		echo "</span>";
    echo "</li>";
  }

  function form_editar(){
    $sql="select * from publicacion	where pub_id='".$this->id_item."'";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					$row=$this->fmt->query->obt_fila($rs);
					$fila_id= $row["pub_id"];
					$fila_nombre= $row["pub_nombre"];
					$fila_descripcion= $row["pub_descripcion"];
					$fila_imagen= $row["pub_imagen"];
					$fila_titulo= $row["pub_titulo"];
					$fila_tipo= $row["pub_tipo"];
					$fila_archivo= $row["pub_archivo"];
					$fila_archivo_config= $row["pub_archivo_config"];
					$fila_css= $row["pub_css"];
					$fila_clase= $row["pub_clase"];
					$fila_item= $row["pub_id_item"];
					$fila_numero= $row["pub_numero"];
					$fila_cat= $row["pub_id_cat"];
					$fila_activar= $row["pub_activar"];
				}
			}

		// $botones = $this->fmt->class_pagina->crear_btn_m("volver","icn-chevron-left","volver","btn btn-link btn-volver btn-menu-ajax ",$this->id_mod,"busqueda");

		//$this->fmt->class_pagina->crear_head_form("Editar Publicación", $botones,"","",$this->id_mod,"form_editar,".$fila_id);
		$this->fmt->class_pagina->crear_head_form("Editar Publicación");
		$id_form="form-editar";

		 //$nombre,$botones_left, $botones_right, $class_modo,$id_mod,$vars
		?>
		<div class="body-modulo col-xs-6 col-xs-offset-3">
			<form class="form form-modulo"  method="POST" id="<?php echo $id_form?>">
				<div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->

				<div class="form-group">
					<label>Nombre publicación:</label>
					<input class="form-control input-lg"  id="inputNombre" name="inputNombre" placeholder=" " value="<?php echo $fila_nombre; ?>" type="text" autofocus />
					<input id="inputId" name="inputId" type="hidden" value="<?php echo $fila_id; ?>" />
				</div>
				<div class="form-group form-descripcion">
					<label>Descripción:</label>
					<textarea class="form-control" rows="2" id="inputDescripcion" name="inputDescripcion" placeholder=""><?php echo $fila_descripcion; ?></textarea>
				</div>
				<div class="form-group">
					<label>Ruta Archivo:</label>
					<input class="form-control" id="inputArchivo" name="inputArchivo" placeholder="" value="<?php echo $fila_archivo; ?>"/>
				</div>
				<div class="form-group">
					<label>Ruta Archivo Config:</label>
					<input class="form-control" id="inputArchivoConfig" name="inputArchivoConfig" placeholder="" value="<?php echo $fila_archivo_config; ?>"/>
				</div>
				<div class="form-group">
					<label>Imagen:</label>
					<input class="form-control" id="inputImagen" name="inputImagen" placeholder="" value="<?php echo $fila_imagen; ?>"/>
				</div>
				<div class="form-group">
					<label>Titulo:</label>
					<input class="form-control" id="inputTitulo" name="inputTitulo" placeholder="" value="<?php echo $fila_titulo; ?>"/>
				</div>
				<div class="form-group">
					<label>Tipo:<?php //echo $fila_tipo; ?></label>
					<!-- <input class="form-control" id="inputTipo" name="inputTipo" placeholder="" value="<?php echo $fila_tipo; ?>" /> -->
						<select class="form-control form-select" name="inputTipo" id="inputTipo">
						<?php  echo $this->opciones_tipo($fila_tipo);  ?></select>
				</div>

				<div class="form-group">
					<label>Ruta Css:</label>
					<input class="form-control" id="inputUrlCss" name="inputUrlCss" placeholder="" value="<?php echo $fila_css; ?>"/>
				</div>
				<div class="form-group">
					<label>Clase:</label>
					<input class="form-control" id="inputClase" name="inputClase"  placeholder="" value="<?php echo $fila_clase; ?>" />
				</div>
				<div class="form-group">
					<label>Id Item:</label>
					<input class="form-control" id="inputItem" name="inputItem"  placeholder="" value="<?php echo $fila_item; ?>"/>
				</div>
				<div class="form-group">
					<label>Número/Items:</label>
					<input class="form-control" id="inputNumero" name="inputNumero"  placeholder="" value="<?php echo $fila_numero; ?>"/>
				</div>

				<!-- <div class="form-group">
					<label>Id categoria:</label>
					<input class="form-control" id="inputCat" name="inputCat"  placeholder="" value="<?php echo $fila_cat; ?>"/>
				</div> -->
				<?php
				$this->fmt->form->select_form_cat_id("Categoría:","inputCat",$fila_cat); //$label,$id,$id_item,$div_class

					$this->fmt->form->radio_activar_form($fila_activar);
					$this->fmt->form->btn_actualizar("form-editar",$this->id_mod,"modificar"); //$id_form,$id_mod,$tarea
				?>
					 <!-- <button type="submit" class="btn btn-info  btn-actualizar hvr-fade btn-lg color-bg-celecte-c btn-lg" name="btn-accion" id="btn-activar" value="actualizar"><i class="icn-sync" ></i> Actualizar</button> -->
				</div>
			</form>
		</div>
		<?php
		$this->fmt->class_modulo->modal_script($this->id_mod);
  }

  function tipo_publicacion($mod_tipo){

		switch ($mod_tipo) {
			case '0':
				$mod_tipo="0: Modulo Nucleo";
				break;
			case '1':
				$mod_tipo="1: Modulo de sitio";
				break;
			case '2':
				$mod_tipo="2: JSON / AJAX / Web Service";
				break;
			default:
				$mod_tipo="no definido";
				break;
		}
		return $mod_tipo;
	}

	function opciones_tipo($fila_tipo){
		$tipos = Array();
		for ($i = 0; $i <= 3; $i++) {
			$tipos [$i]= $this->tipo_publicacion($i);
		}

		for ($i = 0; $i <= 3; $i++) {
			$sel="";
			if ($fila_tipo==$i){
					$sel="selected";
			}
			$aux .='<option value="'.$i.'" '.$sel.' >'.$tipos[$i].'</option>';
		}
		return $aux;
	}

  function modificar(){
    if ($_POST["estado-mod"]=="eliminar"){
		}else{
		 $sql="UPDATE publicacion SET
						pub_nombre='".$_POST['inputNombre']."',
						pub_descripcion='".$_POST['inputDescripcion']."',
						pub_imagen ='".$_POST['inputImagen']."',
						pub_titulo='".$_POST['inputTitulo']."',
						pub_tipo='".$_POST['inputTipo']."',
						pub_archivo='".$_POST['inputArchivo']."',
						pub_archivo_config='".$_POST['inputArchivoConfig']."',
						pub_css='".$_POST['inputUrlCss']."',
						pub_clase='".$_POST['inputClase']."',
						pub_id_item='".$_POST['inputItem']."',
						pub_numero='".$_POST['inputNumero']."',
						pub_id_cat='".$_POST['inputCat']."',
						pub_activar='".$_POST['inputActivar']."'
	          WHERE pub_id='".$_POST['inputId']."'";

			$this->fmt->query->consulta($sql);
		}
		$this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
	}

}
?>
