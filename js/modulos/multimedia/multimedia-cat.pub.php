<?php
header("Content-Type: text/html;charset=utf-8");
require_once(_RUTA_HOST."nucleo/clases/class-constructor.php");
$fmt = new CONSTRUCTOR;


$consulta_m ="SELECT mul_id,mul_url, mul_descripcion, mul_texto_alternativo FROM multimedia  WHERE mul_id<=5 ORDER BY `multimedia`.`mul_orden` asc";
$rs_m =$fmt->query->consulta($consulta_m);
$num_m=$fmt->query->num_registros($rs_m);
if($num_m>0){
	for($im=0;$im<$num_m;$im++){
		list($fila_id,$fila_url, $fila_d, $fila_ta)=$fmt->query->obt_fila($rs_m);
		$ix_id[$im] = $fila_id;
	  $ix_url[$im] = $fila_url;
		$ix_d[$im] = $fila_d;
		$ix_ta[$im] = $fila_ta;
	}
}

?>
<div class="box-slide" >
  <div class="box-slide-inner">
    <div class="box-puntos-recoleccion">
      <a href="http://ensulugar.com" target="_blank" class="link-puntos-recoleccion"></a>
    </div>
    <div id="slider" class="nivoSlider">
      <?php
        for ($i=0; $i < $num_m; $i++) {
      ?>
        <img class="img img<?php echo $i; ?>" src="<?php echo _RUTA_WEB.$ix_url[$i]; ?>" data-thumb="" title="#html<?php echo $i; ?>" alt="" data-transition="boxRain" />
      <?php } ?>
    </div>
  </div>
</div>

<script type="text/javascript" src="js/jquery.nivo.slider.js"></script>
<script type="text/javascript">
$(window).load(function() {
    $('#slider').nivoSlider({
      manualAdvance: false
    });
    $('.box-slide .nivo-nextNav').append("<i  class='icn icn-arrow-circle-right'></i>");
    $('.box-slide .nivo-prevNav').append("<i class='icn icn-arrow-circle-left'></i>");
});
</script>
