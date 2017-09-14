<?php
header("Content-Type: text/html;charset=utf-8");
require_once(_RUTA_NUCLEO."clases/class-constructor.php");
$fmt = new CONSTRUCTOR;
// $fmt->get->validar_get ( $_GET['prod'] );
// $id = $_GET['prod'];
// $sql="SELECT * FROM mod_productos WHERE mod_prod_id='$id'";
// $rs =$fmt->query->consulta($sql);
// $fila=$fmt->query->obt_fila($rs);
// $j=0;

if($fila["mod_prod_imagen"]!=""){
	$img_ax=$fila["mod_prod_imagen"];

	$archivo=_RUTA_IMAGES.$img_ax;
	$archivo_ax=$fmt->archivos->convertir_url_extension($img_ax,"png");
	$imagenFile	= $fmt->archivos->convertir_nombre_thumb($archivo);
	$filepng = $fmt->archivos->convertir_nombre_thumb($archivo_ax);
	$ruta = _RUTA_HT.$filepng;
	if(file_exists($ruta))
		$imagenFile=_RUTA_IMAGES.$filepng;
	$thumb[$j]= "<a class='mul-prod gal_".$j."' nvl='".$j."' tipo='imagen' arch='$archivo' >";
	$thumb[$j].= "<div class='mul-thumb' style='background:url(".$imagenFile.")'>";
	// $thumb[$j].= '<img class="img-responsive" src="'.$imagenFile.'">';
	$thumb[$j].= '<div class="tmbact thumb-'.$j.'"></div>';
	$thumb[$j].= "</div>";
	$thumb[$j].= "</a>";
	$j++;
}
$sql="SELECT DISTINCT mul_url_archivo FROM mod_productos_mul,multimedia WHERE mod_prod_mul_prod_id=".$fila['mod_prod_id']." and mod_prod_mul_mul_id=mul_id ORDER BY mod_prod_mul_orden asc";

					$rs=$fmt->query->consulta($sql);
					$num =$fmt->query->num_registros($rs);

					if ($num>0){
						echo "<div class='box-info box-multimedia no-print'><label>Multimedia</label>";
						echo "<div class='bloque'>";

						for($i=0;$i<$num;$i++){
							list($fila_archivo, $fila_dominio)=$fmt->query->obt_fila($rs);

							if (empty($fila_dominio)){ $aux=_RUTA_IMAGES; } else { $aux = $fmt->categoria->traer_dominio_cat_id($fila_dominio); }
							$ext=$fmt->archivos->saber_extension_archivo($fila_archivo);
							$archivo = $aux . $fila_archivo;
							switch ($ext) {
							    case "mp4":
							       	$nombre_archivo=$fmt->archivos->saber_nombre_archivo($fila_archivo);
									$magenfile = $aux ."archivos/multimedia/". $nombre_archivo . ".jpg";
									$imagenFile= $fmt->archivos->convertir_nombre_thumb($magenfile);
									$type="video";
							        break;
							    case "mp3":

							        $imagenFile = _RUTA_WEB_NUCLEO."images/icon-audio.png";
									$type="music";
							        break;
							    default:

							        $imagenFile= $aux. $fmt->archivos->convertir_nombre_thumb($fila_archivo);
									$type="imagen";
							        break;
							}



							$thumb[$j]= "<a class='mul-prod m-$type gal_".$j."' nvl='".$j."' tipo='$type' arch='$archivo' >";
							$thumb[$j].= "<div class='$type mul-thumb' style='background:url(".$imagenFile.")'>";
							//$thumb[$j].= '<img class="img-responsive" src="'.$imagenFile.'">';
							if ($ext=="mp4"){
								$thumb[$j].= '<div class="tmbact thumb-'.$j.' tbm-video"><i class="icn icn-arrow-o-left fa fa-play-circle"></i></div>';
							}else{
								$thumb[$j].= '<div class="tmbact thumb-'.$j.' "></div>';
							}
							$thumb[$j].= "</div>";
							$thumb[$j].= "</a>";
							$j++;
						}
						for($i=0;$i<count($thumb);$i++){
							echo $thumb[$i];
						}
						echo "</div>";
						echo "</div>";
					}


					?>
<div class="gallerie-overlay" style="display: none;">
	<div class="close-mul"><i class="fa fa-close"></i></div>
	<div class="gallerie-imagebox"></div>
	<div class="gallerie-captionbox">
		<div class="gallerie-control gallerie-control-previous"><i class="fa fa-chevron-circle-left"></i></div>
		<div class="gallerie-control gallerie-control-next"><i class="fa fa-chevron-circle-right"></i></div>
	</div>
	<div class="gallerie-thumbbox">
		<ul>
		<?PHP
			for($i=0;$i<count($thumb);$i++){
				echo "<li>";
					echo $thumb[$i];
				echo "</li>";
			}
		?>

		</ul>
	</div>
</div>
<script>
$(document).ready(function(){
	$(".mul-prod").click(function(){
		$(".gallerie-overlay").css("display","block");
		cargarmul($(this));
	});
	$(".img-producto").click(function(){
		var gal = $(".gal_0");
		$(".gallerie-overlay").css("display","block");
		cargarmul(gal);
	})
	$(".close-mul").click(function(){
		$(".gallerie-overlay").css("display","none");
	});
	$(".gallerie-control-previous").click(function(){
		var cls=$(".active").attr("nvl");
		var numItems = $('li .mul-prod').length;

		if(cls!=0){
			cls--;
			cargarmul($(".gal_"+cls));
		}
		else{
			cargarmul($(".gal_"+(numItems-1)));
		}
	});
	$(".gallerie-control-next").click(function(){
		var cls=$(".active").attr("nvl");
		var numItems = $('li .mul-prod').length;
		if(cls<(numItems-1)){
			cls++;
			cargarmul($(".gal_"+cls));
		}
		else{
			cargarmul($(".gal_0"));
		}
	});

	$(".video").before("<i class='b-video fa fa-play-circle'></i>");
	$(".gallerie-overlay").click( function(){
		//$(".gallerie-overlay").css("display","none");
	});

});
function cargarmul(gal){
	var tipo = gal.attr("tipo");
		var arch = gal.attr("arch");
		var cls = gal.attr("nvl");

		switch (tipo){
			case "imagen":
				$(".gallerie-imagebox").html('<img class="gallerie-image" src="'+arch+'" title=""  >');
				break;
			case "video":
				$(".gallerie-imagebox").html('<video  controls><source src="'+arch+'" type="video/mp4">tu navegador no soporta este formato.</video>');
				break;
			case "music":
				$(".gallerie-imagebox").html('<audio controls><source src="'+arch+'" type="audio/mpeg">tu navegador no soporta este formato</audio>');
				break;
		}
		$(".tmbact").html("");
		$(".mul-prod").removeClass("active");
		$("li .thumb-"+cls).html("En curso");
		$("li .gal_"+cls).addClass("active");
}
</script>
<style>
.mul-prod {
    width: 110px;
    height: 110px;
    position: relative;
    float: left;
}
.gallerie-overlay {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.9);
    z-index: 1000;
}
.gallerie-imagebox {
    width: 100%;
    height: 70%;
    position: relative;
    top: 50px;
    left: 0px;
    margin: 20px 0px;
}
.gallerie-imagebox img, .gallerie-imagebox video, .gallerie-imagebox audio {
    position: absolute;
    top: 0px;
    bottom: 0px;
    left: 0px;
    right: 0px;
    margin: auto;
    border: 4px solid white;
    border-radius: 5px;
    transition: all 500ms;
    -webkit-transition: all 500ms;
    -moz-transition: all 500ms;
    -ms-transition: all 500ms;
    -o-transition: all 500ms;
}
div.gallerie-loading {
    position: absolute;
    top: 0px;
    bottom: 0px;
    left: 0px;
    right: 0px;
    width: 126px;
    height: 22px;
    margin: auto;
    z-index: 10000;
   /* background: url("loading.gif");*/
}
gallerie-captionbox {
    color: white;
    width: 100%;
    height: 50px;
    position: relative;
    top: 0px;
    left: 0px;
    text-align: center;
    padding: 5px 0px 15px 0px;
}
.gallerie-captionbox > div {
    display: inline-block;
}

.gallerie-control {
    font-size: 30px;
    min-width: 40px;
    min-height: 40px;
    padding: 5px;
    margin: 0px;
    color: #fff;
    border: 2px solid transparent;
    vertical-align: top;
    border-radius: 100%;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    transition: all 500ms;
    -webkit-transition: all 500ms;
    -moz-transition: all 500ms;
    -ms-transition: all 500ms;
    -o-transition: all 500ms;
}
.gallerie-control-next {
    position: absolute;
    bottom: 50%;
    right: 0px;
}
.gallerie-control-previous {
    position: absolute;
    bottom: 50%;
}
.gallerie-thumbbox {
    height: 100px;
    min-width: 100%;
    position: relative;
    top: 0px;
    left: 0px;
    margin: 0px auto;
    text-align: center;
    float: left;
    white-space: nowrap;
}
.gallerie-thumbbox ul {
    position: relative;
    list-style-type: none;
    margin: 0px;
    padding: 0px;
    left: 0px;
}
.gallerie-thumbbox li {
    width: 100px !important;
    height: 100px !important;
    display: inline-block;
    text-align: center;
    margin: 0px 10px 0px 10px;
    cursor: pointer;
}
.mul-thumb {
    background: #fff;
}
.close-mul {
    width: 50px;
    height: 50px;
    position: absolute;
    top: 80px;
    color: #FFF;
    right: 10px;
    z-index: 10000;
}
.tmbact {
    width: 100%;
    position: absolute;
    bottom: 0px;
    /*background: #4080ff;*/
    color: #fff;
}
</style>
