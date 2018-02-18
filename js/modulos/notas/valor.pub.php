<?php
header("Content-Type: text/html;charset=utf-8");
require_once(_RUTA_NUCLEO."clases/class-constructor.php");
$fmt = new CONSTRUCTOR;

/*$consulta_v ="SELECT DISTINCT val_id, val_usuario FROM valor,noticia_valor  WHERE not_val_not_id ='".$not_id."' and  not_val_val_id=val_id ORDER BY com_fecha desc";
$rs_v =$fmt->query->consulta($consulta_v);
$num_v=$fmt->query->num_registros($rs_v);
if($num_v>0){
  for($j=0;$j<$num_v;$j++){
    list($val_id, $val_usuario)=$fmt->query->obt_fila($rs_v);
    echo "<div class='box-m-com'>";
    echo "  <div class='box-m-user'>";
    echo '    <img class="img-user" alt="" src="'.$usu_imagenx.'">';
    echo "  </div>";
    echo "  <div class='box-m-ct'>".$com_texto."</div>";
    echo "  <div class='box-m-vl'><a>Me gusta</a> · <a>Responder</a> </div>";
    echo "</div>";
  }
}*/

?>
hola
