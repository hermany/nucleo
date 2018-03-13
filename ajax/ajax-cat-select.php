<?php 

$id_select = $_POST["id_select"];
$id_option = $_POST["id_option"];
$item = $_POST["item"];

$consulta = "SELECT cat_id,cat_nombre FROM categoria WHERE cat_id_padre='$id_option'";
$rs =$fmt->query->consulta($consulta);
$num=$fmt->query->num_registros($rs);
$aux="";
if($num>0){
  for($i=0;$i<$num;$i++){
		$row=$fmt->query->obt_fila($rs);
		$row_id = $row["cat_id"];
    $row_nombre = $row["cat_nombre"];
    $aux .="<option value='$row_id'>$row_nombre</option>"; 
	}
}
 
$fmt->query->liberar_consulta($rs); 
 

echo $item.":".$id_option.":".$id_select.":".$aux;

?>