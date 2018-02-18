<?php
header("Content-Type: text/html;charset=utf-8");

class CATALOGO_PRODUCTOS{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function CATALOGO_PRODUCTOS($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){

		?>
		<link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/m-catalogo.css?reload" rel="stylesheet" type="text/css">
		<div class="box-catalogo container-fluid">
			<div class="sidebar">
				<?php
				$consulta = "SELECT DISTINCT mod_catg_id,mod_catg_nombre,mod_catg_ruta_amigable,mod_catg_id_cat_arranque FROM mod_catalogo WHERE mod_catg_id_padre=0 and mod_catg_activar=1 ORDER BY mod_catg_orden";
				$rs = $this->fmt->query->consulta($consulta);
				$num= $this->fmt->query->num_registros($rs);
				if ($num>0){
				?>
				<label><h4><i class="icn icn-arrow-circle-right"></i> Catálogos</h4></label>
				<?php
					for($i=0;$i<$num;$i++){
						list($fila_id,$fila_nombre,$fila_ruta,$fila_ca)=$this->fmt->query->obt_fila($rs);
						?>
						<div class="title"><?php echo $fila_nombre; ?></div>
						<ul class="list-catalogo">
							<?php
							$consultax = "SELECT cat_id,cat_nombre,cat_ruta_amigable FROM mod_catalogo_categorias,categoria WHERE mod_catg_cat_catg_id='$fila_id' and mod_catg_cat_cat_id=cat_id ORDER BY 'categoria','cat_orden' ASC";
							$rsx = $this->fmt->query->consulta($consultax);
							$numx= $this->fmt->query->num_registros($rsx);
							if ($numx>0){
								for($j=0;$j<$numx;$j++){
									list($fila_idx,$fila_nombrex,$fila_rutax)=$this->fmt->query->obt_fila($rsx);
									echo "<li class='nivel-0'>";
									echo "<a class=''  href='"._RUTA_WEB."dashboard/catalogo-interno/".$fila_rutax."-catg' class='btn-item-cat-catalogo' id='item-cat-".$fila_idx."'>".$fila_nombrex."</a>";
									echo "</li>";
									if ($this->fmt->categoria->tiene_hijos_cat($fila_idx)){
										$this->hijos_cat($fila_idx,'0');
									}
								}
							}
							?>
						</ul>
						<?php
					}
				?>
				</ul>
				<?php } ?>
			</div>
			<div class="tbody catalogo-interno">
				<?php
				  $catg = $_GET["catg"];
				  if(!empty($catg)){
						$cat_prod=$this->fmt->categoria->traer_id_ruta_amigable($catg);
					}else{
						$cat_prod=  $fila_ca;
 					}
					//echo $cat_prod;
					?>
					<div class="header">
						<label for=""><h2><?php echo $this->fmt->categoria->nombre_categoria($cat_prod); ?></h2></label>
						<div class="box-buscar">
							<div class="buscador">
								<form id="form_id" class="" action="javascrit:void(0);" method="post">
									<i class="icn icn-search"></i>
									<input autocomplete="off" class="autocomplete-input clear-input clear-input-text ui-autocomplete-input autocomplete-input" data-field-type="product_auto" id="inputBuscar" name="inputBuscar" placeholder="Escribe el nombre de un producto o detalles" value="" type="text">
								</form>
							</div>
						</div><!-- fin box-buscar -->
					</div>
					<div class="box-productos">

			      <div id="container-item" class="box-items">
							<?php
							$cantidad_pag ='1200';
							$pag_inicio='0';
							$pag_fin=$cantidad_pag;

							if (!empty($_GET['pag']) && $_GET['pag']!=1 ){
								for ($i=1; $i<$_GET['pag'];$i++){
									$pag_inicio .= $pag_inicio + $cantidad_pag;
									$pag_fin .= $pag_fin + $cantidad_pag;
								}
								$npag = $_GET['pag'];
							}else{
								$npag =1;
							}

							$sql3="select DISTINCT mod_prod_id,mod_prod_nombre,mod_prod_ruta_amigable, mod_prod_imagen, mod_prod_id_dominio, mod_prod_tags, mod_prod_id_marca, mod_prod_detalles, mod_prod_precio FROM mod_productos_categorias, mod_productos where mod_prod_id=mod_prod_cat_prod_id and mod_prod_cat_cat_id='$cat_prod' and mod_prod_activar=1 order by mod_prod_cat_orden asc";
							$rs3=$this->fmt->query->consulta($sql3);
							$num_lim = $this->fmt->query->num_registros($rs3);
						    if($num_lim>0){
						    	for($i=0;$i<$num_lim;$i++){
						        	list($fila_id,$fila_nombre,$fila_ra,$fila_imagen, $fila_dominio,$fila_tags, $fila_id_marca, $fila_activar_cat)=$this->fmt->query->obt_fila($rs3);
									$ruta="";
									$cls_cat="";
									if($cat_amig!="")
										$ruta="/".$cat_amig;
									$url = $url_todos.$ruta."/".$fila_ra;
						            //if (empty($fila_dominio)){ $aux=_RUTA_WEB_temp; } else { $aux = $fmt->categoria->traer_dominio_cat_id($fila_dominio); }
									//$marca = TraerMarca($fila_id_marca,$fmt);

						            $img_1= _RUTA_IMAGES.$this->fmt->class_modulo->cambiar_tumb($fila_imagen);
												$img_1= _RUTA_IMAGES.$fila_imagen;
												//$img_1=$this->fmt->archivos->convertir_url_extension($img_1,"png");

						            echo "<div class='mix $filtro $marca' data-myorder='$fila_nombre'>";
						            echo "<a class='item btn-producto' cat='".$cat_prod."' prod='".$fila_id."' style='background:url($img_1) center center'>";
						            $color = $this->fmt->class_modulo->fila_modulo($cat_prod,"cat_color","categoria","cat_"); //$id,$fila,$from,$prefijo
						            if (!empty($color)){
							            $fila_color_cat = $color;
						            }
						            if($fila_activar_cat=="0")
						            	$cls_cat="no-cat";
												echo "</a>";
						            echo "<div class='cat $fila_ncat $cls_cat' style='background-color:".$fila_color_cat." !important'>".$fila_ncat."</div>";
						            echo "<div class='tags' style='display: none;'>".$fila_tags."</div>";
						            echo "<a class='nombre btn-producto' prod='$fila_id'><span class='title'>".$fila_nombre."</span><div class='bg'></div></a>";

						            echo "</div>";
						         }
						    }
							?>
						</div>
						<div class="modal-producto">
							<div class="modal-inner-producto">
								<a class='btn-cerrar-producto'><i class='icn icn-close'></i></a>
								<script src="<?php echo _RUTA_WEB_NUCLEO; ?>js/jquery.slides.min.js"></script>
								<script>
								  $(function(){
								    $("#slides").slidesjs();
								  });
								</script>

								<div class="slide-portada" id="slides">
									<?php
								$sql3x="select DISTINCT mod_prod_id,mod_prod_nombre,mod_prod_ruta_amigable, mod_prod_imagen, mod_prod_id_dominio, mod_prod_tags, mod_prod_id_marca, mod_prod_detalles, mod_prod_precio FROM mod_productos_categorias, mod_productos where mod_prod_id=mod_prod_cat_prod_id and mod_prod_cat_cat_id='$cat_prod' and mod_prod_activar=1 order by mod_prod_cat_orden asc";
										$rs3x=$this->fmt->query->consulta($sql3x);
										$num_limx = $this->fmt->query->num_registros($rs3x);
										if($num_limx>0){
											for($j=0;$j<$num_limx;$j++){
												list($fila_id,$fila_nombre,$fila_ra,$fila_imagen, $fila_dominio,$fila_tags, $fila_id_marca, $fila_resumen, $fila_precio)=$this->fmt->query->obt_fila($rs3x);
												$ruta="";
												echo "	<div class='block-img'  style='background:url("._RUTA_IMAGES.$fila_imagen.") no-repeat center center'  id='prod-$fila_id'>";
												echo "	<div class='texto'>";
												echo "		<h4>".$fila_nombre."</h4>";
												echo "		<span>".$fila_resumen."</span>";
												if (!empty($fila_precio)){
													echo "		<span class='precio'>Bs. ".$fila_precio."</span>";
												}
												echo "	</div>";
												?>
												<div class="box-comprar">
													<a class="btn-comprar" prod="<?php echo $fila_id; ?>"><i class="icn icn-cart"></i></a>
													<div class="form-comprar" style="display:none" id="form-comprar-<?php echo $fila_id; ?>">
														<form class="form-c"   method="post">
															<?php
															echo $fila_id.":"._USU_ID."</br>";
															$fecha=$this->fmt->class_modulo->fecha_hoy('America/La_Paz');
															$this->fmt->form->input_date_form('','inputFecha','',$fecha,'','form-vertical','');//$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
															$this->fmt->form->input_hidden_form("inputProd",$fila_id);
															$this->fmt->form->input_hidden_form("inputUsuario",_USU_ID);
															$this->fmt->form->input_form('Comentario:','inputComentario');
															$this->fmt->form->input_form('Cantidad:','inputCantidad');
															?>
															<div class="botones">
																<a class="btn btn-full btn-comprar-cancelar" prod="<?php echo $fila_id; ?>" >Cancelar</a>
																<a class="btn btn-success btn-comprar-guardar" prod="<?php echo $fila_id; ?>">Guardar</a>
															</div>
														</form>
														<script type="text/javascript">
															$(document).ready(function() {
																$(".btn-comprar").click(function(event) {
																	var id = $(this).attr("prod");
																	$("#form-comprar-"+id ).addClass('on');
																});
																$(".btn-comprar-cancelar").click(function(event) {
																	var id = $(this).attr("prod");
																	$("#form-comprar-"+id).removeClass('on');
																});
																$(".btn-comprar-guardar").click(function(event) {
																	var id = $(this).attr("prod");
																	$("#form-comprar-"+id).removeClass('on');
																});
															});
														</script>
													</div>
												</div>
												<?php
												echo "	</div>";
											}
										}
									?>
								</div>

							</div>
						</div>
					</div><!-- fin box-productos -->
					<div class="pager-list"></div>
					<?php
						if ($num>$cantidad_pag){
							$urlp= $url_todos;

							echo "<div class='box-paginacion'><div class='container'>";

							$num_ini='1';
							$num_fin= ceil($num/$cantidad_pag);

							echo "<div class='list-paginacion'>Página: <span>$num_ini</span> de <span>".$num_fin."</span>  cp: $num </div>";

							echo "<div class='list-paginacion-nav'>";
							if ($pag_inicio==0){
							$ps =$num_ini + 1;
							$aux_s = "<a href='".$urlp."/pag=$ps' class='btn btn-siguiente'>siguiente</a>";
							$aux_a = "";
							}else{
							$ps = $npag + 1 ;
							$pa =$npag - 1 ;
							if ($npag!=$num_fin){
							$aux_s = "<a href='".$urlp."/pag=$ps' class='btn btn-siguiente'>siguiente</a>";
							}
							$aux_a = "<a href='".$urlp."/pag=$pa' class='btn btn-siguiente'>anterior</a>";
							}

							for ($j=$num_ini;$j<=$num_fin;$j++){
							if ($npag==$j){  $ab='active'; }else{  $ab=''; }
							$aux_n .=" <a href='".$urlp."/pag=$j' class='btn btn-num btn-n-$j  $ab'> $j </a> ";
						}
						echo $aux_a." ".$aux_n." ".$aux_s;
						echo "</div>";

						echo "</div></div>";
					}
					?>
					<script type="text/javascript">
						$(document).ready(function() {
							$(".box-marca").click(function(){
								scrollprod();
							});
							$(".btn-prod-cat").click(function(){
								scrollprod();
							});

							$(".btn-producto").click(function(){
								$(".modal-producto").addClass('on');
								$(".block-img").addClass('off');
								var id = $(this).attr("prod");
								console.log(id)
								$("#prod-"+id).addClass('active');
							});
							$(".btn-cerrar-producto").click(function(){
								$(".modal-producto").removeClass('on');
								$(".block-img").removeClass('off');
								$(".block-img").removeClass('active');
							});

							// $('#container-item').mixItUp({
							//   load: {
							// 	  page: 1
							//   },
							//   pagination: {
							// 		limit: 1200
							// 	}
						  // });
						  // var inputText;
						  // var $matching = $();
						  // var delay = (function(){
						  //   var timer = 0;
						  //   return function(callback, ms){
						  //     clearTimeout (timer);
						  //     timer = setTimeout(callback, ms);
						  //   };
						  // })();

							// $("#inputBuscar").keyup(function(){
						  //   delay(function(){
						  //     inputText = $("#inputBuscar").val().toLowerCase();
							//
						  //     if ((inputText.length) > 0) {
						  //       $( '.mix').each(function() {
						  //         $this = $("this");
							//
						  //         var item = $(this).children(".item");
							//
						  //         if($(this).attr('data-myorder').toLowerCase().match(inputText)) {
						  //           $matching = $matching.add(this);
						  //         }
						  //         else if(item.children(".cat").html().toLowerCase().match(inputText)) {
						  //           $matching = $matching.add(this);
						  //         }
						  //         else if(item.children(".tags").html().toLowerCase().match(inputText)) {
						  //           $matching = $matching.add(this);
						  //         }
						  //         else{
						  //           $matching = $matching.not(this);
						  //         }
						  //       });
						  //       $("#container-item").mixItUp('filter', $matching);
						  //     }
							//
						  //     else {
						  //       $("#container-item").mixItUp('filter', 'all');
						  //     }
						  //   }, 200 );
						  // });

							$('#inputBuscar').keyup(function () {
								var rex = new RegExp($(this).val(), 'i');
								$('.box-items .mix').hide();
								$('.box-items .mix').filter(function () {
										return rex.test($(this).text());
								}).show();
							});

							function scrollprod(){
								anc=$("#container-item").offset().top-55;
							  $('html, body').animate({scrollTop: anc }, 1000);
						  }
						}); //fin document
					</script>
			</div>
			<script type="text/javascript">
				$(document).ready(function() {
					$(".slidesjs-next").append("<i class='icn icn-navg icn-chevron-right'></i>");
					$(".slidesjs-previous").append("<i class='icn icn-navg icn-chevron-left'></i>");
				});

			</script>
		</div>
		<?php
  }

	function hijos_cat($cat,$nivel){
		$consultax = "SELECT cat_id,cat_nombre,cat_ruta_amigable FROM categoria WHERE cat_id_padre='$cat' ORDER BY 'categoria','cat_orden' ASC";
		$rsx = $this->fmt->query->consulta($consultax);
		$numx= $this->fmt->query->num_registros($rsx);
		$nivel++;
		if ($numx>0){
			for($j=0;$j<$numx;$j++){
				list($fila_idx,$fila_nombrex,$fila_rutax)=$this->fmt->query->obt_fila($rsx);
				echo "<li class='nivel-$nivel'><i class='icn icn-point-4'></i>";
				echo "<a   href='"._RUTA_WEB."dashboard/catalogo-interno/".$fila_rutax."-catg' class='btn-item-cat-catalogo' id='item-cat-".$fila_idx."'>".$fila_nombrex."</a>";
				echo "</li>";
				if ($this->fmt->categoria->tiene_hijos_cat($fila_idx)){
					$this->hijos_cat($fila_idx,$nivel);
				}
			}
		}
	}

}
