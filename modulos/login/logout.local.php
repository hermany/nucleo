<?php 
require_once(_RUTA_NUCLEO."clases/class-constructor.php");
$fmt = new CONSTRUCTOR();

$fmt->sesion->iniciar_sesion();
$fmt->sesion->cerrar_sesion();

?>
<script type="text/javascript">
		window.location.href = "<?php echo _RUTA_WEB; ?>";
</script>