<?php
  require_once("../clases/class-constructor.php");
  $fmt = new CONSTRUCTOR();
  date_default_timezone_set('America/La_Paz');

  $id = $_POST['inputId'];
  $id_usu = $_POST['inputUsuario'];
  $cm = $_POST['inputComentario'];
  $padre =  $_POST['inputPadre'];
  //echo $id.":".$id_usu.":".$cm;
  $usu_imagen    = _RUTA_WEB.$fmt->usuario->imagen_usuario($id_usu);

  $fecha = date("Y-m-d H:m:s");
  $ingresar ="com_texto,
              com_usuario,
              com_fecha,
              com_activar";
  $valores  ="'".$cm."','".$id_usu."','".$fecha."','1'";
  $sql="insert into comentario (".$ingresar.") values (".$valores.")";
  $fmt->query->consulta($sql,__METHOD__);

  $sql1="select max(com_id) as id from comentario";
  $rs1= $fmt->query->consulta($sql1,__METHOD__);
  $fila = $fmt->query->obt_fila($rs1);
  $idcom = $fila ["id"];
  $ingresar1 ="not_com_not_id, not_com_com_id, not_com_padre_id, not_com_orden";
  $valores1= "'".$id."','".$idcom."','".$padre."','".$idcom."'";
  $sql1="insert into noticia_comentario (".$ingresar1.") values (".$valores1.")";
  $fmt->query->consulta($sql1,__METHOD__; 

  echo "<div class='box-m-com'>";
  echo "  <div class='box-m-user'>";
  echo '    <img class="img-user" alt="" src="'.$usu_imagen.'">';
  echo "  </div>";
  echo "  <div class='box-m-ct'>".$cm."</div>";
  echo "  <div class='box-m-vl'><a>Me gusta</a> · <a>Responder</a> </div>";
  echo "</div>";

?>
