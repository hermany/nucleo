<?php
header("Content-Type: text/html;charset=utf-8");
require_once(_RUTA_NUCLEO."clases/class-constructor.php");
$fmt = new CONSTRUCTOR;

$consulta_c ="SELECT DISTINCT com_id, com_usuario, com_texto FROM comentario,noticia_comentario  WHERE not_com_not_id ='".$not_id."' and com_activar='1' and not_com_com_id=com_id ORDER BY com_fecha desc";
$rs_c =$fmt->query->consulta($consulta_c);
$num_c=$fmt->query->num_registros($rs_c);
if($num_c>0){
  for($j=0;$j<$num_c;$j++){
    list($com_id, $com_usuario, $com_texto)=$fmt->query->obt_fila($rs_c);
    $usu_imagenx    = _RUTA_WEB.$fmt->usuario->imagen_usuario($com_usuario);
    echo "<div class='box-m-com'>";
    echo "  <div class='box-m-user'>";
    echo '    <img class="img-user" alt="" src="'.$usu_imagenx.'">';
    echo "  </div>";
    echo "  <div class='box-m-ct'>".$com_texto."</div>";
    echo "  <div class='box-m-vl'><a>Me gusta</a> · <a>Responder</a> </div>";
    echo "</div>";
  }
}

?>
