<?php
$fmt->get->validar_get($_POST['inputIdMod']);
$id_mod = $_POST['inputIdMod'];

if(!empty($_POST["inputVars"])){
	$vars = $_POST["inputVars"];//tarea,id,estado
	$data = explode(",", $vars);
	$tarea = $data[0];
	$id_item = $data[1];
	$id_estado = $data[2];
}

$sql ="SELECT mod_url,mod_ruta_amigable FROM modulo WHERE mod_id=$id_mod";
$rs = $fmt->query -> consulta($sql,__METHOD__);
$row = $fmt->query -> obt_fila($rs);
$url_mod = _RUTA_WEB_NUCLEO.$row["mod_url"]."?id_mod=".$id_mod."&sitio="._RUTA_DEFAULT;
$url_mod = _RUTA_NUCLEO."".$row["mod_url"];
$url = _RUTA_NUCLEO."".$row["mod_url"];

if (file_exists($url)) {
  echo "<div class='container-fluid dialog'>";
	echo "<div class='preloader-page'></div>";
  echo "<a class='btn-close-modal'><i class='icn-close'></i></a>";
  require_once($url_mod);
  echo "</div>";
}else{
  $fmt->error->error_pag_no_encontrada();
}
?>
<script type="text/javascript">
$(document).ready(function(){
	$(".btn-close-modal").on("click", function(e){
		// alert("close");
		$(".modal-inner").html("");
		$(".modal").removeClass("on");
		$(".modal").removeClass("<?php echo $row["mod_ruta_amigable"]; ?>");
		$(".content-page").css("overflow-y","auto");
		// setTimeout(function(){
		//
		// }, 400);
	});
});
</script>
