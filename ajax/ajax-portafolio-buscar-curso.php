<?php 
$vars = explode(",",$_POST["inputVars"]);
$q= $vars[0];

$search= "cur_nombre LIKE '%" . $q .  "%' OR cur_fecha_inicio LIKE '%" . $q ."%' OR cur_tags LIKE '%" . $q ."%' OR cur_fecha_fin LIKE '%" . $q ."%'";
$consulta_m ="SELECT DISTINCT cur_id,cur_nombre,cur_leyenda,cur_ruta_amigable,cur_imagen,cur_fecha_inicio,cur_tags, cur_fecha_fin FROM curso WHERE  cur_activar=1 and $search ORDER BY cur_fecha_inicio asc";
$rs_m =$fmt->query->consulta($consulta_m);
$num_m=$fmt->query->num_registros($rs_m);
$aux = "";
if($num_m>0){
	for($im=0;$im<$num_m;$im++){
    $row=$fmt->query->obt_fila($rs_m);
    //$fila_id,$fila_url, $leyenda, $descripcion
    // echo "fila_url".$fila_url;
    $cur_id  = $row["cur_id"];
    $aux .= '<div class="item item-curso" ><span>'.$row["cur_nombre"].'</span><a class="btn btn-full btn-seleccionar" nom="'.$row["cur_nombre"].'" cur="'.$row["cur_id"].'">Seleccionar</a></div>';
  }

  echo $aux;
}else{
	echo "";
}

?>